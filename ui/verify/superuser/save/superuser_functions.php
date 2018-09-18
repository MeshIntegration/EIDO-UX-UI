<?php
// ***************************************
// superuser/superuser_functions.php
// 2018 Copyright, Mesh Integration LLC
// 1/16/18 - WEL
// ***************************************

session_start();
require_once '/var/www/html/ui/verify/utilities.php';

// ************* LOCAL FUNCTIONS ****************************************
function get_proc_episode($pe_id, $session_number,$save_to_sess=false)
{
   global $TBLPROCEPISODES;

   $logfile = "superuser.log";
   logMsg("-------------- Get ProcEpisode ---------------",$logfile);
   logMsg("PEID: $pe_id - SN: $session_number", $logfile);
   $arr_proc_episode = array();
   $sn = $session_number;
   if ($sn==1)
      $delay_str = "";
   else
      $delay_str = "c_session".$sn."Delay AS sessionDelay,";

   $sql = "SELECT c_procedureId, c_description, c_numberOfSessions,
                  c_prePost".$sn."CustomMessage AS PrePostCustomMessage,
                  $delay_str 
                  c_prePost".$sn." AS prePost,
                  c_session".$sn."Name AS sessionName,
                  c_session".$sn."Survey1 AS sessionSurvey1,
                  c_session".$sn."Survey2 AS sessionSurvey2,
                  c_session".$sn."Survey3 AS sessionSurvey3,
                  c_session".$sn."Survey4 AS sessionSurvey4,
                  c_session".$sn."Survey5 AS sessionSurvey5
          FROM $TBLPROCEPISODES
          WHERE id = '$pe_id'";
   logMsg($sql, $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $arr_proc_episode['c_procedureId'] = $qryResult['c_procedureId'];
   $arr_proc_episode['c_description'] = $qryResult['c_description'];
   $arr_proc_episode['c_numberOfSessions'] = $qryResult['c_numberOfSessions'];
   $arr_proc_episode['PrePostCustomMessage'] = $qryResult['PrePostCustomMessage'];
   $arr_proc_episode['sessionDelay'] = $qryResult['sessionDelay'];
   $arr_proc_episode['prePost'] = $qryResult['prePost'];
   $arr_proc_episode['sessionName'] = $qryResult['sessionName'];
   if($save_to_sess){  
      $_SESSION['pe_id'.$pe_id]['sess_id'.$sn][0] = $arr_proc_episode['sessionSurvey1'] = $qryResult['sessionSurvey1'];
      $_SESSION['pe_id'.$pe_id]['sess_id'.$sn][1] = $arr_proc_episode['sessionSurvey2'] = $qryResult['sessionSurvey2'];
      $_SESSION['pe_id'.$pe_id]['sess_id'.$sn][2] = $arr_proc_episode['sessionSurvey3'] = $qryResult['sessionSurvey3'];
      $_SESSION['pe_id'.$pe_id]['sess_id'.$sn][3] = $arr_proc_episode['sessionSurvey4'] = $qryResult['sessionSurvey4'];
      $_SESSION['pe_id'.$pe_id]['sess_id'.$sn][4] = $arr_proc_episode['sessionSurvey5'] = $qryResult['sessionSurvey5'];
   }else{
      $arr_proc_episode['sessionSurvey1'] = $qryResult['sessionSurvey1'];
      $arr_proc_episode['sessionSurvey2'] = $qryResult['sessionSurvey2'];
      $arr_proc_episode['sessionSurvey3'] = $qryResult['sessionSurvey3'];
      $arr_proc_episode['sessionSurvey4'] = $qryResult['sessionSurvey4'];
      $arr_proc_episode['sessionSurvey5'] = $qryResult['sessionSurvey5'];
   }
   return $arr_proc_episode;
}

// ***************************************************************
function get_num_surveys_by_proc($pe_id, $session_number)
{
   global $TBLPROCEPISODES;

   $logfile = "superuser.log";
   logMsg("-------------- GetNumSurveysByProc ---------------",$logfile);

   // up to 5 surveys a session 
   // see how many have values for this session
   $sql = "SELECT c_session".$session_number."Survey1, 
                  c_session".$session_number."Survey2,
                  c_session".$session_number."Survey3,                  
                  c_session".$session_number."Survey4,                  
                  c_session".$session_number."Survey5                  
          FROM $TBLPROCEPISODES
          WHERE id = '$pe_id'";
   logMsg($sql, $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $num_surveys=0;
   for ($i=1; $i<=5; $i++)
   {
      $nm = "c_session".$session_number."Survey".$i;
      if ($qryResult[$nm] <> "")
         $num_surveys++;
   }
   return $num_surveys;
}

function get_surveys_by_proc($pe_id, $session_number)
{
   global $TBLPROCEPISODES,$MAX_SURVEYS;

   $logfile = "superuser.log";
   logMsg("-------------- GetNumSurveysByProc ---------------",$logfile);

   // up to 5 surveys a session
   // see how many have values for this session
   $sql = "SELECT *
          FROM $TBLPROCEPISODES
          WHERE id = '$pe_id'";
   logMsg($sql, $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $num_surveys=0;
   $surveys = array();
   for ($i=1; $i<=$MAX_SURVEYS; $i++)
   {
     $nm = "c_session".$session_number."Survey".$i;
     if ($qryResult[$nm]<>"")
        $surveys[] = $qryResult[$nm];
   }
   return $surveys;
}

// ***************************************************************
function save_surveys_to_session($pe_id, $sess_id, $survey_ids) {
   global $TBLPROCEPISODES, $MAX_SURVEYS;

echo "<br />save_surveys_to_session<br />";
print_r($survey_ids);
echo "<br />";

   // don't save empty ones
   $s=1;  // survey number
   for ($i=0; $i<count($survey_ids); $i++) {
      if ($survey_ids[$i]<>"") {
         $varname = "c_session".$sess_id."Survey".$s;
         $sql = "UPDATE $TBLPROCEPISODES
                 SET $varname = ".$survey_ids[$i]." 
                 WHERE id = '$pe_id'";
         dbi_query($sql);
         $s++;
echo "$sql<br />";
     }
   }

echo "-----<br />";
   //  blank out the unused ones
   for ($i=$s; $i<=$MAX_SURVEYS; $i++) {
      $varname = "c_session".$sess_id."Survey".$i;
      $sql = "UPDATE $TBLPROCEPISODES
              SET $varname = '' 
              WHERE id = '$pe_id'";
      dbi_query($sql);
echo "$sql<br />";
   }
exit();
}

// ***************************************************************
function get_all_surveys($search_term="")
{
   global $TBLSURVEYS;

   $logfile = "superuser.log";

   if ($search_term=="") $search_str = "1";
   else $search_str="c_description LIKE '%".$search_term."%'";
   $sql = "SELECT * 
           FROM $TBLSURVEYS
           WHERE $search_str
           ORDER BY c_description";
   $GetQuery = dbi_query($sql);
   $arr_all_surveys=array();
   $i=0;
   while ($qryResult = $GetQuery->fetch_assoc())
   {
      $arr_all_surveys[$i]=$qryResult;
      $arr_all_surveys[$i]['added']=false; 
      $i++;
   }

//   logMsg("Survey count: $i",$logfile);
   return $arr_all_surveys;
}

// *********************************************************************
// return array when you pass array - $surveynumber ($sn) as argument
//

function get_survey_by_num($sn)
{
   global $TBLSURVEYS;
   $sql = "SELECT * FROM $TBLSURVEYS WHERE c_surveyNumber='$sn'";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   return $qryResult;
   //exit();
   
/* ********************************************************
   //  below is the old way based on arrays - not used
   $logfile = "superuser.log";
   logMsg("-------------- GetSurveysByNum ---------------",$logfile);

   if(is_array($sn)){
      $arr_all_surveys = get_all_surveys();
      foreach($arr_all_surveys as $survey){
         if(in_array($survey['id'],$sn)){
	    $result[] = $survey;
         }
      }
   }else{
      $arr_all_surveys = get_all_surveys();
      foreach($arr_all_surveys as $survey){
         if($survey['id'] == $sn){
           return $survey;
         }
      }
   }
   return $result;
*************************  */
}

// *********************************************************************
function get_proc_by_id($id)
{
   global $TBLPROCEPISODES;

   $logfile = "superuser.log";
   logMsg("-------------- Get_proc_by_id ---------------",$logfile);
   
   $sql="SELECT * FROM $TBLPROCEPISODES WHERE id='$id'";
   logMsg($sql, $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   return $qryResult;
}
?> 

