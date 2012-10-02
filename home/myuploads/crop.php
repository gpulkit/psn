<?php
require_once('../../config.php');

			$fb_id = '2602516';	
	   		$api->setFBUser($fb_id,"180892932031091|lb2bJjoxDq4Yb282pT5lGOziNKw"); 
	   		$urls = 'http://testpsn.s3.amazonaws.com/uploads/26/7.jpg?AWSAccessKeyId=AKIAJDRNRQZJJ2NPULPA&Expires=1387584010&Signature=A2ZA%2B0U%2Fq%2BEASpYL22vT5nNw0gQ%3D';
	   		$response = $api->faces_recognize($urls,$fb_id,"facebook.com");
			if(isset($response->photos))
			{
				$count=0;
				foreach ($response->photos as $photo)
				{
					$count++;
					if (empty($photo->tags))
						continue;

					$url = $photo->url;
					$arr = explode("/", $url);

					$str = end ($arr);
					$arr = explode ("?",$str);
					$file = $arr[0];
					
					$present = 0;
					$count=0;
					$width = $photo->width; // full image width
                   	$height = $photo->height; // Full image height
            		//echo all found tags
					foreach ($photo->tags as $tag)
					{
						$count++;
            			//ASSUMING ONE PERSON CANNOT APPEAR MORE THAN ONCE IN THE SAME PHOTOGRAPH
						if (!empty($tag->uids))
						{
                        	//only interested in highest score for this tag
							$uid = $tag->uids[0]->uid;
							$conf = $tag->uids[0]->confidence;

                        	//only print if confidence is higher than recommended threshold
							if ($conf >= 60){ 
								//echo "<br><br>Recognized in ".$file." for UID confidence: ".$conf."<br/>"

		           			}//end of if threshold  
                    	}//end of if empty

                   		// These are results 
                   		$X_face = 10 + $tag->width; // Face Width % of full image. Add 10% for padding
                   		$Y_face = 20 + $tag->height; // Face Height % of full image. Add 20% for padding

                   		$X_face = ceil($X_face / 100 * $width); // Convert Face width from % to pixels
                   		$Y_face = ceil($Y_face / 100 * $height); // Convert Height width from % to pixels

                   		$X_pos = $tag->center->x; // face center X %
                   		$Y_pos = $tag->center->y; // face center Y %

                   		$X_pos = ceil(($X_pos / 100 * $width)-($X_face/2)); // Convert Face X position from % to pixels
                   		$Y_pos = ceil(($Y_pos / 100 * $height)-($Y_face/2)); // Convert Face Y position from % to pixels
                   		$src = imagecreatefromjpeg(urldecode('./uploads/DSC_9814.JPG'));
                   		$dest = imagecreatetruecolor($X_face, $Y_face);
                   		
                   		imagecopy($dest,$src,0,0, $X_pos, $Y_pos, $X_face, $Y_face);

                   		header('Content-Type: image/jpeg');
                   		imagegif($dest,'./uploads/'.$count.'.jpg');

                   		imagedestroy($dest);
                   		imagedestroy($src);
                    	
                	}//end of for tag
          		}//end of for response
       		}//end of isset
       		else
       			echo 'no response';


 
?>