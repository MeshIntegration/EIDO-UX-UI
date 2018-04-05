<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// Set Debug Mode
require_once '/var/www/html/ui/proms/lib/vendor/MysqliDb/MysqliDb.php';

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
$DBNAME = "jwedb";
$db = new MysqliDb ($HOST,$USERNAME,$PASSWORD,$DBNAME); 

// LOCAL DB
//$HOST = "localhost";
//$USERNAME = "root";
//$PASSWORD = "";
//$DBNAME = "jwedb";

$cookie_domain = ".verify.eidosystems.com";
$cookie_expire = 3600*24*365;

$debug = true ;//false ; // true;
if($debug){
   error_reporting(E_ERROR | E_PARSE); 
   ini_set("display_errors", 1);
}

$SITE_URL = "http://verify.eidosystems.com/";
$ABS_PATH = "/var/www/html/ui/proms/" ;

$logdir = "/var/www/html/ui/proms/logs";

// API detail
$BASE_URL = "http://verify.eidosystems.com:8080/jw/web/json/data/";
$BASE_WORKFLOW_URL = "http://verify.eidosystems.com:8080/jw/web/json/workflow/";
$USERNAME = "eidoverify2017";
$PASSWORD = "";
$HASH = "91FCC50BA6AC975A3876E556CCE7D986"; //  md5($USERNAME . "::" . md5($PASSWORD));
//$USERNAME = "admin";
//$PASSWORD = "mi526ApJo!";
//$HASH = md5($USERNAME . "::" . md5($PASSWORD));

// use to set pagination
$row = 10;
$page = 1;
$start = 0;
$pageLimit = 6;
$pageWindow = 6;

?>
