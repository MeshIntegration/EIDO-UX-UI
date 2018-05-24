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
  <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <!--following script is required for comodo secure seal logo, there is a corresponding required script tag inside the html body tag-->
   <!-- <script type="text/javascript">//<![CDATA[
        var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
        document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
        //]]>
    </script>-->
</head>
<body class="registration">
<!--//following script is required for comodo secure seal logo, there is a corresponding required script tag inside the html head tag-->
<!--<script language="JavaScript" type="text/javascript">
    TrustLogo("http://verify.eidosystems.com/ui/verify/img/comodo113x59.png", "SC5", "none");
</script>
<a href="https://ssl.comodo.com/ev-ssl-certificates.php" id="comodoTL">EV SSL</a>-->
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
                          <?php if ($_SESSION['login_error']) { ?>
                                <div class='error_message fi-alert'><strong>Your email or password was incorrect.</strong> - please try again</div>
                          <?php } ?>
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
			  <button id="login" type="submit" name="" value="" class="button large float-right">Login</button>
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
        //save the value that's in the email field upon click of login button and store the value to session storage, or store a value of 0 if blank
    $(document).ready(function() {
        $('#login').click(function() {
            if ($("#username1").val().length > 0) {
                var username = $("#username1").val();
                sessionStorage.setItem("username", username);
            } else {
                var username = "0";
                sessionStorage.setItem("username", username);
            }
        });
        //on close of the invalid login error popup, if it exists (is not 0), fill the email field with email from session storage and put focus on the password field
        $('.close-button').click(function() {
            //   if ( (sessionStorage.username).val().length > 0 ) {
            if (sessionStorage.getItem('username') != "0") {
                $("#username1").val(sessionStorage.username);
                $("#password").focus();
            } else {
                $("#username1").val();
                $("#username1").focus();
            }
        });
        $('#errorModal').foundation('<?php echo $modal_popup;?>');

    });
    // if email is in session storage and remembered flag in session storage is 1, fill email field with the session storage value(passed from forgot password page --> I remembered link)
    $(document).ready(function() {
        if (sessionStorage.getItem("remembered") == 1) {
            $(function() {
                $("#username1").focus();
                $("#username1").val(sessionStorage.username);
                sessionStorage.setItem("remembered", 0);
                $("#username1").blur();
                $("#password").focus();
            })
        }
    });

    //store email value in session storage and set remembered flag to 1 when user clicks I forgot my password link
    $(document).ready(function() {
        $("#forgotpw").click(function() {
            if ($("#username1").val().length > 0) {
                var username = $("#username1").val();
                sessionStorage.setItem("username", username);
                sessionStorage.setItem("remembered", 1);
            }
        })
    });
</script>
</body>

</html>
