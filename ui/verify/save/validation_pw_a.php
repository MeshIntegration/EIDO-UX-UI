<?php
// **************************************
// validation_pw_a.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/25/18
// **************************************

include "./utilities.php";
include "./lib/validation.php";
session_start();
$_SESSION['error_msg']="";

$logfile = "validation.log";

$password=$_POST['password'];
$skip=$_POST['skip'];

if ($skip=="" && $password=="")
{
   $_SESSION['error_msg'] = "NO_PASSWORD";
   header ("Location: validation_pw.php");
   exit();
}

if ($skip=="Y")
   $_SESSION['entered_password']="";
else
   $_SESSION['entered_password']=$password;

header ("Location: validation_mobile.php");
?>
