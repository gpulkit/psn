<?php 
session_start(); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
require_once("./Mobile_Detect.php");
require_once('config.php');
$detect = new Mobile_Detect();
if(isset($_SESSION["user_id"])) {
	header('Location: ./home/');
}

if(isset($_REQUEST['ec']))
{
	$ec = mysql_real_escape_string($_REQUEST['ec']);
}

if(isset($_REQUEST['u']))
{
	$campus_id = mysql_real_escape_string($_REQUEST['u']);
	$result2 = mysql_query("SELECT * FROM orgs WHERE campus_id = '$campus_id'");
}

$result = mysql_query("SELECT * FROM campuses");
if($result == 0)
	mysql_error($conn);
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
var j=0;
function validate_form(i)
{
	var error ='';
	var success =true;
	var x=document.forms["registerform"]["username"].value;
	var y=document.forms["registerform"]["password1"].value;
	var z=document.forms["registerform"]["password2"].value;
	var w=document.forms["registerform"]["phone"].value;
	var v=document.forms["registerform"]["univs"].value;
	var u=document.forms["registerform"]["orgs"].value;

	$('td.e_u').html('');
	$('td.e_p1').html('');
	$('td.e_p2').html('');
	$('td.e_ph').html('');
	$('td.e_orgs').html('');
	$('td.e_univs').html('');
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
	  if (v==null || v=="0")
	  {
	  	$('td.e_univs').html('Required field');
	  	success = false;
	  }
	  if (u==null || u=="0")
	  {
	  	$('td.e_orgs').html('Required field');
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
		Photo Sharing Network <span>[Advanced Beta Release]</span> 
	</div>


	<div class="contentheader_wrapper">
		<div class="contentheader">
			<div class="currenttab">Registration</div>
		</div>
	</div>

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
							<td>E-mail</td> <td> <input name="username" type="text" id="frm_username" value=''  class="username_return"/>  </td> <td class='e_u'></td>
						</tr>
						<tr>
							<td>Password</td> <td> <input name="password1" type="password" />  </td> <td class='e_p1'></td>
						</tr>
						<tr>
							<td>Confirm<br/> Password</td> <td><input name="password2" type="password" />   </td> <td class='e_p2'></td>
						</tr>
						<tr>	
							<td>Cell</td> <td><input name="phone" type="text" />   </td> <td class='e_ph'></td>
						</tr>
						<tr style="<?php if(isset($campus_id)) echo 'display:none;'?>">
							<td>School</td>
							<td>
								<select name='univs' class="univs">
									<?php
									echo "<option value='0'>Select your school</option>";
									while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
									{
										$id = $row['campus_id'];
										$name = $row['campus_name'];
										if(isset($campus_id) && $campus_id == $id)
											echo "<option value='$id' selected='selected'>$name</option>";
										else
											echo "<option value='$id' >$name</option>";
									}
									?>
								</select>
							</td>
							<td class='e_univs'></td>
						</tr>
						<tr>
							<td>Campus<br/> Affiliation</td>
							<td>
								<select name='orgs' class='orgs'>
									<?php
									echo "<option value='0'>Select your campus affiliation</option>";
									while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) 
									{
										$id = $row['org_id'];
										$name = $row['org_name'];
										echo "<option value='$id'>$name</option>";
									}
									?>
								</select>
							</td>
							<td class='e_orgs'></td>
						</tr>
						<tr style="<?php /*if(isset($ec))*/ echo 'display:none;'?>">
							<td>Event Code</td> <td><input name="ecode" type="text" value='<?php if(isset($ec)) echo $ec; ?>'/></td><td>(Optional)</td>
						</tr>
						<tr>
							<td></td>
							<input style="display:none" type="submit" name='submit1'/>
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
<script type="text/javascript">
$('.univs').change(function() {
	var id = $(this).val();
	$.ajax({
		async: false,
		type: 'GET',
		url: './org.php?c='+id,
		success: function(data){
			if (data=="")
			{

			}	
			else
			{
					//alert(data);
					$('.orgs').html(data);		  			
				}
			}
		})
});
</script>

</div>
</body>
