<?php

$appdir=".";
require_once('orgs.php');
if(isset($_REQUEST['file']) && isset($_REQUEST['event_id']))
{
	$file = $_REQUEST['file'];
	$event_id = $_REQUEST['event_id'];
	$gentxt = 
	"Parent Cocktail Reception
	      UM Museum of Art
	        April 26th, 2012";
	      
	$left='UM_Logo.jpg';
	$right='PSN.jpg';
	genTag($left,$right,$gentxt);
	$gentxt = str_replace("\n", '__n__', $gentxt);

	$pic1 = $left;
	$pic2 = $right;
	$txt = str_replace('__n__', "\n", $gentxt);
	$tag = genTag($pic1, $pic2, $txt, true);

	framePic("../photos/".$event_id.'/'.$file, $tag, '../photos/'.$event_id.'/p_'.$file);
}
else
{
	header('Location: ..');
}

?>
