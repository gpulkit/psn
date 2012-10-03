<?php
require_once('../../config.php');

if(isset($_REQUEST['u']))
{
	$user_id = mysql_real_escape_string($_REQUEST['u']);
	//Google Contact API
	$client = new apiClient();
	$client->setApplicationName('Google Contacts PHP Sample');
	$client->setScopes("http://www.google.com/m8/feeds/");
	$client->setClientId($clientid);
	$client->setClientSecret($clientsecret);
	$client->setRedirectUri($redirecturi);
	$client->setDeveloperKey($developerkey);
	$client->setAccessType('offline');
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
	}

	if (isset($token)) {
	  	echo '
	  	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	  	<link type="text/css" href="../../jquery-ui-2/css/ui-lightness/jquery-ui-1.8.24.custom.css" rel="Stylesheet" />	
		<script type="text/javascript" src="../../jquery-ui-2/js/jquery-ui-1.8.24.custom.min.js"></script>
		<script>
		$(function() {
			var availableTags = [';
				 foreach ($result as $title) {
	  				echo '"'.$title->attributes()->address.'",';
				 }
			echo '
			];

			$( "input").live( "focus", function(){
				$(this).val("");
			      $(this).autocomplete({
						source: availableTags
					}).focus();
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
		     	$(this).parents("tr").hide("slow");
			}
		  });
		});
		</script>';

	}
        echo 'Easy Share Assist <br/>';

	$result = mysql_query("SELECT face_id FROM faces WHERE image_id = ANY (SELECT image_id FROM useruploads WHERE user_id = '$user_id') AND email IS NULL ORDER BY face_id DESC");
	if($result == 0)
		echo mysql_error($conn);
        $count = mysql_num_rows($result);
        echo $count.' outstanding faces<br/><br/>';

        if(mysql_num_rows($result) == 0)
        {
            return;
        }
	echo '<table>';
	while($row = mysql_fetch_array($result,MYSQL_ASSOC))
	{
		$face_id = $row['face_id'];
		$timestamp = 500;
		$url = $s3->getAuthenticatedURL($bucket,'faces/'.$face_id.'.jpg', $timestamp,false, false);	
		echo '<tr class="'.$face_id.'"><td><img src="'.$url.'" height="100px" width="70px"></td><td><input id="'.$face_id.'" type="text" style="font-color:grey; width:200px;" value="Enter e-mail address here to share!"/></td></tr>';
	}
	echo '</table>';
}
?>
