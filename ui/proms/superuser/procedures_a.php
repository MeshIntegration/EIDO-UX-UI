<?php
// ***************************************
// superuser/procedures_a.php
// 2018 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// ***************************************

require_once '../utilities.php';
session_start();
$logfile = "wel.log";
$debug = false;

$mode = get_query_string('m');
$id = get_query_string('id');

logMsg("Procedures_a: $mode $id",$logfile);

if ($mode=="add")
{
   $c_description = $_POST['c_description'];
   $c_procedureId = $_POST['c_procedureId'];
   $c_displayName = $_POST['c_displayName'];
   $id = uniqid();

   $sql = "INSERT INTO  app_fd_pro_procEpisodes
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

   $sql = "UPDATE app_fd_pro_procEpisodes
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
   $sess_id = 1;  //  TILL THIS GETS FIXED
   $session_type = $_SESSION['session_type'];;  // PRE or POST
   $number_of_sessions = $_POST['quantity'];;  // Up to 6 
   $session_name = $_POST['sessionName'];
   $session_delay = $_POST['session_delay']; 
   if ($sess_id==1)
      $delay_str = "";
   else
      $delay_str = "c_session".$sess_id."Delay='$session_delay',";

   $sql = "UPDATE app_fd_pro_procEpisodes
          SET c_prePost".$sess_id."='$session_type',
              $delay_str 
              c_session".$sess_id."Name='$session_name',
              c_session".$sess_id."Survey1='".$_SESSION['sessionSurvey1']."', 
              c_session".$sess_id."Survey2='".$_SESSION['sessionSurvey2']."', 
              c_session".$sess_id."Survey3='".$_SESSION['sessionSurvey3']."', 
              c_session".$sess_id."Survey4='".$_SESSION['sessionSurvey4']."', 
              c_session".$sess_id."Survey5='".$_SESSION['sessionSurvey5']."' 
          WHERE id='$id'";
logMsg("updateproc: $sql",$logfile);
   dbi_query($sql);
   header ("Location: procedures.php?m=add");
   exit();

}
?>
