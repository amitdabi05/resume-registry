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
session_start();
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "assign_db";
	try{
		if(isset($_POST['delete']))
		{		
			 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

             $stmt = $conn->prepare('delete from profile where profile_id= :p');

             $stmt->execute(array(':p'=>$_POST['profile_id']));
			 header("Location:index.php"); 
     	}
		
	}
	catch(DOException $e)
	{
		echo "Exception caught: ".$e->getMessage();
	}
	$pdo = null;
	?>
	<?php
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
	<h1>Deleting profile</h1>
	<label>First Name:</label>
	    <?php echo $row['first_name']?><br>
    <label>last Name:</label>
		<?php echo $row['last_name']?><br>
		<form action="delete.php" method="post">
		<input type="text" value="<?php echo $_GET['profile_id']?>" name="profile_id"  hidden>
		<input type="submit" onclick="index.php"  name="delete" value="delete">
		
		</form>
		<button onclick="location.href='index.php';">cancel</button>
	</body>
	</html>