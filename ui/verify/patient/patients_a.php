<?php
// ***************************************
// patient/patients_a.php
// 2018 Copyright, Mesh Integration LLC
// 1/6/17 - WEL
// ***************************************

session_start();
require_once '../utilities.php';
require_once 'functions.php';
$logfile = "patient.log";
$debug=true;  // turn on for extra logging
$ip_address = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];

$mode = get_query_string('m');
$id = get_query_string('id');
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$nhsnumber = $_POST['nhsnumber'];
$hospitalnumber = $_POST['hospitalnumber'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$postalcode = $_POST['postalcode'];
logMsg("patients_a: POST value of postalcode= $postalcode", $logfile);
$email = $_POST['email'];
$mobilenumber = $_POST['mobilenumber'];

logMsg("PATIENTS_A: mode: $mode id: $id $fname $lname",$logfile);

if ($mode=="edit")
{
   logMsg("---------------- Edit -----------------",$logfile);
   // this used to be in the old "edit" and then it went to editreview
   // now we just save it right away 
   // $_SESSION['edit_fname'] = $_POST['fname'];
   // $_SESSION['edit_lname'] = $_POST['lname'];
   // $_SESSION['edit_nhsnumber'] = $_POST['nhsnumber'];
   // $_SESSION['edit_hospitalnumber'] = $_POST['hospitalnumber'];
   // $_SESSION['edit_gender'] = $_POST['gender'];
   // $_SESSION['edit_dob'] = $_POST['dob'];
   // $_SESSION['edit_postalcode'] = $_POST['postalcode'];
   // $_SESSION['edit_email'] = $_POST['email'];
   // $_SESSION['edit_mobilenumber'] = $_POST['mobilenumber'];

   // store postal code with no spaces all caps
   //    $postalcode=strtoupper(str_replace(" ", "", $_POST['postalcode']));
   //            c_postalCode=".escapeQuote($postalcode).",
   $sql = "UPDATE $TBLPTEPISODES
           SET c_firstName=".escapeQuote($_POST['fname']).",
               c_surname=".escapeQuote($_POST['lname']).",
               c_nhsNumber=".escapeQuote($_POST['nhsnumber']).",
               c_referenceNumberHospitalId=".escapeQuote($_POST['hospitalnumber']).",
               c_gender=".escapeQuote($_POST['gender']).",
               c_dateOfBirth=".escapeQuote($_POST['dob']).",
               c_emailAddress=".escapeQuote($_POST['email']).",
               c_mobileNumber=".escapeQuote($_POST['mobilenumber']).",
               dateModified=NOW(),
               dateCreated=NOW() 
           WHERE id='$id'";
   logMsg($sql,$logfile);
   dbi_query($sql);
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   add_to_timeline($id, "Edit Patient Details ($fname $lname)", "Closed", "CHANGELOG", 
                          $browser, $ip_address, "Patient Dashboard");
   header("Location: patients.php?m=editconfirm&id=$id");
   exit();
} 
else if ($mode=="add")
{ 
   // required field check
   if ($fname=="")
      $_SESSION['add_fname_error']=true; else $_SESSION['add_fname_error']=false;
   if (!preg_match("/^[a-zA-Z- ']*$/",$fname))
      $_SESSION['add_fname_format_error']=true; else $_SESSION['add_fname_format_error']=false;

   if ($lname=="")
      $_SESSION['add_lname_error']=true; else $_SESSION['add_lname_error']=false;
   if (!preg_match("/^[a-zA-Z- ']*$/",$lname))
      $_SESSION['add_lname_format_error']=true; else $_SESSION['add_lname_format_error']=false;

   if ($nhsnumber=="")
      $_SESSION['add_nhsnumber_error']=true; else $_SESSION['add_nhsnumber_error']=false;
   if (!preg_match("/^[0-9]*$/",$nhsnumber))
      $_SESSION['add_nhsnumber_format_error']=true; else $_SESSION['add_nhsnumber_format_error']=false;
   if (strlen($nhsnumber)<>9)
      $_SESSION['add_nhsnumber_length_error']=true; else $_SESSION['add_nhsnumber_length_error']=false;

   if ($hospitalnumber=="")
      $_SESSION['add_hospitalnumber_error']=true; else $_SESSION['add_hospitalnumber_error']=false;
   if (!preg_match("/^[a-zA-Z0-9]*$/",$hospitalnumber))
      $_SESSION['add_hospitalnumber_format_error']=true; else $_SESSION['add_hospitalnumber_format_error']=false;

   if ($gender=="")
      $_SESSION['add_gender_error']=true; else $_SESSION['add_gender_error']=false;

   if ($dob=="")
      $_SESSION['add_dob_error']=true; 
   else 
   {
      $_SESSION['add_dob_error']=false;
      if (!preg_match("/^[0-9\/\-]*$/",$dob))
         $_SESSION['add_dob_format_error']=true; 
      else
      {
         $_SESSION['add_dob_format_error']=false;
         $arr_date_cleanup=date_cleanup($dob);
         if (!$arr_date_cleanup['date_valid']) 
            $_SESSION['add_dob_invalid_error']=true; 
         else 
         {
            $_SESSION['add_dob_invalid_error']=false;
            $dob=$arr_date_cleanup['date_formatted'];
         }
      }
   }

   if ($postalcode=="")
      $_SESSION['add_postalcode_error']=true; else $_SESSION['add_postalcode_error']=false;
   if (!preg_match("/^[a-zA-Z0-9 ]*$/",$postalcode) || strlen($postalcode)<5 || strlen($postalcode)>8)
      $_SESSION['add_postalcode_format_error']=true; else $_SESSION['add_postalcode_format_error']=false;

   if ($email<>"" && !filter_var($email, FILTER_VALIDATE_EMAIL)) 
      $_SESSION['add_bad_email_error']=true; else $_SESSION['add_bad_email_error']=false;
   if ($email=="" && $mobilenumber=="")
      $_SESSION['add_no_contact_error']=true; else $_SESSION['add_no_contact_error']=false;
   
   logMsg("patients_a: mode=add", $logfile);
   $_SESSION['add_fname'] = $fname;
   $_SESSION['add_lname'] = $lname;
   $_SESSION['add_nhsnumber'] = $nhsnumber;
   $_SESSION['add_hospitalnumber'] = $hospitalnumber;
   $_SESSION['add_gender'] = $gender;
   $_SESSION['add_dob'] = $dob;
   $_SESSION['add_postalcode'] = $postalcode;
   $_SESSION['add_email'] = $email;
   $_SESSION['add_mobilenumber'] = $mobilenumber;
   
   if ($_SESSION['add_fname_error'] || $_SESSION['add_lname_error'] || $_SESSION['add_nhsnumber_error'] || 
       $_SESSION['add_hospitalnumber_error'] || $_SESSION['add_gender_error'] || $_SESSION['add_dob_error'] || 
       $_SESSION['add_postalcode_error'] || $_SESSION['add_bad_email_error'] || $_SESSION['add_no_contact_error'] || 
       $_SESSION['add_fname_format_error'] || $_SESSION['add_lname_format_error'] || 
       $_SESSION['add_nhsnumber_format_error'] || $_SESSION['add_hospitalnumber_format_error'] || 
       $_SESSION['add_nhsnumber_length_error'] || $_SESSION['add_dob_invalid_error'] ||
       $_SESSION['add_dob_format_error'] || $_SESSION['add_postalcode_format_error'])
   {
      header("Location: patients.php?m=add");
      exit();
   }
   else
   {
      header("Location: patients.php?m=addaddress");
      exit();
   }
}
else if ($mode=="addaddress")
{
   if ($_POST['address']=="") 
      $_SESSION['add_address_error']=true; else $_SESSION['add_address_error']=false;
   $_SESSION['add_address'] = $_POST['address'];
   $_SESSION['add_address2'] = $_POST['address2'];
   $_SESSION['add_city'] = $_POST['city'];
   $_SESSION['add_county'] = $_POST['county'];
   $_SESSION['add_postalcode'] = $_POST['postalcode'];

   if ($_SESSION['add_address_error'])
   {
      header("Location: patients.php?m=addaddress");
      exit();
   }
   else
   {
      header("Location: patients.php?m=addreview");
      exit();
   }
   exit();
}
else if ($mode=="editaddress")
{
   $edit_postalcode=str_replace(" ", "", $_POST['postalcode']);
   $sql = "UPDATE $TBLPTEPISODES
           SET c_address=".escapeQuote($_POST['address']).",
               c_city=".escapeQuote($_POST['city']).",
               c_county=".escapeQuote($_POST['county']).",
               c_postalCode=".escapeQuote($edit_postalcode).",
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   header("Location: patients.php?m=edit&id=$id");
   exit();
}
else if ($mode=="addconfirm")
{
   // get some info about the user putting this record in
   $user_id = $_COOKIE['user_id'];
   $sql = "SELECT u.firstName, u.lastName, 
                 e.departmentId, d.name
           FROM dir_user u, dir_employment e, dir_department d
           WHERE u.id='$user_id'
           AND e.userid='$user_id'
           AND d.id=e.departmentId";
   $GetQuery = dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $username = $qryResult['firstName']." ".$qryResult['lastName'];
   $hospitalname = $qryResult['name'];
   // INSERT
   $pe_id = uniqid();
   // store postalcode with no spaces
   $add_postalcode=strtoupper(str_replace(" ", "", $_SESSION['add_postalcode']));
   // default to EMAIL unless email is blank, then default to MOBILE
   // validation won't let it thru without one having a value
   if ($_SESSION['add_email']=="")
      $preferred="MOBILE";
   else
      $preferred="EMAIL";
   $sql = "INSERT INTO $TBLPTEPISODES
           SET id='".$pe_id."',
               c_userId='$user_id',
               c_userName='$username',
               c_hospitalName='$hospitalname',
               c_firstName=".escapeQuote($_SESSION['add_fname']).",
               c_surname=".escapeQuote($_SESSION['add_lname']).",
               c_nhsNumber=".escapeQuote($_SESSION['add_nhsnumber']).",
               c_referenceNumberHospitalId=".escapeQuote($_SESSION['add_hospitalnumber']).",
               c_gender=".escapeQuote($_SESSION['add_gender']).",
               c_dateOfBirth=".escapeQuote($_SESSION['add_dob']).",
               c_postalCode=".escapeQuote($add_postalcode).",
               c_emailAddress=".escapeQuote($_SESSION['add_email']).",
               c_mobileNumber=".escapeQuote($_SESSION['add_mobilenumber']).",
               c_address=".escapeQuote($_SESSION['add_address']).",
               c_city=".escapeQuote($_SESSION['add_city']).",
               c_county=".escapeQuote($_SESSION['add_county']).",
               c_preferredContactMethod='$preferred',
               c_status='PENDING',
               c_acceptedTC='NO',
               c_hasAlert=' ',
               dateModified=NOW(),
               dateCreated=NOW()";
   dbi_query($sql);
   if ($debug) logMsg($sql,$logfile);
   $fname=$_SESSION['add_fname'];
   $lname=$_SESSION['add_lname'];
   add_to_timeline($pe_id, "New Patient Added ($fname $lname)", "Closed", "CHANGELOG", $browser, $ip_address, "Patient Dashboard");
   add_to_timeline($pe_id, "New Patient Added", "Open", "Event", $browser, $ip_address, "Patient Dashboard");
   // go to the Procedure Proceed screen to ask what next 
   header("Location: patients.php?m=procproceed&id=$pe_id");
   exit();
}
else if ($mode=="gotoaddpt")
{
   clear_add_session();
   $_SESSION['workflow']="ADD";
   header("Location: patients.php?m=add");
   exit();
}
else if ($mode=="gotoprocdate")
{
   clear_add_session();
   $_SESSION['workflow']="PROCDATE";
   header("Location: patients.php?m=procdate&id=$id");
   exit();
}
else if ($mode=="tl_view_all")
{
   if ($_SESSION['tl_view_all'])
      $_SESSION['tl_view_all']=false;
   else
      $_SESSION['tl_view_all']=true;
   header("Location: patients.php?m=overview&id=$id");
   exit();
   
}
else if ($mode=="review")
{
   $timeline_id = get_query_string('tid');
   $arr_update = array();

   $surname_radio=$_POST['surname_radio'];
   $new_surname=escapeQuote($_POST['new_surname']);
   $postalcode_radio=$_POST['postalcode_radio'];
   $new_postalcode=escapeQuote($_POST['new_postalcode']);
   $new_postalcode=strtoupper(str_replace(" ", "", $new_postalcode));
   $dob_radio=$_POST['dob_radio'];
   $new_dob=escapeQuote($_POST['new_dob']);
   $nhsnumber_radio=$_POST['nhsnumber_radio'];
   $new_nhsnumber=escapeQuote($_POST['new_nhsnumber']);
   
   //  P = use patient value
   //  N = use new value
   //  V = use verify - which means leavve it as is
   if ($surname_radio=="P")
      $arr_update[]=" c_surname=c_surnameEntered ";
   else if ($surname_radio=="N")
      $arr_update[]=" c_surname=$new_surname ";
   if ($postalcode_radio=="P")
      $arr_update[]=" c_postalCode=c_postalcodeEntered ";
   else if ($postalcode_radio=="N")
      $arr_update[]=" c_postalCode=$new_postalcode ";
   if ($dob_radio=="P")
      $arr_update[]=" c_dateOfBirth=c_dateOfBirthEntered ";
   else if ($dob_radio=="N")
      $arr_update[]=" c_dateOfBirth=$new_dob ";
   if ($nhsnumber_radio=="P")
      $arr_update[]=" c_nhsNumber=c_nhsNumberEntered ";
   else if ($nhsnumber_radio=="N")
      $arr_update[]=" c_nhsNumber=$new_nhsnumber ";

   $update_str = implode(",", $arr_update);
   
   $sql="UPDATE $TBLPTEPISODES
         SET $update_str
         WHERE id='$id'";
   logMsg("Pt Review: $sql",$logfile);
   dbi_query($sql);

   // set the review request to closed
   // we were using the timeline id to just do the one
   // but do all of them in case there are multiples for this patient 
   $sql="UPDATE $TBLTIMELINES
         SET c_timelineAlertStatus='Closed'
         WHERE c_patientEpisodeId='$id'
           AND c_timelineEntryType='Alert'
           AND c_timelineEntryDetail='Request review'";
logMsg("Update TL: $sql", $logfile);
   dbi_query($sql);

   // update c_hasAlert in pt episodes
   update_alert_status($id);

   $ip_address = $_SERVER['REMOTE_ADDR'];
   $browser = $_SERVER['HTTP_USER_AGENT'];
   add_to_timeline($id, "Request for data review complete", "", "Event", $browser, $ip_address, "Validation");
   add_to_timeline($id, "Review Patient Access", "Closed", "CHANGELOG", $browser, $ip_address, "Patient Dashboard");

   $arr_pt_info=get_pt_info($id);
   // send mail to the patient 
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", $arr_pt_info['c_firstName'], $email_template);
   $email_template = str_replace("**HEADER**", "Account Review", $email_template);

   // CONTENT1 is the preview text
   $content1 = "We're sorry you had problems accessing the system. A member of staff has reviewed your information and reset your account. Please try again and if you have any more issues just get in touch with your medical team.";
   $email_template = str_replace("**CONTENT1**", $content1, $email_template);

   // CONTENT2 is the actual message
   $content2 = "We're sorry you had problems accessing the system. A member of staff has reviewed your information and reset your account. Please try again and if you have any more issues just get in touch with your medical team.";
   $email_template = str_replace("**CONTENT2**", $content2, $email_template);

   // set up the button
   $button_text = "Get Started";
   $email_template = str_replace("**BUTTONTEXT**", $button_text, $email_template);
   $button_url = $SITE_URL."val/validation.php?patientEpisodeId=$id&moreReminders=true";
   $email_template = str_replace("**BUTTONURL**", $button_url, $email_template);
   
   $arr_email = array();
   $arr_email['mail_to']=$arr_pt_info['c_emailAddress'];
   $arr_email['mail_to_name']=$arr_pt_info['c_firstName']." ". $arr_pt_info['c_lastName'];
   $arr_email['bcc']="wayne@mindstreams.com";
   $arr_email['mail_from']=$verify_mail_from;
   $arr_email['mail_from_name']=$verify_mail_from_name;
   $arr_email['subject']="EIDO Verify Account Review";
   $arr_email['body']=$email_template;

   send_email($arr_email);
  
   header("Location: resend_invite.php?rtn=reviewconfirm&id=$id");
   // header("Location: patients.php?m=reviewconfirm&id=$id");
   exit();
}
else if ($mode=="clearalert")
{
   $timeline_id = get_query_string('tid');
   // set the alert to closed
   $sql="UPDATE $TBLTIMELINES
         SET c_timelineAlertStatus='Closed'
         WHERE id='$timeline_id'";
//logMsg("Update TL: $sql", $logfile);
   dbi_query($sql);

   // update c_hasAlert
   update_alert_status($id);
   
   header("Location: patients.php?m=detail&id=$id");
   exit();
}
else if ($mode=="procselect")
{
   $proc_id = $_POST['proc_id'];
   $_SESSION['proc_id_entered']=$proc_id;
   if ($proc_id=="") 
   {
      $_SESSION['proc_select_error']=true; 
      header("Location: patients.php?m=procselect&id=$id");
      exit();
   }
   else 
      $_SESSION['proc_select_error']=false;
logMsg("patients_a: procselect: proc_id (from post[proc_id])= $proc_id",$logfile);
   $sql = "UPDATE $TBLPTEPISODES
           SET c_procedure = '$proc_id'
           WHERE id='$id'";
   logMsg("Patients_a: ProcSelect: $sql", $logfile);
   dbi_query($sql);
   $sql = "SELECT * FROM app_fd_ver_procEpisodes
           WHERE id = '$proc_id'";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $sql2 = "UPDATE $TBLPTEPISODES
            SET dateModified=NOW(),
                c_currentSessionNumber=1,
                c_session1Survey1='".$qryResult['c_session1Survey1']."',
                c_session1Survey2='".$qryResult['c_session1Survey2']."',
                c_session1Survey3='".$qryResult['c_session1Survey3']."',
                c_session1Survey4='".$qryResult['c_session1Survey4']."',
                c_session1Survey5='".$qryResult['c_session1Survey5']."',
                c_session2Survey1='".$qryResult['c_session2Survey1']."',
                c_session2Survey2='".$qryResult['c_session2Survey2']."',
                c_session2Survey3='".$qryResult['c_session2Survey3']."',
                c_session2Survey4='".$qryResult['c_session2Survey4']."',
                c_session2Survey5='".$qryResult['c_session2Survey5']."',
                c_session3Survey1='".$qryResult['c_session3Survey1']."',
                c_session3Survey2='".$qryResult['c_session3Survey2']."',
                c_session3Survey3='".$qryResult['c_session3Survey3']."',
                c_session3Survey4='".$qryResult['c_session3Survey4']."',
                c_session3Survey5='".$qryResult['c_session3Survey5']."',
                c_session4Survey1='".$qryResult['c_session4Survey1']."',
                c_session4Survey2='".$qryResult['c_session4Survey2']."',
                c_session4Survey3='".$qryResult['c_session4Survey3']."',
                c_session4Survey4='".$qryResult['c_session4Survey4']."',
                c_session4Survey5='".$qryResult['c_session4Survey5']."',
                c_session5Survey1='".$qryResult['c_session5Survey1']."',
                c_session5Survey2='".$qryResult['c_session5Survey2']."',
                c_session5Survey3='".$qryResult['c_session5Survey3']."',
                c_session5Survey4='".$qryResult['c_session5Survey4']."',
                c_session5Survey5='".$qryResult['c_session5Survey5']."',
                c_prePost2='".$qryResult['c_prePost2']."',
                c_numberOfSessions='".$qryResult['c_numberOfSessions']."',
                c_displayName='".$qryResult['c_description']."',
                c_description='".$qryResult['c_description']."',
                c_procedureId='".$qryResult['c_procedureId']."',
                c_procedureStatus='PRE',
                c_procedure='".$qryResult['id']."'
            WHERE id='$id'";
   logMsg("Patients_a: ProcSelect: $sql2", $logfile);
   dbi_query($sql2);
   add_to_timeline($id, "Add Procedure to Patient", "Closed", "CHANGELOG", $browser, $ip_address, "Patient Dashboard");

   header("Location: patients.php?m=procdate&id=$id");
   exit();
}
else if ($mode=="procdate")
{
   $rtn=get_query_string('rtn'); // see where to return to
   if ($rtn=="") $rtn=$_POST['rtn'];
   logMsg("patients_a: procdate: rtn=$rtn",$logfile);

   //$date = new DateTime($_POST['proc_date']);
   $proc_date = $_POST['proc_date']; //  $date->format('d/m/Y');
   // check they did not enter a date in the past
   list($d, $m, $y)=explode("/",$proc_date);
   $pdate="$y-$m-$d";
   $pdate=strtotime($pdate);
   $today=strtotime("today");
   if ($pdate<$today) {
      $_SESSION['proc_date_error']=true;
      header("Location: patients.php?m=procdate&id=$id");
      exit();
   }
   else
      $_SESSION['proc_date_error']=false;

   $_SESSION['proc_date_entered']=$proc_date;
   $sql = "UPDATE $TBLPTEPISODES
           SET c_plannedProcedureDate='$proc_date' 
           WHERE id='$id'";
   logMsg("Patients_a: ProcDate: $sql", $logfile);
   dbi_query($sql);
   add_to_timeline($id, "Set Procedure Date", "Closed", "CHANGELOG", $browser, $ip_address, "Patient Dashboard");

   if ($rtn=="pd")
      header("Location: patients.php?m=procdetail&id=$id");
   else
      header("Location: patients.php?m=procsurgeon&id=$id&rtn=%rtn");
   exit();
}
else if ($mode=="procsurgeon")
{
   $proc_surgeon = $_POST['proc_surgeon'];
   $proc_gmcnumber = $_POST['proc_gmcnumber'];
   $sql = "UPDATE $TBLPTEPISODES
           SET c_surgeonName='$proc_surgeon',
               c_gmcNumber='$proc_gmcnumber' 
           WHERE id='$id'";
   logMsg("Patients_a: ProcSurgeon: $sql", $logfile);
   dbi_query($sql);
   $_SESSION['proc_surgeon']=$proc_surgeon;
   $_SESSION['proc_gmcnumber']=$proc_gmcnumber;
   header("Location: patients.php?m=procsummary&id=$id");
   exit();
}
else if ($mode=="procconfirm")
{
   // ********************************************
   //    Start a new procedure episode in Joget
   // *******************************************
   logMsg("--------------- PROCCONFIRM ---------------",$logfile);
   // get the process definition ID to complete the process (step 2)
   // this is the integer version number that changes any time the 
   // process definition changes
 
   $sql = "SELECT c_procedure FROM $TBLPTEPISODES
           WHERE id='$id'";
   logMsg($sql, $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $org_episode_id = $qryResult['c_procedure'];
   logMsg("ORG EPISODE ID: $org_episode_id", $logfile);

   // we need to get the process ID number from Joget
   $URL = $BASE_WORKFLOW_URL."process/list?j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";
   logMsg($URL, $logfile);
   $response = getCurlResponse($URL, array(), 1, "POST", "DEFAULT", "OBJECT", "");
   if ($response->total > 0) 
   {
      logMsg("We got a good response back...",$logfile);
      foreach (array_slice($response->data, 0) as $key => $value) 
      {
         if ($value->name=='Patient Episode' && $value->packageId=='gov') 
         {
            $process_definition_id = str_replace("#", ":", $value->id);
         }
      }
   }
   logMsg("ProcessDefID: $process_definition_id", $logfile);

   //   STEP 1
   // start the process
   // creates a blank patientEpisode record in DB with id = processId (NOT NO MORE)
   // $url = $BASE_WORKFLOW_URL."process/start/gov:188:patientEpisode?j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";
   $url = $BASE_WORKFLOW_URL."process/start/".$process_definition_id."?j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";
   $requestParam = array();
   $loginas = "admin";
   $resp = getCurlResponse($url, $requestParam, 1, "POST", "DEFAULT", "OBJECT", "");
   $activityId = $resp->activityId;
   $processId = $resp->processId;
   logMsg("Patients_a: ProcConfirm1: $url", $logfile);
   logMsg("Patients_a: ProcConfirm: activityId = $activityId  processId = $processId", $logfile);

   //   DON"T NEED TO DO THIS ANY MORE - NO BLANK GETTING CREATED  2/21/18
   // delete that record - we will use our own
   // $sql = "DELETE FROM $TBLPTEPISODES
           // WHERE id='$processId'";
   // logMsg("Patients_a: ProcConfirm: $sql",$logfile);
   // dbi_query($sql);
   // $ar = dbi_affected_rows();
   // logMsg("Patients_a: records deleted: $ar", $logfile);

   //    STEP 2
   // get the process ID and write that into the record we have been building all this time
   $sql = "UPDATE $TBLPTEPISODES
           SET id='$processId',
               c_status='STARTED',
               c_episodeId='$processId'
           WHERE id='$id'";
   logMsg("Patients_a: ProcConfirm: $sql",$logfile);
   dbi_query($sql);
   $ar = dbi_affected_rows();
   logMsg("Patients_a: records updated to new procId: $ar", $logfile);

   // update the patientEpisodeId in the timeline table with the new ID 
   // so those entries are still associated with this patient
   $sql  = "UPDATE $TBLTIMELINES
            SET c_patientEpisodeId='$processId'
          WHERE c_patientEpisodeId='$id'";
   dbi_query($sql);
   $ar = dbi_affected_rows();
   logMsg("Patients_a: Timeline records updated to new patientId: $ar", $logfile);

   // use the activity ID and Org Episode ID (procedure ID) to kick off the process
   $url = $BASE_WORKFLOW_URL."assignment/complete/".$activityId."?j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin&var_organizationEpisodeId=".$org_episode_id; 
   //$url = $BASE_WORKFLOW_URL."assignment/complete/".$activityId."?j_username=eidoverify2017&hash=91FCC50BA6AC975A3876E556CCE7D986&loginAs=admin"; 
   //$url = $BASE_WORKFLOW_URL."assignment/complete/".$activityId."?var_organizationEpisodeId=".$org_episode_id; 
   $requestParam = array();
   $loginas = "admin";
   $resp = getCurlResponse($url, $requestParam, 1, "POST", "DEFAULT", "OBJECT", "");
   $activityId = $resp->activityId;
   $nextActivityId = $resp->nextActivityId;
   $processId = $resp->processId;
   $status = $resp->status;
   logMsg("Patients_a: ProcConfirm2: $url", $logfile);
   logMsg("Patients_a: ProcConfirm: activityId=$activityId  processId=$processId status=$status nextActivityId=$nextActivityId", $logfile);

   //echo "<pre>";
   //print_r($resp);
   //echo "</pre>";
   //exit();

   // unset SESSION variables 
   clear_add_session();
   add_to_timeline($processId, "Start Patient Process", "Closed", "CHANGELOG",
                          $browser, $ip_address, "Patient Dashboard");

   header("Location: patients.php?m=procconfirm");
   exit();
}
else if ($mode=="proccomplete" || $mode=="proccancel")
{
   // $id is the patient episond ID passwed in from UI
   $loginas=get_query_string('loginas');
   logMsg("Patients_a: Proccomplete: loginas: $loginas", $logfile);
   $surgeon = $_POST['proc_surgeon'];
   $gmc_number = $_POST['proc_gmcnumber'];
   //$proc_date = $_POST['proc_date'];

   // write Proceed or Cancel into DB 
   if ($mode=="proccomplete")
   {
      $c_procedureStatus = "POST";
      $value="Proceed";
      add_to_timeline($id, "Mark Procedure Complete", "Closed", "CHANGELOG", $browser, $ip_address, "Patient Dashboard");
      add_to_timeline($id, "PROCEDURE COMPLETE", "Open", "Event", $browser, $ip_address, "Patient Dashboard");
   }
   else
   {
      $c_procedureStatus="CANCEL";
      $value="Cancel";
      add_to_timeline($id, "Cancel Procedure", "Closed", "CHANGELOG", $browser, $ip_address, "Patient Dashboard");
   }

   $sql = "UPDATE $TBLPTEPISODES
           SET c_proceedOrCancel = '$value',
               c_procedureStatus = '$c_procedureStatus'
           WHERE id='$id'";
   dbi_query($sql);
   
   // get the activity ID for this procedure
   $requestParam = array();
   $url = $BASE_WORKFLOW_URL."assignment/list/?processId=".$id."&j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";
   logMsg(" ", $logfile);
   logMsg("Patients_A: ProcComplete: Get Act ID: ".$url, $logfile);
   $resp = getCurlResponse($url, $requestParam, 1, "POST", "DEFAULT", "OBJECT", $loginas);

   //foreach ($resp->data as $key => $value)
   //{
     //$activity_id = $value->activityId;
   //}

   // we just want the first one
   $activity_id = $resp->data->activityId;

   echo "<br /><br />Activity ID:  $activity_id<br /><br />";
   //$assignee_id = $resp->assigneeId;
   logMsg("ActivityId: ".$activity_id, $logfile);
   logMsg(" ", $logfile);

   // pass over the proceedOrCancel variable
   // $url =  $BASE_WORKFLOW_URL."assignment/variable/".$activity_id."/proceedOrCancel?value=".$value."&j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";      // .$assignee_id;
   $url = "http://verify.eidosystems.com:8080/jw/web/json/monitoring/process/variable/".$id."/proceedOrCancel?value=".$value."&j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";

   logMsg("Patients_A: Pass Variable: $url", $logfile);
   $resp = getCurlResponse($url, $requestParam, 1, "POST", "DEFAULT", "OBJECT", $loginas);
   //echo "$url<br /><br />";
   //echo "<pre>";
   //print_r($resp);
   //echo "</pre>";
   
   // give it some time to process
   sleep(3);

   // use the activity ID to set this activity to Complete
   logMsg("Patients_A: ProcComplete: Method URL: ".$BASE_WORKFLOW_URL."assignment/complete/".$id, $logfile);
   $url = $BASE_WORKFLOW_URL."assignment/complete/".$activity_id."?j_username=eidoverify2017&hash=C03B449319B694BD223A7E39142B7E34&loginAs=admin";    //   .$assignee_id;
   $resp = getCurlResponse($url, $requestParam, 1, "POST", "DEFAULT", "OBJECT", $loginas);
   $status = $resp->status;
   logMsg("Status: ".$status, $logfile);

   //echo "Set Complete<br />";
   //echo "<pre>";
   //print_r($resp);
   //echo "</pre>";
   
   header("Location: patients.php?m=main");
   exit();
}
header("Location: patients.php");
exit();

?>
