<?php
/*
 * utilities.php
 * Copyright 2018, Mesh Integrations, LLC 
 * WEL 1/6/18  
 */
require_once '/var/www/html/ui/verify/globalvars.php';
require_once '/var/www/html/ui/verify/lib/functions.php';
require_once '/var/www/html/ui/verify/lib/validation.php';
require_once '/var/www/html/ui/verify/lib/is_dbi_util.php';
require_once '/var/www/html/ui/verify/lib/is_util.php';
require_once '/var/www/html/ui/verify/lib/is_html_util.php';

foreach (glob("/var/www/html/ui/verify/lib/classes/*.php") as $filename) {
   include $filename;

//error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', TRUE);
}

//function load_template($center_content, $right_content, $script, $param) {
//   global $ABS_PATH;
//   include $ABS_PATH .'/template/template.php';
//   echo ob_get_clean();
//}

//function load_front_template($center_content, $script, $param) {
//   global $ABS_PATH;
//   include $ABS_PATH .'/template/front.php';
//   echo ob_get_clean();
//}

//function load_front_center_template($center_content, $script, $param) {
//   global $ABS_PATH;
//   include $ABS_PATH .'/template/front_center.php';
//   echo ob_get_clean();
//}

//function load_blank_template($center_content, $param) {
//   global $ABS_PATH;
//   include $ABS_PATH . '/template/blank.php';
//   echo ob_get_clean();
//}

// ***************************************************
function get_pt_status($id)
{
   global $TBLPTEPISODES, $TBLTIMELINES;
   $logfile = "patient.log";

   $sql = "SELECT * 
           FROM $TBLPTEPISODES
           WHERE id = '$id'";
   $GetQuery=dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   if (strtoupper($qryResult['c_status'])=="EPISODE COMPLETE" || 
                  strtoupper($qryResult['c_procedureStatus'])=="CANCEL")
      $status = "Inactive";
   else if ($qryResult['c_procedureId']=="" || $qryResult['c_status']=='PENDING')
      $status = "Pending";
   else // not inactive so must be Active
      $status = "Active";
//logMsg("GET PT STATUS>>> $sql",$logfile);
//logMsg(">>> Status: $status",$logfile);

   // but check to see if they have any open Alerts
   if ($qryResult['c_hasAlert']=="Y")
              $status = "Alert";

      // get the most recent timeline entry
      // $sql = "SELECT * from $TBLTIMELINES 
      //         WHERE c_patientEpisodeId = '$id'
      //         AND c_timelineAlertStatus='Open'
      //         ORDER BY dateCreated DESC
      //         LIMIT 1";

      // $GetQuery=dbi_query($sql);
      // if ($GetQuery->num_rows>0)
      // {
      //    $qryResult = $GetQuery->fetch_assoc();
      //    if ($qryResult['c_timelineEntryType']=="Alert")
      //       $status = "Alert";
      // } 
//logMsg($sql,$logfile);
// logMsg(">>> IN GET STATUS FUNCTION >>>  Status: $status",$logfile);
   return $status;
}
  
// **************************************************
function format_uk_date($mdate)
{
   // format mysql date to UK format DD/MM/YYYY
   $y=substr($mdate,0,4);
   $m=substr($mdate,5,2);
   $d=substr($mdate,8,2);
   $ukdate = "$d/$m/$y";
   return $ukdate;
}

// **************************************************
function date_cleanup($dt)
{
   if (strpos($dt, "/"))
      list($d,$m,$y)=explode("/",$dt);
   else if (strpos($dt, "-"))
      list($d,$m,$y)=explode("-",$dt);
   else
   {
      $d=substr($dt,0,2);
      $m=substr($dt,2,2);
      $y=substr($dt,4,4);
   }
   $arr_date_cleanup['date_valid']=checkdate($m,$d,$y);
   if ($arr_date_cleanup['date_valid'])
   { 
      if (strlen($d)==1) $d="0".$d;
      if (strlen($m)==1) $m="0".$m;
      $arr_date_cleanup['date_formatted']="$d/$m/$y";
      $arr_date_cleanup['mysql_format']="$y-$m-$d";
//echo "mysql in cleanup: ".$arr_date_cleanup['mysql_format']."<br />";
   }
   return $arr_date_cleanup;
}
// **************************************************
function is_email_unique($email, $id="XXJUNKXX")
{
   $sql="SELECT * 
         FROM dir_user 
         WHERE username='$email'
         AND active=1
         AND id<>'$id'";
   $GetQuery=dbi_query($sql);
   if ($GetQuery->num_rows>0)
      return false;
   else
      return true;
}

// **************************************************
function is_gmc_number_unique($gmc_number, $id){
   $sql="SELECT * 
         FROM dir_user 
         WHERE gmc_number='$gmc_number'
         AND active=1
         AND id<>'$id'";
   $GetQuery=dbi_query($sql);
   if ($GetQuery->num_rows>0)
      return false;
   else
      return true;
}
// **************************************************
function send_email($arr_email)
{
   global $mailhost,$phpmailerdir;
   //global $mailusername, $mailpassword;

   require_once ($phpmailerdir);

   // for testing to not send email
   $testing = false;

   $mail_to = $arr_email['mail_to'];
   $mail_to_name = $arr_email['mail_to_name'];
   $cc = $arr_email['cc'];
   $cc_name = $arr_email['cc_name'];
   $bcc = $arr_email['bcc'];
   $bcc_name = $arr_email['bcc_name'];
   $mail_from = $arr_email['mail_from'];
   $mail_from_name = $arr_email['mail_from_name'];
   $subject = $arr_email['subject'];
   $body = $arr_email['body']; // HTML format
   $attachment = $arr_email['attachment']; // path and filename

//echo "<pre>";
//print_r($arr_email);
//echo "</pre>";

   if ($testing==true)
   {
      $subject = "DEV - ".$subject;
      $mail_to = 'wayne.mindstreams@gmail.com';
      $cc = "";
      $cc_name = "";
      $bcc = "";
      $bcc_name = "";
      $body = "Mail to go to: ".$arr_email['mail_to']." - ".$arr_email['mail_to_name']."<br /><br />".$body;
   }
   $mail = new phpmailer;

   //$mail->Username = $mailusername; // SMTP username
   //$mail->Password = $mailpassword;
   $mail->IsHTML(true);
   $mail->Host = gethostbyname($mailhost);
   $mail->SMTPSecure = 'tls';
   $mail->AddAddress($mail_to, $mail_to_name);
   $mail->From = $mail_from;
   $mail->FromName = $mail_from_name;
   $mail->Sender = $mail_from;
   $mail->AddReplyTo($mail_from, $mail_from_name);
   $mail->Subject = $subject;
   $mail->Body = $body;
   //$mail->AltBody = $body;

   if (strlen($cc)>0 && !$testing)
   {
      $mail->AddCC($cc, $cc_name);
   }
   if (strlen($bcc)>0)
   {
      $mail->AddBCC($bcc, $bcc_name);
   }
   if (strlen($attachment))
   {
      $path = "$attachment";
      $mail->AddAttachment($path);
   }
    if(!$mail->Send())
   {
      logMsg("send_mail ERROR: $mail_to - ($mail_to_name) " . $mail->ErrorInfo, "email.log");
      return false;
   }
   else
   {
      $msg= "Message sent: $mail_to - $subject";
      logMsg($msg, "email.log");
      return true;
   }
}

// *************************************************************
function save_user_pw_key($email, $pwkey)
{
   $arr_user_info=array();
   // see if the email they put in is valid
   $sql = "SELECT * 
            FROM dir_user 
            WHERE username = '".$email."'
            AND active=1";
   $GetQuery = dbi_query($sql);
   if ($GetQuery->num_rows) {
      $qryResult=$GetQuery->fetch_assoc();
      $arr_user_info=array();
      $arr_user_info['firstname']=$qryResult['firstName'];
      $arr_user_info['lastname']=$qryResult['lastName'];

      // save the key used to authenicate an admin password reset
      $sql = "UPDATE dir_user
              SET c_passwordResetKey = '$pwkey'
              WHERE username = '".$email."'
              AND active=1";
      // logMsg("save_user_pw_key: $sql", "wel.log");
      dbi_query($sql);
   } else {
      $arr_user_info['lastname']="ERROR";
   }    
   return $arr_user_info;
}

// ***************************************************************
function  save_user_pw_reset($pwkey, $password)
{
   $sql = "UPDATE dir_user
           SET uipassword = '$password',
               c_passwordResetKey = '*!*!*!*'
           WHERE c_passwordResetKey = '".$pwkey."'";
//   logMsg("save_user_pw_reset: $sql", "wel.log");
   dbi_query($sql);
}

// ***************************************
function get_user_info_by_pwkey($pwkey)
{
   $arr_user_info = array();
   $sql = "SELECT * FROM dir_user
           WHERE c_passwordResetKey='$pwkey'";
//   logMsg("get_user_info_by_pwkey: $sql", "wel.log");
   $GetQuery = dbi_query($sql);
   if ($GetQuery->num_rows==0)
   {
      logMsg("get_user_info_by_pwkey: ERROR finding user","validation.log");
      $arr_user_info['lastName']="ERROR";
   }
   else
   {
      $arr_user_info = $GetQuery->fetch_assoc();
   }
   return $arr_user_info;
}
// ***************************************
function get_user_info($user_id)
{
   $arr_user_info=array();
   $sql = "SELECT * FROM dir_user
          WHERE id='$user_id'";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $arr_user_info=$qryResult;
   $arr_user_info['password']=$qryResult['uipassword'];
   return $arr_user_info;
}

// ***************************************
function save_password($user_id, $password)
{
   $sql = "UPDATE dir_user
          SET uipassword='$password',
              c_pw_reset=0,
              c_dateModified=NOW()
          WHERE id='$user_id'";
   dbi_query($sql);
}
// ***************************************************
function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}
// ***************************************************
function get_proc_info($id)
{
   global $TBLPROCEPISODES, $MAX_SESSIONS;

   $sql = "SELECT c_numberOfSessions, c_description, c_procedureId,
                  c_procedure, c_org
           FROM $TBLPROCEPISODES
           WHERE id='$id'";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $arr_proc_info[0][c_numberOfSessions] = $qryResult[c_numberOfSessions];
   $arr_proc_info[0][c_description] = $qryResult[c_description];
   $arr_proc_info[0][c_procedureId] = $qryResult[c_procedureId];
   $arr_proc_info[0][c_procedure] = $qryResult[c_procedure];
   $arr_proc_info[0][c_org] = $qryResult[c_org];

   for ($i=1; $i<=$MAX_SESSIONS; $i++)
   {
      $var_pre_post = "c_prePost".$i;
      $var_custom_message = "c_prePost".$i."CustomMessage";
      $var_session_survey1 = "c_session".$i."Survey1";
      $var_session_survey2 = "c_session".$i."Survey2";
      $var_session_survey3 = "c_session".$i."Survey3";
      $var_session_survey4 = "c_session".$i."Survey4";
      $var_session_survey5 = "c_session".$i."Survey5";
      $var_session_name = "c_session".$i."Name";

      $sql = "SELECT $var_pre_post, $var_custom_message, $var_session_survey1,
                     $var_session_survey2, $var_session_survey3, $var_session_survey4,
                     $var_session_survey5, $var_session_name
              FROM $TBLPROCEPISODES
              WHERE id='$id'";
      $GetQuery = dbi_query($sql);
      $qryResult = $GetQuery->fetch_assoc();
      $arr_proc_info[$i][$var_pre_post] = $qryResult[$var_pre_post];
      $arr_proc_info[$i][$var_custom_message] = $qryResult[$var_custom_message];
      $arr_proc_info[$i][$var_session_survey1] = $qryResult[$var_session_survey1];
      $arr_proc_info[$i]['survey_name1'] = get_survey_name($qryResult[$var_session_survey1]);
      $arr_proc_info[$i][$var_session_survey2] = $qryResult[$var_session_survey2];
      $arr_proc_info[$i]['survey_name2'] = get_survey_name($qryResult[$var_session_survey2]);
      $arr_proc_info[$i][$var_session_survey3] = $qryResult[$var_session_survey3];
      $arr_proc_info[$i]['survey_name3'] = get_survey_name($qryResult[$var_session_survey3]);
      $arr_proc_info[$i][$var_session_survey4] = $qryResult[$var_session_survey4];
      $arr_proc_info[$i]['survey_name4'] = get_survey_name($qryResult[$var_session_survey4]);
      $arr_proc_info[$i][$var_session_survey5] = $qryResult[$var_session_survey5];
      $arr_proc_info[$i]['survey_name5'] = get_survey_name($qryResult[$var_session_survey5]);
      $arr_proc_info[$i][$var_session_name] = $qryResult[$var_session_name];
   }
   // session delay does not have a value for session 1 so loop over 2 to max
   for ($i=2; $i<=$MAX_SESSIONS; $i++)
   {
      $var_session_delay = "c_session".$i."Delay";
      $sql = "SELECT $var_pre_post, $var_session_delay
              FROM $TBLPROCEPISODES
              WHERE id='$id'";
      $GetQuery = dbi_query($sql);
      $qryResult = $GetQuery->fetch_assoc();
      $arr_proc_info[$i][$var_session_delay] = $qryResult[$var_session_delay];
   }

   //echo "<pre>";
   //print_r ($arr_proc_info);
   //echo "</pre>";
   //exit();

   return $arr_proc_info;
}
// ****************************************************
function get_survey_name($survey_number)
{
   global $TBLSURVEYS;

   $sql = "SELECT c_description
           FROM $TBLSURVEYS
           WHERE c_surveyNumber = '$survey_number'";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $description = $qryResult['c_description'];
   return $description;
}
// EOF
?>
