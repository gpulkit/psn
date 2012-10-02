<?php
session_start();
require_once("../lib2.php");
require_once("../FaceRestClient.php");
//initialize API object with API key and Secret
if(isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}
else
{
	header('Location: ..');
}

$n = 1;
if(isset($_REQUEST['n']))
{
	$n = '';
}


$fb_id = 0;
if(isset($_REQUEST["fb_id"])){
	
	$fb_id = $_REQUEST["fb_id"];
	$fb_token = $_REQUEST["fb_token"];
	$name = $_REQUEST["name"];
	
	$fb_id_sql = mysql_real_escape_string($fb_id);
	$fb_token_sql = mysql_real_escape_string($fb_token);

	$email = $_SESSION["email"];
	$result = mysql_query("UPDATE users SET name = '$name',fb_id = '$fb_id_sql' WHERE email = '$email'");
	
	if($result==0) {
		echo mysql_error($conn);
	}

	global $api;
	$api->setFBUser($fb_id,$fb_token);
	$api->faces_train($fb_id."@facebook.com");
	$api->faces_train("friends@facebook.com");
}
else if(isset($_SESSION["fb_id"])) {
	$fb_id = $_SESSION["fb_id"];
}



$page = 1;
if(isset($_GET["p"])) {
	$page = $_GET["p"];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photo Sharing Network</title>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link type='text/css' href='../css/gallery.css' rel='stylesheet' media='screen' />

<script type="text/javascript" src="../js/jquery-1.7.2.min.js" ></script>
<script type='text/javascript' src='../js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='../js/gallery7.js'></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.19.custom.min.js" ></script> 


<script type="text/javascript">
function toggle(n)
{
	$('div.content').load(
		'../lib2.php',
		{f:'user',
		user_id:'<?php echo $user_id ?>',
		folder: n});
</script>

<script type="text/javascript">
/*$(document).ready(function(){
		parent.document.getElementById('pictureframe').src='<?php echo "soon".$n.".html";?>';
		document.getElementById('light').style.display='block';
		document.getElementById('fade').style.display='block';
    });*/
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34914677-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body>

<div id='light' class='white_content'>
<iframe id='pictureframe' src="" width="700" height="400"> </iframe>
</div>
<div id='fade' class='black_overlay'>
</div>

<div align="center">

<div class="header">
Photo Sharing Network
</div>
<div class="contentheader_wrapper"><div class="contentheader">

<div class="currenttab"><a href=".">Photos of Me</a></div>
<div class="othertab"><a href="./galleries/" >Extended Galleries</a></div>
<div class="othertab"><a href="./myuploads/" >My Uploads</a></div>

</div></div>
<div class="contentwrapper"> 
<div class="content">

<?php printUserPictures($user_id, $page); ?>
 
<div class="clear"></div>         
</div> 
</div>

<div class="contentfooter"></div> 
<span style="color:grey;font-size:11px;"><br/>Interested in learning more about PSN?  Event coverage, beta tests, jobs, technology partnerships, questions?  Ryan (at) photosharingnetwork.com</span>   
<script type="text/javascript">

		var images = new Array()
		function preload() {
			for (i = 0; i < preload.arguments.length; i++) {
				images[i] = new Image()
				images[i].src = preload.arguments[i]
			}
		}
		preload(
			"../img/site/button-pressed-small.gif"
		)
		
</script>
</script>
</div>
</body>
