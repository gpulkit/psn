<?php
require_once('config.php');

if(isset($_REQUEST['c']))
{
	$campus_id = mysql_real_escape_string($_REQUEST['c']);
	$result = mysql_query("SELECT * FROM orgs WHERE campus_id = '$campus_id'");
	if($result == 0)
	{
		mysql_error($conn);
	}
	else
	{
		$count = mysql_num_rows($result);
		if($count == 0)
			echo "<option value='0'>Select your campus affiliation</option>";
		else
		{
			echo "<option value='0'>Select your campus affiliation</option>";
		  	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		  	{
		  		$id = $row['org_id'];
		  		$name = $row['org_name'];
		  		echo "<option value='".$id."'>".$name."</option>";
		  	}
		}
	}
}
?>
