<?php
require_once('config.php');

if(isset($_REQUEST['email']))
{
	$email = mysql_real_escape_string($_REQUEST['email']);
	$result = mysql_query("SELECT email from users WHERE email = '$email'");
	if($result == 0)
	{
		mysql_error($conn);
	}
	else
	{
		$count = mysql_num_rows($result);
		if($count == 0)
			echo '';
		else
			echo $email;
	}
}
?>