<?php
	ini_set('memory_limit','128M');
	require_once('../../lib2.php');
	require_once('../../phpThumb/phpthumb.class.php');

	if(isset($_REQUEST['u']))
	{
		$user_id = mysql_real_escape_string($_REQUEST['u']);
		$query = "INSERT INTO useruploads (user_id) VALUES ('$user_id')";
		$result = mysql_query($query);

		if($result == 0)
		{
			echo mysql_error($conn);
		}
		else
		{

			$image_id = mysql_insert_id();
			$file_name = mysql_real_escape_string($_REQUEST['file']);
			
			if (!S3::putObjectFile('./uploads/'.$file_name, $bucket,'uploads/'.$user_id.'/'.$image_id.'.jpg')){
			        echo "Failed to upload file to S3.<br/>";
			}

			generateThumb('',$file_name);

			if (!S3::putObjectFile('./uploads/t_'.$file_name, $bucket,'thumbs_uploads/'.$user_id.'/'.$image_id.'.jpg')){
			    echo "Failed to upload file to S3.<br/>";
			}

			unlink('./uploads/'.$file_name);
			unlink('./uploads/t_'.$file_name);
			
			printUserUploads($user_id);
		}
	}
	else
		echo 'not set';

	function generateThumb($user_id,$file)
	{
		$phpThumb = new phpThumb();
		$directory = '/'.$user_id.'/';
		$image = $directory.$file;
		$phpThumb->setSourceFilename('../home/myuploads/uploads'.$image);  // for static demo only
		$phpThumb->setParameter('w', '300');
		$phpThumb->setParameter('h', '300');
		$phpThumb->setParameter('config_output_format', 'jpeg');
		$phpThumb->setParameter('config_imagemagick_path', '/usr/local/bin/convert');
		$output_filename = '../home/myuploads/uploads'.$directory.'t_'.basename($image);
		if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
			if ($output_filename || $capture_raw_data) {
			
				if ($phpThumb->RenderToFile($output_filename)) {
					// do something on success
					//echo $output_filename;
					//echo 'Successfully rendered.<br>';
					//<img src="'.$directory.basename($output_filename).'">';
				} else {
					// do something with debug/error messages
					echo 'Failed (size=):<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
				}
				$phpThumb->purgeTempFiles();
			} else {
				$phpThumb->OutputThumbnail();
			}
		} else {
			// do something with debug/error messages
			echo 'Failed (size=).<br>';
			echo '<div style="background-color:#FFEEDD; font-weight: bold; padding: 10px;">'.$phpThumb->fatalerror.'</div>';
			echo '<form><textarea rows="10" cols="200" wrap="off">'.htmlentities(implode("\n* ", $phpThumb->debugmessages)).'</textarea></form><hr>';
		}
	}
	
?>
