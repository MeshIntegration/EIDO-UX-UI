<?php
// **************************************
// pw_reset_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/4/18
// **************************************

include "./utilities.php";
include "./lib/validation.php";
session_start();
$logfile = "wel.log";

$pwkey = get_query_string('k');
if ($pwkey=="")
{
   $_SESSION['error_msg'] = "You are using a bad link or have bad data. Please contact EIDO Verify Supprt for assistance.";
   logMsg("pw_reset: Blank Reset Key passed in",$logfile);
   header("Location:message.php");
   exit();
}
$password = trim($_POST['password']);

save_user_pw_reset($pwkey, $password);

$_SESSION['error_msg'] = "Your password has been reset.<br /><br /><a href='login.php'>Click here</a> to login to the EIDO Verify system.";

//logMsg("pw_reset: reset done for: ".$arr_pt_info['c_patientEpisodeId'],$logfile);
header("Location:message.php");
exit();
?>
