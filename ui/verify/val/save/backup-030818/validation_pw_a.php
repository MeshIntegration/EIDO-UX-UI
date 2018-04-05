<?php
// **************************************
// validation_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/25/18
// **************************************

include "../utilities.php";
include "../lib/validation.php";
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$_SESSION['error_msg']="";

$logfile = "validation.log";

$password=$_POST['password'];
$skip=$_POST['skip'];

logMsg("Entered PW: $password - Skip: $skip",$logfile);

if ($skip=="" && $password=="")
{
   logMsg("No PW Entered ",$logfile);
   $_SESSION['error_msg'] = "NO_PASSWORD";
   header ("Location: validation_pw.php");
   exit();
}

if ($skip=="Y")
   $_SESSION['entered_password']="";
else
   $_SESSION['entered_password']=$password;

// if they have been thru before don't bother with mobile page
if ($arr_pt_info['c_acceptedTC']=="YES")
{
   logMsg("Skip mobile page - Going to mobile action script...",$logfile);
   header ("Location: validation_mobile_a.php?tc=yes");
}
else
{
   // if we are here they must of just accepted TC
   set_accepted_tc($arr_pt_info['id']);
   logMsg("Going to mobile page...",$logfile);
   header ("Location: validation_mobile.php");
}
exit();
?>
