<!doctype html>
<?php
// need to change according to session

require_once '../utilities.php';
$logfile = "wel.log";

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
  <title>Eido Verify - Patient Auth V3 - Screen 5</title>
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
<body class="alert_msg">
<div class="grid-container">
  <!-- Start Header -->
  <?php include '../includes/header.php';?>
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
			<p class="text-center alert"><i class="fi-alert alert alert_icon"></i><br />Data Check</p>
			<p>Please check that your Date of Birth is correct.</p>
			<label class="alert">Date of Birth
			  <div class="input-group">
                <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" 
				  title="Please enter your date of birth - this is required" data-position="top" data-alignment="left" id="tooltip_alert_day">
				    <i class="fi-calendar alert"></i>
				</span>
                <select class="input-group-field" placeholder="Day">
				  <option value="error_day_selected" selected="selected">Day</option>
                  <option value="error_day_1">01</option>
                  <option value="error_day_2">02</option>
                  <option value="error_day_3">03</option>
                  <option value="error_day_4">04</option>
                </select>
				<select class="input-group-field" placeholder="Month">
				  <option value="error_month_selected" selected="selected">Month</option>
                  <option value="error_month_1">01</option>
                  <option value="error_month_2">02</option>
                  <option value="error_month_3">03</option>
                  <option value="error_month">04</option>
                </select>
				<select class="input-group-field" placeholder="Year">
				  <option value="error_year_selected" selected="selected">Year</option>
                  <option value="error_year_1">2018</option>
                  <option value="error_year_2">2018</option>
                  <option value="error_year_3">2018</option>
                  <option value="error_year_4">2018</option>
                </select>
              </div>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large float-right">Next</button>
			</div>
		  </form>
		    <div class="small-12 cell">
			  <p><img src="../img/nottingham_logo.jpg" alt="" class="vendor"/></p>
		    </div>
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
		<p><a href="#" class="aux_help"><img src="../img/icons/help.png" alt="Need any help"/><br />Need any help?</a></p>
	    <p><a href="#" class="aux">Privacy Policy</a><br />
		<a href="#" class="aux">Terms &amp; Conditions</a></p>
		<p>Enter Security Badge Code here</p>
		<p>&nbsp;</p>
	  </div>
	</div>  
	<div class="hide-for-small-only medium-3 cell">&nbsp;</div>  
  </div>
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