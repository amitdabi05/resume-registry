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
		if(isset($_POST['Add']))
		{		
			 $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

             $stmt = $pdo->prepare('update profile set first_name=:f,last_name=:l,email=:e,headline=:h,summary=:s where profile_id=:p');

             $stmt->execute(array( 
			 ':f'=>$_POST['First'], 
			 ':l'=>$_POST['Last'],
			 ':e'=>$_POST['Email'],
			 ':h'=>$_POST['Headline'],
			 ':s'=>$_POST['Summary'],
             ':p'=>$_POST['profile_id'],			 
			 ));
			 header("Location:index.php"); 
     	}
	}
	 catch(PDOException $e){
      echo "Exception caught: ".$e->getMessage();
    }
    $pdo = null;
	?>
	<h1>edit Profile for <?php echo $_SESSION['name']; ?></h1>
	<?php

	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

             $stm = $conn->prepare('select * from profile where profile_id= :p');

             $stm->execute(array(':p'=>$_GET['profile_id'])); 
			$row=$stm->fetch(PDO::FETCH_ASSOC);
			if($stm->rowCount() == true){
				echo"<form action='edit.php' method='post'>";
			
    ?>
<input type="text" value="<?php echo $_GET['profile_id']?>" name="profile_id"  hidden>
<label>First Name:</label>
<input type="text" name="First" size="50" id="fn1" value="<?php echo $row['first_name']?>"><br>
<Label>Last Name:</label>
<input type="text" name="Last" size="50" id="ln1" value="<?php echo $row['last_name']?>"><br>
<label>Email:</label>
<input type="text" name="Email" size="30" id="email1" value="<?php echo $row['email']?>"><br>
<label>Headline:</label><br>
<input type="text" name="Headline" size="60" id="hl1" value="<?php echo $row['headline']?>"><br>
<label>Summary:</label><br>
<textarea rows="10" cols="100" id="sum1"><?php echo $row['summary']?></textarea><br><br>
<input type="submit" onclick="return profile_data_validate();" name="Add" value="Save">
<?php
echo"</form>";
?>
<button onclick="location.href='index.php';">cancel</button>
<?php
}
$conn= null;
?>
<script type="text/javascript">
function profile_data_validate()
{
	try{
	fn=document.getElementById('fn1').value;
	ln=document.getElementById('ln1').value;
    em=document.getElementById('email1').value;
	hl=document.getElementById('hl1').value;
	sum=document.getElementById('sum1').value;
    var atposition =em.indexOf('@');
      if(fn==null || fn=="" || ln==null || ln=="" ||em==null || em=="" || hl==null ||hl=="" || sum==null ||sum=="")
		{
			alert("all fields are reqiured");
			return false;
		}
       if(atposition== -1)
	   {
		   alert("please use authetic email addess");
		 return false;
	   }		   
	   return true;
			}
	catch(e)
	{
		return false;
	}
	return false;
}
</script>

</body>
</html>