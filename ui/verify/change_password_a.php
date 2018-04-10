<?php
// **************************************
// change_password_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/25/18
// **************************************

include "utilities.php";
include "alert_intruders.php";
$rt=get_query_string('rt');
session_start();
$_SESSION['error_msg']="";

$logfile = "user.log";

$password=$_POST['password'];
$old_password=$_POST['old_password'];
$user_id = $_COOKIE['user_id'];
$arr_user_info = get_user_info($user_id);

logMsg("User_id: $user_id - Current Password: ".$arr_user_info['password'],$logfile);
logMsg("Entered Current PW: $old_password - New Password: $password",$logfile);

if ($password=="")
   $_SESSION['error_msg'] = "NO_PASSWORD";
else if ($old_password=="")
   $_SESSION['error_msg'] = "NO_OLD_PASSWORD";
else if ($old_password<>$arr_user_info['password'])
   $_SESSION['error_msg'] = "WRONG_OLD_PASSWORD";
if ($_SESSION['error_msg']<>"")
{
   header ("Location: change_password.php?rt=$return_to");
   exit();
}
save_password($user_id, $password);
if ($rt=="su") $return_to="superuser/users.php";
else if ($rt=="adm") $return_to="admin/users.php";
else if ($rt=="pt") $return_to="patient/patients.php";
else if ($rt=="login") $return_to="login.php";
header ("Location: $return_to");
exit();
?>
