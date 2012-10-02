<?php
require_once('../../config.php');
if(isset($_REQUEST['u']))
{
	$user_id = 	mysql_real_escape_string($_REQUEST['u']);
	$email = 	mysql_real_escape_string($_REQUEST['v']);
	$face_id = 	mysql_real_escape_string($_REQUEST['f']);
	$result = mysql_query("UPDATE faces SET email = '$email' WHERE face_id = '$face_id'");
	$result = mysql_query("SELECT * FROM users WHERE email = '$email'");
	if($result == 0)
		mysql_error($conn);
	else
	{
		if(mysql_num_rows($result) == 0)
		{
			//send email
			//add to face
		}
		else
		{
			
		}
	}
}
?>