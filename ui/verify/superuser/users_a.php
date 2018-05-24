<?php
// ***************************************
// superuser/users_a.php
// 2018 Copyright, Mesh Integration LLC
// 02/19/18 - SD
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
session_start();
$logfile = "superuser.log";
$mode = get_query_string('m');
$id = get_query_string('id');

logMsg("Users_a: mode: $mode - ID: $id",$logfile);

if ($mode=="update") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //  check for required fields and formats here
    if ($firstname == "")
        $_SESSION['add_firstname_error'] = true; else $_SESSION['add_firstname_error'] = false;
    if (!preg_match("/^[a-zA-Z]*$/", $firstname))
        $_SESSION['add_firstname_format_error'] = true; else $_SESSION['add_firstname_format_error'] = false;
    if ($lastname == "")
        $_SESSION['add_lastname_error'] = true; else $_SESSION['add_lastname_error'] = false;
    if (!preg_match("/^[a-zA-Z']*$/", $lastname))
        $_SESSION['add_lastname_format_error'] = true; else $_SESSION['add_lastname_format_error'] = false;
    if ($email == "")
        $_SESSION['add_email_error'] = true; else $_SESSION['add_email_error'] = false;
    if ($email <> "" && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $_SESSION['add_bad_email_error'] = true; else $_SESSION['add_bad_email_error'] = false;
    if (!is_email_unique($email))
        $_SESSION['add_email_duplicate_error'] = true; else $_SESSION['add_email_duplicate_error'] = false;
    if ($password == "")
        $_SESSION['add_password_error'] = true; else $_SESSION['add_password_error'] = false;
    if ($_SESSION['add_firstname_error'] || $_SESSION['add_lastname_error'] ||
        $_SESSION['add_bad_email_error'] || $_SESSION['add_email_error'] || $_SESSION['add_email_duplicate_error'] ||
        $_SESSION['add_firstname_format_error'] || $_SESSION['add_lastname_format_error']) {
            $_SESSION['add_firstname'] = $firstname;
            $_SESSION['add_lastname'] = $lastname;
            $_SESSION['add_email'] = $email;
            $_SESSION['add_password'] = $password;
            header("Location: users.php?m=update&id=$id");
            exit();
    }
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $sql = "UPDATE dir_user
           SET firstName=" . escapeQuote($firstName) . ",
               lastName=" . escapeQuote($lastName) . ",
               email=" . escapeQuote($email) . "
           WHERE id='$id'";
    dbi_query($sql);
    logMsg("UPDATE: $sql", $logfile);
}
else if ($mode=="userreset") {
   //global $TBLPTEPISODES;
   $sql = "UPDATE dir_user
           SET c_pw_reset=1,
               c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   logMsg("USERRESET (password): $sql",$logfile);
} else if ($mode=="userdelete") {

    $sql = "DELETE FROM dir_user_group 
           WHERE userid='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM dir_user_role 
           WHERE userid='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM dir_user 
           WHERE id='$id'";
    dbi_query($sql);

   logMsg("USERDELETE: $sql",$logfile);   

   unset($_SESSION['add_firstname']); 
   unset($_SESSION['add_lastname']); 
   unset($_SESSION['add_email']); 
   unset($_SESSION['add_password']); 
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
else if ($mode=="add") {
logMsg("IN SUPERUSER ADD MODE",$logfile);
   $firstname = $_POST['firstname'];
   $lastname = $_POST['lastname'];
   $email = $_POST['email'];
   $password = $_POST['password'];
   $password2 = $_POST['password2'];

   //  check for required fields and formats here
   if ($firstname=="") {
logMsg("FIRSTNAME ERROR blank",$logfile);
      $_SESSION['add_firstname_error']=true; }
   else $_SESSION['add_firstname_error']=false;
  
   if (!preg_match("/^[a-zA-Z]*$/",$firstname))
      $_SESSION['add_firstname_format_error']=true; else $_SESSION['add_firstname_format_error']=false;
   if ($lastname=="")
      $_SESSION['add_lastname_error']=true; else $_SESSION['add_lastname_error']=false;
   if (!preg_match("/^[a-zA-Z']*$/",$lastname))
      $_SESSION['add_lastname_format_error']=true; else $_SESSION['add_lastname_format_error']=false;
   if ($email=="")
      $_SESSION['add_email_error']=true; else $_SESSION['add_email_error']=false;
   if ($email<>"" && !filter_var($email, FILTER_VALIDATE_EMAIL))
      $_SESSION['add_bad_email_error']=true; else $_SESSION['add_bad_email_error']=false;
   if (!is_email_unique($email))
      $_SESSION['add_email_duplicate_error']=true; else $_SESSION['add_email_duplicate_error']=false;
    if ($password == "")
        $_SESSION['add_password_error'] = true; else $_SESSION['add_password_error'] = false;
    if ($password <> $password2)
        $_SESSION['add_password_match_error'] = true; else $_SESSION['add_password_match_error'] = false;
   if ($_SESSION['add_firstname_error'] || $_SESSION['add_lastname_error'] ||
       $_SESSION['add_bad_email_error'] || $_SESSION['add_email_error'] || 
       $_SESSION['add_email_duplicate_error'] || $_SESSION['add_password_error'] ||
       $_SESSION['add_password_match_error'] || $_SESSION['add_firstname_format_error'] || 
       $_SESSION['add_lastname_format_error']) {
           $_SESSION['add_firstname']=$firstname;
           $_SESSION['add_lastname']=$lastname;
           $_SESSION['add_email']=$email;
           $_SESSION['add_password']=$password;
           header("Location: users.php?m=add");
           exit();
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
           SET firstName=".escapeQuote($firstname).",
               lastName=".escapeQuote($lastname).",
               email=".escapeQuote($email).",
	       uipassword='".$hash."',
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

   unset($_SESSION['add_firstname']); 
   unset($_SESSION['add_lastname']); 
   unset($_SESSION['add_email']); 
   unset($_SESSION['add_password']); 
}
header("Location: users.php");
exit();

?>
