<?php
// val/validation_goto_request.php
// Copyright 2018, Mesh Integrations, LLC
// WEL 4/25/18

require_once '../utilities.php';
$logfile = "validation.log";

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";

$ip_address = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];
add_to_timeline($arr_pt_info['id'], "Request review", "Open", "Alert", 
                $browser, $ip_address, "Validation", $arr_pt_info['c_currentSessionNumber']);

header ("Location:validation_request.php");
?>
