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
<div class="container">
<?php

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "assign_db";
	try{		
			 $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

             $stmt = $pdo->prepare('select * from profile where profile_id= :p');
			 $stmt ->execute(array(':p'=>$_GET['profile_id']));
			 $row= $stmt->fetch(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		echo "Exception caught:" .$e->getMessage();
	}
	?>
	<h1>profile Information</h1><br>
	<label>First Name:</label>
	    <?php echo $row['first_name']?><br>
    <label>last Name:</label>
		<?php echo $row['last_name']?><br>
    <label>Email:</label>
		<?php echo $row['email']?><br>
    <label>Headline:</label>
	   <?php echo $row['headline']?><br>
    <label>Summary:</label>
	   <?php echo $row['summary']?><br>
	   <?php
	    $conn = $pdo->prepare('select * from position where profile_id= :p');
	    $conn ->execute(array(':p'=>$_GET['profile_id']));
		if($conn->rowCount()==true)
		{
			echo"<label>Positions:</label><br><ul>";
			while($ro= $conn->fetch(PDO::FETCH_ASSOC))
			{
				echo"<li>".$ro['year'].":".$ro['description']."</li>";
			}
			echo"</ul>";
		}
	   ?>
	   <a href ="index.php">done</a>
        </div>
	   </body>
</html>