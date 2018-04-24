<?php
// ***************************************
// admin/users_a.php
// 2017-2018 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************
require_once '../utilities.php';
require_once "../alert_intruders.php";
session_start();
$logfile = "admin.log";
$mode = get_query_string('m');
$id = get_query_string('id');
logMsg("Users_a: mode: $mode - ID: $id",$logfile);
if ($mode=="delete")
{
   $sql = "UPDATE dir_user
           SET active=0,
           c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   logMsg("SU DELETE: $sql",$logfile);
      header("Location: users.php");
      exit();


 /*  $sql = "DELETE FROM dir_user_role WHERE userId='$id'";
   dbi_query($sql);
   $sql = "DELETE FROM dir_user_group WHERE userId='$id'";
   dbi_query($sql);
   $sql = "DELETE FROM $TBLSURGEONS WHERE c_userId='$id'";
   dbi_query($sql);
 */
}
else if ($mode=="add")
{


   //  check for required fields and formats here
   if ($firstname=="")
      $_SESSION['add_firstname_error']=true; else $_SESSION['add_firstname_error']=false;
   if (!preg_match("/^[a-zA-Z]*$/",$firstname))
      $_SESSION['add_firstname_format_error']=true; else $_SESSION['add_firstname_format_error']=false;
   if ($lastname=="")
      $_SESSION['add_lastname_error']=true; else $_SESSION['add_lastname_error']=false;
   if (!preg_match("/^[a-zA-Z]*$/",$lastname))
      $_SESSION['add_lastname_format_error']=true; else $_SESSION['add_lastname_format_error']=false;
   if ($email=="")
      $_SESSION['add_email_error']=true; else $_SESSION['add_email_error']=false;
   if ($email<>"" && !filter_var($email, FILTER_VALIDATE_EMAIL))
      $_SESSION['add_bad_email_error']=true; else $_SESSION['add_bad_email_error']=false;
   if (!is_email_unique($email))
      $_SESSION['add_email_duplicate_error']=true; else $_SESSION['add_email_duplicate_error']=false;
   if ($is_surgeon=="1")
   {
      if ($gmc_number=="")
         $_SESSION['add_gmc_number_error']=true; else $_SESSION['add_gmc_number_error']=false;
      if (!preg_match("/^[0-9]*$/",$gmc_number))
         $_SESSION['add_gmc_number_format_error']=true; else $_SESSION['add_gmc_number_format_error']=false;
      if (strlen($gmc_number)<6 || strlen($gmc_number)>7)
         $_SESSION['add_gmc_number_length_error']=true; else $_SESSION['add_gmc_number_length_error']=false;
      if (!is_gmc_number_unique($gmc_number))
         $_SESSION['add_gmc_number_duplicate_error']=true; else $_SESSION['add_gmc_number_duplicate_error']=false;
   }
   else
   {
      $_SESSION['add_gmc_number_error']=false;
      $_SESSION['add_gmc_number_format_error']=false;
      $_SESSION['add_gmc_number_length_error']=false;
      $_SESSION['add_gmc_number_duplicate_error']=false;
   }
   if ($_SESSION['add_firstname_error'] || $_SESSION['add_lastname_error'] || $_SESSION['add_gmc_number_error'] ||
       $_SESSION['add_bad_email_error'] || $_SESSION['add_email_error'] || $_SESSION['add_email_duplicate_error'] ||
       $_SESSION['add_firstname_format_error'] || $_SESSION['add_lastname_format_error'] ||
       $_SESSION['add_gmc_number_format_error'] || $_SESSION['add_gmc_number_length_error'] || 
       $_SESSION['add_gmc_number_duplicate_error'])
   {
      $_SESSION['add_firstname']=$firstname;
      $_SESSION['add_lastname']=$lastname;
      $_SESSION['add_email']=$email;
      $_SESSION['add_gmc_number']=$gmc_number;
      $_SESSION['add_is_surgeon']=$is_surgeon;
      $_SESSION['add_is_admin']=$is_admin;
      header("Location: users.php?m=add");
      exit();
   }

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
               isSurgeon='$is_surgeon',
               password='password'";
   logMsg("ADD SITE OR STAFF USER: $sql",$logfile);
   dbi_query($sql);
      if ($is_surgeon=="1")
   {
      $id = uniqid();
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
   logMsg("ADD SITE OR STAFF USER: $sql",$logfile);

   // Group
   if ($is_admin=="1")
   {
      $group_str="sitedivadmins";
   }
   else
   {
      $group_str="staff";
   }
   $sql = "INSERT INTO dir_user_group
           SET groupId='$group_str',
               username='$email',
               userId='$admin_user_id'";
   dbi_query($sql);
   logMsg("ADD SITE OR STAFF USER: $sql",$logfile);
   unset($_SESSION['add_firstname']);
   unset($_SESSION['add_lastname']);
   unset($_SESSION['add_email']);
   unset($_SESSION['add_gmc_number']);
   unset($_SESSION['add_is_surgeon']);
   unset($_SESSION['add_is_admin']);
header("Location: users.php");
exit();

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
               isSurgeon='$is_surgeon',
               gmc_number='$gmc_number'
           WHERE id='$id'";
   dbi_query($sql);
   logMsg("UPDATE SITE OR STAFF USER: $sql",$logfile);
   // UPDATE Surgeon
   if ($is_surgeon=="1")
   {
      $fullname = "$firstname $lastname";
      $sql = "UPDATE $TBLSURGEONS
              SET c_userId='$id',
                  c_surgeonName='$fullname',
                  c_gmcNumber='$gmc_number',
                  dateModified=NOW(),
                  modifiedBy='$user_id',
                  modifiedByName = '$user_fullname'
              WHERE c_userId='$id'";
      dbi_query($sql);
      logMsg("UPDATE SURGEON: $sql",$logfile);
   }
   // DETERMINE Group
   if ($is_admin=="1")
         $group_str="sitedivadmins";
   else
         $group_str="staff";
   // UPDATE Group
      $sql = "UPDATE dir_user_group
              SET groupId='$group_str',
                  username='$email'
              WHERE userId='$id'";
   dbi_query($sql);
   logMsg("UPDATE SITE OR STAFF USER: $sql",$logfile);
   
   // UPDATE Role
   $sql = "UPDATE dir_user_role
           SET roleId='ROLE_USER',
               username='$email'
           WHERE userId='$id'";
   dbi_query($sql);
   logMsg("UPDATE SITE OR STAFF USER: $sql",$logfile);
} 
header("Location: users.php");
exit();
?>
