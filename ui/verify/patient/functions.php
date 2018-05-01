<?php
// ***************************************
// patient/functions.php
// 2018 Copyright, Mesh Integration LLC
// 1/13/17 - WEL
// ***************************************

session_start();
require_once '/var/www/html/ui/verify/utilities.php';
$logfile = "patient.log";
$debug=true;  // turn on for extra logging

// ********************************************************
function format_tl_date($tl_datetime)
{
   $month_name['01']="Jan";
   $month_name['02']="Feb";
   $month_name['03']="Mar";
   $month_name['04']="Apr";
   $month_name['05']="May";
   $month_name['06']="Jun";
   $month_name['07']="Jul";
   $month_name['08']="Aug";
   $month_name['09']="Sep";
   $month_name['10']="Oct";
   $month_name['11']="Nov";
   $month_name['12']="Dec";

   $this_year = date("Y");
   list($dt, $tm) = explode(" ", $tl_datetime);
   list($y, $m ,$d) = explode("-", $dt);
   $mname = $month_name[$m];
   if ($y==$this_year)
      $fdt = "$d $mname";
   else
      $fdt = "$d $mname $y";
   return $fdt;
}

// ********************************************************
function get_stat_counts($type)
{
   global $TBLTIMELINES, $TBLPTEPISODES;

   if ($type=='active') {
      $sql = "SELECT COUNT(*) AS ct 
              FROM $TBLPTEPISODES
              WHERE c_status<>'Episode Complete'
                AND c_status<>'PENDING'
              AND c_procedureStatus<>'Cancel'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct; 
   }
   else if ($type=='inactive') {
      $sql = "SELECT COUNT(*) AS ct 
              FROM $TBLPTEPISODES
              WHERE c_status='Episode Complete'
              OR c_procedureStatus='Cancel'
              OR c_status='PENDING'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct; 
   }
   else if ($type=='total') {
      $sql = "SELECT COUNT(*) AS ct 
              FROM $TBLPTEPISODES";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct; 
   }
   else if ($type=='alert') {
      $sql = "SELECT COUNT(*) AS ct
              FROM $TBLTIMELINES
              WHERE c_timelineAlertStatus='Open'
              AND c_timelineEntryType='Alert'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct;
   }
   else if ($type=='surveycomplete') {
      $today=date("Y-m-d");
      $sql = "SELECT COUNT(*) AS ct
              FROM $TBLPTEPISODES
              WHERE c_status='Session Complete'
              AND DATE(dateModified)='$today'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct;
   }
   else if ($type=='surveyincomplete') {
      $sql = "SELECT COUNT(*) AS ct
              FROM $TBLPTEPISODES
              WHERE c_status LIKE 'Invited to Session%'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct;
   }
}
// ***************************************************
function get_notifications($type="All", $how_many="All", $patient_id="")
{
   global $TBLTIMELINES, $TBLPTEPISODES;
   $arr_notifications = array();
 
   if ($type<>"")
      $type_str = " AND t.c_timelineEntryType='$type' ";
   else
      $type_str="";
   if ($patient_id<>"")
      $patient_id_str = " AND t.c_patientEpisodeId='$patient_id' ";
   else
      $patient_id_str="";
   if ($how_many<>"")
      $how_many_str = " LIMIT $how_many ";
   else
      $how_many_str="";
   
   $sql="SELECT t.*, pe.c_firstName, pe.c_surname
         FROM $TBLTIMELINES t, $TBLPTEPISODES pe
         WHERE pe.id=t.c_patientEpisodeId
         AND c_timelineAlertStatus='Open'
         $type_str
         $patient_id_str
         ORDER BY t.dateCreated DESC
         $how_many_str";
   logMsg($sql, "patient.log");
   $GetQuery = dbi_query($sql);
   $i=0;
   while ($qryResult=$GetQuery->fetch_assoc())
   {
      $arr_notifications[$i]=$qryResult;
      $i++;
   }
   return $arr_notifications;
}

// **************************************************
function get_address_by_postcode($postcode)
{
   // param: UK post code
   // output: HTML select code with address list
   //         address data is tilde ~ seperated in value
   // uses API for Ideal Postcodes - ideal-postcodes.co.uk

   // $postcode = "LN22PD";  // for testing
   // $postcode = "ZE29XT";  // for testing
   // $postcode = "ID1 1QD";  // Ideal's test postcode for testing

   $logfile = "patient.log";
   $debug = false;
   logMsg("In get_address_by_postalcode:  $postcode",$logfile);

   $api_key  = "ak_j3jxqn23XM9ku2bMrbMQdEGtgr1Ah";  // EIDO Systems Account
   //$api_key  = "iddqd";  // Ideal's API Key for testing  

   if ($debug)
   {
      logMsg("Check if key is valid",$logfile);
      // check if the API key is good or not
      // we assume it is good - this is just here in the event nothing works
      $base_url = "https://api.ideal-postcodes.co.uk/v1/keys/";
      $url = $base_url.$api_key;
      logMsg("URL: $url",$logfile);
      echo $url."<BR /><BR />";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = json_decode(curl_exec($ch), true);
      curl_close($ch);
      echo "<PRE>";
      print_r($response);
      echo "</PRE><BR /><BR />";
      //exit();
   }
   $base_url = "https://api.ideal-postcodes.co.uk/v1/postcodes/";
   $url = $base_url . rawurlencode($postcode) . "?api_key=" . $api_key;
   logMsg("URL: $url",$logfile);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $response = json_decode(curl_exec($ch), true);
   curl_close($ch);
   $addresses = $response["result"];
   if (!isset($addresses))
   {
      return array(response);
   }

   $select="";
   for ($i=0; $i<count($addresses); $i++)
   {
      $address1 = $addresses[$i]['line_1'];
      $address2 = $addresses[$i]['line_2'];
      $city = $addresses[$i]['post_town'];
      $county = $addresses[$i]['county'];
      $select .= "<option value='$address1~$address2~$city~$county~$postcode'>$address1 $city, $county</option>";
   }

   if ($debug)
   {
      echo "Postcode: $postcode<BR /><BR />";
      echo $url."<BR /><BR />";
      echo "<select name='test' size='5'>";
      echo $select;
      echo "</select>";
      
      echo "<PRE>";
      print_r($addresses);
      echo "</PRE>";
      exit();
   }
   else
   {
      return $select;
   }
}

// ********************************************************
function clear_add_session()
{
   unset($_SESSION['add_fname']);
   unset($_SESSION['add_lname']);
   unset($_SESSION['add_nhsnumber']);
   unset($_SESSION['add_hospitalnumber']);
   unset($_SESSION['add_gender']);
   unset($_SESSION['add_dob']);
   unset($_SESSION['add_postalcode']);
   unset($_SESSION['add_email']);
   unset($_SESSION['add_mobilenumber']);
   unset($_SESSION['add_address']);
   unset($_SESSION['add_address2']);
   unset($_SESSION['add_city']);
   unset($_SESSION['add_county']);
   unset($_SESSION['add_postalcode']);
   unset($_SESSION['proc_id_entered']);
   unset($_SESSION['proc_date_entered']);
   unset($_SESSION['proctl_proc_id']);
   unset($_SESSION['add_fname_error']);
   unset($_SESSION['add_lname_error']);
   unset($_SESSION['add_nhsnumber_error']);
   unset($_SESSION['add_hospitalnumber_error']);
   unset($_SESSION['add_gender_error']);
   unset($_SESSION['add_dob_error']);
   unset($_SESSION['add_postalcode_error']);
   unset($_SESSION['add_bad_email_error']);
   unset($_SESSION['add_no_contact_error']);
   unset($_SESSION['proc_select_error']);
   unset($_SESSION['proc_surgeon']);
   unset($_SESSION['proc_gmcnumber']);
   unset($_SESSION['workflow']);
}

// ********************************************************
function get_search_suggestion($looking_for,$search_query){
// get patient list
   global $TBLPTEPISODES;
   $sql = "SELECT *
        FROM $TBLPTEPISODES as episodes
        WHERE #WHERE#
        ORDER BY #ORDER#";

   if($looking_for=='patient'){
      $where[] = "c_surname LIKE '%".$search_query."%' OR c_firstName LIKE '%".$search_query."%'";
      $order[] = "c_surname,c_firstName" ;
   }else if($looking_for=='procedure'){
      $where[] = "c_description LIKE '%".$search_query."%'";
      $order[] = "c_description";
   }else if($looking_for=="surgeon"){
      $where[] = "c_surgeonName LIKE '%".$search_query."%'";
      $order[] = "c_surgeonName";
   }
   
   $sql = str_replace(
          array("#WHERE#","#ORDER#"),
          array(
             implode(" AND ",$where),
             implode(", ",$order)
          ),
          $sql
       );
   $GetQuery = dbi_query($sql);
   $patient = array();
   if ($GetQuery){
      while ($qryResult=$GetQuery->fetch_assoc()){
         $patient[]=$qryResult;
      }
   }
   return $patient;
}

// *************************************************
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

// ****************************************************
function get_future_tl($pe_id, $arr_tl)
{
   global $MAX_SESSIONS;
   $logfile="patient.log";

logMsg("BEFORE: Items in TL array: ".count($arr_tl), $logfile);
   $arr_pt_info=get_pt_info($pe_id);
   $arr_proc_info=get_proc_info($arr_pt_info['c_procedure']);
   $current_session = $arr_pt_info['c_currentSessionNumber'];
   $current_pre_post=$arr_pt_info['c_procedureStatus'];
logMsg("Max Sessions: $MAX_SESSIONS - Curr Sess: $current_session - Curr PrePost: $current_pre_post", $logfile);
   $tl = count($arr_tl);
   $complete_flag=false;
   for ($i=$current_session+1; $i<=$MAX_SESSIONS; $i++)
   {
      $varname="c_prePost".$i;
      $pre_post=$arr_proc_info[$i][$varname];
logMsg("PrePost: $pre_post", $logfile);
      if ($pre_post<>$current_pre_post && !$complete_flag && $arr_pt_info['c_procedureStatus']=="PRE")
      {
         $arr_tl[$tl]['dateCreated']="";
         $arr_tl[$tl]['id']=0;
         $arr_tl[$tl]['c_timelineEntryType']="Event";   
         $arr_tl[$tl]['c_timelineEntryDetail']="Procedure Completed";
logMsg("$i: Procedure Completed", $logfile);
         $complete_flag=true; 
         $tl++;
      }
      else if ($pre_post<>$current_pre_post && !$complete_flag && $arr_pt_info['c_procedureStatus']=="POST")
      {
         $arr_tl[$tl]['dateCreated']="";
         $arr_tl[$tl]['id']=0;
         $arr_tl[$tl]['c_timelineEntryType']="Event";   
         $arr_tl[$tl]['c_timelineEntryDetail']="Procedure Op Complete";
         $complete_flag=true; 
         $tl++;
      }
      $varname = "c_session".$i."Name";
      if ($arr_proc_info[$i][$varname]<>"")
      {
         $arr_tl[$tl]['dateCreated']="";
         $arr_tl[$tl]['id']=0;
         $arr_tl[$tl]['c_timelineEntryType']="Future Event";   
         $arr_tl[$tl]['c_timelineEntryDetail']="<strong>Upcoming Survey</strong><br />".$arr_proc_info[$i][$varname];
logMsg("$i: Upcoming Survey".$arr_proc_info[$i][$varname], $logfile);
         $tl++;
      }
   }
logMsg("AFTER: Items in TL array: ".count($arr_tl), $logfile);
   return $arr_tl;
}

// ***************************************************
function update_alert_status($id)
{
   global $TBLTIMELINES, $TBLPTEPISODES;

   $sql = "SELECT * 
           FROM $TBLTIMELINES 
           WHERE c_patientEpisodeId='$id'
             AND c_timelineEntryType='Alert'
             AND c_timelineAlertStatus='Open'";
   $GetQuery=dbi_query($sql);
   if ($GetQuery->num_rows==0) 
      $c_hasAlert_str="";
   else
      $c_hasAlert_str="Y";
   $sql = "UPDATE $TBLPTEPISODES
           SET c_hasAlert='$c_hasAlert_str',
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
}
?>
