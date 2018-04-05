<?php
// ***************************************
// superuser/ajax/validate_data.php
// 2017 Copyright, Mesh Integration LLC
// 02/20/18 - SD
// use to validate user data
// ***************************************

require_once '/var/www/html/ui/verify/utilities.php';

$logfile = "validation_data.log";
session_start();
if(isset($_POST['type'])){
   $type = $_POST['type'];
}else{
   exit();
}
if($type == "validate_email"){
   if(isset($_POST['email'])){
      $username = $_POST['email'] ; 
      $sql = "SELECT u.*, ur.*
        FROM dir_user u, dir_user_role ur
        WHERE u.username='$username'
        AND u.id=ur.userid";

      $GetQuery=dbi_query($sql);
      if ($GetQuery->num_rows==0){
         echo false;
      }else{ 
         echo true; 
      }
   }
}

exit();
?>

