<?php
// *********************************
// login_a.php
// Copyright 2018, MindStreams LLC
// WEL 1/18/18
// *********************************

include "utilities.php";
$logfile = "wel.log";

$username = $_POST['username'];
$password= $_POST['password'];

$sql = "SELECT u.*, ur.*
        FROM dir_user u, dir_user_role ur
        WHERE u.username='$username'
        AND u.password='$password'
        AND u.id=ur.userid";
logMsg($sql,$logfile);

$GetQuery=dbi_query($sql);
if ($GetQuery->num_rows==0)
{
   $_SESSION['error_msg']="Incorrect email or password. Please try again.";
   header("Location: login.php");
   exit();
}
else
{
   $qryResult=$GetQuery->fetch_assoc();
   $user_id=$qryResult['id'];
   is_setcookie("user_id", $user_id, 0, "/", $cookie_domain);

   $fname = $qryResult['firstName'];
   $lname = $qryResult['lastName'];
   $initials = strtoupper(substr($fname,0,1).substr($lname,0,1));
   is_setcookie("user_initials", $initials, 0, "/", $cookie_domain);

   if (strtoupper($qryResult['roleId'])=="ROLE_ADMIN")
   {
      logMsg("$user_id - Logged in as SUPERUSER",$logfile);
      is_setcookie("user_role", "SUPERUSER", 0, "/", $cookie_domain);
      header("Location: /ui/superuser/users.php");
      exit();
   }

   // Not a SuperUser so now figure out what group they are in
   $sql="SELECT * FROM dir_user_group WHERE userId='$user_id'";
   $GetQuery=dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();

   if (strtoupper($qryResult['groupId'])=="ADMIN")
   {
      is_setcookie("user_role", "ADMIN", 0, "/", $cookie_domain); 
      header("Location: /ui/admin/users.php");
      exit();
   }
   else
   {
      is_setcookie("user_role", "USER", 0, "/", $cookie_domain); 
      header("Location: /ui/patient/patients.php");
      exit();
   }
   exit();
}
?>
