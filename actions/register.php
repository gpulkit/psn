<?php
session_set_cookie_params(0, '/', '.photosharingnetwork.com');
session_start();

include("../lib2.php");

$phone="";
if(isset($_POST["phone"])) {
	$phone=$_POST["phone"];
}

if( doRegister($_POST['ecode'],$_POST["username"],$_POST["password1"],$_POST["password2"],$phone,$_POST['univs'],$_POST['orgs'])) {
	
	doLogin($_POST["username"],$_POST["password1"]);
	header("Location: ../setup.php" );

} else {

	header("Location: ../" );
}


?>
