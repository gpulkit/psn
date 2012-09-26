<?php
require_once('lib2.php');
$result = mysql_query('SELECT * from users');
if($result == 0)
	mysql_error($result,$conn);
else
{
	while (($row = mysql_fetch_array($result, MYSQL_ASSOC)))
	{
		
		$user_id = $row['user_id'];
		echo $row['name'].'<br/>';
		printUserPictures($user_id);

	}
}
?>
