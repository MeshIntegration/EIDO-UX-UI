<?php
// ***************************************
// superuser/organizations_a.php
// 2017 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************

require_once '../utilities.php';
session_start();
$logfile = "superuser.log";
$error_msg="";

$arr_ext=array();
$arr_ext[]="jpg";
$arr_ext[]="png";
$arr_ext[]="gif";

$mode = get_query_string('m');
$id = get_query_string('id');

// set up for creating admin user
list($fname, $lname) = explode(" ", $admin);
$password = "password";

if ($mode=="update")
{
   $org_id=$id;
   $name = $_POST['name'];
   $type = $_POST['type'];
   $email = $_POST['email'];
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $subdivision = $_POST['subdivision'];
   $existing_header_logo = $_POST['existing_header_logo'];
   logMsg("$mode $id $name $type $admin",$logfile);

   if ($_FILES['header_logo']['name']<>"")
   {
      // logMsg($_FILES['header_logo']['name'], $logfile);
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
   
   // get the user id for the org's admin
   $sql = "SELECT u.id
           FROM dir_user u, $TBLORGANISATIONS o
           WHERE o.id='$id'
           AND o.c_email=u.email";
   $GetQuery=dbi_query($sql);
   $qryResult=$GetQuery->fetch_assoc();
   $uid=$qryResult['id'];
   
   $user_id = $_COOKIE['user_id'];
   $sql = "UPDATE $TBLORGANISATIONS
           SET c_name=".escapeQuote($name).",
               c_type=".escapeQuote($type).",
               c_email=".escapeQuote($email).",
               c_logo=".escapeQuote($header_logo).",
               c_admin=".escapeQuote($admin).",
               c_subdivision=".escapeQuote($subdivision).",
               modifiedBy=".escapeQuote($user_id).",
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
   header("Location: organisations.php");
} 
else if ($mode=="add")
{
   logMsg("-------------- START ORG ADD ----------------",$logfile);
   // INSERT
   $org_id = uniqid();
   $name = $_POST['name'];
   $type = $_POST['type'];
   $email = $_POST['email'];
   $fname = $_POST['fname'];
   $lname = $_POST['lname'];
   $subdivision = $_POST['subdivision'];
   $existing_header_logo = $_POST['existing_header_logo'];
   logMsg("$mode $id $name $type $admin",$logfile);

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
      $_SESSION['fname'] = $fname;
      $_SESSION['lname'] = $lname;
      $_SESSION['subdivision'] = $subdivision;
      header("Location: organisations.php?m=add");
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
   $sql = "INSERT INTO dir_user
           SET id=".escapeQuote($email).",
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
   header("Location: organisations.php");
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
?>
