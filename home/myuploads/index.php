<?php
session_set_cookie_params(0, '/', '.photosharingnetwork.com');
session_start();
require_once("../../config.php");

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
$result ='';
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

	}
	else if(isset($_GET['code'])) {
		$client->authenticate();
		$token = $client->getAccessToken();
		$client->setAccessToken($token);
		$authObj = json_decode($token);
		$refreshToken = $authObj->refresh_token;
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

	<link type="text/css" href="../../jquery-ui-2/css/ui-lightness/jquery-ui-1.8.24.custom.css" rel="Stylesheet" />	
	<script type="text/javascript" src="../../jquery-ui-2/js/jquery-ui-1.8.24.custom.min.js"></script>
	<!-- jquery-ui  <script type="text/javascript" src="../../jquery-ui/js/jquery-1.8.0.min.js"></script> -->

	<?php 
	
		echo '
		<script>
		$(function() {
			var availableTags = [';
			if(isset($token))
                        {
				foreach ($result as $title) {
					echo '"'.$title->attributes()->address.'",';
				}
                        }
			echo '
			];

			$( "input").live( "focus", function(){
				//if($(this).val() == "Enter e-mail address here to share!")
					$(this).val("");
				$(this).autocomplete({
					source: availableTags,
					select: function(event, ui) { 
    					console.log("User selected: " + ui.item.value); 
   					}	 
				});
				return false;
			});
		});
		
		$(document).ready(function(){
		  $("input").live("keyup",function(e){
			if(e.keyCode == 13) {
				//alert("dfs");
		    	$.ajax({
		     		type: "GET",
		     		url: "./face_invitation.php?u='.$user_id.'&v="+$(this).val()+"&f="+$(this).attr("id"),
		     		success: function(data1){
		     			//alert(data1);
		     		}
		     	})
				$(this).val("Privately Shared");
				$(this).attr("disabled", true);
		     	$(this).parents("tr").hide(2000);
		     	$("span.count").html(parseInt($("span.count").text())-1); 
			}
		  });
		});
		</script>';
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
/*
$(document).ready(function(){
	    $.ajax({
	     	async: false,
	     	type: 'GET',
	     	url: './faces.php?u='+<?php echo $user_id ?>,
	     	success: function(data1){
					$('div.faces').append(data1);
			}
		})
});
*/
</script>
</head>

<body>
	<div id='light' class='white_content'>
		<iframe id='pictureframe' src="" width="700" height="400"> </iframe>
	</div>
	<div id='fade' class='black_overlay'>
	</div>

	<div align="center">
		<div align="left">
		<div class="header">
			Photo Sharing Network <span>[Advanced Beta Release]</span>
			<a href="mailto:info@photosharingnetwork.com"> 
				<img src="../../img/site/Hello.jpg" width="320px" height="75px" style="float:right;"/>
			</a>
		</div>
		</div>
		
		<div class="contentheader_wrapper">
			<div class="contentheader">
				<div class="othertab"><a href="../../home">Photos of Me</a></div>
				<div class="othertab"><a href="../../home/galleries">Extended Galleries</a></div>
				<div class="currenttab"><a href="../../home/myuploads" >My Uploads</a></div>
			</div>
		</div>

		<div class="contentwrapper">
			<div class="content">
				<!--<h1><u>Upload</u> and <u>Easily Share</u> photos</h1>-->
				<div class="welcomeupload"><img src="../../img/site/upload.png" width="960px" height="300px"/><br/><br/></div>
				<div class="uploadbox">
					<div class="status"></div>
					<br/>
					<div class="uploadbutton">
						<table>
						<form class="uploadbutton">
							<!--<tr>Please select photos to privately share (and to securely store online in your private PSN account).</tr>-->
							<tr><div id="queue1"></div></tr>
							<tr><input id="file_upload" name="file_upload" type="file" multiple="true"></tr>
						</form>
						</table>
					</div>
                                        <div style="padding-right: 30px;">
                                        <br/>Sorry, uploader is currently not compatible with FireFox; please use Chrome, Safari or Explorer, thanks!<br/><br/>                                                                 Any issues, please email ryan@photosharingnetwork.com. 

                                        </div>
				</div>
				<div class="facebox">
					<div class = "link" align="center" style="display: <?php if(isset($token)) echo 'none'; else echo 'block';?>"> 
						<?php echo '<a href="'.$auth.'" ><img src="example.jpg"/></a>';?>
						<!--<?php echo '<a href="'.$auth.'" >Enable auto-fill</a>';?>-->
					</div>
					<br/>
					<!--<iframe class="faces" src='faces.php?u=<?php echo $user_id ?>' width="300" height="400"> </iframe>-->
					<div class="faces" align="center">

						<?php
						
							$result = mysql_query("SELECT face_id FROM faces WHERE image_id = ANY (SELECT image_id FROM useruploads WHERE user_id = '$user_id') AND email IS NULL ORDER BY face_id DESC");
							if($result == 0)
							    mysql_error($conn);
						        $count = mysql_num_rows($result);
                                                        echo '<font size="5px">PSN Easy Sharing Assistant</font><br/><br/>
                                                        This helps you privately share the photos our system doesn\'t automatically recognize.<br/><br/>';
						        echo '<span class="count">'.$count.'</span> outstanding faces to identify (photos to share)<br/><br/>';

						        if(mysql_num_rows($result) == 0)
						        {
						            
						        }
                                                        else
                                                        {
							echo '<table style="border-spacing:2em;">';
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								$face_id = $row['face_id'];
								$timestamp = 500;
								$url = $s3->getAuthenticatedURL($bucket,'faces/'.$face_id.'.jpg', $timestamp,false, false);	
								echo '<tr class="'.$face_id.'"><td><img src="'.$url.'" height="100px" width="70px" style="border: 3px white solid;"></td><td><input id="'.$face_id.'" type="text" style="color:gray;width:200px;" value="Enter e-mail address here to share!"/></td></tr>';
							}
							echo '</table>';
							}
							?>
					</div>
				</div>
			</div>
			<div class="clear"></div> 
		</div>
		<div class="contentfooter"></div> 
	</div>
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
	var faces = 0;
	var recognized = 0;
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
						//$('#file_upload').uploadify('disable', true);
				            //$('div.moreuploads').show();
				            count = queueData.filesQueued;
				            current = 0;
				            folders  = folders +1;
				            faces = 0;
				            recognized = 0;
				            $('div.status').append('<div class="status_'+folders+'" style="width:300px;"></div>')
				            $('div.status').append('<div class="status_'+folders+'_faces"></div>')
				            $('div.status').append('<div class="status_'+folders+'_num">Uploading 0 of '+count+' photos....</div>')
				            $('div.status').append('<br/><br/>');
				            $(function() {
				            	$("div.status_"+folders).progressbar({
				            		value: 0.1
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
                                                        //alert(data1);
				            		var arr = data1.split(',');
				            		faces += parseInt(arr[0],10);
				            		recognized += parseInt(arr[1],10);
				            		current = current +1;
				            			//alert(data1);
				       					$(function() {
				       						$("div.status_"+folders).progressbar(
				       							"value",(current*100/count)
				       							);
				       						$("div.status_"+folders+"_num").html('Uploading '+current+'/'+count + '....')
				       						if(current == count)
				       						{
				       	 						$("div.status_"+folders).hide(3000)
				       							$( "div.status_"+folders+'_faces').html('Uploaded: '+current+' of '+count+ ' photos');
				       							$( "div.status_"+folders+'_num').html('Faces Recognized: '+recognized+' of '+faces + ' faces');
				       							$('#file_upload').uploadify('disable', false);
			       								       							    $.ajax({
			       																     	async: false,
			       																     	type: 'GET',
			       																     	url: './faces.php?u='+<?php echo $user_id ?>,
			       																     	success: function(data1){
			       																				$('div.faces').html(data1);
			       																		}
			       																	})
				       						}
				       					});
				       				}

				       			})
     

},
'onQueueComplete' : function(queueData) {

},
'onFallback' : function() {
            	parent.document.getElementById('pictureframe').src='<?php echo "soon3.html";?>';
            	document.getElementById('light').style.display='block';
            	document.getElementById('fade').style.display='block';

        },
    'onSelectError' : function() {
            alert('The file ' + file.name + ' returned an error and was not added to the queue.');
        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        }

});
});
</script>
</body>
</html>
