<?php
session_start();
require_once("util.php");
?>
<html>
<head>
<title>Amit Dabi</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
  </head>
<body>
<div class="container">
<?php

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "assign_db";
	try{
				
			 $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
			  {
				if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['email'])<1 ||strlen($_POST['headline'])<1 ||strlen($_POST['summary'])<1)
				{
					$_SESSION['error']="All values are required";
					header("Location:add.php");
					return;
				}	
                if(strpos($_POST['email'],'@') === false)	
				{
					$_SESSION['error']="Email address must contian @";
					header("Location:add.php");
					return;
				}					
			     
				$msg=util(); 
                if(is_string($msg))
				{
					$_SESSION['error']=$msg;
					header("Location:add.php");
					return;
				}			
                $msg1=validate(); 
                if(is_string($msg1))
				{
					$_SESSION['error']=$msg1;
					header("Location:add.php");
					return;
				}					
             $stmt = $pdo->prepare('insert into profile(user_id,first_name,last_name,email,headline,summary) values(:u,:f,:l,:e,:h,:s)');
			 

             $stmt->execute(array(':u'=>$_SESSION['user_id'], 
			 ':f'=>$_POST['first_name'], 
			 ':l'=>$_POST['last_name'],
			 ':e'=>$_POST['email'],
			 ':h'=>$_POST['headline'],
			 ':s'=>$_POST['summary'],			
			 ));
			 
			 $profile_id = $pdo->lastInsertId();
			 $rank=1;
			 for($i=1;  $i<=9; $i++)
			 {
				 if(isset($_POST['year'.$i])) 
				 {
				$stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)'); 
				 $stmt->execute(array(
                 ':pid' => $profile_id,
                 ':rank' => $rank,
                 ':year' =>$_POST['year'.$i],
                 ':desc' => $_POST['desc'.$i])
             );
            $rank++;
	         } 
			 }
			 for($i=1;  $i<=9; $i++)
			 {
				 if(isset($_POST['edu_year'.$i])) 
				 {
			     $id=false;
				 $stmt = $pdo->prepare('select * from institution where name= :n'); 
				 $stmt->execute(array(
                 ':n' => $_POST['edu_school'.$i]));
				 $row=$stmt->fetch(PDO::FETCH_ASSOC);
				 if($row!==false)
				 {
					 $id=$row['institution_id'];
				 }
				 else
				 {
					  $stmt = $pdo->prepare('INSERT INTO institution(name) VALUES(:n)'); 
				      $stmt->execute(array(
                     ':n' => $_POST['edu_school'.$i]));
				     $id=$pdo->lastInsertId();
				 }
             
				 $stmt = $pdo->prepare('INSERT INTO education (profile_id, institution_id,rank, year) VALUES ( :pid, :inst ,:rank, :year)'); 
				 $stmt->execute(array(
                 ':pid' => $profile_id,
				 ':inst'=>$id,
                 ':rank' => $rank,
                 ':year' =>$_POST['edu_year'.$i])
             );
            $rank++;
	         } 
			 } 		           
			 $_SESSION['success']="Profile added";
			 header("Location:index.php"); 
			 return;
     	}
	}
	 catch(PDOException $e){
      echo "Exception caught: ".$e->getMessage();
    }
    $pdo = null;
?>
<h1>Adding Profile for <?php echo $_SESSION['name']?></h1>
<?php
    if(isset($_SESSION['error'])) 
    {
	echo "<p style='color:red'>".$_SESSION['error']."</p>";
	unset($_SESSION['error']);
   }
   ?>
<form  method="post">
<label>First Name:</label>
<input type="text" name="first_name" size="50" id="fn1"><br>
<Label>Last Name:</label>
<input type="text" name="last_name" size="50" id="ln1"><br>
<label>Email:</label>
<input type="text" name="email" size="30" id="email1"><br>
<label>Headline:</label><br>
<input type="text" name="headline" size="60" id="hl1"><br>
<label>Summary:</label><br>
<textarea rows="10" cols="100" name="summary" id="sum1"></textarea><br><br>
<label>Education</label>
<input type="submit" name="education" id="ed1" value="+">
<div id="dv2">

</div>
<label>Position</label>
<input type="submit" name="positon" id="po1" value="+">
<div id="dv1">

</div>
<input type="submit" name="Add" value="Add">
<input type="submit" name="Cancel" value="cancel">
</form>
<script>
  count=0;
     $(document).ready(function(){
     window.console&&console.log("document ready call");
	 $('#po1').click(function(event){
	  	event.preventDefault();
		if(count>=9)
		{
			alert("maximum position");
			return;
		}	
		count++;
		window.console&&console.log("adding position= "+count);		
		$('#dv1').append('<div id="dva'+count+'"> \
		<p>Year:<input type="text" name="year'+count+'"> \
		<input type="button" value="-" onclick="$(\'#dva'+count+'\').remove();return false;"><br> \
		<textarea name="desc'+count+'" rows="10" cols="100" ></textarea></p></div>');	
	});
});
cont=0;
     $(document).ready(function(){
     window.console&&console.log("document ready call");
	 $('#ed1').click(function(event){
	  	event.preventDefault();
		if(cont>=9)
		{
			alert("maximum Education");
			return;
		}	
		cont++;
		window.console&&console.log("adding education= "+cont);		
		$('#dv2').append('<div id="education'+cont+'"> \
		<p>Year:<input type="text" name="edu_year'+cont+'"> \
		<input type="button" value="-" onclick="$(\'#education'+cont+'\').remove();return false;"><br> \
		School:<input type="text" name="edu_school'+cont+'" class="school" size="80" ></p></div>');
        $('.school').autocomplete({ source: "school.php" });		
	});
	    $('.school').autocomplete({ source: "school.php" });
});
</script>
</div>
</body>
</html>