<?php
// **************************************
// clear_search.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/24/18
// **************************************

include "../utilities.php";

session_start();
$mode = get_query_string('m');

unset($_SESSION['filter']['top_search_query']);

header ("Location: patients.php?m=$mode");
exit();
?>
