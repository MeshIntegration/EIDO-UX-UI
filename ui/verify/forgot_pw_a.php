<?php
// **************************************
// forgot_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/4/18
// **************************************

include "./utilities.php";
//include "./lib/validation.php";
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$logfile = "admin.log";

$email = trim($_POST['email']);

$pwkey = uniqid("FP");

save_user_pw_key($email, $pwkey);

$subject = "EIDO Verify Password Reset";
$mail_from = $verify_mail_from;
$mail_from_name = $verify_mail_from_name;
$mail_to = $email;
$body = "Hello,<br /><br />A request was made to reset the password for EIDO Verify. If you did not make this request you may ignore this message. If you did request a reset, please click the link below and use that page to enter your new password.<br /><br /><a href='".$SITE_URL."pw_reset.php?k=$pwkey'>Click here to reset your password.</a><br /><br />Thank you<br /><br />EIDO Verify Patient Communications"; 

$arr_email['subject']=$subject;
$arr_email['mail_from']=$mail_from;
$arr_email['mail_from_name']=$mail_from_name;
$arr_email['mail_to']=$mail_to;
$arr_email['body']=$body;

$result = send_email($arr_email);
logMsg("forgot_pw: mail_send_result: $result", $logfile);

$_SESSION['error_msg']="An email has been sent to you with a password reset link. Click the link and follow the instructions to reset your password.<br /><br />Thank you.<br /><br />EIDO Verify Patient Communications";
header("Location:message.php");
exit();
?>
