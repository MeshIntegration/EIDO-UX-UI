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
logMsg("AJAX set_session_data.php: type: " . $_POST['type'], "superuser.log");
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
                $sess_id=$_POST['sess_id'];
                $sess_id_prev = $_POST['sess_id_prev'];
                // save the session name and the dealy
                $session_name = $_POST['sess_name'];
                $session_delay = $_POST['sess_delay'];
                if ($sess_id>1)
                   $delay_str = ", c_session".$sess_id_prev."Delay='".$session_delay."'";
                else
                   $delay_str = "";
                $varname_name = "c_session".$sess_id_prev."Name";
                $sql = "UPDATE $TBLPROCEPISODES
                        SET $varname_name='$session_name' $delay_str
                        WHERE id='$pe_id'";
                logMsg($sql,"wel.log");
                dbi_query($sql);
		echo $_POST['pe_id'];
	}
}
if($type == "sessionname_save") {
	if(isset($_POST['pe_id']) && !empty($_POST['pe_id'])) {
		$pe_id = $_POST['pe_id'];
		$sess_name = $_POST['sess_name'];
		$sess_id = $_POST['sess_id'];
                $varname_name = "c_session".$sess_id."Name";
                $sql = "UPDATE $TBLPROCEPISODES
                        SET $varname_name='$sess_name'
                        WHERE id='$pe_id'";
                logMsg($sql,"wel.log");
                dbi_query($sql);
		$_SESSION['pe_id' . $pe_id]['sessionName' . $sess_id] = $sess_name;
	}
}
if($type == "sessiondelay_save") {
        if(isset($_POST['pe_id']) && !empty($_POST['pe_id'])) {
                $pe_id = $_POST['pe_id'];
                $sess_delay = $_POST['sess_delay'];
                $sess_id = $_POST['sess_id'];
                $varname_name = "c_session".$sess_id."Delay";
                $sql = "UPDATE $TBLPROCEPISODES
                        SET $varname_name='$sess_delay'
                        WHERE id='$pe_id'";
                logMsg($sql,"wel.log");
                dbi_query($sql);
                //$_SESSION['pe_id' . $pe_id]['sessionName' . $sess_id] = $sess_name;
        }
}

if($type == "numberofsession_save") {
	if(isset($_POST['pe_id']) && !empty($_POST['pe_id']) && !empty($_POST['noofsession'])) {
		$pe_id = $_POST['pe_id'];
		$noofsession = $_POST['noofsession'];
logMsg("AJAX numberofsession_save: noofsession = $noofsession","wel.log");
		$_SESSION['pe_id' . $pe_id]['numberofsession'] = $noofsession;
                $sql ="UPDATE $TBLPROCEPISODES SET c_numberOfSessions='$noofsession'
                       WHERE id='$pe_id'";
                dbi_query($sql);
	}
}

//sorts our submitted surveys
if($type == "sort_surveys") {
	/**
	 * @todo check this data first
	 */
	$currentSession = "c_session{$_POST['sess_id']}";
	$sql = "UPDATE $TBLPROCEPISODES SET ";
	for($i=0; $i < count($_POST['surveys']); $i++) {
		$survey = $_POST['surveys'][$i];
		$sql .= $currentSession."Survey".($i + 1)."=".$survey;

		if($i < count($_POST['surveys']) - 1) {
			$sql .= ', ';
		}
	}
	$sql .= " WHERE id='$pe_id'";
logMsg("AJAX SORT SURVEYS - $sql","wel.log");
	dbi_query($sql);
}

if($type == "update_session_name") {
	$sessionName = $_POST['session_name'];
	$currentSession = "c_session{$_POST['sess_id']}Name='$sessionName'";
	$sql = "UPDATE $TBLPROCEPISODES SET $currentSession WHERE id='$pe_id'";
        logMsg(">>>>> save sessionName: $sql", "superuser.log");
	dbi_query($sql);
}

if($type == "update_session_delay") {
	$sessionDelay = $_POST['session_delay'];
	$currentSession = "c_session{$_POST['sess_id']}Delay='$sessionDelay'";
	$sql = "UPDATE $TBLPROCEPISODES SET $currentSession WHERE id='$pe_id'";
        logMsg(">>>>> save sessionDelay: $sql", "superuser.log");
	dbi_query($sql);
}


exit();
?>
