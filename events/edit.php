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
		
		if($_POST['event_date']!='')
		{
			$date = mysql_real_escape_string($_POST['event_date']);
			$query = $query."event_date = '$date' ";
			$edit = true;
		}

		if($_FILES['event_cover']['name']!='')
		{
			$file_name = $_FILES['event_cover']['name'];	
			$allowedExts = array("jpg", "jpeg", "gif", "png","JPG");
			$str = explode(".",$file_name);
			$extension = end($str);
			if ((($_FILES["event_cover"]["type"] == "image/gif")
				|| ($_FILES["event_cover"]["type"] == "image/jpeg")
				|| ($_FILES["event_cover"]["type"] == "image/pjpeg"))
				&& ($_FILES["event_cover"]["size"] < 5000000)
				&& in_array($extension, $allowedExts))
			{
				if ($_FILES["event_cover"]["error"] > 0)
			    {
			    	echo "Error: " . $_FILES["event_cover"]["error"] . "<br />";
			    }
				else
			    {
					
				    if (S3::putObjectFile($_FILES['event_cover']['tmp_name'], $bucket,'thumbs_photos/'.$event_id.'/'.$file_name)){
				        
				    } else {
				        echo "Failed to upload file.";
				    }
					
					if($edit)
						$query = $query.",";
					$query = $query."event_cover = '$file_name' ";
					$edit = true;
			    }
			}
			else
			{
			  	echo "<h1>Invalid file.</h1>";
			}
		}
		
		$query = $query."WHERE event_id = '$event_id'";
		// Debug: echo $query;
		if($edit)
		{	
			$result = mysql_query($query);
			if($result==0) {
					echo mysql_error($conn);
			}
			else
			{
				echo '<h2>Event '. $name . ' edited!</h2><br/>';
			}
		}

		if($_POST['emails'] != '')
		{
			$success = true;
			$str = mysql_real_escape_string($_POST['emails']);
			$emails = explode(",",$str);
			foreach ($emails as $email) {
				$email = trim($email);
				$result = mysql_query("SELECT email from users_events WHERE event_id = '$event_id' AND email = '$email'");
				$num=mysql_num_rows($result);
				if($num == 0)
				{
					$query = "INSERT INTO users_events VALUES('$email','$event_id')";
					//Debug: echo $query;
					$result = mysql_query($query);
					if($result==0) 
					{
						echo mysql_error($conn);
						$success = false;
						break;
					}
				}
			}

			if($success)
				echo '<h2>Email addresses added to the event '. $name . '!</h2><br/>';
		}

	}
	$result = mysql_query("SELECT *  FROM events");
?>
<html>
<head>
	<title>
		Edit an Event
	</title>
</head>
<body>
	<h1>
		Edit an Event! Fill in the form and hit submit.
	</h1>
	<form method='post' action='edit.php' enctype="multipart/form-data">
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
				<td> Event Date: </td>
				<td><input type='date' name='event_date'/></td>
			</tr>
			<tr>
				<td> Upload an album cover:</td>
				<td> <input type='file' name='event_cover'/></td>
			</tr>
			<tr>
				<td>
					Email Ids of Event Attendees:
				</td>
				<td>
					<input type='text' name='emails'/>
				</td>
			</tr>	
		 	<tr>
		 		<td> <input type='submit' name='submit' value='Edit'/> </td>
		 	</tr>
		</table>
</body>
</html>
