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
</head>
<body>
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
					header("Location:edit.php?profile_id=".$_POST['profile_id']);
					return;
				}	
                if(strpos($_POST['email'],'@') === false)	
				{
					$_SESSION['error']="Email address must contian @";
					header("Location:edit.php?profile_id=".$_POST['profile_id']);
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
   
             $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
<input type="submit" name="Add" value="Save">
<?php
echo"</form>";
?>
<button onclick="location.href='index.php';">cancel</button>
<?php
}
$conn= null;
$pdo= null;
?>


</body>
</html>