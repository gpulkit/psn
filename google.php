<?php
require_once "google-api-php-client/src/apiClient.php";
require_once "google-api-php-client/src/contrib/apiPlusService.php";

$clientid = '168031654337-qalpj3vi6ssq29qcsn0dgvhlo0cl9eas.apps.googleusercontent.com';
$clientsecret = 'p23gk7-fKrfmYdEK4XP97ogG';
$redirecturi = 'http://localhost.com:8888/google.php';
$developerkey ='AIzaSyCnW3DWh4Fj3QaiLaydghqRLI30m4SBLRU';

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
 

/*
  // The contacts api only returns XML responses.
$response = json_encode(simplexml_load_string($val->getResponseBody()));
  print "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
*/
  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();

  //header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

}
else
{
	echo '<a href="'.$auth.'"> Auto-Complete </a>';
	//header("Location: " . $auth);
}
//echo '<a  style="font-size:25px;font-weight:bold;" href="https://accounts.google.com/o/oauth2/auth?client_id=$clientid&redirect_uri=$redirecturi&scope=https://www.google.com/m8/feeds/&response_type=code">Click here to Import Gmail Contacts</a>';
?>
<html>
<head>
	<link type="text/css" href="./jquery-ui/css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="./jquery-ui/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="./jquery-ui/js/jquery-ui-1.8.23.custom.min.js"></script>

</head>
<body>
	<?php
	echo '
	<script>
	$(function() {
		var availableTags = [';
			 foreach ($result as $title) {
  				echo '"'.$title->attributes()->address.'",';
			 }
		echo '
		];
		$( "#tags" ).autocomplete({
			source: availableTags
		});
	});
	</script>';?>
	
<div class="demo">

<div class="ui-widget">
	<label for="tags">Tags: </label>
	<input id="tags">
</div>
</div>
	</body>
	</html>
