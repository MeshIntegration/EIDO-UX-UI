<?php
// **************************************
// validation_mobile_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/23/18
// **************************************

include "../utilities.php";
include "../lib/validation.php";
session_start();
$arr_pt_info=$_SESSION['arr_pt_info'];
$logfile = "validation.log";
$tc=get_query_string('tc'); // accepted terms and conditions

if ($tc=="yes")
{
   // we already have the data for mobile from previous time through
   $mobile=$arr_pt_info['c_mobileNumber'];
   $preferred=$arr_pt_info['c_preferredContactMethod'];
}
else
{
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
}

// check the data that was entered against what is in PatientEpisodes  
$error_ct = 0;
$_SESSION['surname_error'] = false;
$_SESSION['postalcode_error'] = false;
$_SESSION['dob_error'] = false;
$_SESSION['nhsnumber_error'] = false;

logMsg("validation_mobile_a: Surname Entered: ".$_SESSION['entered_surname']." - DB: ".$arr_pt_info['c_surname'],$logfile);
if (strtoupper($_SESSION['entered_surname']) <> strtoupper($arr_pt_info['c_surname']))
{
   logMsg("Surname Error",$logfile);
   $_SESSION['surname_error'] = true;
   $error_ct++;
}
logMsg("validation_mobile_a: PostalCode Entered: ".$_SESSION['entered_postalcode']." - DB: ".$arr_pt_info['c_postalCode'],$logfile);
// in the DB it is stored with no space so ceck it that way
$entered_postalcode=strtoupper(str_replace(" ", "", $_SESSION['entered_postalcode']));
if ($entered_postalcode <> $arr_pt_info['c_postalCode'])
{
   logMsg("PostalCode Error",$logfile);
   $_SESSION['postalcode_error'] = true;
   $error_ct++;
}
logMsg("validation_mobile_a: DOB Entered: ".$_SESSION['entered_dob']." - DB: ".$arr_pt_info['c_dateOfBirth'],$logfile);
if ($_SESSION['entered_dob'] <> $arr_pt_info['c_dateOfBirth'])
{
   logMsg("DOB Error",$logfile);
   $_SESSION['dob_error'] = true;
   $error_ct++;
}
logMsg("validation_mobile_a: NHS Entered: ".$_SESSION['entered_nhsnumber']." - DB: ".$arr_pt_info['c_nhsNumber'],$logfile);
if ($_SESSION['entered_nhsnumber'] <> $arr_pt_info['c_nhsNumber'])
{
   logMsg("NHS Error",$logfile);
   $_SESSION['nhsnumber_error'] = true;
   $error_ct++;
}

$_SESSION['error_ct']=$error_ct;
logMsg("validation_mobile_a: $error_ct", $logfile);

// Check moreReminders and handle if true
if ($_SESSION['moreReminders']=="true")
{
   $requestParam = array( 'var_patientEpisodeId' => $id);
   // we need to get process number from DB and put into URL
   //Change the server URL for live vs. dev
   $URL = "http://verify.eidosystems.com:8080/jw/web/json/workflow/process/list?packageId=".$packageId;
   $response = getCurlResponse($URL, array(), 1, "POST", "BASIC_AUTH");
   if ($response->total > 0) 
   {
      foreach (array_slice($response->data, 0) as $key => $value)    
      {
         if ($value->name == 'Patient Validation') 
         {
            $process_id = str_replace("#", ":", $value->id);
         }
      }
   }
   else
   {
      ; // need to redirect to some page if process id not found
   }
   //Change the server URL for live vs. dev
   if (isset($process_id)) {
      $URL = "http://verify.eidosystems.com:8080/jw/web/json/workflow/process/start/" . $process_id;
      $resp = getCurlResponse($URL, $requestParam, 1, "POST");
   }
// print response
//print_r($resp);
//exit();
}

if ($error_ct==0 || $error_ct==1)
{
   // Need to save the entered data so it can be checked in the request review section 3/8/18
   // DON"T save any changed data - per Rob - 3/5/18
   // save the data they entered - one item can change and the other extra items
   save_pt_info($arr_pt_info['id'], $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber'], $_SESSION['entered_password'], $mobile, $preferred);

   // take them to the correct survey
   $goto_url = get_survey_url($arr_pt_info);
   header ("Location: $goto_url");
   exit();
}

header ("Location: validation_review.php");
exit();
?>

