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

// ***************************************************************
function get_all_surveys()
{
   $logfile = "superuser.log";
   logMsg("-------------- Get_all_surveys ---------------",$logfile);

   // this will pull from survey gizmo later
   // SD : replaced : 02/03/2018
   //$sql = "SELECT * FROM app_fd_ver_surveys`ORDER BY c_description";
   //$GetQuery = dbi_query($sql);
   //$arr_all_surveys=array();
   //$i=0;
   //while ($qryResult = $GetQuery->fetch_assoc())
   //{
   //   $arr_all_surveys[$i]=$qryResult;
   //   $arr_all_surveys[$i]['added']=false; 
   //   $i++;
   //}

   // setup the request for survey api
   $URL_method = "https://restapi.surveygizmo.eu/v5/survey" ;
   // we have to set the token in global variable
   $requestParam = array(
      'api_token' => '0187a230cc294375e907f8c3059656cadd920acce87b54eb42',
      'api_token_secret' => 'A9tn4NkVvEPnU',
   ) ;
   $is_url_custom = 1 ;
   $requestType = "GET" ;
   $auth_type = "NONE" ;
   $responseType = "OBJECT" ;
   // call once to get the number of record
   $data = getCurlResponse($URL_method, $requestParam, $is_url_custom, $requestType, $auth_type, $responseType);
   $total_count = (int) $data->total_count;

   if($total_count>$data->results_per_page){
      // we need all surveys
      $requestParam['resultsperpage'] = $total_count ;
      $data = getCurlResponse($URL_method, $requestParam, $is_url_custom, $requestType, $auth_type, $responseType);
   }
   
   // all the survey list
   $survey_result_list = $data->data;
   foreach($survey_result_list as $survey_result){
      $arr_all_surveys[] = array(
         'id'=>$survey_result->id ,
         'dateCreated'=>$survey_result->created_on,
         'dateModified'=>$survey_result->modified_on,
         'c_prePost'=>'Post',
         'c_description'=>$survey_result->title,
         'c_surveyNumber'=>$survey_result->id,
         'createdBy'=>'',
         'createdByName'=>'',
         'modifiedBy'=>'',
         'modifiedByName'=>'',
         'added'=>false,
      );

      // sort column
      $sort_c_description[] = $survey_result->title;
   }
   // sort survey by title
   array_multisort($sort_c_description,SORT_ASC, $arr_all_surveys);
   
   logMsg("Survey count: $i",$logfile);
   return $arr_all_surveys;
}

// *********************************************************************
// return array when you pass array - $surveynumber ($sn) as argument
//

function get_survey_by_num($sn)
{
   global $TBLSURVEYS;

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

