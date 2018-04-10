<?php
// ***************************************
// superuser/users_a.php
// 2017 Copyright, Mesh Integration LLC
// 02/19/18 - SD
// ***************************************

require_once '../utilities.php';
$logfile = "superuser.log";

$mode = get_query_string('m');
$id = get_query_string('id');
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];

if ($mode=="update")
{
   $sql = "UPDATE dir_user
           SET firstName=".escapeQuote($firstName).",
               lastName=".escapeQuote($lastName).",
               email=".escapeQuote($email)."
           WHERE id='$id'";
   dbi_query($sql);
} 
else if ($mode=="userreset")
{
   //global $TBLPTEPISODES;
   $sql = "UPDATE dir_user
           SET c_pw_reset=1,
               c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   header("Location: users.php");
   exit();
}
else if ($mode=="userdelete")
{
   $sql = "UPDATE dir_user
           SET active=0,
           c_dateModified=NOW()
           WHERE id='$id'";
   dbi_query($sql);
   header("Location: users.php");
   exit();
}
else
{
   $id = substr(strtolower($firstName),0,1).strtolower($lastName);

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
//   $hash = password_hash($password, PASSWORD_BCRYPT);

//   if (!password_verify($password, $hash)) {
//      /* Invalid hash generation*/
//      header("Location:".$_SERVER['HTTP_REFERER']);
//      exit;
//   }

   // INSERT
   //          password=".$hash.",
   $sql = "INSERT INTO dir_user
           SET firstName=".escapeQuote($firstName).",
               lastName=".escapeQuote($lastName).",
               email=".escapeQuote($email).",
               password=".$password.",
               active=1,
               timeZone='0',
               id='$id',
               username='$email'";
   dbi_query($sql);
logMsg($sql,$logfile);

   $sql = "INSERT INTO dir_user_role
           SET roleId='ROLE_ADMIN',
               userId='$id'";
   dbi_query($sql);
   logMsg($sql,$logfile);

   header("Location: users.php");
   exit();
}
header("Location: users.php");
exit();

?>
