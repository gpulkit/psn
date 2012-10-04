<?php 
session_start(); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
include("./Mobile_Detect.php");
$detect = new Mobile_Detect();
if(isset($_SESSION["user_id"])) {
	header('Location: ./home/');
}

if(isset($_REQUEST['ec']))
{
	$ec = $_REQUEST['ec'];
}

if(isset($_REQUEST['w']))
{
	echo '<script type="text/javascript">$("td.e_u").html("Wrong Username & Password");</script>';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photo Sharing Network</title>

<link rel="stylesheet" type="text/css" href="./css/main.css??" />
<?php if( $detect->isMobile() ) : ?>
	<link rel="stylesheet" type="text/css" href="./css/mobile_main.css" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
<?php endif ?>

<script type="text/javascript" src="./js/jquery-1.7.2.min.js" ></script>
<script type='text/javascript' src='./js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='./js/gallery7.js'></script>
<script type="text/javascript" src="./js/jquery-ui-1.8.19.custom.min.js" ></script> 
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

  function validate_form()
  {
  	var error ='';
  	var success =true;
  	var x=document.forms["loginform"]["username"].value;
  	var y=document.forms["loginform"]["password"].value;
 
  	$('td.e_u').html('');
  	$('td.e_p1').html('');

  	if (x==null || x=="")
	{
		$('td.e_u').html('Required field');
	  	//error = error + 'Username field empty.<br/>'
	  	success = false;
	}
	if (y==null || y=="")
	{
	  	$('td.e_p1').html('Required field');
	  	//error = error + 'Password field empty.<br/>'
	  	success = false;
	}

	if(success)
		document.forms['loginform'].submit()
  }

</script>


</head>

<body>

<?php if( $detect->isMobile() ) : ?>
<div align="left">
<?php else : ?>
<div align="center">
<?php endif ?>

	<div align="left">
	<div class="header">
		Photo Sharing Network <span>[Advanced Beta Release]</span>
		<a href="mailto:info@photosharingnetwork.com"> 
			<img src="./img/site/Hello.jpg" width="320px" height="75px" style="float:right;"/>
		</a>
	</div>
	</div>

<div class="contentheader_wrapper"><div class="contentheader">

<div class="currenttab">Login</div>

</div></div>

<div class="homecontentwrapper"> 
		<div class = "welcome">
	<br/>Welcome to Photo Sharing Network<br/><br/>We provide an easy and more private way for you to share photos<br/>
	(without over-sharing) <br/><br/>
    </div>
<div class="homecontent"> 
<div class="loginbox">
<form id="loginform" method="post" action="./actions/login2.php">
<table>
<tr>
	<td colspan='2'><h2>Returning Users</h2></td>
</tr>
<tr>
<td> email</td> <td> <input name="username" type="text" id="frm_username" value=''  class="username_return"/>  </td> <td class='e_u'><?php if(isset($_REQUEST['w'])){ echo 'Wrong Username or Password';}?></td>
</tr>
<tr>
	<td> pass</td> <td> <input name="password" type="password" />  </td> <td class='e_p1'></td>
</tr>
<tr>
	<td></td>
	<input style="display:none" type="submit" name='submit1'	 />

	<td>
		<a href="javascript:void(0);" onclick="validate_form()"> 
			<div class="button">Login </div>
		</a>
	</td>
</tr>
</table>
</form>
		New user? <a href='index.php'> <u>Register</u> </a>
</div>

<div class="clear"></div>         
          


</div> 
</div>

<div class="homecontentfooter"></div>    
<span style="color:grey;font-size:11px;"><br/>Interested in learning more about PSN?  Event coverage, beta tests, jobs, technology partnerships, questions?  Ryan (at) photosharingnetwork.com</span>   
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
			"./img/site/button-pressed-small.gif"
		)
		
</script>

</div>
</body>
