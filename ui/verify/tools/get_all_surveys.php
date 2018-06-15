<?php
// get_all_surveys.php
// Copyright 2018, Mesh integration LLC
// get all surveys from Survey Gizmo and load into DB
// WEL 6/8/18

   require_once "/var/www/html/ui/verify/utilities.php";

   $logfile = "survey.log";
   logMsg("--------------  cron: Get_all_surveys.php ---------------",$logfile);

   // clear table
   $sql = "TRUNCATE TABLE $TBLSURVEYS";
   dbi_query($sql);

   // setup the request for survey api
   $requestParam = array(
      'api_token' => $sg_api_token,
      'api_token_secret' => $sf_api_token_secret,
   ) ;
   $is_url_custom = 1 ;
   $requestType = "GET" ;
   $auth_type = "NONE" ;
   $responseType = "OBJECT" ;
   // call once to get the number of record
   $data = getCurlResponse($sg_url_method, $requestParam, $is_url_custom, $requestType, $auth_type, $responseType);
   $total_count = (int) $data->total_count;

   if($total_count>$data->results_per_page){
      // we need all surveys
      $requestParam['resultsperpage'] = $total_count ;
      $data = getCurlResponse($sg_url_method, $requestParam, $is_url_custom, $requestType, $auth_type, $responseType);
   }

   // get the entire survey list
   $survey_result_list = $data->data;
   foreach($survey_result_list as $survey_result){
      $sql = "INSERT INTO $TBLSURVEYS
              SET   c_id=".escapeQuote($survey_result->id)." ,
                    dateCreated=".escapeQuote($survey_result->created_on).",
                    dateModified=".escapeQuote($survey_result->modified_on).",
                    c_prePost='',
                    c_description=".escapeQuote($survey_result->title).",
                    c_surveyNumber=".escapeQuote($survey_result->id).",
                    createdBy='system',
                    createdByName='Verify System',
                    modifiedBy='',
                    modifiedByName=''";
      dbi_query($sql);
      $i++;
   }
   logMsg("Survey count: $i",$logfile);
   logMsg("----- cron: Get All Surveys Complete -----", $logfile);
?>
