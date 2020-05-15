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
	    $conn = $pdo->prepare('select * from education inner join institution on education.institution_id=institution.institution_id where profile_id= :p');
	    $conn ->execute(array(':p'=>$_GET['profile_id']));
		if($conn->rowCount()==true)
		{
			echo"<label>Education:</label><br><ul>";
			while($ro= $conn->fetch(PDO::FETCH_ASSOC))
			{
				echo"<li>".$ro['year'].":".$ro['name']."</li>";
			}
			echo"</ul>";
		}
	   ?>
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