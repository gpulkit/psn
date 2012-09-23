<?php
	require_once('../lib2.php');

	if(isset($_POST['submit']))
	{
		$name ='';
		$date= '';
		$file ='';
		$success = true;
		$file=false;

		if($_POST['event_name']!='')
		{
			$name = mysql_real_escape_string($_POST['event_name']);
		}
		else
		{
			echo "<h1> Error: Enter a name.</h1>";
			$success = false;
		}

		if($_POST['event_date']!='')
		{
			$date = mysql_real_escape_string($_POST['event_date']);
		}
		else
		{
			echo "<h1> Error: Enter the date.</h1>";
			$success = false;
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
				&& ($_FILES["event_cover"]["size"] < 20000)
				&& in_array($extension, $allowedExts))
			{
				if ($_FILES["event_cover"]["error"] > 0)
			    {
			    	echo "Error: " . $_FILES["event_cover"]["error"] . "<br />";
			    	$success = false;
			    }
				else
			    {
					$file = true;   
			    }
			}
			else
			{
			  	echo "<h1>Invalid file.</h1>";
			  	$success = false;
			}
		}
		$text = $_POST['process_text'];
		$leftlogo = $_POST['process_leftlogo'];
		$rightlogo = $_POST['process_rightlogo'];

		if($success)
		{
			if($file)
			{
				$result = 
					mysql_query(
						"INSERT into events (event_name,event_date,event_cover,process_text,process_leftlogo,process_rightlogo) VALUES ('$name','$date','$file_name','$text','$leftlogo','$rightlogo')");
			}
			else
				$result = mysql_query("INSERT into events (event_name,event_date,process_text,process_leftlogo,process_rightlogo) VALUES ('$name','$date','$text','$leftlogo','$rightlogo')");

			if($result==0) {
					echo mysql_error($conn);
			}
			else
			{
				echo '<h2>New event '. $name . ' created!</h2><br/>';
			}
			
			$event_id = mysql_insert_id();

			//photos
			if (S3::putObject(
				'a', 
				$bucket,
				'photos/'.mysql_insert_id().'/',
				S3::ACL_PRIVATE,
        		array(),
		        array( // Custom $requestHeaders
		        	"Content-Type" => "binary/octet-stream",
		            "Cache-Control" => "max-age=315360000",
		            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
		        )))
			{
			    
			} else {
			    echo "Failed to create folder";
			}

			//processed
			if (S3::putObject(
				'a', 
				$bucket,
				'processed/'.mysql_insert_id().'/',
				S3::ACL_PRIVATE,
        		array(),
		        array( // Custom $requestHeaders
		        	"Content-Type" => "binary/octet-stream",
		            "Cache-Control" => "max-age=315360000",
		            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
		        )))
			{
			    
			} else {
			    echo "Failed to create folder";
			}			

			//thumbs_photos
			if (S3::putObject(
				'a', 
				$bucket,
				'thumbs_photos/'.mysql_insert_id().'/',
				S3::ACL_PRIVATE,
        		array(),
		        array( // Custom $requestHeaders
		        	"Content-Type" => "binary/octet-stream",
		            "Cache-Control" => "max-age=315360000",
		            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
		        )))
			{
			    
			} else {
			    echo "Failed to create folder";
			}

			//New Gallery
			if(!$file)
			{
				if (S3::putObject(
					'../NewGallery.JPG', 
					$bucket,
					'thumbs_photos/'.mysql_insert_id().'/NewGallery.JPG',
					S3::ACL_PRIVATE,
	        		array(),
			        array( // Custom $requestHeaders
			        	"Content-Type" => "binary/octet-stream",
			            "Cache-Control" => "max-age=315360000",
			            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
			        )))
				{
				    
				} else {
				    echo "Failed to upload New Gallery to S3.";
				}
			}

			//thumbs_processed
			if (S3::putObject(
				'a', 
				$bucket,
				'thumbs_processed/'.mysql_insert_id().'/',
				S3::ACL_PRIVATE,
        		array(),
		        array( // Custom $requestHeaders
		        	"Content-Type" => "binary/octet-stream",
		            "Cache-Control" => "max-age=315360000",
		            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
		        )))
			{
			    
			} else {
			    echo "Failed to create folder";
			}

			if($file)
			{
				//Event cover
				if (S3::putObjectFile($_FILES['event_cover']['tmp_name'], $bucket,'thumbs_photos/'.$event_id.'/'.$file_name)){
					        
			   	} else {
			        echo "Failed to upload file.";
			    }
		    }

			if(!mkdir('../photos/'.mysql_insert_id()))
			{
				echo "Failed to create event folder on PSN";
			}
			
		}
	}
?>
<html>
<head>
	<title>
		Create an Event
	</title>
</head>
<body>
	<h1>
		Create a new Event! Fill in the form and hit submit.
	</h1>
	<form method='post' action='new.php' enctype="multipart/form-data">
		<table>
			<tr>
				<td> Event Name: </td>
				<td><input type='text' name='event_name'/></td>
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
				<td> Event text: </td>
				<td> <input type='text' name='process_text'/></td>
			</tr>
			<tr>
				<td> Left logo: </td>
				<td> <input type='text' name='process_leftlogo'/></td>
			</tr>
			<tr>
				<td> Right logo: </td>
				<td> <input type='text' name='process_rightlogo'/></td>
			</tr>
		 	<tr>
		 		<td> <input type='submit' name='submit' value='Create'/> </td>
		 	</tr>
		</table>
</body>
</html>
