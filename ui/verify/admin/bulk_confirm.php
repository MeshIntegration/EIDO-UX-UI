<?php
// ***************************************
// admin/bulk_confirm.php
// 2017 Copyright, Mesh Integration LLC
// 04/7/18 - WEL & AM - Created 
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"ADMIN") {
	header("Location: /ui/verify/login.php");
	exit();
}

$logfile = "admin.log";
logMsg("In BULK CONFIRM", $logfile);

$mode = get_query_string('actionRequested');
logMsg("Bulk Confirm Mode: $mode", $logfile);

// Serialized user list
$_SESSION['updateUserList'] = $_POST['users'];

if ($mode=="reset")
   header("Location: users.php?m=bulkreset");
else if ($mode=="delete")
    header("Location: users.php?m=bulkdelete");
exit();

// rest of this not used 

if($updateUserList) {
	foreach($updateUserList as $item) {


		if($mode == "pwdreset") {
			// 1= reset password
			$sql = "UPDATE dir_user SET c_pwd_reset=1, c_dateModified=NOW() WHERE id='{$item['value']}'";

			dbi_query($sql);
			logMsg($sql, $logfile);

		} else if($mode == "delete") {
			//1= active, 0 = inactive customer
			// additional user information tables may be considred here.
			$sql = "UPDATE dir_user SET active=0, c_dateModified=NOW() WHERE id='{$item['value']}'";
			dbi_query($sql);
			logMsg($sql, $logfile);

		}
	}
}

?>
