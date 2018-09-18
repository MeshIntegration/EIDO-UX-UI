<?php
// *********************************
// login_a.php
// Copyright 2018, MindStreams LLC
// WEL 1/18/18
// 3/17/18
//   we use the dir_user_group table 
//   don't use the user_role table any more
//   Group info
//     eidoadmins - SuperUser dashboard
//     sitedivadmins - Admin or Patient Dashboard
//     staff or surgeon - Patient Dashboard
// *********************************

include "utilities.php";
$logfile = "admin.log";
session_start();
$_SESSION['login_error']=false;

$username = $_POST['username'];
$password= $_POST['password'];

$sql = "SELECT u.*, ug.*
        FROM dir_user u, dir_user_group ug
        WHERE u.username='$username'
        AND u.id=ug.userid
        AND u.active=1";
logMsg($sql,$logfile);

$GetQuery=dbi_query($sql);
if ($GetQuery->num_rows==0)
{
   $_SESSION['login_error']=true;
   $_SESSION['username']=$username;
   $_SESSION['password']=$password;
   logMsg("Incorrect email (user not found) $username $password - back to login.php", $logfile);
   header("Location: login.php");
   exit();
}
else {
    // get encrypted password
    $qryResult = $GetQuery->fetch_assoc();
    $hash = $qryResult['uipassword'];
    if (!password_verify($password, $hash)) {
        /* Invalid */
        $_SESSION['username']=$username;
        $_SESSION['password']=$password;
        $_SESSION['login_error'] = true;
        logMsg("Incorrect password. $username $password - back to login.php", $logfile);
        header("Location: login.php");
        exit();
    }
    unset($_SESSION['username']);
    unset($_SESSION['password']);

    $user_id = $qryResult['id'];
    is_setcookie("user_id", $user_id, 0, "/", $cookie_domain);

    // password reset required
    if ($qryResult['c_pw_reset'] == 1) {
        header("Location: change_password.php?rt=login");
        exit();
    }

    $fname = $qryResult['firstName'];
    $lname = $qryResult['lastName'];
    $fullname = "$fname $lname";
    $initials = strtoupper(substr($fname, 0, 1) . substr($lname, 0, 1));
    is_setcookie("user_fullname", $fullname, 0, "/", $cookie_domain);
    is_setcookie("user_initials", $initials, 0, "/", $cookie_domain);

    logMsg("AFTER COOKIES: FN $fullname - INITIALS $initials - GROUP " . $qryResult['groupId'], $logfile);
    if (strtolower($qryResult['groupId']) == "eidoadmins") {
        logMsg("$user_id - Logged in as SUPERUSER", $logfile);
        is_setcookie("user_role", "SUPERUSER", 0, "/", $cookie_domain);
        header("Location: /ui/verify/superuser/users.php");
        exit();
    }

//   Not a SuperUser so now figure out what group they are in
//   $sql="SELECT * FROM dir_user_group WHERE userid='$user_id'";
//   $GetQuery=dbi_query($sql);
//   $qryResult=$GetQuery->fetch_assoc();

logMsg(">>>>  GroupID: ".$qryResult['groupId'], $logfile);

    if ((strtolower($qryResult['groupId']) == "sitedivadmins" || strtolower($qryResult['groupId']) == "staff")) {
        //  determine what organisation they are with
        //  and set a cookie for the ORG ID

        $org_id = $qryResult['c_organizationId'];
        is_setcookie("org_id", $org_id, 0, "/", $cookie_domain);
    }

    if (strtolower($qryResult['groupId']) == "sitedivadmins") {
        is_setcookie("user_role", "ADMIN", 0, "/", $cookie_domain);
        header("Location: /ui/verify/patient/patients.php");
        exit();
    }

    if (strtolower($qryResult['groupId']) == "staff") {
        is_setcookie("user_role", "USER", 0, "/", $cookie_domain);
        header("Location: /ui/verify/patient/patients.php");
        exit();
    }
logMsg("MESSED UP - TRY AGAIN",$logfile);
    $_SESSION['login_error'] = true;
    header("Location: /ui/verify/login.php");
    exit();
}
?>
