<?php
// **************************************
// validation_forgot_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
include "../lib/validation.php";
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$logfile = "validation.log";

$email = $_POST['email'];

$pwkey = uniqid();

save_pw_key($arr_pt_info, $pwkey);

$subject = "EIDO Verify Password Reset";
$mail_from = $verify_mail_from;
$mail_from_name = $verify_mail_from_name;
$mail_to = $email;
$body = "Hello,<br /><br />A request was made to reset the password for EIDO Verify. If you did not make this request you may ignore this message. If you did request a reset, please click the link below and use that page to enter your new password.<br /><br /><a href='".$SITE_URL."val/validation_pw_reset.php?k=$pwkey'>Click here to reset your password.</a><br /><br />Thank you<br /><br />EIDO Verify Patient Communications"; 

$arr_email['subject']=$subject;
$arr_email['mail_from']=$mail_from;
$arr_email['mail_from_name']=$mail_from_name;
$arr_email['mail_to']=$mail_to;
$arr_email['body']=$body;

$result = send_email($arr_email);

$_SESSION['error_msg']="An e-mail has been sent to your mail account. Please click the link and use that page reset your password.<br /><br />Thank you.<br /><br />EIDO Verify Patient Communications";
header("Location:validation_message.php");
exit();
?>
