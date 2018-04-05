<?php
// **************************************
// clear_filter.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/24/18
// **************************************

include "../utilities.php";

session_start();
$mode = get_query_string('m');

unset($_SESSION['filter']);

header ("Location: patients.php?m=$mode");
exit();
?>
