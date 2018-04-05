<?php
// ***************************************
// patient/patients_a.php
// 2018 Copyright, Mesh Integration LLC
// 1/6/17 - WEL
// ***************************************

session_start();
require_once '../utilities.php';
$logfile = "wel.log";
$debug=true;  // turn on for extra logging

$mode = get_query_string('m');
$id = get_query_string('id');
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$nhsnumber = $_POST['nhsnumber'];
$hospitalnumber = $_POST['hospitalnumber'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$postalcode = $_POST['postalcode'];
$email = $_POST['email'];
$mobilenumber = $_POST['mobilenumber'];

logMsg("PATIENTS_A: mode: $mode id: $id $fname $lname",$logfile);

if ($mode=="edit")
{
   $_SESSION['edit_fname'] = $_POST['fname'];
   $_SESSION['edit_lname'] = $_POST['lname'];
   $_SESSION['edit_nhsnumber'] = $_POST['nhsnumber'];
   $_SESSION['edit_hospitalnumber'] = $_POST['hospitalnumber'];
   $_SESSION['edit_gender'] = $_POST['gender'];
   $_SESSION['edit_dob'] = $_POST['dob'];
   $_SESSION['edit_postalcode'] = $_POST['postalcode'];
   $_SESSION['edit_email'] = $_POST['email'];
   $_SESSION['edit_mobilenumber'] = $_POST['mobilenumber'];

   header("Location: patients.php?m=editreview&id=$id");
   exit();
}
else if ($mode==editconfirm)
{
   $sql = "UPDATE app_fd_pro_patientEpisodes
           SET c_firstName=".escapeQuote($_SESSION['fname']).",
               c_surname=".escapeQuote($_SESSION['lname']).",
               c_nhsNumber=".escapeQuote($_SESSION['nhsnumber']).",
               c_referenceNumberHospitalId=".escapeQuote($_SESSION['hospitalnumber']).",
               c_gender=".escapeQuote($_SESSION['gender']).",
               c_dateOfBirth=".escapeQuote($_SESSION['dob']).",
               c_postalCode=".escapeQuote($_SESSION['postalcode']).",
               c_emailAddress=".escapeQuote($_SESSION['email']).",
               c_mobileNumber=".escapeQuote($_SESSION['mobilenumber']).",
               dateModified=NOW(),
               dateCreated=NOW() 
           WHERE id='$id'";
   dbi_query($sql);
   if ($debug) logMsg($sql,$logfile);
} 
else if ($mode=="add")
{
   $_SESSION['add_fname'] = $fname;
   $_SESSION['add_lname'] = $lname;
   $_SESSION['add_nhsnumber'] = $nhsnumber;
   $_SESSION['add_hospitalnumber'] = $hospitalnumber;
   $_SESSION['add_gender'] = $gender;
   $_SESSION['add_dob'] = $dob;
   $_SESSION['add_postalcode'] = $postalcode;
   $_SESSION['add_email'] = $email;
   $_SESSION['add_mobilenumber'] = $mobilenumber;

   header("Location: patients.php?m=addaddress");
   exit();
}
else if ($mode=="addaddress")
{
   $_SESSION['add_address'] = $_POST['address'];
   $_SESSION['add_address2'] = $_POST['address2'];
   $_SESSION['add_city'] = $_POST['city'];
   $_SESSION['add_county'] = $_POST['county'];
   $_SESSION['add_postalcode'] = $_POST['postalcode'];

   header("Location: patients.php?m=addreview");
   exit();
}
else if ($mode=="editaddress")
{
   $sql = "UPDATE app_fd_pro_patientEpisodes
           SET c_address=".escapeQuote($_POST['address']).",
               c_city=".escapeQuote($_POST['city']).",
               c_county=".escapeQuote($_POST['county']).",
               c_postalCode=".escapeQuote($_POST['postalcode']).",
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   header("Location: patients.php?m=edit&id=$id");
   exit();
}
else if ($mode=="addconfirm")
{
   // INSERT
   $pe_id = uniqid();
   $sql = "INSERT INTO app_fd_pro_patientEpisodes
           SET id='".$pe_id."',
               c_firstName=".escapeQuote($_SESSION['fname']).",
               c_surname=".escapeQuote($_SESSION['lname']).",
               c_nhsNumber=".escapeQuote($_SESSION['nhsnumber']).",
               c_referenceNumberHospitalId=".escapeQuote($_SESSION['hospitalnumber']).",
               c_gender=".escapeQuote($_SESSION['gender']).",
               c_dateOfBirth=".escapeQuote($_SESSION['dob']).",
               c_postalCode=".escapeQuote($_SESSION['postalcode']).",
               c_emailAddress=".escapeQuote($_SESSION['email']).",
               c_mobileNumber=".escapeQuote($_SESSION['mobilenumber']).",
               c_address=".escapeQuote($_SESSION['address']).",
               c_city=".escapeQuote($_SESSION['city']).",
               c_county=".escapeQuote($_SESSION['county']).",
               dateModified=NOW(),
               dateCreated=NOW()";
   dbi_query($sql);
   if ($debug) logMsg($sql,$logfile);
}
else if ($mode=="proccomplete" || $mode=="proccancel")
{
   $loginas=get_query_string('loginas');
logMsg("Patients_a: Proccomplete: loginas: $loginas", $logfile);
   $surgeon = $_POST['surgeon'];
   $gmc_number = $_POST['gmc_number'];
   //$proc_date = $_POST['proc_date'];

   // write Proceed or Cancel into DB 
   if ($mode=="proccomplete")
      $value="Proceed";
   else
      $value="Cancel";
   $sql = "UPDATE app_fd_pro_patientEpisodes
           SET c_proceedOrCancel = '$value' 
           WHERE id='$id'";
   dbi_query($sql);
   
   // get the activity ID for this procedure
   $requestParam = array();
   logMsg("Patients_A: ProcComplete: Method URL: ".$BASE_WORKFLOW_URL."assignment/process/view/".$id, $logfile);
   $loginas = str_replace(" ", "", $loginas);
   $resp = getCurlResponse($BASE_WORKFLOW_URL."assignment/process/view/".$id, $requestParam, 1, "GET", "DEFAULT", "OBJECT", $loginas);
   $activity_id = $resp->activityId;
   logMsg("ActivityId: ".$activity_id, $logfile);
echo "Get Acticity<br />";
echo "<pre>";
print_r($resp);
echo "</pre>";

   // use the activity ID to set this activity to Complete
   logMsg("Patients_A: ProcComplete: Method URL: ".$BASE_WORKFLOW_URL."assignment/complete/".$id, $logfile);
   $resp = getCurlResponse($BASE_WORKFLOW_URL."assignment/complete/".$activity_id, $requestParam, 1, "POST", "BASIC_AUTH", "OBJECT", $loginas);
   $status = $resp->status;
   logMsg("Status: ".$status, $logfile);
echo "Set Complete<br />";
echo "<pre>";
print_r($resp);
echo "</pre>";
exit();
}
header("Location: patients.php");
exit();

?>
