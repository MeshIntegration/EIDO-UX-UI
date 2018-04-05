<?php
// ***************************************
// admin/users_a.php
// 2017 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************

require_once '../utilities.php';
$logfile = "wel.log";
logMsg("Users_a: mode: $mode",$logfile);

$mode = get_query_string('m');
$id = get_query_string('id');
$firstName = $_POST['fname'];
$lastName = $_POST['lname'];
$email = $_POST['email'];
$is_surgeon = $_POST['is_surgeon'];
$is_admin = $_POST['is_admin'];
$gmc_number = $_POST['gmc_number'];

if ($mode=="update")
{
   $sql = "UPDATE dir_user
           SET firstName=".escapeQuote($firstName).",
               lastName=".escapeQuote($lastName).",
               email=".escapeQuote($email).",
               active=1,
               timeZone='0',
               id='$id',
               username='$email',
               gmc_number='$gmc_number'
           WHERE id='$id'";
   dbi_query($sql);
logMsg($sql,$logfile);

   $sql = "DELETE FROM dir_user_role WHERE userId='$id'";
   dbi_query($sql);
   $sql = "DELETE FROM dir_user_group WHERE userId='$id'";
   dbi_query($sql);
} 
else
{
   // INSERT
   $id = substr(strtolower($firstName),0,1).strtolower($lastName);
   // User
   $sql = "INSERT INTO dir_user
           SET firstName=".escapeQuote($firstName).",
               lastName=".escapeQuote($lastName).",
               email=".escapeQuote($email).",
               active=1,
               timeZone='0',
               id='$id',
               username='$email',
               gmc_number='$gmc_number'";
   dbi_query($sql);
}

// Role
$sql = "INSERT INTO dir_user_role
        SET roleId='ROLE_USER',
            userId='$id'";
dbi_query($sql);
logMsg($sql,$logfile);

// Group
if ($is_admin=="1")
   $group_str="admin";
else
   $group_str="staff";
$sql = "INSERT INTO dir_user_group
        SET groupId='$group_str',
            userId='$id'";
dbi_query($sql);
logMsg($sql,$logfile);

header("Location: users.php");
exit();

?>
