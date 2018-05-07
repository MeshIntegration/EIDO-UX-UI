<?php
// ***************************************
// superuser/procedures_a.php
// 2018 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// ***************************************

require_once '../utilities.php';
session_start();
$logfile = "procedure.log";
$debug = true;

$mode = get_query_string('m');
$id = get_query_string('id');

logMsg("Procedures_a: $mode $id", $logfile);

if($mode == "add") {
	$c_description = $_POST['c_description'];
	$c_procedureId = $_POST['c_procedureId'];
	$c_displayName = $_POST['c_displayName'];
	$id = uniqid();

	$sql = "INSERT INTO  $TBLPROCEPISODES
           SET id='$id',
               c_description=" . escapeQuote($c_description) . ",
               c_procedureId=" . escapeQuote($c_procedureId) . ",
               c_displayName=" . escapeQuote($c_displayName) . ",
               dateModified=NOW(),
               dateCreated=NOW()";
	dbi_query($sql);
	if($debug)
		logMsg($sql, $logfile);

	header("Location: procedures.php?m=add");
	exit();
} else if($mode == "update") {
	$c_description = $_POST['c_description'];
	$c_procedureId = $_POST['c_procedureId'];
	$c_displayName = $_POST['c_displayName'];

	$sql = "UPDATE $TBLPROCEPISODES
           SET id='$id',
               c_description=" . escapeQuote($c_description) . ",
               c_procedureId=" . escapeQuote($c_procedureId) . ",
               c_displayName=" . escapeQuote($c_displayName) . ",
               dateModified=NOW()
           WHERE id='$id'";
	dbi_query($sql);
	if($debug)
		logMsg($sql, $logfile);

	header("Location: procedures.php?m=add");
	exit();
} else if($mode == "updateproc") {
	$pe_id = $_GET['id'];
	//   $sess_id = $_GET['sess_id'];  //  TILL THIS GETS FIXED
	$id = $_GET['id'];
	$session_type = $_SESSION['session_type'];;  // PRE or POST
	$number_of_sessions = $_POST['quantity'];;  // Up to 6
	$session_name = $_POST['sessionName'];
	$session_delay = $_POST['session_delay'];
	
	header("Location: procedures.php?m=add");
	exit();

}
?>
