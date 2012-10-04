<? 
session_start();
require_once('config.php'); 
require_once("Mobile_Detect.php");
$detect = new Mobile_Detect();

if(isset($_SESSION["user_id"])) {
  $user_id = $_SESSION["user_id"];
}
else
{
  header('Location: .');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Photo Sharing Network</title>

  <link rel="stylesheet" type="text/css" href="./css/main.css" />
  <?php if( $detect->isMobile() ) : ?>
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
  <link rel="stylesheet" type="text/css" href="/css/mobile_main.css" />
<?php endif ?>

<script type="text/javascript">

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-31230422-1']);
_gaq.push(['_trackPageview']);

(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

</script>

</head>

<body>

  <div id="fb-root"></div>

  <script type="text/javascript">
  var name ='';
  window.fbAsyncInit = function() {
    FB.init({
     appId   : <?php echo $config['fb_app_id'];?>, 
            status  : true, // check login status
            cookie  : true, // enable cookies to allow the server to access the session
            xfbml   : true // parse XFBML
          });

        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function(response) {
         var fb_id = response.authResponse.userID;
         var fb_token = response.authResponse.accessToken;
         FB.api('/me', function(response) {
          name = response.name;
          window.location="./home/index.php?name="+name+"&fb_id="+fb_id+"&fb_token="+fb_token+"&n=1";
        });

       });

        document.getElementById("fb").onclick = function() {

        	FB.login(function(response) {
           if (response.authresponse) {
               	// user is logged in and granted some permissions.
                // perms is a comma separated list of granted permissions
                //alert('User allowed.');
              } 
              else {
                      //alert('User did not allow.');
                    	// user is logged in, but did not grant any permissions
                    }
                  }, {scope:'user_photos, friends_photos'});
            //{scope:'user_photos, friends_photos, user_photo_video_tags,friends_photo_video_tags,user_birthday,friends_birthday'});
};

FB.getLoginStatus(function(response) {
 if (response.status === 'connected') {
  var uid = response.authResponse.userID;
  var accessToken = response.authResponse.accessToken;
}
else if (response.status === 'not_authorized') {

} 
else {

}
},true);
};

	//This is for asynchronous loading of the javascript
  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
  </script>


  <?php if( $detect->isMobile() ) : ?>
  <div align="left">
  <?php else : ?>
  <div align="center">
  <?php endif ?>


  <div align="left">
  <div class="header">
    Photo Sharing Network <span>[Advanced Beta Release]</span> 
    <img src="./img/site/Hello.jpg" width="320px" height="75px" style="float:right;"/>
  </div>
  </div>

  <div class="setupcontentheader_wrapper">
    <div class="setupcontentheader">
      <div class="currenttab">Registration</div>
    </div>
  </div>

  <div class="setupcontentwrapper"> 
    <div class="homecontent">
     <div align="left" style="padding-left:100px;">
      <p>
        <font size="6px">We need your beautiful face !</font><br/><br/>
        <font size="3px">(To find you in your friends' photos and then to privately share those photos with you)</font>
      </p>
      <br/>
    </div>

    <div class="imagebox">
      <img src='./img/site/you.png' width='600px' height='500px'/>
    </div>
    <div class="optionsbox" align="center">
      <br/><br/><br/><br/><br/><br/><br/><br/>
      
      <div><font size="6px">Upload from:</font></div>
      <br/><br/>
      <div align="center" style="margin-left:0px;">
      <table>
        <tr>
          <td><div class="small" align="center"></div></td>
           <td width="20px"></td>
          <td><div class="small" align="center">(Express Method)</div></td>
        </tr>
        <tr>
          <td><a href="javascript:void(0);" onclick="alert('We re still building this feature. In the meantime, please use the FB Login.')"> 
        <div class="bigbutton" style="padding-top:20px; height:56px">My Computer</div>
        </a>
          </td>
          <td width="20px">
            Or
          </td>
          <td>
            <a href="javascript:void(0);"> 
            <div class="bigbutton" id="fb" style="padding-top:20px; height:56px">Facebook</div>
            </a>
          </td>
        </tr>
        <tr>
          <td><div class="small" align="center">(Manually select photos)</div></td>
           <td width="20px"></td>
          <td><div class="small" align="center">(Two-Click Auto Detect)</div></td>
        </tr>
         <tr>
          <td><div class="small" align="center"></div></td>
           <td width="20px"></td>
          <td width="194px"><div class="small" align="center">*Please note: We will NOT post anything to your FB account. EVER.</div></td>
        </tr>
      </table>
    </div>

<!--
      <div class="setup_option">
        <a href="javascript:void(0);" onclick="alert('We re still building this feature. In the meantime, please use the FB Login.')"> 
        <div class="bigbutton" style="padding-top:20px; height:56px">Upload Photos</div>
        </a> <br />
        <div class="small">(Manually select photos)</div>
      </div>

      <div  class="setup_option"  align="center">
        <a href="javascript:void(0);"> 
        <div class="bigbutton" id="fb">Select from Facebook </div>
        </a><br />

        <div class="small">(2 Click Auto Detect)</div>
        <div class="small">Please note: We will not post ANYTHING to FB</div>
      </div>-->
    </div>

<!--



-->
<div class="clear"></div>         
<!--
<p class="skip">
<a href="/home">Skip this step</a>
</p>

<div class="clear"></div>  
-->
<!--<small>Debug: <?php echo $_SESSION["email"] ?></small>-->

</div>


<div class="clear"></div>         


</div> 

<div class="setupcontentfooter"></div> 
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

</div>
</body>
