<?php
// ***************************************
// superuser/ajax/set_session_data.php
// 2017 Copyright, Mesh Integration LLC
// 1/21/18 - SD
// use to store session data
// ***************************************
session_start();
if(isset($_POST['type'])){
   $type = $_POST['type'];
}else{
   exit();
}

if($type == "procedures_save"){
   if(isset($_POST['sess_id'])){
      $_SESSION['sess_id'] = $_POST['sess_id'] ; 
   }
}
exit();
?>
