<?php
require_once('config.php');
for($i=150;$i<=405;$i++){
$result = mysql_query("INSERT INTO event_images VALUES('$i',4)");
}
?>

