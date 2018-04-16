<!doctype html>
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

$mode = get_query_string('actionRequested');
//$id = get_query_string('id');

$updateUserList = json_decode($_POST['json']);



foreach($updateUserList as $item) {

	//ADMIN, Eido;eidoadmin@thenakedsurgeon.com;true
    //CUTTER, John;jcutter@thenakedsurgeon.com;true
    //GLAST, Gfirst;gadmin@example.com;true
    
	$input = explode(";", $item);
	$uid = $input[1];
	
	if ($mode=="pwdreset")
	{
		// 1= reset password
		$sql = "UPDATE dir_user SET c_pwd_reset=1, c_dateModified=NOW() WHERE id='$uid'";
		
		dbi_query($sql);
		logMsg($sql,$logfile);
		
	}
	else if ($mode=="delete")
	{
		//1= active, 0 = inactive customer
		// additional user information tables may be considred here.
		$sql = "UPDATE dir_user SET active=0, c_dateModified=NOW() WHERE id='$uid'";
	}
}

$script_name = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);

if ((isset($_GET['page']) && !empty($_GET['page']))){
	$page = $_GET['page'];
	$start = ($page - 1) * $row;
}else if (isset($_SESSION['page'][$script_name]['no']) && !empty($_SESSION['page'][$script_name]['no'])){
	$page = $_SESSION['page'][$script_name]['no'];
	$start = ($page - 1) * $row;
}
$_SESSION['page'][$script_name]['no'] = $page ;





?>
