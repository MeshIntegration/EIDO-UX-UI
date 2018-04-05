<?php
// **************************************
// validation_mobile_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
session_start();
$arr_pt_info=$_SESSION['arr_pt_info'];
$logfile = "validation.log";
$tc=get_query_string('tc'); // accepted terms and conditions

// get the data from the form
$mobile=$_POST['mobile'];
$preferred=$_POST['preferred'];
logMsg("mobile: $mobile  Preferred: $preferred",$logfile);

if ($preferred=="MOBILE" && $mobile=="")
{
   $_SESSION['error_msg']="NO_MOBILE";
   header ("Location: validation_mobile.php");
   exit();
}

// save the entered data 
save_pt_info($arr_pt_info['id'], $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber'], $_SESSION['entered_password'], $mobile, $preferred);

// WE HAVE LIFT OFF
// take them to the correct survey
$goto_url = get_survey_url($arr_pt_info);
header ("Location: $goto_url");
exit();
?>

