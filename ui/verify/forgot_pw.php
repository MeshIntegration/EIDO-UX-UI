<?php
// **************************************
// forgot_pw.php
// Copyright 2018, Mesh Integration LLC
// WEL 2/4/18
// **************************************

include "./utilities.php";
session_start();
$logfile = "validation.log";

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Auth V3 - Screen 2</title>
  <link rel="stylesheet" href="./css/foundation.css">
  <link rel="stylesheet" href="./css/eido.css">
  <link rel="stylesheet" href="./css/dashboard.css">
  <link rel="stylesheet" href="./css/app.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
</head>
<body class="registration">
<div class="grid-container">
  <!-- Start Header -->
  <?php include './includes/header.php';?>
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	    <div class="small-12 medium-6 align-center-middle cell">
		  <p>&nbsp;</p>
		  <form class="login" action="forgot_pw_a.php" method="post">
			<p>&nbsp;</p>
			<h1>Forgot Password</h1>
                        <p>Send a password reset link to your registered email address.</p>
			<label>E-mail
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-mail"></i></span>
                <input id="email" class="input-group-field" name="email" type="text" placeholder="Enter your e-mail address" value="<?php echo $_SESSION['login_email']; ?>">
              </div>
			  <p id="backtologin" class="note text-right"><a href="login.php">I remembered! Back to Login</a></p>
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
  <?php include "./includes/footer.php"; ?>
  <!-- End Content --> 
</div>
      <script src="./js/vendor/jquery.js"></script>
      <script src="./js/vendor/what-input.js"></script>
      <script src="./js/vendor/foundation.js"></script>
      <script src="./js/app.js"></script>
      <script>
        $(document).ready(function () {
                        if (sessionStorage.getItem("remembered") == 1) {
                            $(function () {
                                $("#email").focus();
                                $("#email").val(sessionStorage.username);
                                sessionStorage.setItem("remembered",0);
                                $("#email").blur();
                            })
                        }
        });


        $(document).ready(function () {
                 $("#backtologin").click(function () {
		     if( $("#email").val().length > 0 ) {
                        var username = $("#email").val();
                        sessionStorage.setItem("username",username);
                        sessionStorage.setItem("remembered",1);
                     }
                 });
        });
      </script>  
   </body>
</html>
