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
<h1>Resume Registry</h1>
 <?php
   session_start();
    if(isset($_SESSION['name']))
	{?>
	 <a href="logout.php">logout</a> <br>
	 <a href="add.php">Add New Entry</a>
	 <?php
	}
	else
	{?>
     <a href="login.php">Please log in</a><br>
     <?php
	}
	$servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "assign_db";
	try{
				
			 $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			 $stmt = $pdo->prepare('select profile_id, first_name, headline from profile');
             $stmt->execute();
			 if($stmt->rowCount()==true)
			 {
				 echo "<table border='1'><tr><th>Name</th><th>Headline</th>";
				 if(isset ($_SESSION['name']))
				 {
					 echo"<th>action</th>";
				 }
				 echo"</tr>";
				 while($row = $stmt->fetch(PDO::FETCH_ASSOC))
				 {
					 echo"<tr><td><a href ='view.php?profile_id=".$row['profile_id']."'>".$row['first_name']."</a></td>";
					 echo"<td>".$row['headline']."</td>";
					if(isset ($_SESSION['name']))
				 {
					 echo"<td><a href ='edit.php?profile_id=".$row['profile_id']."'>edit</a>&nbsp;&nbsp";
					 echo"<a href ='delete.php?profile_id=".$row['profile_id']."'>delete</a></td>";
				 } 
				 echo"</tr>";

				 }
				 echo"</table>";
			 }
	}
	catch(PDOException $e){
      echo "Exception caught: ".$e->getMessage();
    }
?>
	</body>
</html>