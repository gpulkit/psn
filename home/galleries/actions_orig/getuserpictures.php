<?php

session_start();

require_once("../../../lib2.php");

if(isset($_SESSION["fb_id"])) {
	printUserPictures($_SESSION["fb_id"], $_POST["page"], $_POST["folder"]);
} else {
	echo "Error: Please login!";
}



?>
