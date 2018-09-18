<?php
// ********************************************************************
// val/login_a.php
// Copyright 2018, Mesh Integrations LLC
// WEL 1/26/18
//    this is patient login - they can login if they set a password 
//    in a previous session - otherwise they get sent back 
//    through validation process
// ********************************************************************

require_once '../utilities.php';
$logfile = "validation.log";
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$moreReminders = $_SESSION['moreReminders'];
$_SESSION['login_error']=false;
$_SESSION['login_email']="";

// limit password attempts
if (isset($_SESSION['pw_num_tries']))
   $pw_num_tries = $_SESSION['pw_num_tries'];
else
   $pw_num_tries = 0;

$email = $_POST['email'];
$password = $_POST['password'];

logMsg("Pt Login: Entered Email: $email Entered PW: $password",$logfile);
logMsg("Pt Login: DB Email: ".$arr_pt_info['c_emailAddress']." DB PW: ".$arr_pt_info['c_password'],$logfile);

if ($email==$arr_pt_info['c_emailAddress'] && $password==$arr_pt_info['c_password'])
{
   //unset($_SESSION['pw_num_tries']);
   //unset($_SESSION['error_msg']);
   //unset($_SESSION['login_email_entered']);
   logMsg("Pt login sucessful. Going to survey...",$logfile);
   $goto_url = get_survey_url($arr_pt_info);
   $_SESSION = array();
 
   header("Location:$goto_url");
   exit();
}
else
{
   $pw_num_tries++;
   logMsg("Pt login failed. Number of attempts: $pw_num_tries",$logfile);
   if ($pw_num_tries<$pw_max_tries)
   {
      $_SESSION['pw_num_tries']=$pw_num_tries;
      $_SESSION['login_error']=true;
      $_SESSION['login_email_entered']=$email;
      header("Location:login.php");
      exit();
   }
   else
   {
      $_SESSION['login_email_entered']=$email;
      header("Location:validation_forgot_pw.php");
      exit();
   }
}
header ("Location: login.php");
?>

