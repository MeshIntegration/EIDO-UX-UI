<!doctype html>
<?php
// **************************************
// val/validation_forgot_pw.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/26/18
// **************************************

include "../utilities.php";
session_start();
$logfile = "validation.log";
if (isset($_SESSION['pe_id']))
   $arr_pt_info=get_pt_info($_SESSION['pe_id']);
if (isset($_SESSION['login_email_entered']))
   $email = $_SESSION['login_email_entered'];
else
   $email="";

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Auth V3 - Screen 2</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script>
	    $( function() {
   $( "#tooltip_error_day" ).tooltip().tooltip("open");
});
	  </script>
</head>
<body class="registration">
<div class="grid-container">
  <!-- Start Header -->
  <?php include '../includes/val_header.php';?>
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	    <div class="small-12 medium-6 align-center-middle cell">
		  <p>&nbsp;</p>
		  <form class="login" action="validation_forgot_pw_a.php" method="post">
			<p>&nbsp;</p>
			<h1>Forgot Password</h1>
                        <p>Send a password reset link to your registered email address.</p>
			<label>E-mail
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-mail"></i></span>
                <input class="input-group-field" name="email" type="text" placeholder="Enter your e-mail" value="<?php echo $email; ?>">
              </div>
			  <p class="note text-right"><a href="login.php?f=1">I remembered! Back to Login</a></p>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large float-right">Send Link</button>
			</div>
		  </form>
		    <div class="small-12 cell">
			  <p><hr></p>
		    </div>
		  </div>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	  </div>	  
    </div>
	<!-- End Content-Full -->
  </div>
  <?php include "../includes/val_footer.php"; ?>
  <!-- End Content --> 
</div>
      <script src="../js/vendor/jquery.js"></script>
      <script src="../js/vendor/what-input.js"></script>
      <script src="../js/vendor/foundation.js"></script>
      <script src="../js/app.js"></script>
      <script>
         $(document).ready(function () {
		   
         });
      </script>  
   </body>
</html>
