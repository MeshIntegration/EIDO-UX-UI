<?php
// ***************************************
// superuser/ajax/set_session_data.php
// 2017 Copyright, Mesh Integration LLC
// 1/21/18 - SD
// use to store session data
// ***************************************
include "../../utilities.php";
session_start();
if(isset($_POST['type'])){
   $type = $_POST['type'];
}else{
   exit();
}
logMsg("AJAX set_session_data.php: SessNum: ".$_POST['sess_id'], "superuser.log");
logMsg("AJAX set_session_data.php: NumOfSess: ".$_POST['num_sess'], "superuser.log");
$pe_id=$_POST['pe_id'];
$num_sess=$_POST['num_sess'];

if ($_POST['num_sess'] <>"")
{
   $sql = "UPDATE $TBLPROCEPISODES
          SET c_numberOfSessions = '$num_sess'
          WHERE id='$pe_id'";
   dbi_query($sql);
}
if($type == "procedures_save"){
   if(isset($_POST['sess_id']) && $_POST['sess_id']<>""){
      $_SESSION['session_number'] = $_POST['sess_id'] ; 
      echo $_POST['pe_id'];
   }
}
if($type == "sessionname_save"){
   if(isset($_POST['pe_id']) && !empty($_POST['pe_id'])){
      $pe_id = $_POST['pe_id'];
      $sess_name = $_POST['sess_name'];
      $sess_id = $_POST['sess_id'];
      $_SESSION['pe_id'.$pe_id]['sessionName'.$sess_id] = $sess_name;
   }
}
if($type == "numberofsession_save"){
   if(isset($_POST['pe_id']) && !empty($_POST['pe_id'])){
      $pe_id = $_POST['pe_id'];
      $noofsession = $_POST['noofsession'];
      $_SESSION['pe_id'.$pe_id]['numberofsession'] = $noofsession;
   }
}

exit();
?>
