<!doctype html>
<?php
// need to change according to session

require_once 'utilities.php';
$logfile = "wel.log";
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg'] = "";

if ($error_msg<>"")
{
  echo "<script>";
  echo "$('a.errorModal').trigger('click');";
  echo "</script>";
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
  <script>
	    $( function() {
   $( "#tooltip_error_day" ).tooltip().tooltip("open");
});
	  </script>
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
				<input class="input-group-field" type="text" name="username" placeholder="Enter your e-mail address">
                          </div>
			</label>
			<label>Password
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-lock"></i></span>
                <input class="input-group-field" type="password" name="password" placeholder="Enter your password">
              </div>
			  <p class="note text-right"><a href="#">I forgot my password</a></p>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large float-right">login</button>
			</div>
		  </form>
                  <a href="#" data-reveal-id="errorModal">WAYNE</a>
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

<div id="errorModal" class="reveal-modal small" data-reveal aria-labelledby="Login Error" aria-hidden="true" role="dialog">
  <h2 id="modalTitle">Please try again...</h2>
  <p>Something is wrong with your email or passsord. Please check them and try again.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

      <script src="./js/vendor/jquery.js"></script>
      <script src="./js/vendor/what-input.js"></script>
      <script src="./js/vendor/foundation.js"></script>
      <script src="./js/app.js"></script>
      <script>
         $(document).ready(function () {
		   
         });
      </script>  
   </body>
</html>
