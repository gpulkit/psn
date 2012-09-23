<?php
require_once('lib2.php');
$result = mysql_query('SELECT user_id from users');
if($result == 0)
	mysql_error($result,$conn);
else
{
	while (($row = mysql_fetch_array($result, MYSQL_ASSOC)))
	{
		
		$user_id = $row['user_id'];
		echo $user_id.'<br/>';
		printUserPictures($user_id);

	}
}
?>