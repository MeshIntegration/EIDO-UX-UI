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
$username=$arr_user_info['username'];
$old_hash=$arr_user_info['uipassword'];

logMsg("User_id(cookie): $user_id - Username: $username - Current Password: ".$arr_user_info['uipassword'],$logfile);
logMsg("Entered Current PW: $old_password - New Password: $password",$logfile);

if ($password=="")
   $_SESSION['error_msg'] = "NO_PASSWORD";
else if ($old_password=="")
   $_SESSION['error_msg'] = "NO_OLD_PASSWORD";
// else if ($old_password<>$arr_user_info['uipassword'])
// $hash=$arr_user_info['uipassword'];
else if (!password_verify($old_password, $old_hash))
   $_SESSION['error_msg'] = "WRONG_OLD_PASSWORD";
if ($_SESSION['error_msg']<>"")
{
   header ("Location: change_password.php?rt=$return_to");
   exit();
}

$hash = password_hash($password, PASSWORD_BCRYPT);
if (!password_verify($password, $hash)) {
   /* Invalid hash generation*/
   header("Location:".$_SERVER['HTTP_REFERER']);
   exit;
}

save_password($user_id, $hash);

$_SESSION['error_msg']="<center><h1>Reset Password</h1><p>Your password has been reset. Use your new password to login.</p><a href='login.php' class='button large active'>Goto Login</a></center>";
header ("Location: message.php");
exit();

// ****** not used now ******
if ($rt=="suu") $return_to="superuser/users.php";
else if ($rt=="suo") $return_to="superuser/organisations.php";
else if ($rt=="sup") $return_to="superuser/procedures.php";
else if ($rt=="adm") $return_to="admin/users.php";
else if ($rt=="pt") $return_to="patient/patients.php";
else $return_to="login.php";
header ("Location: $return_to");
exit();
?>
