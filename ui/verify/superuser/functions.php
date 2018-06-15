<?php
// ***************************************
// superuser/functions.php
// 2017 Copyright, Mesh Integration LLC
// 12/17/17 - WEL
// ***************************************
session_start();
require_once '/var/www/html/ui/verify/utilities.php';
require_once 'superuser_functions.php';
$logfile = "superuser.log";

$mode = get_query_string('m');
$org_id = get_query_string('org_id');
$f = get_query_string('f');

logMsg("functions.php: mode: $mode", $logfile);
if($f == "delete_orgproc") {
	$opid = get_query_string('opid');
	$sql = "DELETE FROM $TBLORGPROCEDURES
           WHERE id=$opid";
	dbi_query($sql);
	$goto = "organisations.php?m=$mode&id=$org_id";

} else if($mode == "delete_proc_survey") {

	$pe_id = get_query_string('id');
	$sess_id = get_query_string('sess_id');
	$survey = get_query_string('survey');

	//determine which survey to delete
	$field = "c_session{$sess_id}Survey$survey";
	$sql = "UPDATE $TBLPROCEPISODES SET $field = NULL WHERE id='$pe_id'";
	dbi_query($sql);

	$goto = "procedures.php?m=managesurveys&id=$pe_id&sess_id=$sess_id";

} else if($mode == "add_survey_to_temp") {
logMsg("In: add_survey_to_temp","procedure.log");
	$indx = get_query_string('s');  // index into survey array
	$c_surveyNumber = get_query_string('sn');  // survey number
	$pe_id = get_query_string('id'); // procedure id
	$sess_id = get_query_string('sess_id'); // current session number
	$arr_add_surveys = $_SESSION['arr_add_surveys'][$pe_id];

	// check for number of survey limit
	$no_of_survey_stored = get_num_surveys_by_proc($pe_id, $sess_id);
$surveyct = count($arr_add_surveys);
logMsg("MaxSurveys: $MAX_SURVEYS - PEID: $pe_id - SESS: $sess_id - NUMSURV: $surveyct","procedure.log");

	//if(($no_of_survey_stored + count($arr_add_surveys) >= $MAX_SURVEYS)) {
	if((count($arr_add_surveys) >= $MAX_SURVEYS)) {
logMsg("In: too many surveys","procedure.log");
		// max survey already selected
		// dont allow this add operation
		$_SESSION['error_msg'] = "The current number of surveys plus the selected ones are greater that the maximum of (" . $MAX_SURVEYS . ") allowed.";
		$goto = "procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";
	} else {
logMsg("In: add to array","procedure.log");
		unset($_SESSION['arr_add_surveys']);
//echo "------------<br />";
//echo "<PRE>";
//print_r($arr_add_surveys);
//echo "</PRE>";
		$arr_add_surveys[] = $c_surveyNumber;
//echo "------------<br />";
//echo "<PRE>";
//print_r($arr_add_surveys);
//echo "</PRE>";
//exit();
		$_SESSION['arr_add_surveys'][$pe_id] = array_unique($arr_add_surveys);

		// take it out of the all survey list
		$arr_all_surveys = $_SESSION['arr_all_surveys'];
		$arr_all_surveys[$indx]['added'] = true;
		$_SESSION['arr_all_surveys'] = $arr_all_surveys;
		// add survey to session
		$_SESSION['pe_id' . $pe_id]['sess_id' . $sess_id][] = $c_surveyNumber;
		$goto = "procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";
	}
} else if($mode == "delete_survey_from_temp") {
	$indx = get_query_string('t');  // index into temp array
	$c_surveyNumber = get_query_string('sn');  // survey number
	$pe_id = get_query_string('id'); // procedure id
	$sess_id = get_query_string('sess_id'); // current session number
	logMsg("delete_survey_from_temp: pe_id: $pe_id sess_id: $sess_id SurveyNum: $c_surveyNumber indx: $indx", $logfile);

	//  move all the elements up to fill in hole where deleted one was
	$arr_add_surveys = $_SESSION['arr_add_surveys'][$pe_id];
	if(($key = array_search($c_surveyNumber, $arr_add_surveys)) !== false) {
		unset($arr_add_surveys[$key]);
	}
	$_SESSION['arr_add_surveys'][$pe_id] = array_values($arr_add_surveys);

	$arr_all_surveys = $_SESSION['arr_all_surveys'];
	// look for this survey by number and mark it as available (added=false)
	for($i = 0; $i < count($arr_all_surveys); $i++) {
		if($arr_all_surveys[$i]['c_surveyNumber'] == $c_surveyNumber) {
			$arr_all_surveys[$i]['added'] = false;
		}
	}
	$_SESSION['arr_all_surveys'] = $arr_all_surveys;
	$goto = "procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";

} else if($mode == "add_selected_surveys") {

logMsg("---------------------------------------","wel.log");
	$pe_id = get_query_string('id'); // procedure id
	$sess_id = get_query_string('sess_id'); // current session number
	$arr_add_surveys = array_unique(array_filter($_SESSION['arr_add_surveys'][$pe_id]));

	$num_current_surveys = get_num_surveys_by_proc($pe_id, $sess_id);
logMsg("NumSurveysInDc: $num_current_surveys", "wel.log");
	$num_selected_surveys = count($arr_add_surveys);
logMsg("NumSelectedSurveys: $num_selected_surveys", "wel.log");
	// if($num_current_surveys + $num_selected_surveys > $MAX_SURVEYS) {
	if($num_selected_surveys > $MAX_SURVEYS) {
		$_SESSION['error_msg'] = "The current number of surveys plus the selected ones are greater that the maximum of five (5) allowed.";
		$goto = "procedures.php?m=addsurveys&id=$pe_id&sess_id=$sess_id";
	} else {
		// store to database
		if($num_selected_surveys > 0) {
			// for($i = $num_current_surveys; $i < $MAX_SURVEYS; $i++) {
		// 		$relative_index = $i - $num_current_surveys;
		// 		$c_session_survey[] = "c_session" . $sess_id . "Survey" . ($relative_index + 1) . "='" . $arr_add_surveys[$relative_index] . "'";
			for($i = 0; $i<$num_selected_surveys; $i++) {
				//$relative_index = $i - $num_current_surveys;
				$c_session_survey[] = "c_session" . $sess_id . "Survey" . ($i + 1) . "='" . $arr_add_surveys[$i] . "'";
			}
			echo $sql = "UPDATE $TBLPROCEPISODES
                                     SET " . implode(",", $c_session_survey) . " 
                                     WHERE id='$pe_id';";
logMsg($sql,"wel.log");
		}
		dbi_query($sql);
		unset($_SESSION['arr_add_surveys'][$pe_id]);
	}
	$goto = "procedures.php?m=managesurveys&id=$pe_id&sess_id=$sess_id";

} else if($mode == "prepost") {
	logMsg("PrePost: " . get_query_string('t'), $logfile);

	$pe_id = get_query_string('id'); // procedure id
	$sess_id = get_query_string('sess_id'); // current session number
	$_SESSION['pe_id' . $pe_id]['session_type' . $sess_id] = strtoupper(get_query_string('t'));
	$goto = "procedures.php?m=managesurveys&id=$pe_id&sess_id=$sess_id";
} else if($mode == "add_proc_to_temp") {
	$indx = get_query_string('i');  // index into survey array
	$pid = get_query_string('pid');  //  proc id
	$pe_id = get_query_string('id'); // procedure id   WTH is this????
	$org_id = get_query_string('org_id'); // current org id

	logMsg("Functions.php: add_proc_to_temp: org_id: $org_id procID: $pid indx: $indx", $logfile);
	// add it to the temp list
	$arr_add_procs = $_SESSION['arr_add_procs'];
	logMsg("Items in temp array before: " . count($arr_add_procs), $logfile);
	$arr_add_procs[] = $pid;
	logMsg("Items in temp array after: " . count($arr_add_procs), $logfile);
	$_SESSION['arr_add_procs'] = $arr_add_procs;

	//   NOT SURE WE NEED THIS NOW
	// take it out of the all survey list
	$arr_all_procs = $_SESSION['arr_all_procs'];
	logMsg("Items in procs list before: " . count($arr_all_procs), $logfile);
	$arr_all_procs[$indx]['added'] = true;
	logMsg("Items in procs list after: " . count($arr_all_procs), $logfile);
	$_SESSION['arr_all_procs'] = $arr_all_procs;

	$goto = "organisations.php?m=procadd&id=$org_id";
} else if($mode == "delete_proc_from_temp") {
	$indx = get_query_string('t');  // index into temp array
	$pid = get_query_string('pid');  // proc id
	$org_id = get_query_string('id'); // org id
	logMsg("delete_proc_from_temp: pe_id: $pe_id ProcID: $pid indx: $indx", $logfile);

	//  move all the elements up to fill in hole where deleted one was
	$arr_add_procs = $_SESSION['arr_add_procs'];

	//unset($arr_add_surveys[$indx]);

	for($i = $indx; $i < (count($arr_add_procs) - 1); $i++) {
		$arr_add_procs[$i]['id'] = $arr_add_procs[$i + 1]['id'];
		logMsg("i: $i - " . $arr_add_procs[$i]['id'], $logfile);
	}
	// delete the last element of the array
	unset($arr_add_procs[count($arr_add_procs) - 1]);
	// write it back to session
	$_SESSION['arr_add_procs'] = $arr_add_procs;

	$arr_all_procs = $_SESSION['arr_all_procs'];
	// look for this proc by id and mark it as available (added=false)
	for($i = 0; $i < count($arr_all_procs); $i++) {
		logMsg("delete_survey_from_temp: $i arrayValue: " . $arr_all_procs[$i]['id'] . " PID: $pid", $logfile);
		if($arr_all_procs[$i]['id'] == $pid) {
			logMsg("found it: $i", $logfile);
			$arr_all_procs[$i]['added'] = false;
		}
	}
	$_SESSION['arr_all_procs'] = $arr_allprocs;
	$goto = "organisations.php?m=procadd&id=$org_id";
} else if($mode == "add_selected_procs") {
	$arr_add_procs = $_SESSION['arr_add_procs'];
	logMsg("add_selected_procs: org_id: $org_id ", $logfile);
	logMsg("add_selected_procs: items in arr_add_procs: " . count($arr_add_procs), $logfile);
	for($i = 0; $i < count($arr_add_procs); $i++) {
		$plid = uniqid();
		$userid = $_COOKIE['user_id'];
		$sql = "INSERT INTO $TBLPROCLICENSES
              SET id='$plid',
                  c_organization = '$org_id',
                  c_procedure = '" . $arr_add_procs[$i] . "',
                  dateCreated=NOW(),
                  dateModified=NOW(),
                  createdBy='$userid'";
		logMsg("AddSelectedProcs: $sql", $logfile);
		dbi_query($sql);
	}
	$goto = "organisations.php?m=overview&id=$org_id";
} else if($mode == "delete_orgproc") {
	$proc_id = get_query_string('pid');
	$sql = "DELETE FROM $TBLPROCLICENSES 
           WHERE c_organization='$org_id'
           AND c_procedure='$proc_id'";
	dbi_query($sql);
	logMsg("DeleteOrgProc: $sql", $logfile);
	$goto = "organisations.php?m=orgproc&id=$org_id";
}

header("Location: $goto");
exit();

?> 

