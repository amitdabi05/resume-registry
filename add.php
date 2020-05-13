<?php
session_start();
require_once("util.php");
?>
<html>
<head>
<title>Amit Dabi</title>
<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">
	<script
    src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous">
	</script>
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
</script>
</div>
</body>
</html>