<?php
// **************************************
// validation_review_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 3/4/18
// **************************************

include "../utilities.php";
include "../lib/validation.php";
session_start();
$arr_pt_info=$_SESSION['arr_pt_info'];
$logfile = "validation.log";
$ip_address = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];

$error_ct = $_SESSION['error_ct'];
// loser - just blow him back to login
if ($error_ct == 4)
{
   logMsg("Data Review: HARD FAIL (4 errors)",$logfile);
   add_to_timeline($arr_pt_info['id'], "Patient validation error (hard fail)", "Open", "Alert", $browser, $ip_address, "Validation");
   header("Location: validation.php");
   exit();
}
// get the data from the form
$c_surname=trim($_POST['c_surname']);
$c_postalCode=trim($_POST['c_postalCode']);
// in the DB it is stored with no space so ceck it that way
$c_postalCode=strtoupper(str_replace(" ", "", $c_postalCode));
$c_nhsNumber=trim($_POST['c_nhsNumber']);
$dob_day=$_POST['dob_day'];
$dob_month=$_POST['dob_month'];
$dob_year=$_POST['dob_year'];
$c_dateOfBirth = "$dob_day/$dob_month/$dob_year";

// check the data that was entered on the review against what is in PatientEpisodes  
$new_error_ct = 0;

if ($_SESSION['surname_error'])
{
   $_SESSION['entered_surname']=$c_surname;
   if (strtoupper($c_surname) <> strtoupper($arr_pt_info['c_surname']))
   {
      logMsg("Data Review: Surname Error",$logfile);
      $new_error_ct++;
   }
   else
      $_SESSION['surname_error']=false;
}
   
if ($_SESSION['postalcode_error'])
{
   $_SESSION['entered_postalcode']=$c_postalCode;
   if ($c_postalCode <> $arr_pt_info['c_postalCode'])
   {
      logMsg("Data Review: PostalCode Error",$logfile);
      $new_error_ct++;
   }
   else
      $_SESSION['postalcode_error']=false;
}

if ($_SESSION['dob_error'])
{
   $_SESSION['entered_dob'];
   if ($c_dateOfBirth <> $arr_pt_info['c_dateOfBirth'])
   {
      logMsg("Data Review: DOB Error",$logfile);
      $new_error_ct++;
   }
   else
      $_SESSION['dob_error']=false;
}

if ($_SESSION['nhsnumber_error'])
{
   $_SESSION['entered_nhsnumber']=$c_nhsNumber;
   if ($c_nhsNumber <> $arr_pt_info['c_nhsNumber'])
   {
      logMsg("Data Review: NHS Error",$logfile);
      $new_error_ct++;
   }
   else
      $_SESSION['nhsnumber_error']=false;
}

logMsg("validation_review_a: New Error Count: $new_error_ct", $logfile);
//if ($error_ct==2 && $new_error_ct==2)
if ($new_error_ct==2)
{
   logMsg("validation_review_a: SOFT FAIL logged", $logfile);
   add_to_timeline($arr_pt_info['id'], "Patient validation error (soft fail)", "Open", "Alert", $browser, $ip_address, "Validation");
   $_SESSION['error_ct']=$new_error_ct; 
   header("Location: validation_review.php");
   exit();
}

if ($error_ct==3 && $new_error_ct>1)
{
   logMsg("validation_review_a: HARD FAIL logged", $logfile);
   add_to_timeline($arr_pt_info['id'], "Patient validation error (soft fail)", "Open", "Alert", $browser, $ip_address, "Validation");
   header("Location: login.php");
   exit();
}

// WE HAVE LIFT OFF
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
      ; // need to redirect to same page if process id not found
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

// save the entered data to use inthe request review section
// DON"T save changed data per Rob - 3/5/18
// save the data they entered - one item can change and the other extra items
save_pt_info($arr_pt_info['id'], $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber'], $_SESSION['entered_password'], $mobile, $preferred);

// take them to the correct survey
$goto_url = get_survey_url($arr_pt_info);
header ("Location: $goto_url");
exit();

header ("Location: login.php");
exit();
?>

