<!doctype html>
<?php
// need to change according to session
require_once 'utilities.php';
session_start();
$logfile = "wel.log";
logMsg("Login Page - /ui/verify/login.php", $logfile);
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg'] = "";
if ($error_msg<>"")
{
   $modal_popup = "open" ;
}else{
   $modal_popup = "close";
}
if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}
?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - User Login</title>
  <link rel="stylesheet" href="./css/foundation.css">
  <link rel="stylesheet" href="./css/eido.css">
  <link rel="stylesheet" href="./css/dashboard.css">
  <link rel="stylesheet" href="./css/app.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
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
		  <form class="login" action="login_a.php" method="post">
			<p>&nbsp;</p>
			<h1>Login</h1>
			<label>E-mail
			  <div class="input-group">
                                <span class="input-group-label"><i class="fi-mail"></i></span>
				<input id="username1" class="input-group-field" type="text" name="username" placeholder="Enter your e-mail address" autofocus>
                          </div>
			</label>
			<label>Password
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-lock"></i></span>
                <input id="password" class="input-group-field" type="password" name="password" placeholder="Enter your password">
              </div>
			  <p id="forgotpw" class="text-right" style="font-weight:200"><a href="forgot_pw.php">I forgot my password</a></p>
			</label>
			<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large float-right">login</button>
			</div>
		  </form>
		  </div>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	  </div>	  
    </div>
	<!-- End Content-Full -->
  </div>
  <div class="grid-x">
    <div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	<div class="small-12 medium-6 cell">
	  <div class="small-12 cell text-center">
		<p>&nbsp;</p>
		<p><a href="#" class="aux_help"><img src="./img/icons/help.png" alt="Need any help"/><br />Need any help?</a></p>
	        <p>Copyright EIDO Systems Ltd, 2018. All rights reserved. <a href="#" class="aux">Legal</a></p>
		<p>&nbsp;</p>
	  </div>
	</div>  
    <div class="hide-for-small-only medium-3 cell">&nbsp;</div>  
  </div>
  <!-- End Content --> 
</div>

<div class="reveal" id="errorModal" data-reveal>
  <h1>Please try again...</h1>
  <p><?php echo $error_msg;?></p>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
      <script src="./js/vendor/jquery.js"></script>
      <script src="./js/vendor/what-input.js"></script>
      <script src="./js/vendor/foundation.js"></script>
      <script src="./js/app.js"></script>
      <script>
        $(document).ready(function () {
                $('#errorModal').foundation('<?php echo $modal_popup;?>');
		
	});
	$(document).ready(function () {
		
			if (sessionStorage.getItem('remembered') == "1") {
			$(function () {				
				$("#username1").focus();
                        	$("#username1").val(sessionStorage.username);
				sessionStorage.removeItem('username');
				sessionStorage.removeItem('remembered');
				if !($("#username1").val() == ""){
				$("#password").focus();
				}
                	})
		}
		});
		
		$(document).ready(function () {
                $("#forgotpw").click(function () {
                        var username1 = $("#username1").val();
                        sessionStorage.setItem('username',username1);
                })
         });
      </script>  
   </body>
</html>
