<!doctype html>
<?php
// message.php
// Copyright 2018, Mesh Integration LLC

require_once './utilities.php';
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$logfile = "wel.log";
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EIDO Verify</title>
  <link rel="stylesheet" href="./css/foundation.css">
  <link rel="stylesheet" href="./css/eido.css">
  <link rel="stylesheet" href="./css/dashboard.css">
  <link rel="stylesheet" href="./css/app.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
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
		<p>&nbsp;</p>
		<h6 class="caution"><?php echo $error_msg; ?></h6>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	     </div>	  
          </div>
      </div>
  </div>
<!-- End Content-Full -->
  <!-- footer --> 
  <?php include "./includes/footer.php"; ?>
  <!-- end footer --> 
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
