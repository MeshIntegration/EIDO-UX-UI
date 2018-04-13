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
   else if ($qryResult['c_procedureId']=="")
      $status = "Pending";
   else // not inactive so must be Active
      $status = "Active";
//logMsg("GET PT STATUS>>> $sql",$logfile);
//logMsg(">>> Status: $status",$logfile);

   // but check to see if they have any open Alerts
   // get the most recent timeline entry
   $sql = "SELECT * from $TBLTIMELINES 
           WHERE c_patientEpisodeId = '$id'
           AND c_timelineAlertStatus='Open'
           ORDER BY dateCreated DESC
           LIMIT 1";

   $GetQuery=dbi_query($sql);
   if ($GetQuery->num_rows>0)
   {
      $qryResult = $GetQuery->fetch_assoc();
      if ($qryResult['c_timelineEntryType']=="Alert")
         $status = "Alert";
   } 
//logMsg($sql,$logfile);
//logMsg(">>> Status: $status",$logfile);
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
      logMsg("send_mail ERROR: " . $mail->ErrorInfo, "email.log");
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
   // save the key used to authenicate an admin password reset
   $sql = "UPDATE dir_user
           SET c_passwordResetKey = '$pwkey'
           WHERE email = '".$email."'";
   logMsg("save_user_pw_key: $sql", "wel.log");
   dbi_query($sql);
}

// ***************************************************************
function  save_user_pw_reset($pwkey, $password)
{
   $sql = "UPDATE dir_user
           SET password = '$password'
           WHERE passwordResetKey = '".$pwkey."'";
   logMsg("save_user_pw_reset: $sql", "wel.log");
   dbi_query($sql);
}

// ***************************************
function get_user_info_by_pwkey($pwkey)
{
   $arr_user_info = array();
   $sql = "SELECT * FROM dir_user
           WHERE passwordResetKey='$pwkey'";
   logMsg("get_user_info_by_pwkey: $sql", "wel.log");
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
   $arr_user_info['password']=$qryResult['password'];
   return $arr_user_info;
}

// ***************************************
function save_password($user_id, $password)
{
   $sql = "UPDATE dir_user
          SET password='$password',
              c_pw_reset=0,
              c_dateModified=NOW()
          WHERE id='$user_id'";
   dbi_query($sql);
}
// EOF
?>
