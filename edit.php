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
  if(!isset($_SESSION['name']))
  {
	  die("Access denied");
	  return ;
	  
  }
  if(isset($_POST['cancel']))
  {
     header("Location:index.php");
    return;	 
  }
  
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "assign_db";
	try{
			 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			 $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
			  {
				if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['email'])<1 ||strlen($_POST['headline'])<1 ||strlen($_POST['summary'])<1)
				{
					$_SESSION['error']="All values are required";
					header("Location:edit.php?profile_id=".$_POST['profile_id']);
					return;
				}	
                if(strpos($_POST['email'],'@') === false)	
				{
					$_SESSION['error']="Email address must contian @";
					header("Location:edit.php?profile_id=".$_POST['profile_id']);
					return;
				}				
                $msg=util(); 
                if(is_string($msg))
				{
					$_SESSION['error']=$msg;
					header("Location:edit.php?profile_id=".$_POST['profile_id']);
					return;
				}									
			  	 $msg1=validate(); 
                if(is_string($msg1))
				{
					$_SESSION['error']=$msg1;
					header("Location:edit.php");
					return;
				}					  
             $stmt = $pdo->prepare('update profile set first_name=:f,last_name=:l,email=:e,headline=:h,summary=:s where profile_id=:p');
             
             $stmt->execute(array( 
			 ':f'=>$_POST['first_name'], 
			 ':l'=>$_POST['last_name'],
			 ':e'=>$_POST['email'],
			 ':h'=>$_POST['headline'],
			 ':s'=>$_POST['summary'],
             ':p'=>$_POST['profile_id'],			 
			 ));
			
			  $sm = $conn->prepare('delete from education where profile_id= :p');
              $sm->execute(array(':p'=>$_GET['profile_id']));
			  $sm = $conn->prepare('delete from position where profile_id= :p');
              $sm->execute(array(':p'=>$_GET['profile_id']));
			  			 
			$rank=1;
			 for($i=1;  $i<=9; $i++)
			 {
				 if(isset($_POST['year'.$i])) 
				 {
				$stt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)'); 
				 $stt->execute(array(
                 ':pid' => $_GET['profile_id'],
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
                 ':pid' => $_GET['profile_id'],
				 ':inst'=>$id,
                 ':rank' => $rank,
                 ':year' =>$_POST['edu_year'.$i])
             );
            $rank++;
	         } 
			 } 		           
			  
			 $_SESSION['success']="Profile updated";
			 header("Location:index.php"); 
			 return;
     	}
	}
	 catch(PDOException $e){
      echo "Exception caught: ".$e->getMessage();
    }
 ?>
<h1>Editing Profile for <?php echo $_SESSION['name']?></h1>
<?php
    if(isset($_SESSION['error'])) 
    {
	echo "<p style='color:red'>".$_SESSION['error']."</p>";
	unset($_SESSION['error']);
   }
   
             $stm = $conn->prepare('select * from profile where profile_id= :p');

             $stm->execute(array(':p'=>$_GET['profile_id'])); 
			$row=$stm->fetch(PDO::FETCH_ASSOC);
			if($stm->rowCount() == true){
				echo"<form  method='post'>";
			
    ?>
<input type="text" value="<?php echo $_GET['profile_id']?>" name="profile_id"  hidden>
<label>First Name:</label>
<input type="text" name="first_name" size="50" id="fn1" value="<?php echo $row['first_name']?>"><br>
<Label>Last Name:</label>
<input type="text" name="last_name" size="50" id="ln1" value="<?php echo $row['last_name']?>"><br>
<label>Email:</label>
<input type="text" name="email" size="30" id="email1" value="<?php echo $row['email']?>"><br>
<label>Headline:</label><br>
<input type="text" name="headline" size="60" id="hl1" value="<?php echo $row['headline']?>"><br>
<label>Summary:</label><br>
<textarea rows="10" cols="100" name="summary" id="sum1"><?php echo $row['summary']?></textarea><br><br>
<label>Education</label>
<input type="submit" name="education" id="ed1" value="+">
<div id="dv2">
<?php
         $st = $conn->prepare('select * from education inner join institution on education.institution_id=institution.institution_id where profile_id= :p');
         $st->execute(array(':p'=>$_GET['profile_id'])); 
		 
		 $num_amit_dabi=0;
    if($st->rowCount() == true)
	{
    while($ro= $st->fetch(PDO::FETCH_ASSOC))
	   {
		 $num_amit_dabi++;
		echo"<div id='education".$num_amit_dabi."'> 
		<p>Year:<input type='text' value='".$ro['year']."' name='edu_year".$num_amit_dabi."'> 
		<input type='button' value='-' onclick='$(\"#education".$num_amit_dabi."\").remove();return false;'><br> 
		school:<input type='text' size='80' class='school' value='".$ro['name']."' name='edu_school".$num_amit_dabi."'></p></div>";
	   }
	}	
?>
</div>  
<label>Position</label>
<input type="submit" name="positon" id="po1" value="+">
<div id="dv1">
<?php
         $st = $conn->prepare('select * from position where profile_id= :p');
         $st->execute(array(':p'=>$_GET['profile_id'])); 
		 
		 $num=0;
    if($st->rowCount() == true)
	{
    while($ro= $st->fetch(PDO::FETCH_ASSOC))
	   {
		 $num++;
		echo"<div id='dva".$num."'> 
		<p>Year:<input type='text' value='".$ro['year']."' name='year".$num."'> 
		<input type='button' value='-' onclick='$(\"#dva".$num."\").remove();return false;'><br> 
		<textarea name='desc".$num."' rows='10' cols='100'>".$ro['description']."</textarea></p></div>";
	   }
	}	
?>
</div>  
<input type="submit" name="Add" value="Save">
<input type="submit" name="cancel" value="Cancel">

<?php
echo"</form>";
?>
<script>

         cont=<?=$num_amit_dabi?>;
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
			count=<?=$num?>;
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
<?php
}
$conn= null;
$pdo= null;
?>
</div>
</body>
</html>