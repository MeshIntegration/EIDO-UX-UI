<?php
// ***************************************
// superuser/organizations_a.php
// 2017 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************

require_once '../utilities.php';
session_start();
$logfile = "organisation.log";
$error_msg="";

$arr_ext=array();
$arr_ext[]="jpg";
$arr_ext[]="png";
$arr_ext[]="gif";

$mode = get_query_string('m');
$id = get_query_string('id');
logMsg("organisations_a - mode: $mode id: $id",$logfile);

// set up for creating admin user
list($fname, $lname) = explode(" ", $admin);
//$password = "password";

if ($mode=="update") {
   $org_id=$id;
   $name = $_POST['name'];
   $type = $_POST['type'];
   $email = $_POST['email'];
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $admin_email = $_POST['admin_email'];
   $admin_fname = $_POST['admin_fname'];
   $admin_lname = $_POST['admin_lname'];

   $c_admin = $fname." ".$lname;
   $subdivision = $_POST['subdivision'];
   $existing_header_logo = $_POST['existing_header_logo'];
   logMsg("$mode $id $name $type $c_admin",$logfile);

   // get the original oganisation name in case it changes
   // and the primary admin id
   $sql = "SELECT c_name, c_firstUser 
           FROM $TBLORGANISATIONS
           WHERE id='$org_id'";
   $GetQuery=dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $org_name = $qryResult['c_name'];
   $c_firstUser_orig = $qryResult['c_firstUser'];

   include "inc_org_error_check.php";

   // not going to check this one for now
   // can't use the function I have as it looks in users
   // if (!is_email_unique($email))
   //    $_SESSION['add_email_duplicate_error']=true; else $_SESSION['add_email_duplicate_error']=false;

   if (!is_email_unique($admin_email, $c_firstUser_orig))
      $_SESSION['add_admin_email_duplicate_error']=true; else $_SESSION['add_admin_email_duplicate_error']=false;

   if ($_SESSION['add_logo_type_error'] || $_SESSION['add_logo_error'] ||
       $_SESSION['add_orgname_error'] || $_SESSION['add_orgname_format_error'] ||
       $_SESSION['add_fname_error'] || $_SESSION['add_fname_format_error'] ||
       $_SESSION['add_lname_error'] || $_SESSION['add_lname_format_error'] ||
       $_SESSION['add_email_error'] ||
       $_SESSION['add_bad_email_error'] || $_SESSION['add_type_error'] ||
       $_SESSION['add_admin_fname_error'] || $_SESSION['add_admin_fname_format_error'] ||
       $_SESSION['add_admin_lname_error'] || $_SESSION['add_admin_lname_format_error'] ||
       $_SESSION['add_admin_email_error'] || $_SESSION['add_admin_email_duplicate_error'] ||
       $_SESSION['add_admin_bad_email_error']) {
         $_SESSION['error_msg'] = $error_msg;
         $_SESSION['name'] = $name;
         $_SESSION['type'] = $type;
         $_SESSION['email'] = $email;
         $_SESSION['fname'] = $fname;
         $_SESSION['lname'] = $lname;
         $_SESSION['admin_email'] = $admin_email;
         $_SESSION['admin_fname'] = $admin_fname;
         $_SESSION['admin_lname'] = $admin_lname;
         $_SESSION['subdivision'] = $subdivision;
         header ("Location: organisations.php?m=update&id=$id");
         exit();
   }
   
   //Upload the file
   if ($_FILES['header_logo']['size'] > 0)
   {
      $target_file_name = $org_id.".".$ext;
      $target_file_path = $ABS_PATH."img/org_logos/".$target_file_name;
      // logMsg($_FILES['header_logo']['tmp_name'], $logfile);
      // logMsg($target_file_path, $logfile);
      // logMsg("Existing logo: $existing_header_logo", $logfile);
      move_uploaded_file($_FILES['header_logo']['tmp_name'], $target_file_path);
      $header_logo=$target_file_name;
   }
   else if ($existing_header_logo<>"")
      $header_logo = $existing_header_logo;
   else
      $header_logo = "";
   
   // get the user id for the org's first user/primary admin
   $sql = "SELECT u.id, u.username
           FROM dir_user u, $TBLORGANISATIONS o
           WHERE o.id='$id'
           AND o.c_firstUser=u.id";
   $GetQuery=dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $admin_uid=$qryResult['id'];
   
   $user_id = $_COOKIE['user_id'];
   $sql = "UPDATE $TBLORGANISATIONS
           SET c_name=".escapeQuote($name).",
               c_type=".escapeQuote($type).",
               c_email=".escapeQuote($email).",
               c_logo=".escapeQuote($header_logo).",
               c_admin=".escapeQuote($c_admin).",
               c_subdivision=".escapeQuote($subdivision).",
               c_level='ORG',
               c_firstUser=".escapeQuote($admin_email).",
               modifiedBy=".escapeQuote($user_id).",
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);

   // if the name changed - update the name in pt episodes & dir_organisations
   // Aipent is too stupid to do a two table lookup  
   if ($org_name<>$name) {  
      $sql = "UPDATE $TBLPTEPISODES
              SET c_hospitalName=".escapeQuote($name)."
              WHERE c_hospitalName=".escapeQuote($org_name);
      dbi_query($sql);
      logMsg($sql, $logfile);

      $sql = "UPDATE dir_organization
              SET name=".escapeQuote($name).",
                  description=".escapeQuote($name)."
              WHERE id='$org_id'";
      dbi_query($sql);
      logMsg($sql, $logfile);
   }

   $sql = "DELETE FROM dir_user_role WHERE userId='$admin_uid'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "DELETE FROM dir_user_group WHERE userId='$admin_uid'";
   dbi_query($sql);
   logMsg($sql,$logfile);
   
   $sql = "UPDATE dir_user 
           SET username=".escapeQuote($admin_email).",
               firstName=".escapeQuote($admin_fname).",
               lastName=".escapeQuote($admin_lname).",
               email=".escapeQuote($admin_email).",
               c_organizationId=".escapeQuote($org_id).",
               c_dateModified='NOW(),
               active='1',
               timezone='0'
            WHERE id='$admin_uid'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$admin_uid',
               username = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId = 'sitedivadmins',
               userId = '$admin_uid',
               username = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);
   header("Location: organisations.php");
   exit();
} 
else if ($mode=="gotoaddorg") {
   unset($_SESSION['add_orgname_error']);
   unset($_SESSION['add_orgname_format_error']);
   unset($_SESSION['add_fname_error']);
   unset($_SESSION['add_fname_format_error']);
   unset($_SESSION['add_lname_error']);
   unset($_SESSION['add_lname_format_error']);
   unset($_SESSION['add_email_error']);
   unset($_SESSION['add_email_duplicate_error']);
   unset($_SESSION['add_bad_email_error']);
   unset($_SESSION['add_admin_fname_error']);
   unset($_SESSION['add_admin_fname_format_error']);
   unset($_SESSION['add_admin_lname_error']);
   unset($_SESSION['add_admin_lname_format_error']);
   unset($_SESSION['add_admin_email_error']);
   unset($_SESSION['add_admin_email_duplicate_error']);
   unset($_SESSION['add_admin_bad_email_error']);
   unset($_SESSION['add_type_error']);
   unset($_SESSION['add_logo_error']);
   unset($_SESSION['add_logo_type_error']);
   header("Location: organisations.php?m=add");
   exit();
}
else if ($mode=="clearsession") {
   $goto = get_query_string('g');
   $_SESSION = array();
   header ("Location: organisations.php?m=$goto&id=$id");
   exit();
}
else if ($mode=="add") {
   logMsg("-------------- START ORG ADD ----------------",$logfile);
   // INSERT
   $org_id = uniqid();
   $name = $_POST['name'];
   $type = $_POST['type'];
   $email = $_POST['email'];
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $admin_email = $_POST['admin_email'];
   $admin_fname = $_POST['admin_fname'];
   $admin_lname = $_POST['admin_lname'];
   $subdivision = $_POST['subdivision'];
   $existing_header_logo = $_POST['existing_header_logo'];
   logMsg("$mode $id $name $type $admin",$logfile);

   include "inc_org_error_check.php";

   // not going to check this one for now
   // if (!is_email_unique($email))
   //    $_SESSION['add_email_duplicate_error']=true; else $_SESSION['add_email_duplicate_error']=false;

   // have to do this one here because it is different for add and update
   if (!is_email_unique($admin_email))
      $_SESSION['add_admin_email_duplicate_error']=true; else $_SESSION['add_admin_email_duplicate_error']=false;

   if ($_SESSION['add_logo_type_error'] || $_SESSION['add_logo_error'] || 
       $_SESSION['add_orgname_error'] || $_SESSION['add_orgname_format_error'] || 
       $_SESSION['add_fname_error'] || $_SESSION['add_fname_format_error'] ||
       $_SESSION['add_lname_error'] || $_SESSION['add_lname_format_error'] ||
       $_SESSION['add_email_error'] || 
       $_SESSION['add_bad_email_error'] || $_SESSION['add_type_error'] || 
       $_SESSION['add_admin_fname_error'] || $_SESSION['add_admin_fname_format_error'] ||
       $_SESSION['add_admin_lname_error'] || $_SESSION['add_admin_lname_format_error'] ||
       $_SESSION['add_admin_email_error'] || $_SESSION['add_admin_email_duplicate_error'] || 
       $_SESSION['add_admin_bad_email_error']) {
         $_SESSION['error_msg'] = $error_msg;
         $_SESSION['name'] = $name;
         $_SESSION['type'] = $type;
         $_SESSION['email'] = $email;
         $_SESSION['fname'] = $fname;
         $_SESSION['lname'] = $lname;
         $_SESSION['admin_email'] = $admin_email;
         $_SESSION['admin_fname'] = $admin_fname;
         $_SESSION['admin_lname'] = $admin_lname;
         $_SESSION['subdivision'] = $subdivision;
         header ("Location: organisations.php?m=add");
         exit();
   }

   //Upload the file
   if ($_FILES['header_logo']['size'] > 0)
   {
      $target_file_name = $org_id.".".$ext;
      $target_file_path = $ABS_PATH."img/org_logos/".$target_file_name;
      // logMsg($_FILES['header_logo']['tmp_name'], $logfile);
      // logMsg($target_file_path, $logfile);
      move_uploaded_file($_FILES['header_logo']['tmp_name'], $target_file_path);
      $header_logo=$target_file_name;
   }

   // create ID for first user account
   // we are using email as the USER ID
   $firstuser_uid = $admin_email;

   $user_id = $_COOKIE['user_id'];
   // we put the account owner in the organisations table
   $owner = $fname." ".$lname;
   $sql = "INSERT INTO $TBLORGANISATIONS
           SET id='".$org_id."',
               c_name=".escapeQuote($name).",
               c_description=".escapeQuote($name).",
               c_type=".escapeQuote($type).",
               c_level='ORG',
               c_email=".escapeQuote($email).",
               c_admin=".escapeQuote($owner).",
               c_logo=".escapeQuote($header_logo).",
               c_subdivision=".escapeQuote($subdivision).",
               c_firstUser=".escapeQuote($firstuser_uid).",
               createdBy=".escapeQuote($user_id).",
               dateModified=NOW(),
               dateCreated=NOW()";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_organization 
           SET id='$org_id',
               name=".escapeQuote($name).",
               description=".escapeQuote($name).";"; 
   dbi_query($sql);
   logMsg($sql,$logfile);

   // Ceate the first admin user 
   // create and encrypt password
   $password = random_password();
   $hash = password_hash($password, PASSWORD_BCRYPT);
   if (!password_verify($password, $hash)) {
      /* Invalid hash generation*/
      header("Location:".$_SERVER['HTTP_REFERER']);
      exit;
   }
   logMsg("pw: $password - hash: $hash", $logfile);

   $sql = "INSERT INTO dir_user
           SET id=".escapeQuote($admin_email).",
               username=".escapeQuote($admin_email).",
               uipassword=".escapeQuote($hash).",
               firstName=".escapeQuote($admin_fname).",
               lastName=".escapeQuote($admin_lname).",
               email=".escapeQuote($admin_email).",
               c_organizationId='$org_id',
               c_dateModified=NOW(),
               c_dateCreated=NOW(),
               active='1',
               timezone='0',
               gmc_number=''";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$firstuser_uid',
               username='$admin_email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId = 'sitedivadmins',
               userId = '$firstuser_uid',
               username='$admin_email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // Not sure if we need this at this level but can't hurt
   // $emp_id = uniqid();
   // $sql = "INSERT INTO dir_employment
   //         SET id='$emp_id',
   //             userid='$admin_email',
   //             departmentId='n/a',
   //             organizationId='$org_id'";
   // dbi_query($sql);
   // logMsg($sql,$logfile);

   // send mail to the new user
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", $admin_fname, $email_template);
   $email_template = str_replace("**HEADER**", "Welcome", $email_template);

   $content1 = "We have created an account for you in the EIDO Verify system. Here are your account credentials.<br /><br />
         Username: $admin_email<br />
         Password: $password<br /><br />
         <a href='https://verify.eidosystems.com'>Click here to log into the EIDO Verify system</a><br /><br />";
         $email_template = str_replace("**CONTENT1**", $content1, $email_template);

   $content2 = "<p>We have created an account for you in the EIDO Verify system. Here are your account credentials.</p>
         <p>Username: $admin_email<br />
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
   $arr_email['mail_to_name']="$fname $lname";
   $arr_email['bcc']="wayne@mindstreams.com";
   $arr_email['mail_from']=$verify_mail_from;
   $arr_email['mail_from_name']=$verify_mail_from_name;
   $arr_email['subject']="EIDO Verify Account Information";
   $arr_email['body']=$email_template;

   send_email($arr_email);
   header("Location: organisations.php");
   exit();
}
else if ($mode=="editdiv") {
   $admin_fname = $_POST['admin_fname'];
   $admin_lname = $_POST['admin_lname'];
   $admin_email = $_POST['admin_email'];
   $name = $_POST['name'];

   // get the original oganisation name in case it changes
   // and the primary admin id
   $sql = "SELECT c_name, c_firstUser
           FROM $TBLORGANISATIONS
           WHERE id='$org_id'";
   $GetQuery=dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $org_name = $qryResult['c_name'];
   $c_firstUser_orig = $qryResult['c_firstUser'];

   //  check for required fields and formats here
   if ($name=="")
      $_SESSION['add_orgname_error']=true; else $_SESSION['add_orgname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$name))
      $_SESSION['add_orgname_format_error']=true; else $_SESSION['add_orgname_format_error']=false;
   // first admin
   if ($admin_fname=="")
      $_SESSION['add_admin_fname_error']=true; else $_SESSION['add_admin_fname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$admin_fname))
      $_SESSION['add_admin_fname_format_error']=true; else $_SESSION['add_admin_fname_format_error']=false;
   if ($admin_lname=="")
      $_SESSION['add_admin_lname_error']=true; else $_SESSION['add_admin_lname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$admin_lname))
      $_SESSION['add_admin_lname_format_error']=true; else $_SESSION['add_admin_lname_format_error']=false;
   if ($admin_email=="")
      $_SESSION['add_admin_email_error']=true; else $_SESSION['add_admin_email_error']=false;
   if ($admin_email<>"" && !filter_var($admin_email, FILTER_VALIDATE_EMAIL))
      $_SESSION['add_bad_admin_email_error']=true; else $_SESSION['add_bad_admin_email_error']=false;
   if (!is_email_unique($admin_email, $c_firstUser_orig))
      $_SESSION['add_admin_email_duplicate_error']=true; else $_SESSION['add_admin_email_duplicate_error']=false;

   if ( $_SESSION['add_orgname_error'] || $_SESSION['add_orgname_format_error'] ||
       $_SESSION['add_bad_email_error'] ||
       $_SESSION['add_admin_fname_error'] || $_SESSION['add_admin_fname_format_error'] ||
       $_SESSION['add_admin_lname_error'] || $_SESSION['add_admin_lname_format_error'] ||
       $_SESSION['add_admin_email_error'] || $_SESSION['add_admin_email_duplicate_error'] ||
       $_SESSION['add_admin_bad_email_error']) {
         $_SESSION['name'] = $name;
         $_SESSION['admin_email'] = $email;
         $_SESSION['admin_fname'] = $fname;
         $_SESSION['admin_lname'] = $lname;
         header ("Location: organisations.php?m=editdiv?id=$id");
         exit();
   }
   $user_id = $_COOKIE['user_id'];
   $sql = "UPDATE $TBLORGANISATIONS
           SET c_name=".escapeQuote($name).",
               modifiedBy=".escapeQuote($user_id).",
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);

   // if the name changed - update the name in pt episodes & dir_organisations
   // Aipent is too stupid to do a two table lookup
   if ($org_name<>$name) {
      $sql = "UPDATE $TBLPTEPISODES
              SET c_hospitalName=".escapeQuote($name)."
              WHERE c_hospitalName=".escapeQuote($org_name);
      dbi_query($sql);
      logMsg($sql, $logfile);

      $sql = "UPDATE dir_organization
              SET name=".escapeQuote($name).",
                  description=".escapeQuote($name)."
              WHERE id='$org_id'";
      dbi_query($sql);
      logMsg($sql, $logfile);
   }

   // get the user id for the org's first user/primary admin
   $sql = "SELECT u.id, u.username
           FROM dir_user u, $TBLORGANISATIONS o
           WHERE o.id='$id'
           AND o.c_firstUser=u.id";
   $GetQuery=dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $admin_uid=$qryResult['id'];

   $sql = "DELETE FROM dir_user_role WHERE userId='$admin_uid'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "DELETE FROM dir_user_group WHERE userId='$admin_uid'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "UPDATE dir_user
           SET username=".escapeQuote($admin_email).",
               firstName=".escapeQuote($admin_fname).",
               lastName=".escapeQuote($admin_lname).",
               email=".escapeQuote($admin_email).",
               c_dateModified='NOW(),
               active='1',
               timezone='0'
            WHERE id='$admin_uid'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$admin_uid',
               username = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId = 'sitedivadmins',
               userId = '$admin_uid',
               username = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);
   header("Location: organisations.php");
   exit();
}
else if ($mode=="deletediv") {
logMsg("----- deletediv -----",$logfile);
   // get the parent ID
   $sql = "SELECT c_parentId FROM $TBLORGANISATIONS WHERE id='$id'";
   $GetQuery = dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $org_id=$qryResult['c_parentId'];
logMsg($sql,$logfile);
logMsg("org_id: $org_id",$logfile);

   // delete all users associated with this division
   $sql = "SELECT id FROM dir_user WHERE c_organizationId='$id'";
   $GetQuery = dbi_query($sql);
   while ($qryResult = $GetQuery->fetch_assoc()) {
      $uid = $queryResult['id'];
      $sql_du = "DELETE FROM dir_user_group WHERE userid='$uid'";
      dbi_query($sql_du);

      $sql_du = "DELETE FROM dir_user_role WHERE userid='$uid'";
      dbi_query($sql_du);

      $sql_du = "DELETE FROM dir_user WHERE id='$uid'";
      dbi_query($sql_du);
      logMsg("Deleted userId: $uid from user tables", $logfile);
   }
   // now set the division inactive
   $user_id = $_COOKIE['user_id'];
   $sql_org = "UPDATE $TBLORGANISATIONS 
              SET c_status='INACTIVE',
                  dateModified=NOW(),
                  modifiedBy='$user_id'
              WHERE id='$id'";
logMsg($sql_org,$logfile);
   dbi_query($sql_org);
   logMsg("Organisation ID: $id set to INACTIVE", $logfile);

   header("Location: organisations.php?m=listdivs&id=$org_id");
   exit(); 
}
else if ($mode=="editcust") {
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $email = $_POST['email'];
   $gmcnumber = $_POST['gmcnumber'];
   $sql="SELECT c_organizationId FROM dir_user WHERE id='$id'";
   logMsg($sql,$logfile);
   $GetQuery = dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $org_id=$qryResult['c_organizationId'];

   $sql = "UPDATE dir_user SET
              firstName=".escapeQuote($fname).",
              lastName=".escapeQuote($lname).",
              email=".escapeQuote($email).",
              username=".escapeQuote($email).",
              gmc_number=".escapeQuote($gmcnumber).",
              c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sname = "$fname $lname";
   $sql = "UPDATE $TBLSURGEONS
           SET dateModified=NOW(),
               c_gmcNumber='$gmcnumber',
               c_surgeonName='$sname'
               WHERE c_userId='$id'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   header("Location: organisations.php?m=listdivs&id=$org_id");
   exit(); 
}
else if ($mode=="deletecust") {
   // get org ID so we can return to that list
   $sql = "SELECT c_organizationId FROM dir_user WHERE id='$id'";
   $GetQuery = dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $org_id=$qryResult['c_organizationId'];
 
   $sql = "DELETE FROM dir_user_group WHERE userid='$id'";
   dbi_query($sql);

   $sql = "DELETE FROM dir_user_role WHERE userid='$id'";
   dbi_query($sql);

   $sql = "DELETE FROM dir_user WHERE id='$id'";
   dbi_query($sql);
   logMsg("Deleted Customer ID: $id from user tables", $logfile);

   $sql = "DELETE FROM $TBLSURGEONS WHERE c_userId='$id'";
   dbi_query($sql);
   logMsg("Deleted Customer ID: $uid from surgeon table", $logfile);

   header("Location: organisations.php?m=listdivs&id=$org_id");
   exit(); 
}
else if ($mode=="adddiv" || $mode=="addcust") {
   logMsg("-------------- START SUBDIV ADD ----------------",$logfile);
   $org_id=uniqid();
   $name = $_POST['name'];               // org name
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $email = $_POST['email'];
   $admin_email = $_POST['admin_email'];
   $admin_fname = $_POST['admin_fname'];
   $admin_lname = $_POST['admin_lname'];
   $type = $_POST['type'];               // defaults to same as parent
   $subdivision = $_POST['subdivision']; // defaults to No
   $gmcnumber = $_POST['gmcnumber'];     // blank for DIVs - only for ustomers

   if ($mode=="adddiv") {
      include "inc_org_error_check.php";

      // not going to check this one for now
      // if (!is_email_unique($email))
      //    $_SESSION['add_email_duplicate_error']=true; else $_SESSION['add_email_duplicate_error']=false;

      // have to do this one here because it is different for add and update
      if (!is_email_unique($admin_email))
         $_SESSION['add_admin_email_duplicate_error']=true; else $_SESSION['add_admin_email_duplicate_error']=false;
   
      if ($_SESSION['add_logo_type_error'] || $_SESSION['add_logo_error'] ||
          $_SESSION['add_orgname_error'] || $_SESSION['add_orgname_format_error'] ||
          $_SESSION['add_fname_error'] || $_SESSION['add_fname_format_error'] ||
          $_SESSION['add_lname_error'] || $_SESSION['add_lname_format_error'] ||
          $_SESSION['add_email_error'] ||
          $_SESSION['add_bad_email_error'] || $_SESSION['add_type_error'] ||
          $_SESSION['add_admin_fname_error'] || $_SESSION['add_admin_fname_format_error'] ||
          $_SESSION['add_admin_lname_error'] || $_SESSION['add_admin_lname_format_error'] ||
          $_SESSION['add_admin_email_error'] || $_SESSION['add_admin_email_duplicate_error'] ||
          $_SESSION['add_admin_bad_email_error']) {
            $_SESSION['error_msg'] = $error_msg;
            $_SESSION['name'] = $name;
            $_SESSION['type'] = $type;
            $_SESSION['email'] = $email;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['admin_email'] = $admin_email;
            $_SESSION['admin_fname'] = $admin_fname;
            $_SESSION['admin_lname'] = $admin_lname;
            $_SESSION['subdivision'] = $subdivision;
            header ("Location: organisations.php?m=adddiv&id=$id");
            exit();
      }
   }
   $user_id = $_COOKIE['user_id'];       // id of super user putting this in
   // create ID for first user account
   $firstuser_uid = $admin_email;        // we are using email as the USER ID

   if ($mode=="adddiv") { 
      // add DIVISION
      $sql = "INSERT INTO $TBLORGANISATIONS
              SET id='".$org_id."',
                  c_name=".escapeQuote($name).",
                  c_description=".escapeQuote($name).",
                  c_type=".escapeQuote($type).",
                  c_level='DIV',
                  c_subdivision=".escapeQuote($subdivision).",
                  c_parentId=".escapeQuote($id).",
                  c_firstUser=".escapeQuote($firstuser_uid).",
                  createdBy=".escapeQuote($user_id).",
                  dateModified=NOW(),
                  dateCreated=NOW()";
      dbi_query($sql);
      logMsg($sql,$logfile);
   
      $sql = "INSERT INTO dir_organization
              SET id='$org_id',
                  name=".escapeQuote($name).",
                  description=".escapeQuote($name).";";
      dbi_query($sql);
      logMsg($sql,$logfile);

      // $sql = "INSERT INTO dir_department 
      //         SET id='".$div_id."',
      //             name=".escapeQuote($name).",
      //             description=".escapeQuote($name).",
      //             organizationId=".escapeQuote($org_id).";";
      // dbi_query($sql);
      // logMsg($sql,$logfile);
   } else {
      // add CUSTOMER (surgeon)
      $sname="$fname $lname";
      $sql = "INSERT INTO $TBLSURGEONS
              SET id='".$div_id."',
                  dateCreated=NOW(),
                  dateModified=NOW(),
                  createdBy='$user_d',
                  c_userId='$email',
                  c_gmcNumber='$gmcnumber',
                  c_surgeonName='$sname'";
      dbi_query($sql);
      logMsg($sql,$logfile);
      // so we can use these values to build user account
      $admin_fname=$fname;
      $admin_lname=$lname;
      $admin_email=$email;
   }

   // CREATE PRIMARY ADMIN USER 
   $password = random_password();
   $hash = password_hash($password, PASSWORD_BCRYPT);
   if (!password_verify($password, $hash)) {
      // Invalid hash generation
      header("Location:".$_SERVER['HTTP_REFERER']);
      exit;
   }
   logMsg("pw: $password - hash: $hash", $logfile);

   $sql = "INSERT INTO dir_user
           SET id=".escapeQuote($admin_email).",
               username=".escapeQuote($admin_email).",
               uipassword=".escapeQuote($hash).", 
               email=".escapeQuote($admin_email).",
               firstName=".escapeQuote($admin_fname).",
               lastName=".escapeQuote($admin_lname).",
               active=1,
               timezone='0',
               c_dateCreated=NOW(),
               c_dateModified=NOW(),
               c_organizationId='$org_id',
               gmc_number='$gmcnumber'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // ROLE - User or Admin
   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // GROUP - Admin or Staff
   $sql = "INSERT INTO dir_user_group
           SET groupId = 'sitedivadmins',
               userId = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // EMPLOYMENT - tie user/organisation/subdivision (or cust/surgeon) all together
   // $emp_id=uniqid();
   // $sql = "INSERT INTO dir_employment
   //         SET id='$email',
   //             userid='$email',
   //             departmentId='$div_id',
   //             organizationId='$org_id'";
   // dbi_query($sql);
   // logMsg($sql,$logfile);

   // send mail to the new user
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", $fname, $email_template);
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
   $arr_email['mail_to_name']="$fname $lname";
   $arr_email['bcc']="wayne@mindstreams.com";
   $arr_email['mail_from']=$verify_mail_from;
   $arr_email['mail_from_name']=$verify_mail_from_name;
   $arr_email['subject']="EIDO Verify Account Information";
   $arr_email['body']=$email_template;

   send_email($arr_email);

   header("Location: organisations.php?m=listdivs&id=$org_id");
   exit(); 
}
else if ($mode == "removelogo") {
	$target_file_path = $ABS_PATH."img/org_logos/";
	// get the current logo address
	$sql = "SELECT * FROM $TBLORGANISATIONS WHERE id='$id'";
	$GetQuery=dbi_query($sql);
	$qryResult=$GetQuery->fetch_assoc();
	unlink($target_file_path."{$qryResult['c_logo']}");

	//update
    dbi_query("UPDATE $TBLORGANISATIONS SET c_logo = NULL WHERE id='$id'");
}
header("Location: organisations.php");
exit();

/*  *********************** EXTRA JUNK - NOT SURE HOW THIS GOT COPIED HERE ***************
         $_SESSION['error_msg'] = $error_msg;
         $_SESSION['name'] = $name;
         $_SESSION['type'] = $type;
         $_SESSION['email'] = $email;
         $_SESSION['fname'] = $fname;
         $_SESSION['lname'] = $lname;
         $_SESSION['admin_email'] = $admin_email;
         $_SESSION['admin_fname'] = $admin_fname;
         $_SESSION['admin_lname'] = $admin_lname;
         $_SESSION['subdivision'] = $subdivision;
         header ("Location: organisations.php?m=add");
         exit();
   }

   //Upload the file
   if ($_FILES['header_logo']['size'] > 0)
   {
      $target_file_name = $org_id.".".$ext;
      $target_file_path = $ABS_PATH."img/org_logos/".$target_file_name;
      // logMsg($_FILES['header_logo']['tmp_name'], $logfile);
      // logMsg($target_file_path, $logfile);
      move_uploaded_file($_FILES['header_logo']['tmp_name'], $target_file_path);
      $header_logo=$target_file_name;
   }

   $user_id = $_COOKIE['user_id'];
   $admin = $fname." ".$lname;
   $sql = "INSERT INTO $TBLORGANISATIONS
           SET id='".$org_id."',
               c_name=".escapeQuote($name).",
               c_description=".escapeQuote($name).",
               c_type=".escapeQuote($type).",
               c_email=".escapeQuote($email).",
               c_admin=".escapeQuote($admin).",
               c_logo=".escapeQuote($header_logo).",
               c_subdivision=".escapeQuote($subdivision).",
               createdBy=".escapeQuote($user_id).",
               dateModified=NOW(),
               dateCreated=NOW()";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_organization 
           SET id='$org_id',
               name=".escapeQuote($name).",
               description=".escapeQuote($name).";"; 
   dbi_query($sql);
   logMsg($sql,$logfile);

   // we are using email as the USER ID
   $uid = $email;
   // create and encrypt password
   $password = random_password();
   $hash = password_hash($password, PASSWORD_BCRYPT);
   if (!password_verify($password, $hash)) {
      // Invalid hash generation
      header("Location:".$_SERVER['HTTP_REFERER']);
      exit;
   }
   logMsg("pw: $password - hash: $hash", $logfile);

   $sql = "INSERT INTO dir_user
           SET id=".escapeQuote($email).",
               username=".escapeQuote($email).",
               uipassword=".escapeQuote($hash).",
               firstName=".escapeQuote($fname).",
               lastName=".escapeQuote($lname).",
               email=".escapeQuote($email).",
               active='1',
               timezone='0',
               gmc_number=''";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$uid',
               username='$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId = 'sitedivadmins',
               userId = '$uid',
               username='$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // send mail to the new user
   include "../includes/inc_email_template.php";

   // need a button - include it into template
   include "../includes/inc_email_button.php";
   $email_template = str_replace("**EMAILBUTTON**", $email_button, $email_template);

   $email_template = str_replace("**FIRSTNAME**", $fname, $email_template);
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
   $arr_email['mail_to_name']="$fname $lname";
   $arr_email['bcc']="wayne@mindstreams.com";
   $arr_email['mail_from']=$verify_mail_from;
   $arr_email['mail_from_name']=$verify_mail_from_name;
   $arr_email['subject']="EIDO Verify Account Information";
   $arr_email['body']=$email_template;

   send_email($arr_email);
   header("Location: organisations.php");
   exit();
}
else if ($mode=="adddiv" || $mode=="addcust")
{
   logMsg("-------------- START SUBDIV ADD ----------------",$logfile);
   $org_id=$id;
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $email = $_POST['email'];
   $name = $_POST['name'];

   $div_id = uniqid();
   $user_id = $_COOKIE['user_id'];

   if ($mode=="adddiv")
   {
      $sql = "INSERT INTO dir_department 
              SET id='".$div_id."',
                  name=".escapeQuote($name).",
                  description=".escapeQuote($name).",
                  organizationId=".escapeQuote($org_id).";";
      dbi_query($sql);
      logMsg($sql,$logfile);
   }
   else
   {
      $sname="$fname $lname";
      $sql = "INSERT INTO $TBLSURGEONS
              SET id='".$div_id."',
                  dateCreated=NOW(),
                  dateModified=NOW(),
                  createdBy='$user_d',
                  c_surgeonName='$sname'";
      dbi_query($sql);
      logMsg($sql,$logfile);
   }

   // USER 
   $sql = "INSERT INTO dir_user
           SET id=".escapeQuote($email).",
               username=".escapeQuote($email).",
               password='password',
               email=".escapeQuote($email).",
               firstName=".escapeQuote($fname).",
               lastName=".escapeQuote($lname).",
               active=1,
               timezone='0',
               gmc_number=''";
   dbi_query($sql);

   // ROLE - User or Admin
   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // GROUP - Admin or Staff
   $sql = "INSERT INTO dir_user_group
           SET groupId = 'admin',
               userId = '$email'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   // EMPLOYMENT - tie user/organisation/subdivision (or cust/surgeon) all together
   // $emp_id=uniqid();
   $sql = "INSERT INTO dir_employment
           SET id='$email',
               userid='$email',
               departmentId='$div_id',
               organizationId='$org_id'";
   dbi_query($sql);
   logMsg($sql,$logfile);
   header("Location: organisations.php?m=listdivs&id=$org_id");
   exit(); 
}

if($mode == "removelogo") {
	$target_file_path = $ABS_PATH."img/org_logos/";
	// get the current logo address
	$sql = "SELECT * FROM $TBLORGANISATIONS WHERE id='$id'";
	$GetQuery=dbi_query($sql);
	$qryResult=$GetQuery->fetch_assoc();
	unlink($target_file_path."{$qryResult['c_logo']}");

	//update
    dbi_query("UPDATE $TBLORGANISATIONS SET c_logo = NULL WHERE id='$id'");

}
header("Location: organisations.php");
exit();

***************************************************  */
?>
