<!doctype html>
<?php
// **************************************
// val/login.php
// Copyright 2018, Mesh Integration LLC
// WEL 1/25/18
// **************************************

include "../utilities.php";
session_start();
$logfile = "validation.log";
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg'] = "";
$forgot_login_flag=get_query_string('f');
if (isset($forgot_login_flag))
   $email = $_SESSION['login_email_entered'];

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
		  <form class="login" action="login_a.php" method="post">
			<p>&nbsp;</p>
			<h1>Login</h1>
                    <?php if (strlen($error_msg)) { ?>
                       <label class="caution" >The e-mail or password entered do not match our records.<br />Please try again.</label>
                    <?php } ?>
			<label>E-mail
			  <div class="input-group">
                <span class="input-group-label"><i class="fi-mail"></i></span>
				<input id="username1" class="input-group-field" name="email" type="text" value="<?php echo $email; ?>" placeholder="Enter your e-mail address">
              </div>
			</label>
			<label>Password
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-lock"></i></span>
                <input class="input-group-field" type="password" name="password" id="form_password"  placeholder="Enter your password">
                <span class="input-group-label toggle-password" toggle="#form_password">SHOW</span>
              </div>
			  <p id="forgotpw" class="note text-right"><a href="validation_forgot_pw.php">I forgot my password</a></p>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large float-right">login</button>
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
  <?php include '../includes/val_footer.php';?>
  <!-- End Content --> 
</div>
      <script src="../js/vendor/jquery.js"></script>
      <script src="../js/vendor/what-input.js"></script>
      <script src="../js/vendor/foundation.js"></script>
      <script src="../js/app.js"></script>
      <script>
         $(document).ready(function () {
            $(".toggle-password").click(function() {
               var input = $($(this).attr("toggle"));
               if (input.attr("type") == "password") {
                  input.attr("type", "text");
                  $(this).html("HIDE");
               } else {
                  input.attr("type", "password");
                  $(this).html("SHOW");
               }
            })
	});
         $(document).ready(function (){
                if ( !$("#username1").val() ) {
                        $("#username1").val(sessionStorage.username2)
                }

                $("#forgotpw").click(function(){
                        var username1 = $("#username1").val();
                        sessionStorage.setItem('username1',username1);
                });
         }); 
      </script>  
   </body>
</html>
