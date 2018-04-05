<?php
/*
 * utilities.php
 * Copyright 2018, Mesh Integrations, LLC 
 * WEL 1/6/18  
 */
require_once '/var/www/html/ui/proms/globalvars.php';
require_once '/var/www/html/ui/proms/lib/functions.php';
require_once '/var/www/html/ui/proms/lib/is_dbi_util.php';
require_once '/var/www/html/ui/proms/lib/is_util.php';

foreach (glob("/var/www/html/ui/proms/lib/classes/*.php") as $filename) {
   include $filename;

//error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', TRUE);
}

function load_template($center_content, $right_content, $script, $param) {
   global $ABS_PATH;
   include $ABS_PATH .'/template/template.php';
   echo ob_get_clean();
}

function load_front_template($center_content, $script, $param) {
   global $ABS_PATH;
   include $ABS_PATH .'/template/front.php';
   echo ob_get_clean();
}

function load_front_center_template($center_content, $script, $param) {
   global $ABS_PATH;
   include $ABS_PATH .'/template/front_center.php';
   echo ob_get_clean();
}

function load_blank_template($center_content, $param) {
   global $ABS_PATH;
   include $ABS_PATH . '/template/blank.php';
   echo ob_get_clean();
}

// ***************************************************
function get_pt_status($id)
{
   $sql = "SELECT * 
           FROM app_fd_pro_patientEpisodes
           WHERE id = '$id'";
   $GetQuery=dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   if ($qryResult['c_status']=="Episode Complete")
      $status = "Inactive";
   else if ($qryResult['c_proceedOrCancel']=="Cancel")
      $status = "Inactive";
   else if ($qryResult['c_status']=="Email Bounced")
      $status = $qryResult['c_status'];
   else
      $status = "Active";
 
   return $status;
}
  
?>
