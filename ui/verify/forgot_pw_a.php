<?php
// **************************************
// forgot_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/4/18
// **************************************

include "./utilities.php";
//include "./lib/validation.php";
session_start();
$logfile = "admin.log";

$email = trim($_POST['email']);

$pwkey = uniqid("FP");

$arr_user_info = save_user_pw_key($email, $pwkey);
if ($arr_user_info['lastname']=="ERROR") {
   logMsg("Bad email entered in forgot password screen: $email");
} else {
   // send mail with forgot password instructions 
   include "includes/inc_email_template.php";

   // need a button - include it into template
   include "includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", ucfirst(strtolower($arr_user_info['firstname'])), $email_template);
   $email_template = str_replace("**HEADER**", "Password Reset", $email_template);

   $content1 = "You recently requested to change the password for your EIDO Verify account. Click the button below to reset it.<br /><br />If you didn't request a password reset, please ignore this email.<br /><br />If you're having problems with the reset button, just copy and paste this link into your browser.<a href='".$SITE_URL."pw_reset.php?k=$pwkey'>".$SITE_URL."pw_reset.php?k=$pwkey</a><br /><br />";
   $email_template = str_replace("**CONTENT1**", $content1, $email_template);

   $content2 = "<p>You recently requested to change the password for your EIDO Verify account. Click the button below to reset it.</p><p>If you didn't request a password reset, please ignore this email.</p><p>If you're having problems with the reset button, just copy and paste this link into your browser.<a href='".$SITE_URL."val/validation_pw_reset.php?k=$pwkey'>".$SITE_URL."val/validation_pw_reset.php?k=$pwkey</a></p>";
   $email_template = str_replace("**CONTENT2**", $content2, $email_template);

   // set up the button
   $button_text = "Reset your password";
   $email_template = str_replace("**BUTTONTEXT**", $button_text, $email_template);
   $button_url = $SITE_URL."pw_reset.php?k=$pwkey";
   $email_template = str_replace("**BUTTONURL**", $button_url, $email_template);

   $arr_email['subject']="EIDO Verify Password Reset";
   $arr_email['mail_from']=$verify_mail_from;
   $arr_email['mail_from_name']=$verify_mail_from_name;
   $arr_email['mail_to']=$email;
   $arr_email['mail_to_name']=ucwords(strtolower($arr_user_info['firstname']." ".$arr_user_info['lastname']));
   $arr_email['body']=$email_template;

   $result = send_email($arr_email);
   logMsg("forgot_pw: email: $email - mail_send_result: $result", $logfile);

   $_SESSION['error_msg']="<center><h1>Forgot Password</h1></center>An email has been sent to you with a password reset link. Click the link and follow the instructions to reset your password.";
}
header("Location:message.php");
exit();
?>
