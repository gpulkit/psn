<?php
require_once('../../config.php');
require_once '../../src/class.phpmailer.php';
require_once('../../mail.php');

if(isset($_REQUEST['u']))
{
	$user_id = 	mysql_real_escape_string($_REQUEST['u']);
	$email = 	mysql_real_escape_string($_REQUEST['v']);
	$face_id = 	mysql_real_escape_string($_REQUEST['f']);
	$result = mysql_query("UPDATE faces SET email = '$email' WHERE face_id = '$face_id'");
	if($result == 0)
		mysql_error($conn);
	$result = mysql_query("SELECT * FROM users WHERE email = '$email'");
	if($result == 0)
		mysql_error($conn);
	else
	{
		if(mysql_num_rows($result) == 0)
		{
             echo smtpmailer('gpulkit@umich.edu','Photos@photosharingnetwork.com','Photo Sharing Network','test3','You have been invited for an album.');
		}
		else
		{
			//$row = mysql_fetch_array($result,MYSQL_ASSOC);
			//$user_id2 = $row['user_id'];
			//$query_str = "INSERT INTO pictures (image_id, user_id, event) VALUES ('$image_id','$user_id',0)";
			echo smtpmailer('gpulkit@umich.edu','Photos@photosharingnetwork.com','Photo Sharing Network','test3','A photo has been added');	
		}
	}
}
?>
