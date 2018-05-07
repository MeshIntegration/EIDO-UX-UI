<?php
// ***************************************
// superuser/ajax/set_session_data.php
// 2017 Copyright, Mesh Integration LLC
// 1/21/18 - SD
// use to store session data
// ***************************************
include "../../utilities.php";
session_start();
if(isset($_POST['type'])) {
	$type = $_POST['type'];
} else {
	exit();
}
logMsg("AJAX set_session_data.php: SessNum: " . $_POST['sess_id'], "superuser.log");
logMsg("AJAX set_session_data.php: NumOfSess: " . $_POST['num_sess'], "superuser.log");
$pe_id = $_POST['pe_id'];
$num_sess = $_POST['num_sess'];

if($_POST['num_sess'] <> "") {
	$sql = "UPDATE $TBLPROCEPISODES
          SET c_numberOfSessions = '$num_sess'
          WHERE id='$pe_id'";
	dbi_query($sql);
}
if($type == "procedures_save") {
	if(isset($_POST['sess_id']) && $_POST['sess_id'] <> "") {
		$_SESSION['session_number'] = $_POST['sess_id'];
		echo $_POST['pe_id'];
	}
}
if($type == "sessionname_save") {
	if(isset($_POST['pe_id']) && !empty($_POST['pe_id'])) {
		$pe_id = $_POST['pe_id'];
		$sess_name = $_POST['sess_name'];
		$sess_id = $_POST['sess_id'];
		$_SESSION['pe_id' . $pe_id]['sessionName' . $sess_id] = $sess_name;
	}
}
if($type == "numberofsession_save") {
	if(isset($_POST['pe_id']) && !empty($_POST['pe_id'])) {
		$pe_id = $_POST['pe_id'];
		$noofsession = $_POST['noofsession'];
		$_SESSION['pe_id' . $pe_id]['numberofsession'] = $noofsession;
	}
}

//sorts our submitted surveys
if($type == "sort_surveys") {
	/**
	 * @todo check this data first
	 */
	$currentSession = "c_session{$_POST['sess_id']}";
	$sql = "UPDATE $TBLPROCEPISODES SET ";
	for($i=0; $i < count($_POST['surveys']); $i ++) {
		$survey = $_POST['surveys'][$i];
		$sql .= $currentSession."Survey".($i + 1)."=".$survey;

		if($i < count($_POST['surveys']) - 1) {
			$sql .= ', ';
		}
	}

	$sql .= " WHERE id='$pe_id'";
	dbi_query($sql);

}

if($type == "update_session_name") {
	$sessionName = $_POST['session_name'];
	$currentSession = "c_session{$_POST['sess_id']}Name='$sessionName'";
	$sql = "UPDATE $TBLPROCEPISODES SET $currentSession WHERE id='$pe_id'";

	dbi_query($sql);
}
if($type == "update_session_delay") {
	$sessionDelay = $_POST['session_delay'];
	$currentSession = "c_session{$_POST['sess_id']}Delay='$sessionDelay'";
	$sql = "UPDATE $TBLPROCEPISODES SET $currentSession WHERE id='$pe_id'";

	dbi_query($sql);
}


exit();
?>
