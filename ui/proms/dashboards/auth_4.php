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
  <title>Eido Verify - Patient Auth V3 - Screen 4</title>
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
			<p>We don't have a mobile number for you.</p>
            <p>If you add it, we can text you with updates...</p>
			<label>Mobile Number
			  <div class="input-group">
                <span class="input-group-label"><i class="fi-telephone"></i></span>
                <input class="input-group-field" type="text" placeholder="Enter your mobile number"><br />
              </div>
			</label>
			<div class="grid-x grid-padding-x">
  			  <div class="small-12 cell">
                <label>Which contact method would you prefer?<br />
                <input type="radio" name="preferred_contact_email" value="Email" id="preferred_contact_email" required><label for="preferred_contactRed">E-mail</label><br />
                <input type="radio" name="preferred_contact_mobile" value="Mobile" id="preferred_contact_mobile"><label for="preferred_contactBlue">Mobile</label>
				</label>
              </div>
			</div>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large inactive text-left">Back</button>
			  <button type="submit" name="" value="" class="button large float-right">Next</button>
			</div>
			<div class="grid-x">
			  <div class="small-1 cell">
			    <input id="checkbox1" type="checkbox" class="">
			  </div>
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