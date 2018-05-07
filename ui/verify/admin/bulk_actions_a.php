<?php
// ***************************************
// admin/bulk_actions.php
// 2017 Copyright, Mesh Integration LLC
// 03/30/18 - MT - Created 
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"ADMIN")
{
	header("Location: /ui/verify/login.php");
	exit();
}

$logfile = "admin.log";
logMsg("In BULK_ACTONS_A.PHP",$logfile);

$mode = get_query_string('actionRequested');

// Serialized user list
$updateUserList = $_POST['users'];

if($updateUserList) {
	foreach($updateUserList as $item) {
		if($mode == "reset") {
			// 1= reset password
			$sql = "UPDATE dir_user 
                                SET c_pwd_reset=1, c_dateModified=NOW() 
                                WHERE id='{$item['value']}'";
			dbi_query($sql);
			logMsg($sql, $logfile);

		} else if($mode == "delete") {
			//1= active, 0 = inactive user
			// additional user information tables may be considred here.
			$sql = "UPDATE dir_user 
                                SET active=0, c_dateModified=NOW() 
                                WHERE id='{$item['value']}'";
			dbi_query($sql);
			logMsg($sql, $logfile);

		}
	}
}
header("Location: users.php");
exit();
?>
