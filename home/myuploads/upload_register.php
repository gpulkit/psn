<?php
session_start();
require_once('../../config.php');
if(isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];

	$query="INSERT INTO upload_register VALUES ('$user_id')";
	mysql_query($query);
	header('Location: soon1.html');
}
?>