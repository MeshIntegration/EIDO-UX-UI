<?php
// ***************************************
// alert_intruders.php
// Copyright 2018, Mesh Integration, LLC
// WEL 1/18/18
// ***************************************

$logfile = "wel.log";

$user_id = stripslashes($_COOKIE["user_id"]);
logMsg("userId: ".$user_id, $logfile);

if ($user_id)
{
   $user_role = stripslashes($_COOKIE["user_role"]);
logMsg("userRole: ".$user_role, $logfile);
   $user_initials = stripslashes($_COOKIE["user_initials"]);
   $user_fullname = stripslashes($_COOKIE["user_fullname"]);
logMsg("userInitials: ".$user_initials, $logfile);
}
else
{
   header ("Location: /ui/verify/login.php");
   exit();
}
?>
