<?php
require_once('config.php');
if(isset($_POST['f']))
{
	if($_POST['f'] == 'event')
	{
		printEventPictures($_POST['event_id'], 1, $_POST['folder']);
	}
	else if($_POST['f'] == 'user')
	{
		printUserPictures($_POST['user_id'], 1, $_POST['folder']);
	}
}
function timestamp()
{
  	$expireinterval = 31536000;
	$gracetime = $expireinterval + 10;
	$timestamp = time();
	$timestamp -= ($timestamp % $expireinterval);
	$timestamp += $expireinterval + $gracetime;
	$timestamp = $timestamp - time();
	return $timestamp;
}

function printUserPictures($user_id = 0, $page = 1, $folder = "photos") {

	$pictures_per_page = 48;	
	global $conn;
	global $s3;
	global $bucket;
	$result = mysql_query("SELECT *  FROM pictures WHERE user_id = '$user_id'", $conn);
	if($user_id == 0) {
		$count = 0;
	} 
	else {
		if($result==0) {
			echo mysql_error($conn);
		}
		$count = mysql_num_rows($result);
	}
	
	//*******************
	// Print framing option
	//*******************
	echo '<div class="frame_options">';
	if ($folder == "processed") {
		echo '<input onclick="toggle(\'photos\')" id="framedcheckbox1"  type="checkbox"  checked="checked" value="framed" /> Framed Photos';
	
	} else {
		echo '<input onclick="toggle(\'processed\')" id="framedcheckbox1"  type="checkbox"  value="framed" /> Framed Photos';
	}
	echo '</div>';

	//*******************
	// Print pagination
	//*******************
	echo '<div class="pagination">';
	echo '<a href="../actions/logout.php">Logout</a>';
	echo '</div>';
	echo '<div style="clear:both"></div>';
	
	//*******************
	// Print empty folder message
	//*******************
	if($count==0) {
		echo '<div class="mainmessage">Nothing here yet!</div>';
		echo '<div style="clear:both"></div>';
		return;
	}
	
	//*******************
	// Print thumbnails
	//*******************
	//mysql_data_seek($result, ($page-1)*$pictures_per_page );

	$i = 0;
	while ( ($row = mysql_fetch_array($result, MYSQL_ASSOC))){// && ($i < $pictures_per_page) ) {
		$image_id = $row['image_id'];
		$result2 = mysql_query("SELECT event_id FROM event_images WHERE image_id = '$image_id'" , $conn);
		$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);	
		$event_id = $row2['event_id'];

		$i++;
		$timestamp = timestamp();
                if($row['event'] == 1)
                {
	  	    $image_link = $s3->getAuthenticatedURL($bucket,$folder.'/'.$event_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);
		    $thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_'.$folder.'/'.$event_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	
                }
                else 
                {
	  	    $image_link = $s3->getAuthenticatedURL($bucket,'uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);
		    $thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	
                }
		$description = "No description";
		$title = "Full Image";
	
		echo '<div class="thumb_container">';
		echo '<div class="thumb_div">';
		//echo '<a target="_new" class="thumb" href="'.$imgsrc.'">';
		//echo '<div class="overlay_download"> Download </div>';
		//echo '</a>';
		echo '<div class="thumb_pic_user">';
		echo '<a class="thumb" href="'.$image_link.'">';
        	//echo '<img style="position:relative" src="'.$thumb_link.'" title="'.$title.'" onmouseover="this.src=\''.$image_link.'\'; this.style.zIndex=\'1\'; this.style.position=\'absolute\';" onmouseout="this.src=\''.$thumb_link.'\'; this.style.zIndex=\'0\'; this.style.position=\'relative\';" />';
			echo '<img src="'.$thumb_link.'" title="'.$title.'" />';	
        	echo '</a>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo "\n";
	}
	echo '<div style="clear:both"></div>';
	mysql_free_result($result);
}

function printUserUploads($user_id = 0) {
	
	$pictures_per_page = 24;
	global $conn;
	global $s3;
	global $bucket;
	$result = mysql_query("SELECT image_id FROM useruploads WHERE user_id = '$user_id' ORDER BY image_id DESC");
	if($user_id == 0) {
		$count = 0;
	} else {
		if($result==0) {
			echo mysql_error($conn);
		}
		$count = mysql_num_rows($result);
	}
	
	//*******************
	// Print logout
	//*******************
	echo '<div class="pagination">';
	echo '<a href="../../actions/logout.php">Logout</a>';
	echo '</div>';
	echo '<div style="clear:both"></div>';
	
	//*******************
	// Print empty folder message
	//*******************
	if($count==0) {
		echo '<div class="mainmessage">Nothing here yet!</div>';
		echo '<div style="clear:both"></div>';
		return;
	}
	
	//*******************
	// Print thumbnails
	//*******************
	//mysql_data_seek($result, ($page-1)*$pictures_per_page );
	$i = 0;
	while ( ($row = mysql_fetch_array($result, MYSQL_ASSOC))){// && ($i < $pictures_per_page) ) {
		$timestamp = timestamp();
	  	$thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	
	    $image_link = $s3->getAuthenticatedURL($bucket,'uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	

		$i++;
		$root_dir = "../actions/mobileuploads/";
		//$imgsrc   = $root_dir.$row["imgsrc"];
		$description = "No description";
		$title = "Full Image";
		echo '<div class="thumb_container">';
		echo '<div class="thumb_div">';
		//echo '<a target="_new" class="thumb" href="'.$imgsrc.'">';
		//echo '<div class="overlay_download"> Download </div>';
		//echo '</a>';
		echo '<div class="thumb_pic_user">';
		echo '<a class="thumb" href="'.$image_link.'">';
        	echo '<img src="'.$thumb_link.'" title="'.$title.'" />';	
        	echo '</a>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo "\n";
	}

	echo '<div style="clear:both"></div>';
	mysql_free_result($result);
}

function printEvents($email=0, $user_id = 0, $page = 1) {
	
	global $conn;
	global $s3;
	global $bucket;

	$pictures_per_page = 48;

	#$result = mysql_query("SELECT event_id from users_events WHERE email = '$email'");
	$result = mysql_query("SELECT event_id FROM events WHERE org_id = (SELECT org_id FROM users WHERE user_id = '$user_id') UNION SELECT event_id FROM users_events WHERE email = '$email'");

	if($result==0) {
		echo mysql_error($conn);
	}
	$count = mysql_num_rows($result);
	/*
	//*******************
	// Print empty folder message
	//*******************
	
	if($count==0) {
		echo '<div class="mainmessage">Nothing here yet!</div>';
		echo '<div style="clear:both"></div>';
		return;
	}*/

		$result2 = mysql_query("SELECT image_id FROM useruploads WHERE user_id = '$user_id'");
		$timestamp = 500;
		if(mysql_num_rows($result) == 0)
			$thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_photos/NewGallery.JPG', $timestamp, false, false);
		else
		{
			$row = mysql_fetch_array($result2, MYSQL_ASSOC);
			$thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	
		}
	
	echo '<div style="clear:both"></div>';

		echo '<div class="thumb_container">';
	    echo '<div class="thumb_div">';
	    echo '<div class="album_title">';
	    echo 'My Uploads';
	    echo '</div>';
	    echo '<div class="thumb_pic_album">';
	    echo '<a class="thumb" href="./?e=0" >';
            echo '<img src="'.$thumb_link.'" title="My uploads" /><br/>';
	    echo '</a>';
	    echo '</div>';
	    echo '</div>';
	    echo '</div>';

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$eventid = $row['event_id'];
		$result2 = mysql_query("SELECT event_name, event_cover  FROM events WHERE event_id = '$eventid'" , $conn);
		$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);

	    $timestamp = timestamp();
            if($row2['event_cover'] == 'NewGallery.JPG')
    	        $thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_photos/'.$row2["event_cover"], $timestamp, false, false);	
            else
	        $thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_photos/'.$eventid.'/'.$row2["event_cover"], $timestamp, false, false);	
	    $description = "No description";
	    $title = $row2["event_name"];
	
	    echo '<div class="thumb_container">';
	    echo '<div class="thumb_div">';
	    echo '<div class="album_title">';
	    echo $title;
	    echo '</div>';
	    echo '<div class="thumb_pic_album">';
	    echo '<a class="thumb" href="./?e='.$eventid.'" >';
            echo '<img src="'.$thumb_link.'" title="'.$title.'" /><br/>';
	    echo '</a>';
	    echo '</div>';
	    echo '</div>';
	    echo '</div>';
	}
	
	echo '<div style="clear:both"></div>';
	mysql_free_result($result);
}

function printEventPictures($user_id, $event_id, $page = 1, $folder = "processed") {
	
	$pictures_per_page = 100;
	global $conn;
	global $s3;
	global $bucket;

	$result = mysql_query("SELECT DISTINCT events.event_id AS event_id, event_images.image_id AS image_id , events.event_name AS name FROM event_images, events WHERE events.event_id = event_images.event_id AND events.event_id=".$event_id);
	$count = 0;
	if($result==0) {
		echo mysql_error($conn);
	}
	else
	{
		$count = mysql_num_rows($result);
	}
	//*******************
	// Print navigation
	//*******************
	echo '<div class="topnavigation">';
	echo '<a  href=".">';
	echo 'Extended Galleries';
	echo '</a>'; 
	echo ' :: '; 
	if($count) {
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		echo '<a onclick="showEvent('.$row["event_id"].')"  href="javascript:void(0);">';
		echo $row["name"];
		echo '</a>'; 
		mysql_data_seek($result, 0);
	}
	echo '</div>';
	
	//*******************
	// Print framing option
	//*******************
	echo '<div class="frame_options">';
	if ($folder == "processed") {
		echo '<input onclick="toggle(\'photos\')" id="framedcheckbox2"  type="checkbox"  checked="checked" value="framed" /> Framed Photos';
	
	} else {
		echo '<input onclick="toggle(\'processed\')" id="framedcheckbox2"  type="checkbox"  value="framed" /> Framed Photos';
	}

	echo '</div>';
	
	//*******************
	// Print logout
	//*******************
	echo '<div class="pagination">';
	echo '<a href="../../actions/logout.php">Logout</a>';
	echo '</div>';
	echo '<div style="clear:both"></div>';
	
	//*******************
	// Print empty folder message
	//*******************
	if($count==0 && $event_id != 0) {
		echo '<div class="mainmessage">Nothing here yet!</div>';
		echo '<div style="clear:both"></div>';
		//return;
	}
	
	//*******************
	// Print content
	//*******************
	//mysql_data_seek($result, ($page-1)*$pictures_per_page );
	
	$i = 0;
	while (($row = mysql_fetch_array($result, MYSQL_ASSOC))) {
		
		$i++;
		$timestamp = timestamp();
		$image_link = $s3->getAuthenticatedURL($bucket,$folder.'/'.$event_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);
		$thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_'.$folder.'/'.$event_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	
		$description = "No description";
		$title = "Full Image";
		echo '<div class="thumb_container">';
		echo '<div class="thumb_div">';
		//echo '<a   target="_new" class="thumb" href="'.$imgsrc.'">';
		//echo '<div class="overlay_download"> Download </div>';
		//echo '</a>';
		echo '<div class="thumb_pic">';
		echo '<a class="thumb" href="'.$image_link.'">';
        	echo '<img src="'.$thumb_link.'" title="'.$title.'" />';
        	echo '</a>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	if($event_id == 0)
	{
		$result = mysql_query("SELECT image_id FROM useruploads WHERE user_id = '$user_id'");

		while (($row = mysql_fetch_array($result, MYSQL_ASSOC))) {		
			$i++;
			$timestamp = timestamp();
			$image_link = $s3->getAuthenticatedURL($bucket,'uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);
			$thumb_link = $s3->getAuthenticatedURL($bucket,'thumbs_uploads/'.$user_id.'/'.$row["image_id"].'.jpg', $timestamp, false, false);	
			$description = "No description";
			$title = "Full Image";
			echo '<div class="thumb_container">';
			echo '<div class="thumb_div">';
			//echo '<a   target="_new" class="thumb" href="'.$imgsrc.'">';
			//echo '<div class="overlay_download"> Download </div>';
			//echo '</a>';
			echo '<div class="thumb_pic">';
			echo '<a class="thumb" href="'.$image_link.'">';
	        	echo '<img src="'.$thumb_link.'" title="'.$title.'" />';
	        	echo '</a>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
	
	echo '<div style="clear:both"></div>';
	mysql_free_result($result);
}

function doLogin($username, $password) {
	
	global $conn;
	$un = mysql_real_escape_string($username);
	$ps = md5($password);
	$result = mysql_query("SELECT user_id, email, fb_id FROM users WHERE email='$un' AND password='$ps'", $conn);
	if($result==0) {
		echo mysql_error($conn);
	}
	
	$count = mysql_num_rows($result);
	if($count > 0) {
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$_SESSION["fb_id"] = $row["fb_id"];
		$_SESSION["user_id"] = $row["user_id"];
		$_SESSION["email"] = $row["email"];
		return true;
	} else {
		return false;
	}
}

function checkUsername($username) {
	return true;
}

function checkPasswords($password1, $password2) {
	if( $password1 == $password2 ) {
		return true;	
	} else {
		return false;
	}
}

function doRegister($ecode, $username, $password1, $password2, $phone="", $univ, $org) {
	
	global $conn;
	global $bucket;

	$un = mysql_real_escape_string($username);
	$ps = md5($password1);
	$ph = mysql_real_escape_string($phone);
	$univ = mysql_real_escape_string($univ);
	$org_id = mysql_real_escape_string($org);

	$result = mysql_query("INSERT INTO users (email, password, phnumber, org_id) VALUES ('$un','$ps','$ph','$org_id')", $conn);
	$id = mysql_insert_id($conn);
	
	if($ecode == '010')
	{
		$result = mysql_query("INSERT INTO users_events (email, event_id) VALUES ('$un',10)");
	}

	if($ecode == '013')
	{
		$result = mysql_query("INSERT INTO users_events (email, event_id) VALUES ('$un',13)");
	}	
        
        if($ecode == '014')
	{
		$result = mysql_query("INSERT INTO users_events (email, event_id) VALUES ('$un',14)");
	}


	if($id) {
		if (S3::putObject(
		    'a', 
		    $bucket,
		    'uploads/'.mysql_insert_id().'/',
		    S3::ACL_PRIVATE,
		    array(),
		    array( // Custom $requestHeaders
		        "Content-Type" => "binary/octet-stream",
		        "Cache-Control" => "max-age=315360000",
		        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
		    )))
		{
		    
		} else {
		    echo "Failed to create folder in S3.";
		}

		if (S3::putObject(
		    'a', 
		    $bucket,
		    'thumbs_uploads/'.mysql_insert_id().'/',
		    S3::ACL_PRIVATE,
		    array(),
		    array( // Custom $requestHeaders
		        "Content-Type" => "binary/octet-stream",
		        "Cache-Control" => "max-age=315360000",
		        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
		    )))
		{
		    
		} else {
		    echo "Failed to create folder in S3.";
		}
		/*
		if(!mkdir('../home/myuploads/uploads/'.mysql_insert_id(),0777))
		{
		    //echo "Failed to create user folder on PSN.";
		}
		*/
		return true;
	} else {
		echo mysql_error();
		return false;
	}
}

function doRegisterUpload($userid, $imagename) {
	
	$conn = connectToDatabase();
	$img = mysql_escape_string($imagename);
	$us = $userid;
	$result = mysql_query("INSERT INTO useruploads ( user_id, imgsrc ) VALUES ('$us','$img')", $conn);
	$id = mysql_insert_id($conn);
	
	if($id) {
		return true;
	} else {
		return false;
	}
}
?>
