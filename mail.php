<?php
//require_once 'config.php';
$error ='';

function smtpmailer($to, $from, $from_name, $subject, $body) { 
    global $error;
    global $mailpass;
    global $mailuser;
    $mail = new PHPMailer();  // create a new object
    $mail->IsHTML(true);
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465; 
    $mail->Username = $mailuser;
    $mail->Password = $mailpass;         
    $mail->SetFrom($from,$from_name);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    $mail->addBCC("gupta.pulkit89@gmail.com","Pulkit Gupta");
    $mail->addBCC("ryanjacobs16@gmail.com","Ryan Jacobs");
    if(!$mail->Send()) 
    {
        $error = 'Mail error: '.$mail->ErrorInfo;
        //return false;
    } else {
        $error = 'Message sent!';
        //return true;
    }
    return $error;
}

//echo smtpmailer('gpulkit@umich.edu','Photos@photosharingnetwork.com','Photo Sharing Network','test3','testbody');
//echo $error;
?>
