<?php
set_time_limit(3000);
require_once('./twilio-php/Services/Twilio.php');
require_once './src/class.phpmailer.php';
require_once './lib2.php';
require_once('./phpThumb/phpthumb.class.php');
$app_dir = './process';
require_once('./process/orgs.php');

$event = 4;

//search();
read_event_folder($event);

function search()
{
	global $api;
	global $fb_token;
	global $event;
	global $conn;
        global $AccountSid;
        global $AuthToken;
        global $from;
        $client = new Services_Twilio($AccountSid, $AuthToken);
	
	$query_str = "SELECT * FROM users INNER JOIN users_events ON users.email = users_events.email WHERE users_events.event_id ='$event'";
	
	$photoUrls = read_event_folder($event);

	$count_photos = 0;
	foreach ($photoUrls as $photoUrl)
	{
        //max photos per recognition call is 30, so break photos to groups if needed
		$urls[] = $photoUrl;
		$count_photos++;
		if (($count_photos % 30) == 0 || $count_photos == count($photoUrls))
		{	
			$result = mysql_query($query_str);
			if($result == 0) {
					
				echo mysql_error($conn);
			}
			
			while($row = mysql_fetch_array($result))
			{
				if($row['fb_id'])
				{
					$fb_id= $row['fb_id'];
					$user_id= $row['user_id'];

					echo '<br/>User: '.$row['name'].'<br/>';
                                        smtpmailer('gpulkit@umich.edu','Photos@photosharingnetwork.com','Photo Sharing Network','test3','testbody');
					
					$mailer_email->IsHTML(true);
					$mailer_email->addBCC("gupta.pulkit89@gmail.com","Pulkit Gupta");
					$mailer_email->addBCC("ryanjacobs16@gmail.com","Pulkit Gupta");
					$mailer_email->AddAddress($row['email'],$row['name']);
					$mailer_email->Subject = 'A new photo of you has been posted to your Photo Sharing Network account';

					$mailer_sms_3 = new PHPMailer();
                                        $mailer_sms_3->IsSMTP(); // enable SMTP
                                        $mailer_sms_3->SMTPSecure = 'ssl';
                                        $mailer_sms_3->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
                                        $mailer_sms_3->SMTPAuth = true;  // authentication enabled
                                        $mailer_sms_3->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
                                        $mailer_sms_3->Host = 'smtp.gmail.com';
                                        $mailer_sms_3->Port = 465; 
                                        $mailer_sms_3->Username = 'gupta.pukit89@gmail.com';  
                                        $mailer_sms_3->Password = 'tgip0518';           
					//$mailer_sms_3->FromName = 'Photo Sharing Network';
					$mailer_sms_3->From = 'gupta.pulkit89@gmail.com';
					$mailer_sms_3->addBCC("gupta.pulkit89@gmail.com","Pulkit Gupta");
					$mailer_sms_3->AddAddress($row['phnumber'].'@txt.att.net');
					$mailer_sms_3->AddAddress($row['phnumber'].'@tmomail.net');
					$mailer_sms_3->AddAddress($row['phnumber'].'@vzwpix.com');

			   		$api->setFBUser($fb_id,"180892932031091|lb2bJjoxDq4Yb282pT5lGOziNKw"); //app access token

			   		$response = $api->faces_recognize($urls,$row['fb_id'],"facebook.com");
					if(isset($response->photos))
					{
						//echo $response;
						foreach ($response->photos as $photo)
						{
		            		//skip empty tags and errors
							if (empty($photo->tags))
								continue;

							$url = $photo->url;
							$arr = explode("/", $url);

							$str = end ($arr);
							$arr = explode ("?",$str);
							$file = $arr[0];
							$arr = explode(".",$file);
							$image_id = $arr[0];
							
							$present = 0;

		            		//echo all found tags
							foreach ($photo->tags as $tag)
							{
		            			//ASSUMING ONE PERSON CANNOT APPEAR MORE THAN ONCE IN THE SAME PHOTOGRAPH
								if (!empty($tag->uids))
								{
		                        	//only interested in highest score for this tag
									$uid = $tag->uids[0]->uid;
									$conf = $tag->uids[0]->confidence;

		                        	//only print if confidence is higher than recommended threshold
									if ($conf >= 60){ 
										echo "<br><br>Recognized ".$row['name']." in ".$file." for UID ".$row['fb_id']. " confidence: ".$conf."<br/>";

										$query_str = "INSERT INTO pictures (image_id, user_id, event) VALUES ('$image_id','$user_id',1)";
										
										$result2 = mysql_query($query_str);
										if($result2 == 0) {
					
											echo mysql_error($conn);
										}

										//send mail only once
										if(!$present)
										{
											echo $url.$file."<br/>";
											$mailer_email->Body = "<p>";
											$mailer_email->AddEmbeddedImage('./1.jpg','1', $file); // attach file logo.jpg
											$mailer_email->Body .="<img src='cid:1' width='150px' height='100px'> ";	
											$mailer_email->Body .="<br><br>This photo and others are now accessible on your personal private photo archive page on www.EmoryPartyPics.com, a site powered by the Photo Sharing Network platform.<br><br><font size='1'>Image not of you?  Please reply to this email to let us know. We’re still making improvements to our software.<br>Have other comments, or ideas for how to improve our system? Please let us know - We want to hear from you! Thanks!</font></p>";

											if(!$mailer_email->Send())
											{
												echo "<br>Email Message was not sent";
												echo "<br>Mailer Error: " . $mailer_email->ErrorInfo;
												//exit;
											}
											else{
												echo "<br>Email sent<br/>";
											}
											$mailer_email->ClearAllRecipients();
											$present=1;	
										}

										$mailer_sms_3->Body  ="
										You've got a new photo!";
										$mailer_sms_3->Body .='
										www.photosharingnetwork.com/d.php?f='.$image_id;
										$mailer_sms_3->Body .= "
										Click on the link above to view.";
										
										if($row['phnumber']!=''){
                                                                                    $to = $row['phnumber'];
        	                                                                    $body ="You've got a new photo!\nwww.photosharingnetwork.com/d.php?f=$image_id\nClick on the link above to view.";

                                                                                        //$client->account->sms_messages->create($from, $to, $body);
											//if(!$mailer_sms_3->Send())
											{
												echo "<br>SMS was not sent";
												echo "<br>Mailer Error: " . $mailer_sms_3->ErrorInfo;
												//exit;
											}
											//else
												echo "<br>SMS sent<br/>";

										}

										$mailer_sms_3->ClearAllRecipients();
				           			}//end of if threshold  
		                    	}//end of if empty
		                	}//end of for tag
		          		}//end of for response
		       		}//end of isset
				}//if(fb_id)
		    }//end of while(uids)
       		$urls=array();
       		$count_photos = 0;
       	}//end of if count
	}//end of for photourl
}//end of function

function read_event_folder($event)
{
	global $s3;
	global $conn;
	global $bucket;

	$urls = array();
	if ($handle = opendir('./photos/'.$event.'/')) {
		echo 'Reading event '.$event.'....<br/>';
   		while (false !== ($file = readdir($handle))){
    		if ($file != "." && $file != "..")
	 	 	{
	 	 		echo $file.'<br/><br/>';
	 	 		/*
                                $query_str = "INSERT INTO event_images (event_id) VALUES ('$event')";
	 	 		$result = mysql_query($query_str);
	 	 		
	 	 		if($result == 0) {
	 	 				
	 	 			echo mysql_error($conn);
	 	 		}

	 	 		$image_id = mysql_insert_id();
                                */
	 	 		$image_id = 0;
	 	 		
	 	 		s3rename('photos',$bucket,$file,$event,$image_id);
				
				process($event,$file);
				s3rename('processed',$bucket,'p_'.$file,$event,$image_id);
				generateThumb($event,$file);
				s3rename('thumbs_photos',$bucket,'t_'.$file,$event,$image_id);
				generateThumb($event,'p_'.$file);
				s3rename('thumbs_processed',$bucket,'t_p_'.$file,$event,$image_id);

			    $timestamp = 500;
			    $urls[] = $s3->getAuthenticatedURL($bucket,'photos/'.$event.'/'.$image_id.'.jpg', $timestamp, false, false);	

			    unlink('./photos/'.$event.'/'.$file);
			    unlink('./photos/'.$event.'/t_'.$file);
			    unlink('./photos/'.$event.'/p_'.$file);
			    unlink('./photos/'.$event.'/t_p_'.$file);

			}
		}
		//TODO: delete the contents
		closedir($handle);
		echo 'Reading event '.$event.' completed.<br/><br/>';
	}
	
	return $urls;		
}

function process($event,$file)
{
/*
$gentxt =
"Alpha Epsilon Phi - Phi Stock 
        Indiana University
      September 29th, 2012";*/
$gentxt = 
"Grilled Cheese with the G Phi B's
        September 27th, 2012";
	      
	$left='./process/GammaPhi.jpg';
	$right='./process/PSN.jpg';
	genTag($left,$right,$gentxt);
	$gentxt = str_replace("\n", '__n__', $gentxt);

	$pic1 = $left;
	$pic2 = $right;
	$txt = str_replace('__n__', "\n", $gentxt);
	$tag = genTag($pic1, $pic2, $txt, true);

	framePic("./photos/".$event.'/'.$file, $tag, './photos/'.$event.'/p_'.$file);
}

function s3rename($folder,$bucket,$file,$event,$image_id)
{
	global $s3;

	if (!S3::putObjectFile('./photos/'.$event.'/'.$file, $bucket,$folder.'/'.$event.'/'.$image_id.'.jpg')){
		echo "Failed to upload file to S3.<br/>";
	}
}

function generateThumb($event,$file)
{
	$phpThumb = new phpThumb();
	$directory = '/'.$event.'/';
	$image = $directory.$file;
	$phpThumb->setSourceFilename('../photos'.$image);  // for static demo only
	$phpThumb->setParameter('w', '300');
	$phpThumb->setParameter('h', '300');
	$phpThumb->setParameter('config_output_format', 'jpeg');
	$phpThumb->setParameter('config_imagemagick_path', '/usr/local/bin/convert');
	$output_filename = '../photos'.$directory.'t_'.basename($image);
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
