<?php
// ***************************************
// patient/ajax/get_address_by_postcode.php
// 2018 Copyright, Mesh Integration LLC
// 02/04/18 - SD
// use to get address by postcode
// ***************************************

require_once "../functions.php";
session_start();

if(isset($_POST['postcode']) && !empty($_POST['postcode'])){
   $postcode = $_POST['postcode'];
   if(!empty($postcode)){
      $response = get_address_by_postcode($postcode);
      if (!is_array($response)){
         echo $response;
      }
   }
}
exit();
?>

