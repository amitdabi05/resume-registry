
<?php
function util()
{
	for($i=1; $i<=9; $i++)
	{
		if(!isset($_POST['year'.$i]))
		continue;
	    if(!isset($_POST['desc'.$i]))
		continue; 
	   if(strlen($_POST['year'.$i])<1 || strlen($_POST['desc'.$i])<1)
	  {
		return "All values are required";
	   }
	   if(!is_numeric($_POST['year'.$i]))
	   {
		   return "Year must be numeric";
	   }
	 }
	 return true;
	}
?>