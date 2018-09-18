<!doctype html>
<?php
require_once '../utilities.php';
$logfile = "validation.log";

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";

// I don't think we know the preferred yet because of changing the flow around
// if ($_SESSION['entered_preferred']=="EMAIL")
   // $preferred = "email";
// else
   // $preferred = "SMS text message";

//  doing this in validation_goto_request.php so it does not duplicate entry 
//  if this page gets refreshed.
//$ip_address = $_SERVER['REMOTE_ADDR'];
//$browser = $_SERVER['HTTP_USER_AGENT'];
//add_to_timeline($arr_pt_info['id'], "Request review", "Open", "Alert", 
//         $browser, $ip_address, "Validation", $arr_pt_info['c_currentSessionNumber']);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EIDO Verify</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
  <script>
	    $( function() {
   $( "#tooltip_error_day" ).tooltip().tooltip("open");
});
	  </script>
</head>
<body class="alert_msg">
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
		  <form class="login">
			<p>&nbsp;</p>
			<p class="text-center alert">Review Requested</p>
			<p>Your data will be doubled checked and the results sent to you.</p>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			<a href="https://www.google.com" class="button large inactive float-right">Read More</a>
			</div>
			<div class="clear"></div>
		  </form>
		    <div class="small-12 cell">
			  <p><img src="../img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
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
