<?php
// **************************************
// validation2_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "./utilities.php";
include "./lib/validation.php";
$logfile = "validation.log";

session_start();
$_SESSION['error_msg']="";
$arr_pt_info=$_SESSION['arr_pt_info'];

$dob_day = $_POST['dob_day'];
$dob_month = $_POST['dob_month'];
$dob_year = $_POST['dob_year'];
$c_nhsNumber = $_POST['c_nhsNumber'];

logMsg("day: $dob_day  month: $dob_month year: $dob_year ",$logfile);

if ($dob_day=="" || $dob_month=="" || $dob_year=="")
{
   $_SESSION['error_msg']="NO_DATE";
   header ("Location: validation2.php");
   exit();
}
$_SESSION['entered_dob'] = "$dob_day/$dob_month/$dob_year";
$_SESSION['entered_nhsnumber'] = "$c_nhsNumber";

header ("Location: validation_tc.php");
exit();
?>

