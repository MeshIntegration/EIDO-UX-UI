<?php
// ***************************************
// patient/resend_invite.php
// 2018 Copyright, Mesh Integration LLC
// 3/25/18 - WEL
// ***************************************

session_start();
require_once '../utilities.php';
$logfile = "patient.log";
$id=get_query_string('id');
$arr_pt_info=get_pt_info($id);

$webhook = "https://apiant.eidoverify.com/webhook/ef45684f98284e528cd34b66af315c7f-f6556bcc0b4548a782112abd4cd58ca0";

$curr_sess = $arr_pt_info['c_currentSessionNumber'];
$var_name = "c_prePost".$curr_sess."CustomMessage";
$sql = "SELECT $var_name AS custom_message FROM $TBLPROCEPISODES WHERE id='".$arr_pt_info['c_procedure']."'";
logMsg("Resend Invite - custom message: $sql",$logfile);
$GetQuery = dbi_query($sql);
$qryResult = $GetQuery->fetch_assoc();
$custom_message = $qryResult['custom_message'];
$prepost = ucwords(strtolower($arr_pt_info['c_procedureStatus']));

$data = array("episodeId" => $arr_pt_info['id'], 
              "patientEmail" => $arr_pt_info['c_emailAddress'],
              "patientPhone" => $arr_pt_info['c_mobileNumber'],
              "patientFirstName" => $arr_pt_info['c_firstName'], 
              "hospitalName" => $arr_pt_info['c_hospitalName'],
              "prePost" => $prepost,  
              "prePostCustomMessage" => $custom_message,
              "orgEpisodeId" => $arr_pt_info['c_procedure'],
              "reminder" => "false",
              "currentSessionName" => $arr_pt_info['c_currentSessionName']);
$data_string = json_encode($data);                                                                                   
logMsg("Resend Invite JSON: $data_string", $logfile);
                                                                                                                     
$ch = curl_init($webhook);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
$result = curl_exec($ch);

header ("Location: patients.php?main");
exit();
?>

