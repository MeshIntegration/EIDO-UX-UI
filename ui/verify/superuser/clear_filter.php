<?php
// **************************************
// clear_filter.php
// Copyright 2018, Mesh Integration LLC
// WEL 8/2/18
// **************************************

include "../utilities.php";
session_start();
$logfile="superuser.log";
$mode = get_query_string('m');

unset($_SESSION['filter']['name']);
unset($_SESSION['filter']['time_added']);
unset($_SESSION['filter']);

$filename=$_SERVER['HTTP_REFERER'];
header ("Location: ".$filename."?m=$mode");
exit();
?>

