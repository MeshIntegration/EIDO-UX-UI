<?php
// **************************************
// validation_mobile_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "./utilities.php";
include "./lib/validation.php";
session_start();
$logfile = "validation.log";

$mobile=$_POST['mobile'];
$preferred=$_POST['preferred'];
logMsg("mobile: $mobile  Preferred: $preferred",$logfile);

if ($preferred=="MOBILE" && $mobile=="")
{
   $_SESSION['error_msg']="NO_MOBILE";
   header ("Location: validation_mobile.php");
   exit();
}

// check the data that was entered against what is in PatientEpisodes  
$error_ct = 0;
$_SESSION['surname_error'] = false;
$_SESSION['postalcode_error'] = false;
$_SESSION['dob_error'] = false;
$_SESSION['nhsnumber_error'] = false;

if ($_SESSION['entered_surname'] <> $arr_pt_info['c_surname'])
{
   $_SESSION['surname_error'] = true;
   $error_ct++;
}
logMsg("validation_mobile_a: PostalCode Entered: ".$_SESSION['entered_postalcode']." - DB: ".$arr_pt_info['c_postalCode'],$logfile);
if ($_SESSION['entered_postalcode'] <> $arr_pt_info['c_postalCode'])
{
logMsg("PostalCode Error",$logfile);
   $_SESSION['postalcode_error'] = true;
   $error_ct++;
}
if ($_SESSION['entered_dob'] <> $arr_pt_info['c_dateOfBirth'])
{
   $_SESSION['dob_error'] = true;
   $error_ct++;
}
if ($_SESSION['entered_nhsnumber'] <> $arr_pt_info['c_nhsNumber'])
{
   $_SESSION['nhsnumber_error'] = true;
   $error_ct++;
}

if ($error_ct==0 || $error_ct==1)
{
   // do the stuff that takes them to the correct survey
   // do this in a function so the data review screens can use it too
   // remeber to do the thing for more reminders
}
else
{
   // error_ct = 2 - soft fail - they get the option for a review
   // error_ct = 3 or 4 - hard fail
   $_SESSION['error_ct']=$error_ct;
   header ("Location: validation_review.php");
   exit();
}
?>

