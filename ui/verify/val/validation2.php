<!doctype html>
<?php

require_once '../utilities.php';
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$logfile = "validation.log";

// we don't think bots will make it to the second screen
//$browser = $_SERVER['HTTP_USER_AGENT'];
//if (strpos(strtolower($browser), "bit.ly"))
//{
   //logMsg("Validation: bitlybot detected", $logfile);
   //exit();
//}
//$ip_address = $_SERVER['REMOTE_ADDR'];
//add_to_timeline($patientEpisodeId, "Survey Email Clicked", "Open", "Event", $browser, $ip_address, "Validation");
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
		  <form class="login standard-padding two-x" action="validation2_a.php" method="post">
			  <p class='lead'>What is your date of birth?</p>
                            <?php if ($_SESSION['dob_error']) { ?>
                                  <div class='error_message fi-alert'><strong>Please enter your Date of Birth</strong> - this is required</div>
                            <?php } ?>

		  <label class="<?php echo $date_class; ?> label-dob" style="">Date of Birth
			  <div class="input-group">
          <?php if ($date_class=="caution") { ?>
             <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please enter your date of birth - this is required" data-position="top" data-alignment="left" id="tooltip_error_day">
          <?php } else { ?>
             <span class="input-group-label input-bg-white">
          <?php } ?>
             <i class="fi-calendar <?php echo $date_class; ?>"></i></span>
	             <div class="select">
		             <select class="input-group-field" name="dob_day" placeholder="Day">
                   <?php include "../includes/select_day.html"; ?>
                </select>
	             </div>

	             <div class="select">

				<select class="input-group-field" name="dob_month" placeholder="Month">
                   <?php include "../includes/select_month.html"; ?>
                </select>
	             </div>
	             <div class="select">

				<select class="input-group-field" name="dob_year" placeholder="Year">
                   <?php include "../includes/select_year.html"; ?>
                </select>
	             </div>
              </div>
	</label>
			  <p class="lead">And finally your NHS number?</p>
                            <?php if ($_SESSION['nhsnumber_error']) { ?>
                                  <div class='error_message fi-alert'><strong>Please enter your NHS Number</strong> - this is required</div>
                            <?php } ?>
	<label>NHS Number
	  <div class="input-group login">
                <span class="input-group-label"><i class="fi-target"></i></span>
               <input class="input-group-field" type="text" name="c_nhsNumber" placeholder="Enter your number">
          </div>
		  <p class="note text-left font-normal">You can find your NHS Number on a letter from your GP or hospital, or on a medical ID card.</p>
	</label>
	<div class="small-12 text-right cell"><p>&nbsp;</p></div>
		<div class="small-12 cell">
			  <button type="submit" name="" value="" class="button large inactive float-left">Back</button>
			  <button type="submit" name="" value="" class="button large float-right">Next</button>
		</div>
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

  <!-- footer --> 
      <?php include "../includes/val_footer.php"; ?>
  <!-- end footer --> 
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
