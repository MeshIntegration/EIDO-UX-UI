<?php
// **************************************
// validation_pw_reset_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/3/18
// **************************************

include "../utilities.php";
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$logfile = "validation.log";

$password = $_POST['password'];

save_pw_reset($arr_pt_info, $password);
logMsg("Validation_pw_reset: reset done for: ".$arr_pt_info['c_patientEpisodeId'],$logfile);

// once they reset just log them in per Rob
$goto_url = get_survey_url($arr_pt_info);
header("Location:$goto_url");
exit();

//$_SESSION['pw_num_tries']=0;
//$_SESSION['error_msg'] = "Your password has been reset.<br /><br /><a href='login.php'>Click here</a> to login to the EIDO Verify system.";
//header("Location:validation_message.php");
//exit();
?>
