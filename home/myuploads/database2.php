<?php
	ini_set('memory_limit','128M');
	require_once('../../lib2.php');
	require_once('../../phpThumb/phpthumb.class.php');
	$faces = 0;
	$recognized = 0;

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
			
			$timestamp = 500;
			$urls[] = $s3->getAuthenticatedURL($bucket,'uploads/'.$user_id.'/'.$image_id.'.jpg', $timestamp,false, false);	

			$query="SELECT * FROM users WHERE user_id = ANY (SELECT DISTINCT user_id FROM pictures WHERE image_id = ANY (SELECT image_id FROM pictures WHERE user_id = '$user_id')) OR user_id = ANY (SELECT user_id FROM users WHERE org_id = (SELECT org_id FROM users WHERE user_id = '$user_id')) OR user_id = '$user_id'";
			$result = mysql_query($query);
			if($result == 0)
			{
				echo mysql_error($conn);
			}
			else
			{
                                $tags = array();
				while($row = mysql_fetch_array($result,MYSQL_ASSOC))
				{
					$fb_id = $row['fb_id'];
					if($fb_id == '')
						continue;
			   		$api->setFBUser($fb_id,"180892932031091|lb2bJjoxDq4Yb282pT5lGOziNKw"); 
			   		$response = $api->faces_recognize($urls,$fb_id,"facebook.com");
					if(isset($response->photos))
					{
						foreach ($response->photos as $photo)
						{
							if (empty($photo->tags))
								continue;

							$width = $photo->width; // full image width
		                   	                $height = $photo->height; // Full image height
							$count=0;

							foreach ($photo->tags as $tag)
							{
								//echo $tag->width." ".$tag->height;

								if(($tag->width < 6.0) || ($tag->height < 6.0))
									continue;
								if($tag->attributes->face->confidence < 80)
									continue;
                                if(!isset($tags[$tag->tid]))
                                {
                            		$tags[$tag->tid] = 0;
                            		$faces++;
                            	}

                                if($tags[$tag->tid] == 2)
                                	continue;

								$count++;
								$fail = false;
								if (!empty($tag->uids))
								{
									$uid = $tag->uids[0]->uid;
									$conf = $tag->uids[0]->confidence;
									if ($conf >= 60){ 
										$query_str = "INSERT INTO pictures (image_id, user_id, event) VALUES ('$image_id','$user_id',0)";
							            $tags[$tag->tid] = 2;
							            $recognized++;
										$result2 = mysql_query($query_str);
										if($result2 == 0) {
					
											echo mysql_error($conn);
										}
				           			}
				           			else
				           			{
				           				$fail = true;
				           			}//end of if threshold  
				           			if($fail && $tags[$tag->tid] !=1 )
				           				crop($tag,$width,$height,$file_name,$count,$image_id);
		                    	}//end of if empty
		                    	else if($tags[$tag->tid] != 1)
		                    	{
		                    		crop($tag,$width,$height,$file_name,$count,$image_id);
		                    	}

							    if($tags[$tag->tid] == 0)
							    {
							    	$tags[$tag->tid] = 1;
							    }

		                	}//end of for tag
		          		}//end of for response
		       		}//end of isset
				}
			}

       		unlink('./uploads/'.$file_name);
       		unlink('./uploads/t_'.$file_name);
		}
		echo $faces.','.$recognized;
	}

	function crop($tag,$width,$height,$file_name,$count,$image_id)
	{
		global $bucket;
		global $s3;
		global $conn;

		$X_face = 10 + $tag->width; // Face Width % of full image. Add 10% for padding
		$Y_face = 20 + $tag->height; // Face Height % of full image. Add 20% for padding

		$X_face = ceil($X_face / 100 * $width); // Convert Face width from % to pixels
		$Y_face = ceil($Y_face / 100 * $height); // Convert Height width from % to pixels

		$X_pos = $tag->center->x; // face center X %
		$Y_pos = $tag->center->y; // face center Y %

		$X_pos = ceil(($X_pos / 100 * $width)-($X_face/2)); // Convert Face X position from % to pixels
		$Y_pos = ceil(($Y_pos / 100 * $height)-($Y_face/2)); // Convert Face Y position from % to pixels
		$src = imagecreatefromjpeg(urldecode('./uploads/'.$file_name));
		$dest = imagecreatetruecolor($X_face, $Y_face);
		
		imagecopy($dest,$src,0,0, $X_pos, $Y_pos, $X_face, $Y_face);
	        	
		//header('Content-Type: image/jpeg');
		$path = './uploads/'.$file_name.'_'.$count.'.jpg';
		imagejpeg($dest,$path,20);
		
		$query = "INSERT INTO faces (image_id) VALUES ('$image_id')";
		$result = mysql_query($query);
		if($result == 0)
		{
			echo mysql_error($conn);
		}
		else
		{

			$face_id = mysql_insert_id();
			if (!S3::putObjectFile($path, $bucket,'faces/'.$face_id.'.jpg')){
				echo "Failed to upload file to S3.<br/>";
			}
			//$timestamp = 500;
			//$url = $s3->getAuthenticatedURL($bucket,'faces/'.$face_id.'.jpg', $timestamp,false, false);	
			//echo '<tr class="'.$face_id.'"><td><img src="'.$url.'" height="100px" width="70px"></td><td><input id="'.$face_id.'" type="text" color="grey" value="Enter email_address here to share..."/></td></tr>';
		}
		
		imagedestroy($dest);
		imagedestroy($src);
		unlink($path);
	}

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
