<?php
// *********************************
// logout.php
// Copyright 2018, MindStreams LLC
// WEL 2/22/18
// *********************************

include "../utilities.php";
$logfile = "superuser.log";
session_start();

unset($_COOKIE['user_id']);
unset($_COOKIE['user_initials']);
unset($_COOKIE['user_role']);
unset($_COOKIE['org_id']);
header("Location: login.php");
exit();
?>
