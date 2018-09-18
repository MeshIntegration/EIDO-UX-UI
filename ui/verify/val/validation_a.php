<?php
// **************************************
// validation_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
$logfile = "validation.log";

// you got these already and should be in session
$patientEpisodeId = get_query_string('patientEpisodeId');
//$moreReminders = get_query_string('moreReminders');
//$status = get_query_string('status');

session_start();
$arr_pt_info = get_pt_info($patientEpisodeId);
$_SESSION['patientEpisodeId'] = $arr_pt_info['id'];
$c_surname = $_POST['c_surname'];
$c_postalCode = $_POST['c_postalCode'];
$c_address = $_POST['c_address'];

logMsg("Surname: $c_surname Postal Code: $c_postalCode",$logfile);

// get rid of spaces and be sure upper case
$c_postalCode=strtoupper(str_replace(" ", "", $c_postalCode));
 
$_SESSION['entered_surname'] = $c_surname; 
$_SESSION['entered_postalcode'] = $c_postalCode; 
$_SESSION['entered_address'] = $c_address;

if ($c_surname=="")
    $_SESSION['surname_error']=true; else $_SESSION['surname_error']=false;
if ($c_postalCode=="")
    $_SESSION['postalcode_error']=true; else $_SESSION['postalcode_error']=false;
if ($_SESSION['surname_error'] || $_SESSION['postalcode_error'])
{
    header ("Location: validation.php?patientEpisodeId=".$_SESSION['patientEpisodeId']."&moreReminders=".$_SESSION['moreReminders']);
    exit();
}

header ("Location: validation2.php");
exit();
?>
