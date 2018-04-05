<?php
// **************************************
// validation_forgot_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
include "../includes/inc_email_template.php";

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$logfile = "validation.log";

$email = $_POST['email'];

$pwkey = uniqid();

save_pw_key($arr_pt_info, $pwkey);

$firstname = $arr_pt_info['c_firstName'];
logMsg($firstname,$logfile);
$content = "You recently requested to change the password for your EIDO Verify account. Click the button below to reset it.<br /><br />If you didn't request a password reset, please ignore this email.<br /><br />If you're having problems with the reset button, just copy and paste this link into your browser.<br /><a href='".$SITE_URL."val/validation_pw_reset.php?k=$pwkey'>".$SITE_URL."val/validation_pw_reset.php?k=$pwkey</a><br /><br />EIDO Healthcare Ltd, 19-21 Main Street, Keyworth, Nottinghamshire, NG12 5AA"; 
$button = "<a href='".$SITE_URL."val/validation_pw_reset.php?k=$pwkey'><img src='".$SITE_URL."img/reset_pw_btn.png'></a>";

$body = str_replace("**FIRSTNAME**", $firstname, $email_template);
$body = str_replace("**CONTENT**", $content, $body);
$body = str_replace("**BUTTON**", $button, $body);

$subject = "Password Reset";
$mail_from = $verify_mail_from;
$mail_from_name = $verify_mail_from_name;
$mail_to = $email;
$mail_to_name = $arr_pt_info['c_firstName']." ".$arr_pt_info['c_surame'];;

$arr_email['subject']=$subject;
$arr_email['mail_from']=$mail_from;
$arr_email['mail_from_name']=$mail_from_name;
$arr_email['mail_to']=$mail_to;
$arr_email['mail_to_name']=$mail_to_name;
$arr_email['body']=$body;

$result = send_email($arr_email);

$_SESSION['error_msg']="An e-mail has been sent to your mail account. Please click the link and use that page reset your password.<br /><br />Thank you.<br /><br />EIDO Verify Patient Communications";
header("Location:validation_message.php");
exit();
?>
