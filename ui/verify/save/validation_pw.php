<!doctype html>
<?php
require_once './utilities.php';
require_once './lib/validation.php';
$logfile = "validation.log";

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Auth V3 - Screen 3</title>
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
  <?php include './includes/val_header.php';?>
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	    <div class="small-12 medium-6 align-center-middle cell">
		  <p>&nbsp;</p>
		  <form class="login" action="validation_pw_a.php" method="post">
			<p>&nbsp;</p>
			<h5>Now create a password, to save you time on your next visit.</h5>
			<label>Password
			  <div class="input-group">
                <span class="input-group-label"><i class="fi-lock"></i></span>
                <input class="input-group-field" name="password" type="password" placeholder="Enter your password"><br />
              </div>
			  <p><span class="">Insert Strength Meter Here</span></p>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="button" name="" value="" class="button large inactive text-left">Back</button>
			  <button type="submit" name="" value="" class="button large active float-right">Next</button>
			</div>
			<div class="grid-x">
			  <div class="small-1 cell">
			    <input id="skip" name="skip" type="checkbox" value="Y" class="">
			  </div>
			  <div class="small-11 cell"><label for="checkbox1" class="adjust">SKIP this step. I will enter my details again next time</label></div>
			</div>
		  </form>
		    <div class="small-12 cell">
			  <p><img src="./img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
		    </div>
		  </div>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	  </div>	  
    </div>
	<!-- End Content-Full -->
  </div>
  <?php include "./includes/val_footer.php"; ?>
  <!-- End Content --> 
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
