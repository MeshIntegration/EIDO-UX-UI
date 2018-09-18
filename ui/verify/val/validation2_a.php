<?php
// **************************************
// validation2_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
$logfile = "validation.log";

session_start();
//$arr_pt_info=$_SESSION['arr_pt_info'];
$arr_pt_info = get_pt_info($_SESSION['patientEpisodeId']);
$_SESSION['patientEpisodeId'] = $arr_pt_info['id'];
$dob_day = $_POST['dob_day'];
$dob_month = $_POST['dob_month'];
$dob_year = $_POST['dob_year'];
$c_nhsNumber = $_POST['c_nhsNumber'];

logMsg("day: $dob_day  month: $dob_month year: $dob_year ",$logfile);

$_SESSION['entered_dob_day'] = $dob_day;
$_SESSION['entered_dob_month'] = $dob_month;
$_SESSION['entered_dob_year'] = $dob_year;
$_SESSION['entered_dob'] = "$dob_day/$dob_month/$dob_year";
$_SESSION['entered_nhsnumber'] = $c_nhsNumber;


if ($dob_day=="" || $dob_month=="" || $dob_year=="")
   $_SESSION['dob_error']=true; else $_SESSION['dob_error']=false;
if ($c_nhsNumber=="")
   $_SESSION['nhsnumber_error']=true; else $_SESSION['nhsnumber_error']=false;

if ($_SESSION['dob_error'] || $_SESSION['nhsnumber_error']) {
   header ("Location: validation2.php");
   exit();
}

save_entered_pt_info($arr_pt_info['id'], $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber']);

if ($arr_pt_info['c_acceptedTC']=="YES")
   header ("Location: validation_review.php");
else
   header ("Location: validation_tc.php");
exit();
?>

