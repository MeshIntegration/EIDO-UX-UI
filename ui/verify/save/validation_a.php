<?php
// **************************************
// validation_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "./utilities.php";
include "./lib/validation.php";
$logfile = "validation.log";

$patientEpisodeId = get_query_string('patientEpisodeId');
$moreReminders = get_query_string('moreReminders');

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$_SESSION['error_msg'] = "";

$c_surname = $_POST['c_surname'];
$c_postalCode = $_POST['c_postalCode'];
$c_address = $_POST['c_address'];
 
logMsg("Surname: $c_surname Postal Code: $c_postalCode",$logfile);

if ($c_surname=="" || $c_postalCode=="")
{
   $_SESSION['error_msg'] = "Surname and address are both required fields";
   header ("Location: validation.php?patientEpisodeId=$patientEpisodeId&moreReminders=$moreReminders");
   exit();
}
$_SESSION['entered_surname'] = $c_surname; 
$_SESSION['entered_postalcode'] = $c_postalCode; 
$_SESSION['entered_address'] = $c_address; 

header ("Location: validation2.php");
exit();
?>
