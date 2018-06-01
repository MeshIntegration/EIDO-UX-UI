<!doctype html>
<?php
// ***************************************
// superuser/procedures.php
// 2017 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
require_once 'superuser_functions.php';
if ($_GET['c']=='02022018'){
   $arr_all_surveys=get_all_surveys();
 
   // setup the request for survey api
   $URL_method = "http://restapi.surveygizmo.eu/v5/survey" ;
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
   }

}    
?>


