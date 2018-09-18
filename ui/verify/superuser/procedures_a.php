<?php
// ***************************************
// superuser/procedures_a.php
// 2018 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// ***************************************

require_once '../utilities.php';
require_once 'superuser_functions.php';
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

        if ($c_description=="")
           $_SESSION['description_error']=true;
        else
           $_SESSION['description_error']=false;

        if ($c_procedureId=="")
           $_SESSION['procedureid_error']=true;
        else
           $_SESSION['procedureid_error']=false;

        if ($c_displayName=="")
           $_SESSION['displayname_error']=true;
        else
           $_SESSION['displayname_error']=false;

        if ($_SESSION['description_error'] || $_SESSION['procedureid_error'] || $_SESSION['displayname_error']) {
           header("Location:procedures.php?m=add&id=$id");
           exit();
        }

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

} else if ($mode == "updateproc") {
   logMsg("---- UPDATEPROC (called from ManageSurveys button click) ------","wel.log");
   $pe_id = $_GET['id'];
   $sess_id = get_query_string('sess_id');
   $ms = get_query_string('ms');
   $number_of_sessions = $_POST['quantity'];;  // Up to 6
   $session_name = $_POST['sessionName'];
   $session_delay = $_POST['session_delay'];
   if ($sess_id>1)
      $delay_str = ", c_session".$sess_id."Delay='".$session_delay."'";
   else
      $delay_str = "";
   $varname_name = "c_session".$sess_id."Name";
   $sql = "UPDATE $TBLPROCEPISODES
           SET $varname_name='$session_name' $delay_str
           WHERE id='$pe_id'";
   logMsg($sql,"wel.log");
   dbi_query($sql);

// we got rid of Update Session button so this logic not needed
//   if ($_POST['updateproc']=="Update Procedure") {
      // Update Procedure button was clicked
      // clear out any old data in unused sessions
      for ($s=$number_of_sessions+1; $s<=$MAX_SURVEYS; $s++) {
         $varname_name = "c_session".$s."Name";
         $varname_delay = "c_session".$s."Delay";
         $varname_type = "c_prePost".$s;
         $sql = "UPDATE $TBLPROCEPISODES
                 SET  $varname_name = '',
                      $varname_delay = '',
                      $varname_type = ''
                 WHERE id='$pe_id'";
         dbi_query($sql);
logMsg("UpdateProc: $sql","wel.log");
      }
 
      // error check
      include "inc_proc_error_check.php";
      if ($error_flag)
         header("Location: procedures.php?m=msall&id=$pe_id&ms=all&sess_id=$sess_id");
      else
         header("Location: procedures.php?m=update&id=$pe_id");
	 exit();
//   } else {
//      // Update Session button was clicked
//      header("Location: procedures.php?m=managesurveys&gfdb=1&id=$pe_id");
//      exit();
//   }

} else if ($mode=="msall") {
      // MANAGE SURVEYS ALL
      $pe_id = $_GET['id'];
      $c_numberOfSessions =  $number_of_sessions = $_POST['quantity_all'];
      $sql = "UPDATE $TBLPROCEPISODES
              SET c_numberOfSessions='$c_numberOfSessions'
              WHERE id='$pe_id'";
      dbi_query($sql);

      $arr_proc_info=get_proc_info($pe_id);
      // loop over each session save name and delay (type gets saved whrn they click it)
      for ($s=1; $s<=$c_numberOfSessions; $s++) {
         $varname_name = "c_session".$s."Name";
         $value_name = $_POST[$varname_name];
         $varname_delay = "c_session".$s."Delay";
         $value_delay = $_POST[$varname_delay];
         if ($s==1)
            $delay_str="";
         else
            $delay_str = ", ".$varname_delay."=".escapeQuote($value_delay);
         $sql = "UPDATE $TBLPROCEPISODES
                 SET    $varname_name=".escapeQuote($value_name).$delay_str."
                 WHERE id = '$pe_id'";
logMsg("MSALL: ".$sql, $logfile);
         dbi_query($sql);
      }

      // clear out any old data in unused sessions
      for ($s=$c_numberOfSessions+1; $s<=$MAX_SURVEYS; $s++) {
         $varname_name = "c_session".$s."Name";
         $varname_delay = "c_session".$s."Delay";
         $varname_type = "c_prePost".$s;
         $sql = "UPDATE $TBLPROCEPISODES
                 SET  $varname_name = '',
                      $varname_delay = '',
                      $varname_type = ''
                 WHERE id='$pe_id'";
         dbi_query($sql);
logMsg("MSALL: $sql","wel.log");
      }
 
      // check the data that got saved
      include "inc_proc_error_check.php";
      if ($error_flag)
         header("Location: procedures.php?m=msall&id=$pe_id");
      else
         header("Location: procedures.php?m=update&id=$pe_id");
      exit();

} else if ($mode=="clearsession") {
   $goto = get_query_string('g');
   $_SESSION = array();
   header ("Location: procedures.php?m=$goto&id=$pe_id");
   exit();
}

?>
