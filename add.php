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
		
		if(isset($_POST['Add']))
		{		
			 $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
             $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

             $stmt = $pdo->prepare('insert into profile(user_id,first_name,last_name,email,headline,summary) values(:u,:f,:l,:e,:h,:s)');

             $stmt->execute(array(':u'=>$_SESSION['user_id'], 
			 ':f'=>$_POST['first_name'], 
			 ':l'=>$_POST['last_name'],
			 ':e'=>$_POST['email'],
			 ':h'=>$_POST['headline'],
			 ':s'=>$_POST['summary'],			
			 ));
			 header("Location:index.php"); 
     	}
	}
	 catch(PDOException $e){
      echo "Exception caught: ".$e->getMessage();
    }
    $pdo = null;
?>
<h1>Adding Profile for UMSI</h1>
<span style="color:red" id="add1"></span><br>
<form action="add.php" method="post">
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
<input type="submit" onclick="return profile_data_validate();" name="Add" value="Add">
<input type="submit" name="Cancel" value="cancel">
</form>
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
		document.getElementById('add1').innerHTML = 'All values are required.';
			return false;
		}
       
       else if(atposition== -1)
	   {
        document.getElementById('add1').innerHTML = 'emial address must contain @.';	
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
</div>
</body>
</html>