<?php

session_start();

include("../lib.php");

doLogin($_POST["username"],$_POST["password"]);

//doLogin($_GET["username"],$_GET["password"]);

?>