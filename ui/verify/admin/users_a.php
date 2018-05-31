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

    $sql = "DELETE FROM dir_user_group 
            WHERE userid='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM dir_user_role 
            WHERE userid='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM dir_user 
            WHERE id='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM $TBLSURGEONS 
            WHERE c_userId='$id'";
    dbi_query($sql);

    logMsg("DELETE: $sql",$logfile);

    /*  $sql = "DELETE FROM dir_user_role WHERE userId='$id'";
      dbi_query($sql);
      $sql = "DELETE FROM dir_user_group WHERE userId='$id'";
      dbi_query($sql);
      $sql = "DELETE FROM $TBLSURGEONS WHERE c_userId='$id'";
      dbi_query($sql);
    */
}
if ($mode=="reset") {
   $sql = "UPDATE dir_user
           SET c_pw_reset=1,
           c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
}
if ($mode=="add") {
   $firstname = $_POST['firstname'];
   $lastname = $_POST['lastname'];
   $email = $_POST['email'];
   $is_surgeon = $_POST['is_surgeon'];
   $is_admin = $_POST['is_admin'];
   $gmc_number = $_POST['gmc_number'];
   //  check for required fields and formats here
   if ($firstname=="")
      $_SESSION['add_firstname_error']=true; else $_SESSION['add_firstname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$firstname))
      $_SESSION['add_firstname_format_error']=true; else $_SESSION['add_firstname_format_error']=false;
   if ($lastname=="")
      $_SESSION['add_lastname_error']=true; else $_SESSION['add_lastname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$lastname))
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
   } else {
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
      $_SESSION['add_afirstname']=$firstname;
      $_SESSION['add_alastname']=$lastname;
      $_SESSION['add_aemail']=$email;
      $_SESSION['add_agmc_number']=$gmc_number;
      $_SESSION['add_ais_surgeon']=$is_surgeon;
      $_SESSION['add_ais_admin']=$is_admin;
      header("Location: users.php?m=add");
      exit();
   }
   // INSERT
   $admin_user_id = uniqid();
   $password = random_password();
   $hash = password_hash($password, PASSWORD_BCRYPT);
   if (!password_verify($password, $hash)) {
      /* Invalid hash generation*/
      header("Location:".$_SERVER['HTTP_REFERER']);
      exit;
   }
   logMsg("pw: $password - hash: $hash", $logfile);

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
               uipassword='$hash'";
   logMsg("ADD: $sql",$logfile);
   dbi_query($sql);
      if ($is_surgeon=="1")
   {
      $fullname = "$firstname $lastname";
      $sql = "INSERT INTO $TBLSURGEONS
              SET id='$admin_user_id',
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
   if ($is_admin=="1") {
       $group_str = "sitedivadmins";
   }
   if ($is_admin<>"1") {
       $group_str = "staff";
   }
   $sql = "INSERT INTO dir_user_group
           SET groupId='$group_str',
               username='$email',
               userId='$admin_user_id'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // send mail to the new user
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", $firstname, $email_template);
   $email_template = str_replace("**HEADER**", "Welcome", $email_template);

   $content1 = "We have created an account for you in the EIDO Verify system. Here are your account credentials.<br /><br />
         Username: $email<br />
         Password: $password<br /><br />
         <a href='https://verify.eidosystems.com'>Click here to log into the EIDO Verify system</a><br /><br />";
         $email_template = str_replace("**CONTENT1**", $content1, $email_template);

   $content2 = "<p>We have created an account for you in the EIDO Verify system. Here are your account credentials.</p>
         <p>Username: $email<br />
         Password: $password</p>
         <p>Click the button below to log into the EIDO Verify systemi</p>";
         $email_template = str_replace("**CONTENT2**", $content2, $email_template);

   // set up the button
   $button_text = "Get Started";
   $email_template = str_replace("**BUTTONTEXT**", $button_text, $email_template);
   $button_url = "https://verify.eidosystems.com";
   $email_template = str_replace("**BUTTONURL**", $button_url, $email_template);

   // content3 after the button
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
   header("Location: users.php?m=update&id=$admin_user_id");
   exit();
}

if ($mode=="gotoadd") {
    $_SESSION['add_firstname_error']=false;
    $_SESSION['add_firstname_format_error']=false;
    $_SESSION['add_lastname_error']=false;
    $_SESSION['add_lastname_format_error']=false;
    $_SESSION['add_bad_email_error']=false;
    $_SESSION['add_email_error']=false;
    $_SESSION['add_email_duplicate_error']=false;
    $_SESSION['add_gmc_number_error']=false;
    $_SESSION['add_gmc_number_format_error']=false;
    $_SESSION['add_gmc_number_length_error']=false;
    $_SESSION['add_gmc_number_duplicate_error']=false;
    unset($_SESSION['add_afirstname']);
    unset($_SESSION['add_alastname']);
    unset($_SESSION['add_aemail']);
    unset($_SESSION['add_agmc_number']);
    unset($_SESSION['add_ais_surgeon']);
    unset($_SESSION['add_ais_admin']);
   header("Location: users.php?m=add");
   exit();
}
if ($mode=="gotoupdate") {
    $_SESSION['add_firstname_error']=false;
    $_SESSION['add_firstname_format_error']=false;
    $_SESSION['add_lastname_error']=false;
    $_SESSION['add_lastname_format_error']=false;
    $_SESSION['add_bad_email_error']=false;
    $_SESSION['add_email_error']=false;
    $_SESSION['add_email_duplicate_error']=false;
    $_SESSION['add_gmc_number_error']=false;
    $_SESSION['add_gmc_number_format_error']=false;
    $_SESSION['add_gmc_number_length_error']=false;
    $_SESSION['add_gmc_number_duplicate_error']=false;

 //  header("Location: users.php?m=update&id=$id");
 //  exit();
//}
//if ($mode="view") {

        $_SESSION['update_firstname'] = $firstName;

        $_SESSION['update_lastname'] = $lastName;

        $_SESSION['update_email'] = $email;

        $_SESSION['update_gmc_number'] = $gmc_number;

        $_SESSION['update_is_surgeon'] = $is_surgeon;

        $_SESSION['update_is_admin'] = $is_admin;

    header("Location: users.php?m=update&id=$id");
    exit();

}
if ($mode=="update") {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $is_surgeon = $_POST['is_surgeon'];
    $is_admin = $_POST['is_admin'];
    $gmc_number = $_POST['gmc_number'];
    $user_id = $id;

    //  check for required fields and formats here
    if ($firstname=="")
        $_SESSION['add_firstname_error']=true; else $_SESSION['add_firstname_error']=false;
    if (!preg_match("/^[a-zA-Z' -]*$/",$firstname))
        $_SESSION['add_firstname_format_error']=true; else $_SESSION['add_firstname_format_error']=false;
    if ($lastname=="")
        $_SESSION['add_lastname_error']=true; else $_SESSION['add_lastname_error']=false;
    if (!preg_match("/^[a-zA-Z' -]*$/",$lastname))
        $_SESSION['add_lastname_format_error']=true; else $_SESSION['add_lastname_format_error']=false;
    if ($email=="")
        $_SESSION['add_email_error']=true; else $_SESSION['add_email_error']=false;
    if ($email<>"" && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $_SESSION['add_bad_email_error']=true; else $_SESSION['add_bad_email_error']=false;
    if (!is_email_unique($email, $id))
        $_SESSION['add_email_duplicate_error']=true; else $_SESSION['add_email_duplicate_error']=false;
    if ($is_surgeon=="1")
    {
        if ($gmc_number=="")
            $_SESSION['add_gmc_number_error']=true; else $_SESSION['add_gmc_number_error']=false;
        if (!preg_match("/^[0-9]*$/",$gmc_number))
            $_SESSION['add_gmc_number_format_error']=true; else $_SESSION['add_gmc_number_format_error']=false;
        if (strlen($gmc_number)<6 || strlen($gmc_number)>7)
            $_SESSION['add_gmc_number_length_error']=true; else $_SESSION['add_gmc_number_length_error']=false;
        if (!is_gmc_number_unique($gmc_number, $id))
            $_SESSION['add_gmc_number_duplicate_error']=true; else $_SESSION['add_gmc_number_duplicate_error']=false;
    } else {
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
        $_SESSION['update_firstname']= $firstname;
        $_SESSION['update_lastname']= $lastname;
        $_SESSION['update_email']= $email;
        $_SESSION['update_gmc_number']= $gmc_number;
        $_SESSION['update_is_surgeon']= $is_surgeon;
        $_SESSION['update_is_admin']= $is_admin;

        header("Location: users.php?m=view&id=$user_id");
        exit();
   }


    // delete records from user_group user_role surgeons table
    // and then recreate
    $sql = "DELETE FROM dir_user_group 
           WHERE userid='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM dir_user_role 
           WHERE userid='$id'";
    dbi_query($sql);
    $sql = "DELETE FROM $TBLSURGEONS 
           WHERE id='$id'";
    dbi_query($sql);


    // UPDATE User
    $sql = "UPDATE dir_user
           SET firstName=".escapeQuote($firstname).",
               lastName=".escapeQuote($lastname).",
               email=".escapeQuote($email).",
               active=1,
               timeZone='0',
               username='$email',
               isSurgeon='$is_surgeon',
               gmc_number='$gmc_number'
           WHERE id='$id'";
    dbi_query($sql);
    logMsg($sql,$logfile);

    // DETERMINE Group
    if ($is_admin=="1") {
        $group_str = "sitedivadmins";
    }
    else
        $group_str = "staff";


    // UPDATE Group
    $sql = "INSERT INTO dir_user_group
           SET groupId='$group_str',
               username='$email',
               userId='$id'";
    dbi_query($sql);
    logMsg($sql,$logfile);
    // UPDATE Role
    $sql = "INSERT INTO dir_user_role
           SET roleId='ROLE_USER',
               username='$email',
               userId='$id'";
    dbi_query($sql);
    logMsg($sql,$logfile);

    // ADD Surgeon
    if ($is_surgeon=="1")
    {
        $fullname = "$lastname, $firstname";
        $sql = "INSERT INTO $TBLSURGEONS
              SET id='$id',
                  c_userId='$id',
                  c_surgeonName='$fullname',
                  c_gmcNumber='$gmc_number',
                  dateCreated=NOW(),
                  dateModified=NOW(),
                  createdBy='$user_id'";

        dbi_query($sql);
        logMsg("UPDATE SURGEON: $sql",$logfile);
    }
}

header("Location: users_a.php?m=gotoupdate&id=$user_id");
exit();
?>
