<?php
require_once('config.php');
if(isset($_REQUEST['f']))
{
	$image_id = $_REQUEST['f'];
	$query = "SELECT event_id FROM event_images WHERE image_id = '$image_id'";
	$result = mysql_query($query);
	if($result == 0 )
		mysql_error($conn);
	else if(mysql_num_rows($result) == 0)
	{
		header('Location: .');
	}
	else
	{
		$row = mysql_fetch_array($result);
		$event_id = $row['event_id'];
		$image_link = $s3->getAuthenticatedURL($bucket,'photos/'.$event_id.'/'.$image_id.'.jpg', 20, false, false);
		echo "<body bgcolor='black' text='white' alink='white' vlink='white' link='white'>";
		echo "
		<br><br>
		<div align='center'>
		<img src='".$image_link."'/>
		<br>
		<br><font size='8'>
		This photo and others 
		<br>
		will be accessible on:
		<br>
		<font size='5'><br></font>
		<a href='http://www.emorypartypics.com' style='text-decoration:none' target='_parent'><font size='5'>www. </font>Emory Party Pics<font size='5'> .com</font></a>
		</font>
		<font
		</div>";
		/*
		Message for the slacker next to
you who hasn't registered yet:
You can do so now.
We brought laptops; find us.
	*/

		echo "</body>";
	}
}
else
{
	header('Location: .');
}

?>