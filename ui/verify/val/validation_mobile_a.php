<?php
// **************************************
// validation_mobile_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
session_start();
$arr_pt_info = get_pt_info($_SESSION['patientEpisodeId']);
$patientEpisodeId = $arr_pt_info['id'];
$_SESSION['patientEpisodeId'] = $arr_pt_info['id'];
//$arr_pt_info=$_SESSION['arr_pt_info'];
$password=$arr_pt_info['c_password'];
$logfile = "validation.log";
//$tc=get_query_string('tc'); // accepted terms and conditions

// get the data from the form
$mobile=$_POST['mobile'];
$email=$_POST['email'];
$preferred=$_POST['preferred'];
$preferenceset=$_POST['preferenceset'];

logMsg("mobile: $mobile  email: $email Preferred: $preferred preferenceSet: $preferenceset",$logfile);

if ($preferred=="MOBILE" && $mobile=="") {
   $_SESSION['error_msg']="NO_MOBILE";
   header ("Location: validation_mobile.php");
   exit();
}
if ($preferred=="EMAIL" && $email=="") {
   $_SESSION['error_msg']="NO_EMAIL";
   header ("Location: validation_mobile.php");
   exit();
}




// save the entered data 
save_pt_info($patientEpisodeId, $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber'], $password, $mobile, $preferred, $email, $preferenceset);


//if no password is set and an email already existed, or was just provided, take them to create password
if ($password=="" && $email<>"" && $preferred=="EMAIL") {
    header ("Location: validation_pw.php?patientEpisodeId=$patientEpisodeId");
    exit();
}
else {


// WE HAVE LIFT OFF
// take them to the correct survey
$goto_url = get_survey_url($arr_pt_info);
$_SESSION = array();

header ("Location: $goto_url");
exit();

}
?>