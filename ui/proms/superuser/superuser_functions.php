<?php
// ***************************************
// superuser/superuser_functions.php
// 2018 Copyright, Mesh Integration LLC
// 1/16/18 - WEL
// ***************************************

require_once '../utilities.php';
session_start();

// ************* LOCAL FUNCTIONS ****************************************
function get_proc_episode($pe_id, $session_number)
{
   $logfile = "wel.log";
   $arr_proc_episode = array();
   $sn = $session_number;
   if ($sn==1)
      $delay_str = "";
   else
      $delay_str = "c_session".$sn."Delay AS sessionDelay,";

   $sql = "SELECT c_procedureId, c_description,
                  c_prePost".$sn."CustomMessage AS PrePostCustomMessage,
                  $delay_str 
                  c_prePost".$sn." AS prePost,
                  c_session".$sn."Name AS sessionName,
                  c_session".$sn."Survey1 AS sessionSurvey1,
                  c_session".$sn."Survey2 AS sessionSurvey2,
                  c_session".$sn."Survey3 AS sessionSurvey3,
                  c_session".$sn."Survey4 AS sessionSurvey4,
                  c_session".$sn."Survey5 AS sessionSurvey5
          FROM app_fd_pro_procEpisodes
          WHERE id = '$pe_id'";
logMsg($sql, $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $arr_proc_episode['c_procedureId'] = $qryResult['c_procedureId'];
   $arr_proc_episode['c_description'] = $qryResult['c_description'];
   $arr_proc_episode['PrePostCustomMessage'] = $qryResult['PrePostCustomMessage'];
   $arr_proc_episode['sessionDelay'] = $qryResult['sessionDelay'];
   $arr_proc_episode['prePost'] = $qryResult['prePost'];
   $arr_proc_episode['sessionName'] = $qryResult['sessionName'];
   $arr_proc_episode['sessionSurvey1'] = $qryResult['sessionSurvey1'];
   $arr_proc_episode['sessionSurvey2'] = $qryResult['sessionSurvey2'];
   $arr_proc_episode['sessionSurvey3'] = $qryResult['sessionSurvey3'];
   $arr_proc_episode['sessionSurvey4'] = $qryResult['sessionSurvey4'];
   $arr_proc_episode['sessionSurvey5'] = $qryResult['sessionSurvey5'];

//echo "<PRE>";
//print_r($arr_proc_episode);
//echo "</PRE>";
//exit();

   return $arr_proc_episode;
}

// ***************************************************************
function get_num_surveys_by_proc($pe_id, $session_number=1)
{
   $session_number = 1; // for now

   // up to 5 surveys a session 
   // see how many have values for this session
   $sql = "SELECT c_session".$session_number."Survey1, 
                  c_session".$session_number."Survey2,
                  c_session".$session_number."Survey3,                  
                  c_session".$session_number."Survey4,                  
                  c_session".$session_number."Survey5                  
          FROM app_fd_pro_procEpisodes
          WHERE id = '$pe_id'";
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

// ***************************************************************
function get_all_surveys()
{
   $logfile="wel.log";

   // this will pull from survey gizmo later
   $sql = "SELECT * FROM app_fd_pro_surveys`ORDER BY c_description";
   $GetQuery = dbi_query($sql);
   $arr_all_surveys=array();
   $i=0;
   while ($qryResult = $GetQuery->fetch_assoc())
   {
      $arr_all_surveys[$i]=$qryResult;
      $arr_all_surveys[$i]['added']=false; 
      $i++;
   }
   logMsg("Survey count: $i",$logfile);
   return $arr_all_surveys;
}

// *********************************************************************
function get_survey_by_num($sn)
{
   $logfile="wel.log";
   $sql="SELECT * FROM app_fd_pro_surveys WHERE c_surveyNumber='$sn'";
   //logMsg("get_survey_by_num: $sql", $logfile);
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   return $qryResult;
}
?> 

