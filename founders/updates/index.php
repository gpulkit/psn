<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php
 if ($handle = opendir('.')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != ".." && $file != "index.php")
	  {
          	$thelist .= '<a href="'.$file.'" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize=\'20pt\'" onmouseout="this.style.fontSize=\'11pt\'">'.$file.'</a><br><br>';
          }
       }
  closedir($handle);
  }
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Photo Sharing Network</title>

<style type="text/css">
	
p {
	font-family: "Comic Sans MS", sans-serif, serif, "Lucida Grande", Verdana, Arial;
	font-weight: bold;
	text-align: justify; 
	padding-left: 10px;
	padding-right: 10px;
	margin-top: 10px;
	margin-bottom: 2px;
}
</style>

</head>

<body bgcolor="black" link="white" vlink="white" alink="white">


<p>List of files:</p>

<p>
<a href="__Our_Resumes.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">__Our_Resumes.pdf</a><br><br>

<a href="3_17__Photo_Sharing_Network__Excelerate_Submission.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">3_17__Photo_Sharing_Network__Excelerate_Submission.pdf</a><br><br>

<a href="3_21__Usage_Scenarios.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">3_21__Usage_Scenarios.pdf</a><br><br>

<a href="3_26__Current_Status.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">3_26__Current_Status.pdf</a><br><br>

<a href="3_26__Technical_Challenges.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">3_26__Technical_Challenges.pdf</a><br><br>


<a href="3_29__Excelerate_Additional_Questions.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">3_29__Excelerate_Additional_Questions.pdf</a><br><br>

<a href="3_31__Facial_Recognition_Tests.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">3_31__Facial_Recognition_Tests.pdf</a><br><br>

<a href="4_1__Refined_Elevator_Pitch_and_Market_Size.pdf" TARGET="_blank" style="text-decoration:none" onmouseover="this.style.fontSize='20pt'" onmouseout="this.style.fontSize='11pt'">4_1__Refined_Elevator_Pitch_and_Market_Size.pdf</a><br><br>

</p>



</body>
</html>