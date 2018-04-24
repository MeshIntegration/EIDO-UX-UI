<?php
// ***************************************
// superuser/users_a.php
// 2017 Copyright, Mesh Integration LLC
// 02/19/18 - SD
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
session_start();
$logfile = "superuser.log";
$mode = get_query_string('m');
$id = get_query_string('id');

logMsg("Users_a: mode: $mode - ID: $id",$logfile);

if ($mode=="update")
{
   $sql = "UPDATE dir_user
           SET firstName=".escapeQuote($firstName).",
               lastName=".escapeQuote($lastName).",
               email=".escapeQuote($email)."
           WHERE id='$id'";
   dbi_query($sql);
   logMsg("UPDATE: $sql",$logfile);
} 
else if ($mode=="userreset")
{
   //global $TBLPTEPISODES;
   $sql = "UPDATE dir_user
           SET c_pw_reset=1,
               c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   logMsg("PWRESET: $sql",$logfile);
}
else if ($mode=="userdelete")
{
   $sql = "UPDATE dir_user
           SET active=0,
           c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   logMsg("DELETE: $sql",$logfile);   
}

/* *******************API STUFF COMMENTED OUT*********************************************

   $requestParam = array('id' => $id,
                         'firstName' => $firstName,
                         'lastName' => $lastName,
                         'email' => $email,
                         'username' => $email);
   
   logMsg("Supersuer: Users: Add User: ".$BASE_URL."form/load/ug07Proms/", $logfile);
   //$loginas="admin";
   $resp = getCurlResponse($BASE_URL."form/store/ug07Proms/userProvisioning/".$id, $requestParam, 1, 
                           "POST", "DEFAULT", "OBJECT", $loginas);
   $id = $resp->id;
   logMsg("New Id: ".$id, $logfile);
echo $resp;
echo "<br />";
exit();
*************************************************************************  */
else
{
   $firstname = $_POST['firstname'];
   $lastname = $_POST['lastname'];
   $email = $_POST['email'];
   $password = $_POST['password'];
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
   if ($_SESSION['add_firstname_error'] || $_SESSION['add_lastname_error'] ||
       $_SESSION['add_bad_email_error'] || $_SESSION['add_email_error'] || $_SESSION['add_email_duplicate_error'] ||
       $_SESSION['add_firstname_format_error'] || $_SESSION['add_lastname_format_error'])
   {
      $_SESSION['add_firstname']=$firstname;
      $_SESSION['add_lastname']=$lastname;
      $_SESSION['add_email']=$email;
      $_SESSION['add_password']=$password;
   }


   $id = uniqid();
   $hash = password_hash($password, PASSWORD_BCRYPT);
   if (!password_verify($password, $hash)) {
      /* Invalid hash generation*/
      header("Location:".$_SERVER['HTTP_REFERER']);
      exit;
   }

   // INSERT

   $sql = "INSERT INTO dir_user
           SET firstName=".escapeQuote($firstName).",
               lastName=".escapeQuote($lastName).",
               email=".escapeQuote($email).",
	       password='".$hash."',
               active='1',
               timeZone='0',
               id='$id',
               c_dateModified=NOW(),
               username='$email'";
   dbi_query($sql);
   logMsg("ADD: $sql",$logfile);

   $sql = "INSERT INTO dir_user_role
           SET roleId='ROLE_ADMIN',
               userId='$id',
               username='$email'";
   dbi_query($sql);
   logMsg("ADD: $sql",$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId='eidoadmins',
               userId='$id',
	       username='$email'";
   dbi_query($sql);
   logMsg("ADD: $sql",$logfile);

}
header("Location: users.php");
exit();

?>
