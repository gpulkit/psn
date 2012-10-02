<?php
require_once('../../config.php');
$user_id ='';
$email = $_POST['email'];
$emails = $_POST['emails'];
$name = $_POST['name';]
$result = mysql_query("SELECT * FROM users WHERE user = '$user_id'");

if($result == 0)
	mysql_error($conn);

$user = mysql_fetch_array($result, MYSQL_ASSOC));
$email = $user['email'];

mysql_query('INSERT INTO ')

?>