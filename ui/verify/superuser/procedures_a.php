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

logMsg("Procedures_a: $mode $id",$logfile);

if ($mode=="add")
{
   $c_description = $_POST['c_description'];
   $c_procedureId = $_POST['c_procedureId'];
   $c_displayName = $_POST['c_displayName'];
   $id = uniqid();

   $sql = "INSERT INTO  $TBLPROCEPISODES
           SET id='$id',
               c_description=".escapeQuote($c_description).",
               c_procedureId=".escapeQuote($c_procedureId).",
               c_displayName=".escapeQuote($c_displayName).",
               dateModified=NOW(),
               dateCreated=NOW()";
   dbi_query($sql);
   if ($debug) logMsg($sql,$logfile);
   
   header ("Location: procedures.php?m=add");
   exit();
}
else if ($mode=="update")
{
   $c_description = $_POST['c_description'];
   $c_procedureId = $_POST['c_procedureId'];
   $c_displayName = $_POST['c_displayName'];

   $sql = "UPDATE $TBLPROCEPISODES
           SET id='$id',
               c_description=".escapeQuote($c_description).",
               c_procedureId=".escapeQuote($c_procedureId).",
               c_displayName=".escapeQuote($c_displayName).",
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   if ($debug) logMsg($sql,$logfile);

   header ("Location: procedures.php?m=add");
   exit();
}
else if ($mode=="updateproc")
{
   $pe_id = $_GET['id'];
   //   $sess_id = $_GET['sess_id'];  //  TILL THIS GETS FIXED
   $id = $_GET['id'];
   $session_type = $_SESSION['session_type'];;  // PRE or POST
   $number_of_sessions = $_POST['quantity'];;  // Up to 6 
   $session_name = $_POST['sessionName'];
   $session_delay = $_POST['session_delay']; 
   if ($sess_id==1)
      $delay_str = "";
   else
      $delay_str = "c_session".$sess_id."Delay='$session_delay',";
   
   for($total_session=1;$total_session<=$MAX_SESSIONS;$total_session++){
      //reset all values
      $session_name = "";
      $session_type = "";
      $sess_id = $total_session ;
             
      if($total_session <= $number_of_sessions && is_array($_SESSION["pe_id".$pe_id]['sess_id'.$sess_id])){
         $arr_survey_info = $_SESSION["pe_id".$pe_id]['sess_id'.$sess_id];
      }else{
         $arr_survey_info = array("","","","","",""); 
      }
      if($total_session <= $number_of_sessions && isset($_SESSION["pe_id".$pe_id]['sessionName'.$sess_id]) && !empty($_SESSION["pe_id".$pe_id]['sessionName'.$sess_id])){
         $session_name = $_SESSION["pe_id".$pe_id]['sessionName'.$sess_id];
      }else{
         if ($total_session <= $number_of_sessions){
            echo $session_name = "Session ".$total_session;
	 }
      }
      if($total_session <= $number_of_sessions && isset($_SESSION["pe_id".$pe_id]['session_type'.$sess_id]) && !empty($_SESSION['pe_id'.$pe_id]['session_type'.$sess_id])){ 
         $session_type = $_SESSION['pe_id'.$pe_id]['session_type'.$sess_id];
      }else{
         $session_type = "";
      }
      $sql = "UPDATE $TBLPROCEPISODES
          SET c_prePost".$sess_id."='".$session_type."',
              c_session".$sess_id."Name='".$session_name."',
              c_session".$sess_id."Survey1='".$arr_survey_info[0]."', 
              c_session".$sess_id."Survey2='".$arr_survey_info[1]."', 
              c_session".$sess_id."Survey3='".$arr_survey_info[2]."', 
              c_session".$sess_id."Survey4='".$arr_survey_info[3]."', 
              c_session".$sess_id."Survey5='".$arr_survey_info[4]."' 
          WHERE id='$id';";
      logMsg("updateproc: $sql",$logfile);
      dbi_query($sql);
    }
   header ("Location: procedures.php?m=add");
   exit();

}
?>
