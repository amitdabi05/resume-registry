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
session_start();
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
			  
				  
             $stmt = $pdo->prepare('insert into profile(user_id,first_name,last_name,email,headline,summary) values(:u,:f,:l,:e,:h,:s)');

             $stmt->execute(array(':u'=>$_SESSION['user_id'], 
			 ':f'=>$_POST['first_name'], 
			 ':l'=>$_POST['last_name'],
			 ':e'=>$_POST['email'],
			 ':h'=>$_POST['headline'],
			 ':s'=>$_POST['summary'],			
			 ));
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
<input type="submit" name="Add" value="Add">
<input type="submit" name="Cancel" value="cancel">
</form>
</div>
</body>
</html>