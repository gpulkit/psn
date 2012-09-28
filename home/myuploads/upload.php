<?php

session_start();
require_once("../../lib2.php");

if(isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
}
else
{
	header('Location: ../..');
}

$fb_id = 0;

if(isset($_SESSION["fb_id"])) {
	$fb_id = $_SESSION["fb_id"];
}

//Google Contact API
$client = new apiClient();
$client->setApplicationName('Google Contacts PHP Sample');
$client->setScopes("http://www.google.com/m8/feeds/");
$client->setClientId($clientid);
$client->setClientSecret($clientsecret);
$client->setRedirectUri($redirecturi);
$client->setDeveloperKey($developerkey);
$auth = $client->createAuthUrl();

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  $req = new apiHttpRequest("https://www.google.com/m8/feeds/contacts/default/full?max-results=5000");
  $val = $client->getIo()->authenticatedRequest($req);
  $xml =  new SimpleXMLElement($val->getResponseBody());
  $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
  $result = $xml->xpath('//gd:email');
  $_SESSION['token'] = $client->getAccessToken();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photo Sharing Network</title>
<link rel="stylesheet" type="text/css" href="./uploadify/uploadify.css" />
<link rel="stylesheet" type="text/css" href="../../css/main.css" />
<link type='text/css' href='../../css/gallery.css' rel='stylesheet' media='screen' />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="./uploadify/jquery.uploadify.min.js"></script>

<!--<script type="text/javascript" src="../../js/jquery-1.7.2.min.js" ></script>-->
<script type='text/javascript' src='../../js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='../../js/gallery7.js'></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.19.custom.min.js" ></script>


<link type="text/css" href="../../jquery-ui/css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="../../jquery-ui/js/jquery-ui-1.8.23.custom.min.js"></script>
<!-- jquery-ui  <script type="text/javascript" src="../../jquery-ui/js/jquery-1.8.0.min.js"></script> -->

<?php 

if (isset($_GET['code'])) {
	/*
			$( "input" ).autocomplete({
			source: availableTags
		});
		*/
  	echo '

	<script>
	$(function() {
		var availableTags = [';
			 foreach ($result as $title) {
  				echo '"'.$title->attributes()->address.'",';
			 }
		echo '
		];

		$( "input").live( "focus", function(){
		      $(this).autocomplete({
					source: availableTags
				}).focus();
		      return false;
		});
	});
	</script>';
}

?>
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

<script type="text/javascript">
$(document).ready(function(){
  $("input").live('keyup',function(e){
	if(e.keyCode == 13) {
		//alert('dfs');
    	$.ajax({
     		type: 'GET',
     		url: './face_invitation.php?u='+<?php echo $user_id ?>+'&v='+$(this).val()+'&f='+$(this).attr('id'),
     		success: function(data1){
     			//alert(data1);
     		}
     	})
     	$(this).parents('tr').remove();
	}
  });
});
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


<div class="othertab"><a href="../../home">Photos of Me</a></div>
<div class="othertab"><a href="../../home/galleries">Extended Galleries</a></div>
<div class="currenttab"><a href="../../home/myuploads" >My Uploads</a></div>
</div></div>

<div class="contentwrapper"> 
<div class="content">

<div class="uploadbox" >
<h1>Upload multiple photos</h1>
<br/>
<table>
<form>
		<tr><div id="queue1"></div></tr>
		<tr><input id="file_upload" name="file_upload" type="file" multiple="true"></tr>
</form>
</table>
</div>
<div class="message"></div>
<div style="height:200px;" class="pictures">
<table>
<div class="faces">
	<input type='text'/>
	<div> Face invitation<br/> </div>
	<?php echo '<a href="'.$auth.'"> Auto-Complete </a>';?>
</div>
</table>
</div>

<div class="clear"></div> 

</div> 
</div>

<div class="contentfooter"></div> 

<script type="text/javascript">

		var images = new Array()
		function preload() {
			for (i = 0; i < preload.arguments.length; i++) {
				images[i] = new Image()
				images[i].src = preload.arguments[i]
			}
		}
		preload(
			"/img/site/button-pressed-small.gif"
		)
		
</script>

	<script type="text/javascript">
		
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				//'debug'	: true,
				'fileTypeDesc' : 'Image Files',
				'swf'      : './uploadify/uploadify.swf',
				'uploader' : './uploadify/uploadify.php',
				'onUploadSuccess' : function(file, data, response) {
				            //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
				           	$.ajax({
				            		type: 'GET',
				            		url: './database2.php?u='+<?php echo $user_id ?>+'&file='+file.name,
				            		success: function(data1){
				            			alert(data1);
				       					$('div.faces').append(data1);
				            		}

				            	})
				       }
			});
		});
	</script>

</div>
</body>
