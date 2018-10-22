<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// Set Debug Mode
require_once '/var/www/html/ui/verify/lib/vendor/MysqliDb/MysqliDb.php';

global $db, $logdir, $DBPREFIX;

// LIVE DB - dbi_util.php style
define("DB_HOSTNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "oBlA7300$");
define("DB_NAME", "jwedb");
define("DB_PREFIX", "dir_");

// This is for the alternate DB libray 
// only used in validation for now
$HOST = "localhost";
$USERNAME = "root";
$PASSWORD = "oBlA7300$";
$DBNAME = "jwdb";
$db = new MysqliDb ($HOST,$USERNAME,$PASSWORD,$DBNAME); 

// LOCAL DB
//$HOST = "localhost";
//$USERNAME = "root";
//$PASSWORD = "";
//$DBNAME = "jwedb";

$cookie_domain = ".verifydev.eidosystems.com";
$cookie_expire = 3600*24*365;

$debug = true ;//false ; // true;
if($debug){
   error_reporting(E_ERROR | E_PARSE); 
   ini_set("display_errors", 1);
}

$SITE_URL = "https://verifydev.eidosystems.com/ui/verify/";
$ABS_PATH = "/var/www/html/ui/verify/" ;

$logdir = "/var/www/html/ui/verify/logs";

// Joget API detail
$BASE_URL = "https://jogetdev.meshconnect.app/jw/web/json/data/";
$BASE_WORKFLOW_URL = "https://jogetdev.meshconnect.app/jw/web/json/workflow/";
$USERNAME = "eidoverify2017";
$PASSWORD = "";
$HASH = "91FCC50BA6AC975A3876E556CCE7D986"; //  md5($USERNAME . "::" . md5($PASSWORD));
//$USERNAME = "admin";
//$PASSWORD = "mi526ApJo!";
//$HASH = md5($USERNAME . "::" . md5($PASSWORD));

// Survey Gizmo API 
$sg_api_token = '0187a230cc294375e907f8c3059656cadd920acce87b54eb42';
$sg_api_token_secret = 'A9tn4NkVvEPnU';
$sg_url_method = "https://restapi.surveygizmo.eu/v5/survey" ;

// use to set pagination
$row = 10;
$page = 1;
$start = 0;
$pageLimit = 6;
$pageWindow = 6;

// patient login password
$pw_max_tries = 5;

// PHPMailer
$phpmailerdir = $ABS_PATH."lib/PHPMailer-5.2.16/PHPMailerAutoload.php";
$mailhost = "localhost";

// PORTING VARIABLES

$verify_mail_from_name = "EIDO Verify Patient Communications";
$verify_mail_from = "verifyadmin@eidosystems.com";
//$verify_mail_from = "promsadmin@eidosystems.com";

// TIMELINE ENTRY DETAIL

$timeline_entry_detail = array(
   'Patient validation error',
   'Patient validation error',
   'Session Complete',
   'Session Started',
   'Survey Complete',
   'Survey Email Clicked',
);

// DB Table Names
// these should be set up to point to the correct tables depending in if it is Verify or PROMS
$TBLORGANISATIONS = "app_fd_ver_organizations";
$TBLSURGEONS = "app_fd_ver_surgeons";
$TBLPROCEPISODES = "app_fd_ver_procEpisodes";
$TBLPROCLICENSES = "app_fd_ver_procLicenses";
$TBLSURVEYS = "app_fd_ver_surveys";
$TBLTIMELINES = "app_fd_ver_patientTimelines";
$TBLPTEPISODES = "app_fd_ver_patientEpisodes";
$TBLORGPROCEDURES = "app_fd_ver_org_procedures";

// max number of sessions in a procedure
$MAX_SESSIONS = 5;
$MAX_SURVEYS = 5;
?>
