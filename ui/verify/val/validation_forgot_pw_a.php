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

   // send mail with forgot password instructions
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", ucfirst(strtolower($firstname)), $email_template);
   $email_template = str_replace("**HEADER**", "Password Reset", $email_template);

   // preview text
   $content1 = "You recently requested to change the password for your EIDO Verify account. Click the button below to reset it.<br /><br />If you didn't request a password reset, please ignore this email.<br /><br />If you're having problems with the reset button, just copy and paste this link into your browser.</br ><a href='".$SITE_URL."val/validation_pw_reset.php?k=$pwkey'>".$SITE_URL."val/validation_pw_reset.php?k=$pwkey</a><br /><br />";
   $email_template = str_replace("**CONTENT1**", $content1, $email_template);

   // main content
   $content2 = "You recently requested to change the password for your EIDO Verify account. Click the button below to reset it.</p><p>If you didn't request a password reset, please ignore this email.";
   $email_template = str_replace("**CONTENT2**", $content2, $email_template);

   // set up the button
   $button_text = "Reset your password";
   $email_template = str_replace("**BUTTONTEXT**", $button_text, $email_template);
   $button_url = $SITE_URL."val/validation_pw_reset.php?k=$pwkey";
   $email_template = str_replace("**BUTTONURL**", $button_url, $email_template);

   // content3 is after the button
   $content3 = "<p style='font-size:12px'>If you're having problems with the reset button, just copy and paste this link into your browser.<br /><a href='".$SITE_URL."val/validation_pw_reset.php?k=$pwkey'>".$SITE_URL."val/validation_pw_reset.php?k=$pwkey</a></p>";
   $email_template = str_replace("**CONTENT3**", $content3, $email_template);

$arr_email['subject']="Password Reset";
$arr_email['mail_from']=$verify_mail_from;
$arr_email['mail_from_name']=$verify_mail_from_name;
$arr_email['mail_to']=$email;
$arr_email['mail_to_name']=ucwords(strtolower($arr_pt_info['c_firstName']." ".$arr_pt_info['c_surame']));
$arr_email['body']=$email_template;

$result = send_email($arr_email);

$_SESSION['error_msg']="<center><h1>Forgot Password</h1></center>An e-mail has been sent to your mail account. Please click the link and use that page reset your password.<br /><br />Thank you.<br /><br />EIDO Verify Patient Communications";
header("Location:validation_message.php");
exit();
?>
