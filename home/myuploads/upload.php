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
$result_events = mysql_query("SELECT * FROM events WHERE collab = 1 AND event_id = ANY (SELECT event_id FROM users_events WHERE email = (SELECT email FROM users WHERE user_id = '$user_id'))");

//Google Contact API
$client = new apiClient();
$client->setApplicationName('Google Contacts PHP Sample');
$client->setScopes("http://www.google.com/m8/feeds/");
$client->setClientId($clientid);
$client->setClientSecret($clientsecret);
$client->setRedirectUri($redirecturi);
$client->setDeveloperKey($developerkey);
$client->setAccessType('offline');
$auth = $client->createAuthUrl();

if(!isset($token))
{
	$token_result = mysql_query("SELECT gat FROM users WHERE user_id = '$user_id'");
	$row = mysql_fetch_array($token_result,MYSQL_ASSOC);

	if(isset($row['gat']))
	{
		$token = $row['gat'];
        $client->refreshToken($token);  
		$req = new apiHttpRequest("https://www.google.com/m8/feeds/contacts/default/full?max-results=5000");
	  	$val = $client->getIo()->authenticatedRequest($req);
	  	$xml =  new SimpleXMLElement($val->getResponseBody());
	  	$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
	  	$result = $xml->xpath('//gd:email');
	  	//$token = $client->getAccessToken();
	  	
	}
	else if(isset($_GET['code'])) {
	  $client->authenticate();
	  $token = $client->getAccessToken();
	  $client->setAccessToken($token);
	  $authObj = json_decode($token);
	  $refreshToken = $authObj->refresh_token;
	  //echo $refreshToken;
	  //echo $token;
	  $error = mysql_query("UPDATE users SET gat = '$refreshToken' WHERE user_id = '$user_id'");
	  if($error == 0)
	  	mysql_error($conn);
	  $req = new apiHttpRequest("https://www.google.com/m8/feeds/contacts/default/full?max-results=5000");
	  $val = $client->getIo()->authenticatedRequest($req);
	  $xml =  new SimpleXMLElement($val->getResponseBody());
	  $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
	  $result = $xml->xpath('//gd:email');
	  $token = $client->getAccessToken();
	}
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


<link type="text/css" href="../../jquery-ui-2/css/ui-lightness/jquery-ui-1.8.24.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="../../jquery-ui-2/js/jquery-ui-1.8.24.custom.min.js"></script>
<!-- jquery-ui  <script type="text/javascript" src="../../jquery-ui/js/jquery-1.8.0.min.js"></script> -->

<?php 

if (isset($token)) {
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
<div class="uploadbox">
<h1><u>Upload</u> and <u>Easily Share</u> photos</h1>
<br/>
<div>Placeholder for explanation</div>
<div> Please select photos to privately share (and to securely store online in your private PSN account).</div>
<div class="status">
</div>

<div class="uploadbutton">
<form class="uploadbutton">
		<tr><div id="queue1"></div></tr>
		<tr><input id="file_upload" name="file_upload" type="file" multiple="true"></tr>
</form>
<div class="moreuploads" style="display:none;">Upload More Photos from
a Different Folder after the previous upload completes.</div>
</div>
</div>


<div class="message"></div>
<div style="height:200px;" class="pictures">
<table>
<div class="faces">
<div class = "link" style="display: <?php if(isset($token)) echo 'none'; else echo 'block';?>"> 
<?php echo '<a href="'.$auth.'" ><img src="example.jpg"></img></a>';?>
</div>
<iframe class="faces" src='faces.php?u=<?php echo $user_id ?>' width="300" height="400"> </iframe>
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
		var count = 0;
		var current = 0;
		var folders = -1;
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
				'onDialogClose'  : function(queueData) {
						if(queueData.filesQueued != 0)
						{
				            $('#file_upload').uploadify('disable', true);
				            $('div.moreuploads').show();

				            //alert(queueData.filesQueued + ' files were queued of ' + queueData.filesSelected + ' selected files. There are ' + queueData.queueLength + ' total files in the queue.');
				            count = queueData.filesQueued;
				            current = 0;
				            folders  = folders +1;
				            /*
				            $( "#file_upload-button" ).css('height','0');
				            $( "#file_upload-button" ).css('width','0');
				            $( ".uploadify-button-text" ).html('');
				            */
				            $('div.status').append('<div class="status_'+folders+'"></div>')
				            $('div.status').append('<div class="status_'+folders+'_num">Uploading 0/'+count+'.</div>')
				            $(function() {
				            		$( "div.status_"+folders).progressbar({
				            			value: 0
				            		});
				            	});
				        }
				        },
				'onUploadSuccess' : function(file, data, response) {
				            //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
				           	$.ajax({
				            		type: 'GET',
				            		url: './database2.php?u='+<?php echo $user_id ?>+'&file='+file.name,
				            		success: function(data1){
				            			current = current +1;
				            			//alert(data1);
				       					//$('div.faces').append(data1);
				       					$('iframe.faces').attr("src", $('iframe.faces').attr("src"));
				       					$(function() {
				       							$("div.status_"+folders).progressbar(
				       								"value",(current*100/count)
				       							);
				       							$("div.status_"+folders+"_num").html('Uploading'+current+'/'+count + '.')
				       							if(current == count)
				          							$('#file_upload').uploadify('disable', false);
				       						});
				       					
				            		}

				            	})
				          	
				       },
				'onQueueComplete' : function(queueData) {
				          
				      }

			});
		});
	</script>

</div>
</body>
