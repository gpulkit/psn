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
		$result2 = mysql_query("SELECT * FROM users WHERE user_id = '$user_id'");
		$row2 = mysql_fetch_array($result);
		$name = $row2['name'];
		if($name== '')
			$name = $row['email'];
		if(mysql_num_rows($result) == 0)
		{

		}
		else
		{
			//$row = mysql_fetch_array($result,MYSQL_ASSOC);
			//$user_id2 = $row['user_id'];
			//$query_str = "INSERT INTO pictures (image_id, user_id, event) VALUES ('$image_id','$user_id',0)";
		}
		$subject = $name.' just shared a photo with you';
		$body = 'Hi there,<br/>'.$name.' has just privately shared photos with you through Photo Sharing
		Network, a private photo sharing system currently in invitation-only beta test mode.<br/>
		See your photo(s) and gain pre-launch access to our site <a href="www.photosharingnetwork.com?e="'.$email.'">here</a>.<br/>
		Hope you find our private photo sharing system useful - Thanks!<br/><br/>
		-Your friends at Photo Sharing Network<br/><br/><br/>
		Photo Sharing Network provides a variety of advanced photo sharing solutions which are easy
		and private.<br/><br/>

		You and your friends can use PSN to automatically and privately share photos with each other.<br/>
		Upload pictures, then sit back and let our advanced recognition technologies privately share<br/>
		photos with – and only with – the people that are in each of your photos. Learn more on our<br/>
		site: <a href="www.photosharingnetwork.com">Photo Sharing Network .com</a>.<br/><br/>

		<font size="3px">CURRENTLY IN BETA TEST You are one of the first to access our system!<br/>
		Something not working properly? Have ideas for making something better? Please let us know so we can improve!<br/>
		Please submit feedback here, Thanks! Yes, the rumor is true - We\'re raffling off Chipotle gift cards to those giving
		feedback</font>';

		echo smtpmailer('gupta.pulkit89@gmail.com','Photos@photosharingnetwork.com','Photo Sharing Network',$subject,$body);
	}
}
?>
