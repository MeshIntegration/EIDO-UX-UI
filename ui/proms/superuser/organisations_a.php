<?php
// ***************************************
// superuser/organizations_a.php
// 2017 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************

require_once '../utilities.php';
session_start();
$logfile = "wel.log";
$error_msg="";
$arr_ext=array();
$arr_ext[]="jpg";
$arr_ext[]="png";
$arr_ext[]="gif";

$mode = get_query_string('m');
$id = get_query_string('id');
$name = $_POST['name'];
$type = $_POST['type'];
$email = $_POST['email'];
$admin = $_POST['admin'];
$subdivision = $_POST['subdivision'];
$existing_header_logo = $_POST['existing_header_logo'];

logMsg("$mode $id $name $type $admin",$logfile);

// set up for creating admin user
list($fname, $lname) = explode(" ", $admin);
$password = "password";

if ($mode=="update")
{
   if ($_FILES['header_logo']['name']<>"")
   {
logMsg($_FILES['header_logo']['name'], $logfile);
      if ($_FILES['header_logo']['error'] > 0)
      {
         $error_msg .= "There was an error uploading your file.<br >";
         $error_msg .= $_FILES['header_logo']['error']."<br >";
         logMsg("File Upload Error: ".$_FILES['header_logo']['error'], $logfile);
      }
      else
      {
         $loc = strrpos($_FILES['header_logo']['name'], ".");
         $ext = strtolower(substr($_FILES['header_logo']['name'], $loc+1));
         if (!in_array($ext, $arr_ext))
            $error_msg .= "File must be an image format.<br >";
      }
   }
   if (strlen($error_msg)>0)
   {
      $_SESSION['error_msg'] = $error_msg;
      header("Location: organisations.php?m=update&id=$id");
   }

   //Upload the file
   if ($_FILES['header_logo']['size'] > 0)
   {
      $target_file_name = $org_id.".".$ext;
      $target_file_path = $ABS_PATH."img/org_logos/".$target_file_name;
logMsg($_FILES['header_logo']['tmp_name'], $logfile);
logMsg($target_file_path, $logfile);
logMsg("Existing logo: $existing_header_logo", $logfile);
      move_uploaded_file($_FILES['header_logo']['tmp_name'], $target_file_path);
      $header_logo=$target_file_name;
   }
   else if ($existing_header_logo<>"")
      $header_logo = $existing_header_logo;
   else
      $header_logo = "";
   
   // get the user id for the org's admin
   $sql = "SELECT u.id
           FROM dir_user u, app_fd_pro_organizations o
           WHERE o.id='$id'
           AND o.c_email=u.email";
   $GetQuery=dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $uid=$qryResult['id'];
   
   $sql = "UPDATE app_fd_pro_organizations
           SET c_name=".escapeQuote($name).",
               c_type=".escapeQuote($type).",
               c_email=".escapeQuote($email).",
               c_logo=".escapeQuote($header_logo).",
               c_admin=".escapeQuote($admin).",
               c_subdivision=".escapeQuote($subdivision).",
               dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);

   $sql = "UPDATE dir_user 
           SET username=".escapeQuote($email).",
               password=".escapeQuote($password).",
               firstName=".escapeQuote($fname).",
               lastName=".escapeQuote($lname).",
               email=".escapeQuote($email).",
               active='1',
               timezone='0'
            WHERE id='$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);

   $sql = "DELETE FROM dir_user_role WHERE userId='$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);

   $sql = "DELETE FROM dir_user_group WHERE userId='$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);
   
   $sql = "INSERT INTO dir_user_role
           SET roleId = 'ROLE_USER',
               userId = '$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId = 'admin',
               userId = '$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);
} 
else
{
   // INSERT
   $org_id = uniqid();

   if ($_FILES['header_logo']['name']<>"")
   {
logMsg($_FILES['header_logo']['name'], $logfile);
      if ($_FILES['header_logo']['error'] > 0)
      {
         $error_msg .= "There was an error uploading your file.<br >";
         $error_msg .= $_FILES['header_logo']['error']."<br >";
         logMsg("File Upload Error: ".$_FILES['header_logo']['error'], $logfile);
      }
      else
      {
         $loc = strrpos($_FILES['header_logo']['name'], ".");
         $ext = strtolower(substr($_FILES['header_logo']['name'], $loc+1));
         if (!in_array($ext, $arr_ext))
            $error_msg .= "File must be an image format.<br >";
      }
   }
   if (strlen($error_msg)>0)
   {
      $_SESSION['error_msg'] = $error_msg;
      $_SESSION['name'] = $name;
      $_SESSION['type'] = $type;
      $_SESSION['email'] = $email;
      $_SESSION['admin'] = $admin;
      $_SESSION['subdivision'] = $subdivision;
      header("Location: organisations.php?m=add");
   }

   //Upload the file
   if ($_FILES['header_logo']['size'] > 0)
   {
      $target_file_name = $org_id.".".$ext;
      $target_file_path = $ABS_PATH."img/org_logos/".$target_file_name;
logMsg($_FILES['header_logo']['tmp_name'], $logfile);
logMsg($target_file_path, $logfile);
      move_uploaded_file($_FILES['header_logo']['tmp_name'], $target_file_path);
      $header_logo=$target_file_name;
   }

   $sql = "INSERT INTO app_fd_pro_organizations
           SET id='".$org_id."',
               c_name=".escapeQuote($name).",
               c_type=".escapeQuote($type).",
               c_email=".escapeQuote($email).",
               c_admin=".escapeQuote($admin).",
               c_logo=".escapeQuote($header_logo).",
               c_subdivision=".escapeQuote($subdivision).",
               dateModified=NOW(),
               dateCreated=NOW()";
   dbi_query($sql);
logMsg($sql,$logfile);

   $uid = uniqid();
   $sql = "INSERT INTO dir_user
           SET id='$uid',
               username=".escapeQuote($email).",
               password=".escapeQuote($password).",
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
               userId = '$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_group
           SET groupId = 'admin',
               userId = '$uid'";
   dbi_query($sql);
logMsg($sql,$logfile);
}
header("Location: organisations.php");
exit();

?>
