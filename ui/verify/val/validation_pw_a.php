<?php
// **************************************
// validation_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/25/18
// **************************************

include "../utilities.php";
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$_SESSION['error_msg']="";

$logfile = "validation.log";

$password=$_POST['password'];
$skip=get_query_string('skip');

logMsg("password_a: Entered PW: $password - Skip: $skip",$logfile);

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

//Additions required to accomplish newly specified flow - Andrew 5/14/18
//may have came from contact


   logMsg("Mobile page done already - going to survey...",$logfile);
   // save the entered data to use in the request review section
   save_pt_info($arr_pt_info['id'], $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber'], $_SESSION['entered_password'], $arr_pt_info['c_mobileNumber'], $arr_pt_info['c_preferred_ContactMethod'], $arr_pt_info['c_emailAddress']);

   // WE HAVE LIFT OFF - take them to the correct survey
   $goto_url = get_survey_url($arr_pt_info);
   $_SESSION = array();
   session_destroy();
   header ("Location: $goto_url");
   exit();
