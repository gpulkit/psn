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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photo Sharing Network</title>
<link rel="stylesheet" type="text/css" href="./css/main.css" />
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
	var j=0;
  function validate_form(i)
  {
  	var error ='';
  	var success =true;
  	var x=document.forms["registerform"]["username"].value;
  	var y=document.forms["registerform"]["password1"].value;
  	var z=document.forms["registerform"]["password2"].value;
  	var w=document.forms["registerform"]["phone"].value;
  	$('td.e_u').html('');
  	$('td.e_p1').html('');
  	$('td.e_p2').html('');
  	$('td.e_ph').html('');
  	if(i==1)
  	{
  		j=1;
  	}
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
	if (z==null || z=="")
	{
		$('td.e_p2').html('Required field');
	  	//error = error + 'Confirm Password field empty.<br/>'
	  	success = false;
	}

	if (w==null || w=="" && i==0 && j==0)
	{
		$('td.e_ph').html('Your cell is required for the real-time photo sharing feature.<br/>Interested in using PSN without this feature? Please click <a href="javascript:void(0)" style="color:red;" onclick="validate_form(1);">HERE</a>');
	  	//error = error + 'Confirm Password field empty.<br/>'
	  	success = false;
	}


	if(y!=z)
	{
		$('td.e_p2').html('Passwords do not match');
		//error = error + '.<br/>'
	  	success = false;
	}

	$.ajax({
			async: false,
			type: 'GET',
			url: './username.php?email='+x,
			success: function(data){
				if (data=="")
				{
					
				}	
				else
				{
					$('td.e_u').html('Username not Available');
					//error = error + 'Username not Available.<br/>';
		  			success = false;
		  			
				}
			}
		})

	if(success)
		document.forms['registerform'].submit()
  }

</script>


</head>

<body>

<?php if( $detect->isMobile() ) : ?>
<div align="left">
<?php else : ?>
<div align="center">
<?php endif ?>


<div class="header">
Photo Sharing Network
</div>


<div class="contentheader_wrapper"><div class="contentheader">

<div class="currenttab">Registration</div>

</div></div>

<div class="homecontentwrapper"> 
	<div class = "welcome">
	<br/>Welcome to Photo Sharing Network<br/><br/>We provide an easy and more private way for you to share photos<br/>
	(without over-sharing) <br/><br/>
    </div>
<div class="homecontent"> 

<div class="registerbox">
<form id="registerform" method="post" action="./actions/register.php">
<table>
<tr>
	<td colspan='2'><h2>New Users</h2></td>
</tr>
<tr>
<td>email</td> <td> <input name="username" type="text" id="frm_username" value=''  class="username_return"/>  </td> <td class='e_u'></td>
</tr>
<tr>
	<td>pass</td> <td> <input name="password1" type="password" />  </td> <td class='e_p1'></td>
</tr>
<tr>
	<td>confirm<br/> pass</td> <td><input name="password2" type="password" />   </td> <td class='e_p2'></td>
</tr>
<tr>	
	<td>cell</td> <td><input name="phone" type="text" />   </td> <td class='e_ph'></td>
</tr>
<tr>
	<td> <span style="display: <?php if(isset($ec)) echo 'none'; else echo 'block';?>">event<br/> code</span></td> <td><input style="display: <?php if(isset($ec)) echo 'none'; else echo 'block';?>" name="ecode" type="text" value='<?php if(isset($ec)) echo $ec; ?>'/>   </td>
</tr>
<tr>
	<td></td>
	<input style="display:none" type="submit" name='submit1'	 />

	<td>
		<a href="javascript:void(0);" onclick="validate_form(0)"> 
			<div class="button">Register </div>
		</a>
	</td>
</tr>
</table>
</form>
Returning user? <a href='login.php'> <u>Login</u> </a>
</div>
<!--
<div class="loginbox">
<h1>Returning Users</h1>


<form id="loginform" method="post" action="./actions/login2.php">
<table>
<tr>
<td> <h2>email </h2></td> <td> <input name="username" type="text" />  </td>
</tr>
<tr>
<td> <h2>password </h2></td> <td> <input name="password" type="password" />  </td>
</tr>
<tr>
<td>&nbsp;   </td> <td>&nbsp;   </td>
</tr>
</table>

<input style="display:none" type="submit"	 />

<a href="javascript:void(0);" onclick="document.forms['loginform'].submit()"> 
<div class="button">Login</div>
</a>

</form>

</div>
-->
<div class="clear"></div>         
          


</div> 
</div>

<div class="homecontentfooter"></div>    
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
