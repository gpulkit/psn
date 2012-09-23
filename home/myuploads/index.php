<?php

session_start();
include_once("../../lib2.php");

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

<!--<script type="text/javascript" src="/gallery/js/jquery-1.7.2.min.js" ></script>-->
<script type='text/javascript' src='/../../js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='/../../js/gallery7.js'></script>
<script type="text/javascript" src="/../../js/jquery-ui-1.8.19.custom.min.js" ></script>

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
		parent.document.getElementById('pictureframe').src='<?php echo "soon.html";?>';
		document.getElementById('light').style.display='block';
		document.getElementById('fade').style.display='block';
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

<div class="currenttab"><a href="../../home/myuploads" >My Uploads</a></div>
<div class="othertab"><a href="../../home">Photos of Me</a></div>
<div class="othertab"><a href="../../home/galleries">Extended Galleries</a></div>
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
<?php printUserUploads($user_id); ?>
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
				            		url: './database.php?u='+<?php echo $user_id ?>+'&file='+file.name,
				            		success: function(data1){
				            			//alert(data1);
				       					$('div.pictures').html(data1);
				            		}

				            	})
				       }
			});
		});
	</script>

</div>
</body>
