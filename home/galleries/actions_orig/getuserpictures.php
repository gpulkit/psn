<?php
session_start();
require_once("../../../lib2.php");
if(isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}
else
{
	header('Location: ..');
}

	printUserPictures($user_id, $_POST["page"], $_POST["folder"]);

?>
