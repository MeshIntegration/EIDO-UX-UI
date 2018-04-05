<?php

require_once "../functions.php";
session_start();
if(isset($_POST['looking_for']) && isset($_POST['search_query'])){
   $looking_for = $_POST['looking_for'];
   $search_query = $_POST['search_query'];
   if(!empty($search_query)){
      $response = get_search_suggestion($looking_for,$search_query);
      if (count($response)){
         if ($looking_for=="patient"){
            $data = array_map(function($sub){
               return ($sub['c_surname']." ".$sub['c_firstName']);
            },$response);
         }else if($looking_for=="procedure"){
            $data = array_map(function($sub){
               return $sub['c_description'];
            },$response);            
         }else if($looking_for=="surgeon"){
            $data = array_map(function($sub){
               return $sub['c_surgeonName'];
            },$response);
         }       
      }
      if(is_array($data)){
         $data = array_unique($data);
      }
      echo json_encode($data);
   }
}
exit();

?>
