<?php
// ***************************************
// admin/users_a.php
// 2017-2018 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
$logfile = "admin.log";

$mode = get_query_string('m');
$id = get_query_string('id');
logMsg("Users_a: mode: $mode - ID: $id",$logfile);

if ($mode=="delete")
{
   $sql = "DELETE FROM dir_user WHERE id='$id'";
   dbi_query($sql);

   $sql = "DELETE FROM dir_user_role WHERE userId='$id'";
   dbi_query($sql);

   $sql = "DELETE FROM dir_user_group WHERE userId='$id'";
   dbi_query($sql);

   $sql = "DELETE FROM $TBLSURGEONS WHERE c_userId='$id'";
   dbi_query($sql);
}


else if ($mode=="add")
{
   $firstname = $_POST['fname'];
   $lastname = $_POST['lname'];
   $email = $_POST['email'];
   $is_surgeon = $_POST['is_surgeon'];
   $is_admin = $_POST['is_admin'];
   $gmc_number = $_POST['gmc_number'];

   // INSERT
   $admin_user_id = uniqid();
   //  substr(strtolower($firstName),0,1).strtolower($lastName);
   // User
   $sql = "INSERT INTO dir_user
           SET firstName=".escapeQuote($firstname).",
               lastName=".escapeQuote($lastname).",
               email=".escapeQuote($email).",
               active=1,
               timeZone='0',
               id='$admin_user_id',
               username='$email',
               gmc_number='$gmc_number',
               password='password'";
   logMsg("ADD: $sql",$logfile);
   dbi_query($sql);

      if ($is_surgeon=="1")
   {
      $id=uniqid();
      $fullname = "$firstname $lastname";
      $sql = "INSERT INTO $TBLSURGEONS
              SET id='$id',
                  c_userId='$admin_user_id',
                  c_surgeonName='$fullname',
                  c_gmcNumber='$gmc_number',
                  dateCreated=NOW(),
                  dateModified=NOW(),
                  createdBy='$user_id',
                  createdByName = '$user_fullname'";
      dbi_query($sql);
      logMsg("ADD SURGEON: $sql",$logfile);
    }
      //  Role
      $sql = "INSERT INTO dir_user_role
              SET roleId='ROLE_USER',
                  username='$email',
                  userId='$admin_user_id'";
      dbi_query($sql);
      logMsg($sql,$logfile);

      // Group
      if ($is_admin=="1")
         $group_str="admin";
      else if ($is_surgeon=="1")
         $group_str="surgeon";
      else
         $group_str="staff";
      $sql = "INSERT INTO dir_user_group
              SET groupId='$group_str',
                  username='$email',
                  userId='$admin_user_id'";
      dbi_query($sql);
      logMsg($sql,$logfile);

}




else if ($mode=="update")
{
   $firstname = $_POST['fname'];
   $lastname = $_POST['lname'];
   $email = $_POST['email'];
   $is_surgeon = $_POST['is_surgeon'];
   $is_admin = $_POST['is_admin'];
   $gmc_number = $_POST['gmc_number'];

   // UPDATE User
   $sql = "UPDATE dir_user
           SET firstName=".escapeQuote($firstname).",
               lastName=".escapeQuote($lastname).",
               email=".escapeQuote($email).",
               active=1,
               timeZone='0',
               id='$id',
               username='$email',
               gmc_number='$gmc_number'
           WHERE id='$id'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // UPDATE Surgeon
   if ($is_surgeon=="1" || $is_admin=="0")
   {
      $fullname = "$firstname $lastname";
      $sql = "UPDATE $TBLSURGEONS
              SET c_userId='$id',
                  c_surgeonName='$fullname',
                  c_gmcNumber='$gmc_number',
                  dateModified=NOW(),
                  modifiedBy='$user_id',
                  modifiedByName = '$user_fullname'
              WHERE id='$id'";
      dbi_query($sql);
      logMsg($sql,$logfile);
   }

   // DETERMINE Group
   if ($is_admin=="1" || $is_surgeon=="0")
         $group_str="admin";
   else if ($is_admin=="0" || $is_surgeon=="1")
         $group_str="surgeon";
   else
         $group_str="staff";

   // UPDATE Group
      $sql = "UPDATE dir_user_group
              SET groupId='$group_str',
                  username='$email'
              WHERE userId='$id'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // UPDATE Role
   $sql = "UPDATE dir_user_role
           SET roleId='ROLE_USER',
               username='$email'
           WHERE userId='$id'";
   dbi_query($sql);
   logMsg($sql,$logfile);


} 

header("Location: users.php");
exit();

?>
