<?php
// *********************************
// login_a.php
// Copyright 2018, MindStreams LLC
// WEL 1/18/18
// 3/17/18
//   we use the dir_user_group table 
//   don't use the user_role table any more
//   Group info
//     eidoadmins - SuperUser dashboard
//     sitedivadmins - Admin or Patient Dashboard
//     staff or surgeon - Patient Dashboard
// *********************************

include "utilities.php";
$logfile = "admin.log";
session_start();

$username = $_POST['username'];
$password= $_POST['password'];

$sql = "SELECT u.*, ug.*
        FROM dir_user u, dir_user_group ug
        WHERE u.username='$username'
        AND u.id=ug.userid";
logMsg($sql,$logfile);

$GetQuery=dbi_query($sql);
if ($GetQuery->num_rows==0)
{
   $_SESSION['error_msg']="Incorrect email or password. Please try again.";
   logMsg("Incorrect email or password. $username %password - back to login.php", $logfile);
   header("Location: login.php");
   exit();
}
else
{
   if (0 && !password_verify($password, $hash)) {
      /* Invalid */
      $_SESSION['error_msg']="Incorrect email or password. Please try again.";
      logMsg("Incorrect email or password. $username %password - back to login.php", $logfile);
      header("Location: login.php");
      exit;
   }


   $qryResult=$GetQuery->fetch_assoc();
   $user_id=$qryResult['id'];
   is_setcookie("user_id", $user_id, 0, "/", $cookie_domain);

   $fname = $qryResult['firstName'];
   $lname = $qryResult['lastName'];
   $fullname = "$fname $lname";
   $initials = strtoupper(substr($fname,0,1).substr($lname,0,1));
   is_setcookie("user_fullname", $fullname, 0, "/", $cookie_domain);
   is_setcookie("user_initials", $initials, 0, "/", $cookie_domain);

   if (strtolower($qryResult['groupId'])=="eidoadmins")
   {
      logMsg("$user_id - Logged in as SUPERUSER",$logfile);
      is_setcookie("user_role", "SUPERUSER", 0, "/", $cookie_domain);
      header("Location: /ui/verify/superuser/users.php");
      exit();
   }

   // Not a SuperUser so now figure out what group they are in
   //$sql="SELECT * FROM dir_user_group WHERE userId='$user_id'";
   //$GetQuery=dbi_query($sql);
   //$qryResult=$GetQuery->fetch_assoc();

   if (strtolower($qryResult['groupId'])=="sitedivadmins")
   {
      // determine what organisation they are with
      // and set a cookie for the ORG ID
      $sql = "SELECT id 
              FROM app_fd_ver_organizations 
              WHERE c_email='$email'";
      $GetQuery=dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $org_id = $qryResult['id'];
      is_setcookie("org_id", $org_id, 0, "/", $cookie_domain); 

      is_setcookie("user_role", "ADMIN", 0, "/", $cookie_domain); 
      header("Location: /ui/verify/admin/users.php");
      exit();
   }
   else
   {
      is_setcookie("user_role", "USER", 0, "/", $cookie_domain); 
      header("Location: /ui/verify/patient/patients.php");
      exit();
   }
   exit();
}
?>
