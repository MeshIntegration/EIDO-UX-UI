<?php
// **************************************
// clear_filter.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/24/18
// **************************************

include "../utilities.php";
session_start();
$logfile="patient.log";
$mode = get_query_string('m');

unset($_SESSION['filter']['name']);
unset($_SESSION['filter']['status']);
unset($_SESSION['filter']['time_added']);
unset($_SESSION['filter']['activity']);
unset($_SESSION['filter']['gender']);
unset($_SESSION['filter']);

header ("Location: patients.php?m=$mode");
exit();
?>
