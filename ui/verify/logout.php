<?php
// *********************************
// logout.php
// Copyright 2018, MindStreams LLC
// WEL 2/22/18
// *********************************

include "utilities.php";
$logfile = "admin.log";
session_start();
//logMsg("logout",$logfile);

is_setcookie("user_id", "", 0, "/", $cookie_domain);
is_setcookie("user_initials", "", 0, "/", $cookie_domain);
is_setcookie("user_role", "", 0, "/", $cookie_domain);
is_setcookie("user_fullname", "", 0, "/", $cookie_domain);

unset($_COOKIE['user_id']);
unset($_COOKIE['user_initials']);
unset($_COOKIE['user_role']);
unset($_COOKIE['org_id']);
header("Location: login.php");
exit();
?>
