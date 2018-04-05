<?php
// ***************************************
// superuser/functions.php
// 2017 Copyright, Mesh Integration LLC
// 12/17/17 - WEL
// ***************************************

require_once '../utilities.php';
require_once 'superuser_functions.php';
session_start();
$logfile = "wel.log";

$mode = get_query_string('m');
$org_id = get_query_string('org_id');
$f = get_query_string('f');

logMsg("functions.php: mode: $mode", $logfile);
if ($f=="delete_orgproc")
{
   $opid = get_query_string('opid');
   $sql = "DELETE FROM app_fd_eido_org_procedures
           WHERE id=$opid";
   dbi_query($sql);
   $goto = "organisations.php?m=$mode&id=$org_id";
}
else if ($mode=="delete_proc_survey")
{
   $pe_id = get_query_string('id');
   $sess_id = get_query_string('sess_id');
   $survey = get_query_string('survey');
   $indx = "sessionSurvey".$survey;
   logMsg("Delete_proc_survey: $pe_id $session $survey", $logfile);
   logMsg("Delete_proc_survey: Session before: ". $_SESSION[$indx], $logfile);
   $_SESSION[$indx] = "";
   logMsg("Delete_proc_survey: Session after: ". $_SESSION[$indx], $logfile);
   $goto = "procedures.php?m=managesurveys&id=$pe_id&sess=$sess";
}
else if ($mode=="add_survey_to_temp")
{
  $indx=get_query_string('s');  // index into survey array
  $c_surveyNumber=get_query_string('sn');  // survey number
  $pe_id=get_query_string('id'); // procedure id
  $sess_id=get_query_string('sess_id'); // current session number

  logMsg("add_survey_to_temp: pe_id: $pe_id sess_id: $sess_id SurveyNum: $c_surveyNumber indx: $indx",$logfile);
  // add it to the temp list
  $arr_add_surveys = $_SESSION['arr_add_surveys'];
logMsg("Items in temp array before: ".count($arr_add_surveys),$logfile);
  $arr_add_surveys[]['c_surveyNumber'] = $c_surveyNumber;
logMsg("Items in temp array after: ".count($arr_add_surveys),$logfile);
  $_SESSION['arr_add_surveys'] = $arr_add_surveys;

  // take it out of the all survey list
  $arr_all_surveys = $_SESSION['arr_all_surveys'];
logMsg("Items in survey list before: ".count($arr_all_surveys),$logfile);
  $arr_all_surveys[$indx]['added']=true;
logMsg("Items in survey list after: ".count($arr_all_surveys),$logfile);
  $_SESSION['arr_all_surveys']=$arr_all_surveys;

  $goto="procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";
}
else if ($mode=="delete_survey_from_temp")
{
   $indx=get_query_string('t');  // index into temp array
   $c_surveyNumber=get_query_string('sn');  // survey number
   $pe_id=get_query_string('id'); // procedure id
   $sess_id=get_query_string('sess_id'); // current session number
   logMsg("delete_survey_from_temp: pe_id: $pe_id sess_id: $sess_id SurveyNum: $c_surveyNumber indx: $indx",$logfile);

   //  move all the elements up to fill in hole where deleted one was
   $arr_add_surveys = $_SESSION['arr_add_surveys'];

   //unset($arr_add_surveys[$indx]);

   for ($i=$indx; $i<(count($arr_add_surveys)-1); $i++)
   {
      $arr_add_surveys[$i]['c_surveyNumber'] = $arr_add_surveys[$i+1]['c_surveyNumber'];
      logMsg("i: $i - ".$arr_add_surveys[$i]['c_surveyNumber'], $logfile);
   }
    // delete the last element of the array
    unset($arr_add_surveys[count($arr_add_surveys)-1]); 
    // write it back to session
   $_SESSION['arr_add_surveys']=$arr_add_surveys;

   $arr_all_surveys = $_SESSION['arr_all_surveys'];
   // look for this survey by number and mark it as available (added=false)
   for ($i=0; $i<count($arr_all_surveys); $i++)
   {
logMsg("delete_survey_from_temp: $i arrayValue: ".$arr_all_surveys[$i]['c_surveyNumber']." surveyNum: $c_surveyNumber",$logfile);
     if ($arr_all_surveys[$i]['c_surveyNumber']==$c_surveyNumber) 
     {
logMsg("found it: $i",$logfile);
       $arr_all_surveys[$i]['added']=false;
     }
   }
   $_SESSION['arr_all_surveys']=$arr_all_surveys;
   $goto="procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";
}
else if ($mode=="add_selected_surveys")
{
   $pe_id=get_query_string('id'); // procedure id
   $sess_id=get_query_string('sess_id'); // current session number
   $arr_add_surveys = $_SESSION['arr_add_surveys'];
   logMsg("add_selected_surveys: pe_id: $pe_id sess_id: $sess_id ",$logfile);
   $num_current_surveys=get_num_surveys_by_proc($pe_id);
   $num_selected_surveys = count($arr_add_surveys);
   if ($num_current_surveys+$num_selected_surveys>5)
   {
      $_SESSION['error_msg'] = "The current number of surveys plus the selected ones are greater that the maximum of five (5) allowed.";
      $goto="procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";
   }
   else
   {
      // write the selected surveys to the session
      $j=0;
      for ($i=($num_current_surveys+1); $i<=5; $i++)
      {
         $nm = "sessionSurvey".$i;
         $_SESSION[$nm] = $arr_add_surveys[$j]['c_surveyNumber'];
         $j++;
      }
   }
   $goto="procedures.php?m=managesurveys&id=$pe_id&sess_id=$sess_id";
}
else if ($mode=="prepost")
{
logMsg("PrePost: ".get_query_string('t'), $logfile);

   $pe_id=get_query_string('id'); // procedure id
   $sess_id=get_query_string('sess_id'); // current session number

   $_SESSION['session_type'] = strtoupper(get_query_string('t'));
   $goto="procedures.php?m=managesurveys&id=$pe_id&sess_id=$sess_id";
}
header("Location: $goto");
exit();

?> 

