<?php

session_start();

include("../lib2.php");

if( doLogin($_POST["username"],$_POST["password"]) ) {
	header("Location: ../home" );

} else {
	header("Location: ../login.php?w=true" );

}


?>