<?php
	require_once('../lib2.php');

	if(isset($_POST['submit']))
	{
		$name ='';
		$date= '';
		$file ='';
		$file=false;
		$query = 'UPDATE events SET ';
		$edit = false;
		$event_id = $_POST['list'];
	
		$query = "SELECT * FROM event_images WHERE event_id = '$event_id'";
		$result = mysql_query($query);
		if($result==0) {
				echo mysql_error($conn);
		}
		else
		{
			while($row = mysql_fetch_array($result,MYSQL_ASSOC))
			{
				$image_id =$row['image_id'];
				$query = "DELETE FROM pictures WHERE image_id = '$image_id'";
				$result2 = mysql_query($query);
				if($result2==0) {
					echo mysql_error($conn);
				}
			}
		}

		$query = "DELETE FROM users_events WHERE event_id = '$event_id'";
		$result2 = mysql_query($query);
		if($result2==0) {
			echo mysql_error($conn);
		}
		else
		{
			echo '<h2>Event '. $event_id . ' deleted!</h2><br/>';
		}

	}
	$result = mysql_query("SELECT *  FROM events");
?>
<html>
<head>
	<title>
		Delete an Event
	</title>
</head>
<body>
	<h1>
		Delete an Event! Fill in the form and hit submit.
	</h1>
	<form method='post' action='delete.php' enctype="multipart/form-data">
		<table>
			<tr>
				<td> List of Events: </td>
				<td>
					<select name='list'>
						<?php 
							while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
							{
								$id = $row['event_id'];
								$name = $row['event_name'];
								echo "<option value='$id'>$name</option>";
							}
						?>
					</select>
				</td>
			</tr>
		 	<tr>
		 		<td> <input type='submit' name='submit' value='Delete'/> </td>
		 	</tr>
		</table>
</body>
</html>
