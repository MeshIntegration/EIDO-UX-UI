<?php
// **************************************
// clear_search.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/24/18
// **************************************

include "../utilities.php";
$logfile="patient.log";

session_start();
$mode = get_query_string('m');

logMsg(">>> In clear_search.php mode=$mode",$logfile);

unset($_SESSION['filter']['top_search_query']);
unset($_SESSION['filter']['procedure_date']);

// status, gender, Search within and tag search will reset
//unset($_SESSION['filter']['status']);
//unset($_SESSION['filter']['name']);
//unset($_SESSION['filter']['time_added']);
//unset($_SESSION['filter']['activity']);
//unset($_SESSION['filter']['gender']);

header ("Location: patients.php?m=$mode");
exit();
?>
