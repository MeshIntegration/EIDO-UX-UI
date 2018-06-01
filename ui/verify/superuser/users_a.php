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
    //$password = $_POST['password'];

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
    if (!is_email_unique($email, $id))
        $_SESSION['add_email_duplicate_error'] = true; else $_SESSION['add_email_duplicate_error'] = false;
    //if ($password == "")
        //$_SESSION['add_password_error'] = true; else $_SESSION['add_password_error'] = false;
    if ($_SESSION['add_firstname_error'] || $_SESSION['add_lastname_error'] ||
        $_SESSION['add_bad_email_error'] || $_SESSION['add_email_error'] || $_SESSION['add_email_duplicate_error'] ||
        $_SESSION['add_firstname_format_error'] || $_SESSION['add_lastname_format_error']) {
            $_SESSION['add_firstname'] = $firstname;
            $_SESSION['add_lastname'] = $lastname;
            $_SESSION['add_email'] = $email;
            //$_SESSION['add_password'] = $password;
            header("Location: users.php?m=update&id=$id");
            exit();
    }

    $sql = "UPDATE dir_user
           SET firstName=" . escapeQuote($firstname) . ",
               lastName=" . escapeQuote($lastname) . ",
               email=" . escapeQuote($email) . "
           WHERE id='$id'";
    dbi_query($sql);
    unset($_SESSION['add_firstname']);
    unset($_SESSION['add_lastname']);
    unset($_SESSION['add_email']);
    logMsg("UPDATE: $sql", $logfile);
} else if ($mode=="gotoadd") {
    $_SESSION['add_firstname_error']=false;
    $_SESSION['add_firstname_format_error']=false;
    $_SESSION['add_lastname_error']=false;
    $_SESSION['add_lastname_format_error']=false;
    $_SESSION['add_bad_email_error']=false;
    $_SESSION['add_email_error']=false;
    $_SESSION['add_email_duplicate_error']=false;
    $_SESSION['add_password_error']=false;
    $_SESSION['add_password_match_error']=false;
    unset($_SESSION['add_firstname']);
    unset($_SESSION['add_lastname']);
    unset($_SESSION['add_email']);
    unset($_SESSION['add_password']);
   header("Location: users.php?m=add");
   exit();
} else if ($mode=="gotoupdate") {
    $_SESSION['add_firstname_error']=false;
    $_SESSION['add_firstname_format_error']=false;
    $_SESSION['add_lastname_error']=false;
    $_SESSION['add_lastname_format_error']=false;
    $_SESSION['add_bad_email_error']=false;
    $_SESSION['add_email_error']=false;
    $_SESSION['add_email_duplicate_error']=false;
    unset($_SESSION['add_firstname']);
    unset($_SESSION['add_lastname']);
    unset($_SESSION['add_email']);
    header("Location: users.php?m=update&id=$id");
    exit();
} else if ($mode=="userreset") {
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
   $firstname = $_POST['firstname'];
   $lastname = $_POST['lastname'];
   $email = $_POST['email'];
   $password = $_POST['password'];
   $password2 = $_POST['password2'];

   //  check for required fields and formats here
   if ($firstname=="") {
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

   // send email to the new user 
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", $firstname, $email_template);
   $email_template = str_replace("**HEADER**", "Welcome", $email_template);

   $content1 = "We have created an account for you in the EIDO Verify system. Here are your account credentials.<br /><br />
         Username: $email<br />
         Password: $password<br /><br />
         <a href='http://verify.eidosystems.com'>Click here to log into the EIDO Verify system</a><br /><br />";
         $email_template = str_replace("**CONTENT1**", $content1, $email_template);

   $content2 = "<p>We have created an account for you in the EIDO Verify system. Here are your account credentials.</p>
         <p>Username: $email<br />
         Password: $password</p>
         <p>Click the button below to log into the EIDO Verify systemi</p>";
         $email_template = str_replace("**CONTENT2**", $content2, $email_template);

   // set up the button
   $button_text = "Get Started";
   $email_template = str_replace("**BUTTONTEXT**", $button_text, $email_template);
   $button_url = "http://verify.eidosystems.com";
   $email_template = str_replace("**BUTTONURL**", $button_url, $email_template);

   // contnt3 after the button
   $content3="";
   $email_template = str_replace("**CONTENT3**", $content3, $email_template);

   $arr_email = array();
   $arr_email['mail_to']=$email;
   $arr_email['mail_to_name']="$firstname $lastname";
   $arr_email['bcc']="wayne@mindstreams.com";
   $arr_email['mail_from']=$verify_mail_from;
   $arr_email['mail_from_name']=$verify_mail_from_name;
   $arr_email['subject']="EIDO Verify Account Information";
   $arr_email['body']=$email_template;
   send_email($arr_email);
   
   unset($_SESSION['add_firstname']); 
   unset($_SESSION['add_lastname']); 
   unset($_SESSION['add_email']); 
   unset($_SESSION['add_password']); 
}
header("Location: users.php");
exit();

?>
