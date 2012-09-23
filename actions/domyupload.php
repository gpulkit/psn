<?php

session_start();

include("../lib2.php");


if ( ($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") ) {
	  if ($_FILES["file"]["error"] > 0) {
			//do nothing
	   } else {
				if (file_exists("mobileuploads/" . $_FILES["file"]["name"])) {
				 //do nothing
				} else {
				  move_uploaded_file($_FILES["file"]["tmp_name"], "mobileuploads/" . $_SESSION["id"]."_".$_FILES["file"]["name"]);
				  doRegisterUpload( $_SESSION["id"] , $_SESSION["id"]."_".$_FILES["file"]["name"]);
				}
	  }
} else {
  //do nothing
}

header("Location: /home/myuploads/" );


?>