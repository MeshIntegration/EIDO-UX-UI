<?php
// ***************************************
// patient/ajax/set_proc_id_data.php
// 2018 Copyright, Mesh Integration LLC
// 3/28/18 - SD
// use to set proc_id in session data
// ***************************************
include "../../utilities.php";
$logfile="patient.log";
session_start();

if(isset($_POST['type'])){
   $type = $_POST['type'];
}else{
   exit();
}
logMsg("AJAX set_proc_id_data.php: ProcId: ".$_POST['proc_id'], $logfile);
$proc_id=$_POST['proc_id'];

if($type == "proc_id"){
   if(isset($_POST['proc_id']) && $_POST['proc_id']<>""){
      $_SESSION['proctl_proc_id'] = $_POST['proc_id'] ;
      //echo $_POST['pe_id'];
   }
}
exit();
?>
