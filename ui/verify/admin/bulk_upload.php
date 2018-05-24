<?php
// **************************************
// admin/bulk_upload.php
// Copyright 2018, Mesh Integration LLC
// WEL 4/26/18
// **************************************

include "../utilities.php";
$logfile="admin.log";
session_start();
$_SESSION['error_msg']=$error_msg="";
$arr_ext[] = "csv";
$arr_ext[] = "CSV";

//  A = Add users
//  D = Delete or Remove users
$action = $_POST['action'];

   if ($_FILES['bulk_file']['name']<>"") {
      logMsg($_FILES['bulk_file']['name'], $logfile);
      if ($_FILES['bulk_file']['error'] > 0) {
         $error_msg .= "There was an error uploading your file.<br >";
         $error_msg .= $_FILES['bulk_file']['error']."<br >";
         logMsg("File Upload Error: ".$_FILES['bulk_file']['error'], $logfile);
      } else {
         $loc = strrpos($_FILES['bulk_file']['name'], ".");
         $ext = strtolower(substr($_FILES['bulk_file']['name'], $loc+1));
         if (!in_array($ext, $arr_ext))
            $error_msg .= "File must be in CSV format.<br >";
      }
   }

   if (strlen($error_msg)) {
      $_SESSION['error_msg']=$error_msg;
      header ("Location: users.php?m=bulk");
      exit();
   }

   //Upload the file
   if ($_FILES['bulk_file']['size'] > 0)
   {
      $target_file_name="bulk_upload_".uniqid().".csv";
      $target_file_path = $ABS_PATH."admin/uploads/".$target_file_name;
      // logMsg($_FILES['header_logo']['tmp_name'], $logfile);
      // logMsg($target_file_path, $logfile);
      move_uploaded_file($_FILES['bulk_file']['tmp_name'], $target_file_path);
   }

   $fp = fopen("uploads/".$target_file_name, "r");
   $ct=0;
   while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
      $lastname = trim($data[0]); 
      $firstname = trim($data[1]); 
      $email = trim($data[2]); 
      $is_surgeon = trim($data[3]); 
      $is_admin = trim($data[4]); 
      $gmc_number = trim($data[5]); 
      if ($is_surgeon=="Y") $is_surgeon=1; else $is_surgeon=0;
      if ($is_admin=="Y") $is_admin=1; else $is_admin=0;

      if ($action=="D") {
         $sql="UPDATE dir_user
                  SET active=0
                WHERE email='$email'
                  AND lastName=".escapeQuote($lastname).";";
         dbi_query($sql);
         $ct++;
      } else if ($action=="A") {
         // INSERT
         $admin_user_id = uniqid();
         $password = random_password();
         $hash = password_hash($password, PASSWORD_BCRYPT);
         if (!password_verify($password, $hash)) {
            /* Invalid hash generation*/
            header("Location:".$_SERVER['HTTP_REFERER']);
            exit;
         }
      
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
                     password='".$hash."',
                     uipassword='".$hash."'";
         logMsg("ADD: $sql",$logfile);
         dbi_query($sql);
         if ($is_surgeon=="1") {
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
         else
            $group_str="staff";
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
         $email_template = str_replace("**HEADER`**", "Welcome", $email_template);
   
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

         $arr_email = array();
         $arr_email['mail_to']=$email;
         $arr_email['mail_to_name']="$firstname $lastname";
         $arr_email['bcc']="wayne@mindstreams.com";
         $arr_email['mail_from']=$verify_mail_from;
         $arr_email['mail_from_name']=$verify_mail_from_name;
         $arr_email['subject']="EIDO Verify Account Information";
         $arr_email['body']=$email_template;
         send_email($arr_email);

         $ct++;
      }
   }

if ($action=="A") $action_str="added"; else $action_str="removed";
$msg = "$ct users $action_str<br />";
$_SESSION['bulk_msg'] = $msg;
header ("Location: users.php?m=bulk");
exit();
?>
