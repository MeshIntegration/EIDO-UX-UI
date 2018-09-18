<?php
// ***************************************
// lib/validation.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/20/18
// ***************************************

$logfile = "validation.log";

function get_pt_info($patientEpisodeId)
{
   global $TBLPTEPISODES, $TBLORGANISATIONS;

   $arr_pt_info = array();
   $sql = "SELECT * FROM $TBLPTEPISODES
           WHERE id='$patientEpisodeId'";
   logMsg("get_pt_info: $sql", "validation.log");
   $GetQuery = dbi_query($sql);
   if ($GetQuery->num_rows==0)
   {
      logMsg("get_pt_info: ERROR finding patientEpisode","validation.log");
      $arr_pt_info['c_surname']="ERROR";
   }
   else
   {
      $arr_pt_info = $GetQuery->fetch_assoc();
      $sql = "SELECT o.c_logo 
              FROM $TBLORGANISATIONS o, $TBLPTEPISODES pe  
              WHERE pe.c_hospitalId=o.id
              AND pe.id='$patientEpisodeId'";
      logMsg("get_pt_info LOGO: $sql", "validation.log");
      $GetQuery = dbi_query($sql);
      $qryResult = $GetQuery->fetch_assoc();
      $arr_pt_info['logo'] = $qryResult['c_logo'];
   }
   return $arr_pt_info;
}

// ***************************************
function get_pt_info_by_pwkey($pwkey)
{
   global $TBLPTEPISODES;

   $arr_pt_info = array();
   $sql = "SELECT * FROM $TBLPTEPISODES
           WHERE c_passwordResetKey='$pwkey'";
   logMsg("get_pt_info_by_pwkey: $sql", "validation.log");
   $GetQuery = dbi_query($sql);
   if ($GetQuery->num_rows==0)
   {
      logMsg("get_pt_info_by_pwkey: ERROR finding patientEpisode","validation.log");
      $arr_pt_info['c_surname']="ERROR";
   }
   else
   {
      $arr_pt_info = $GetQuery->fetch_assoc();
   }
   return $arr_pt_info;
}

// ***************************************
function save_pt_info($id, $surname, $postalcode, $dob, $nhsnumber, $password, $mobile, $preferred, $email, $preferenceset)
{
   global $TBLPTEPISODES;
   
//   if ($preferenceset=="NO")
//   {
//       $mpd_str = "";
//   }
//   else
//   {
//       $mpd_str = ", c_mobilePageDone='YES' ";
//   }
//     $mpd_str  <-- removed from last SET line of query below, to replace: uncomment above if/else, add a comma after c_preferenceSet=... line and then add $mpd_str after the comma
   $sql = "UPDATE $TBLPTEPISODES
          SET c_surnameEntered=".escapeQuote($surname).",
              c_postalCodeEntered=".escapeQuote($postalcode).",
              c_dateOfBirthEntered=".escapeQuote($dob).",
              c_emailAddress=".escapeQuote($email).",
              c_nhsNumberEntered=".escapeQuote($nhsnumber).",
              c_password=".escapeQuote($password).",
              c_mobileNumber=".escapeQuote($mobile).",
              c_preferredContactMethod=".escapeQuote($preferred).",
	          c_preferenceSet=".escapeQuote($preferenceset)."
	                   
          WHERE id='$id'";
   logMsg("Save Pt Info - Validation: $sql", "validation.log");
   dbi_query($sql);
}

// ***************************************
function save_entered_pt_info($id, $surname, $postalcode, $dob, $nhsnumber)
{
   global $TBLPTEPISODES;

   $sql = "UPDATE  $TBLPTEPISODES
          SET c_surnameEntered=".escapeQuote($surname).",
              c_postalCodeEntered=".escapeQuote($postalcode).",
              c_dateOfBirthEntered=".escapeQuote($dob).",
              c_nhsNumberEntered=".escapeQuote($nhsnumber)."
          WHERE id='$id'";
   logMsg("Save Entered Pt Info - Validation: $sql", "validation.log");
   dbi_query($sql);
}


// ***************************************
function add_to_timeline($patientEpisodeId, $name, $status, $type, 
                               $browser, $ip_address, $subsystem, $session_number)
{
   global $TBLTIMELINES, $TBLPTEPISODES;
   if ($type=="CHANGELOG")
   {
      $createdBy=$_COOKIE['user_id'];
      $createdByName=$_COOKIE['user_fullname'];
   }
   else
   {
      $createdBy = 'admin';
      $createdByName='Admin Admin';
   }
   $id=uniqid();
   $sql = "INSERT INTO $TBLTIMELINES
           SET id='$id',
               dateCreated = NOW(),
               dateModified = NOW(),
               createdBy = '$createdBy',
               createdByName='$createdByName',
               c_patientEpisodeId = '$patientEpisodeId',
               c_timelineEntryType='$type',
               c_timelineEntryDetail = '$name',
               c_timelineAlertStatus= '$status',
               c_sessionNumber = '$session_number',
               c_deviceType = '$browser',
               c_ipAddress = '$ip_address',
               c_system = 'Verify',
               c_subsystem='$subsystem'";
   dbi_query($sql);

   // update c_hasAlert flag in pt episodes
   if ($type=="Alert") {
      $sql = "UPDATE $TBLPTEPISODES
              SET c_hasAlert='Y',
                  dateModified=NOW()
              WHERE id='$patientEpisodeId'";
      dbi_query($sql);
   }
}

// ***************************************
function get_survey_url($arr_pt_info)
{
   $goto_url = "http://patientinfo.eidoverify.com/s3/Landing-Page?";
   // $goto_url = "https://requestb.in/rnpeuvrn?";
   $current_session = $arr_pt_info['c_currentSessionNumber'];
   // live or dev
   $dev=$_SESSION['dev'];
   
   if ($current_session == "1")
      $sid_param = $arr_pt_info['c_session1Survey1'] . "%3B" . $arr_pt_info['c_session1Survey2'] . "%3B" . $arr_pt_info['c_session1Survey3'] . "%3B" . $arr_pt_info['c_session1Survey4'] . "%3B" . $arr_pt_info['c_session1Survey5'];
   else if ($current_session == "2")
      $sid_param = $arr_pt_info['c_session2Survey1'] . "%3B" . $arr_pt_info['c_session2Survey2'] . "%3B" . $arr_pt_info['c_session2Survey3'] . "%3B" . $arr_pt_info['c_session2Survey4'] . "%3B" . $arr_pt_info['c_session2Survey5'];
   else if ($current_session == "3")
      $sid_param = $arr_pt_info['c_session3Survey1'] . "%3B" . $arr_pt_info['c_session3Survey2'] . "%3B" . $arr_pt_info['c_session3Survey3'] . "%3B" . $arr_pt_info['c_session3Survey4'] . "%3B" . $arr_pt_info['c_session3Survey5'];
   else if ($current_session == "4")
      $sid_param = $arr_pt_info['c_session4Survey1'] . "%3B" . $arr_pt_info['c_session4Survey2'] . "%3B" . $arr_pt_info['c_session4Survey3'] . "%3B" . $arr_pt_info['c_session4Survey4'] . "%3B" . $arr_pt_info['c_session4Survey5'];
   else if ($current_session == "5")
      $sid_param = $arr_pt_info['c_session5Survey1'] . "%3B" . $arr_pt_info['c_session5Survey2'] . "%3B" . $arr_pt_info['c_session5Survey3'] . "%3B" . $arr_pt_info['c_session5Survey4'] . "%3B" . $arr_pt_info['c_session5Survey5'];
   else if ($current_session == "6")
      $sid_param = $arr_pt_info['c_session6Survey1'] . "%3B" . $arr_pt_info['c_session6Survey2'] . "%3B" . $arr_pt_info['c_session6Survey3'] . "%3B" . $arr_pt_info['c_session6Survey4'] . "%3B" . $arr_pt_info['c_session6Survey5'];
   $goto_url .= "sids=" . $sid_param;
   $goto_url .= "&pid=" . $arr_pt_info['c_procedureId'];
   // concat liv or dev onto pid based on dev parameter
   if ($dev=="true")
      $dev_str="dev";
   else
      $dev_str="liv";
   $goto_url .= $dev_str;
   
   $goto_url .= "&session=" . $arr_pt_info['id'] . $sid_param;
   $goto_url .= "&hid=" . urlencode($arr_pt_info['c_hospitalName']);
   $goto_url .= "&eid=" . $arr_pt_info['id'];
   // $goto_url .= "&dev=" . $dev;

   logMsg("SURVEY GOTO URL: ".$goto_url, "validation.log");
   return $goto_url;
} 

// ***************************************************************
function save_pw_key($arr_pt_info, $pwkey)
{
   global $TBLPTEPISODES;

   // save the key used to authenicate a password reset
   $sql = "UPDATE $TBLPTEPISODES
           SET c_passwordResetKey = '$pwkey'
           WHERE id = '".$arr_pt_info['id']."'";
   dbi_query($sql);
}

// ***************************************************************
function  save_pw_reset($arr_pt_info, $password)
{
   global $TBLPTEPISODES;

   $sql = "UPDATE $TBLPTEPISODES
           SET c_password = '$password',
               c_passwordResetKey = ''
           WHERE id = '".$arr_pt_info['id']."'";
   dbi_query($sql);
}
// ***************************************************************
function set_accepted_tc($id)
{
   global $TBLPTEPISODES, $logfile;
   
   $sql = "UPDATE $TBLPTEPISODES
           SET c_acceptedTC = 'YES'
           WHERE id = '$id'";
   logMsg("Set TC: ". $sql, $logfile);
   dbi_query($sql);
}
?>
