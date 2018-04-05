<!doctype html>
<?php
require_once './utilities.php';
require_once './lib/validation.php';
$logfile = "validation.log";

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";

if ($_SESSION['error_ct']==1)
{
   $data_msg = "Data Check";
   if ($_SESSION['surname_error']) $data_msg2 = "Please check the spelling of your Surname.";
   if ($_SESSION['postalcode_error']) $data_msg2 = "Please check that your Post Code is correct.";
   if ($_SESSION['dob_error']) $data_msg2 = "Please check that your Date of Birth is correct.";
   if ($_SESSION['nhsnumber_error']) $data_msg2 = "Please check that your NHS Number is correct.";
}
else //  ($_SESSION['error_ct']>1)
{
   $data_msg = "We couldn't match your data";
   $data_msg2 = "Please check the data yiou entered below.";
}
if ($_SESSION['dob_error']) 
{
   $dob_day = substr($_SESSION['entered_dob'],0,2);
   $dob_month = substr($_SESSION['entered_dob'],3,2);
   $dob_year = substr($_SESSION['entered_dob'],6,4);
   logMsg("Date parts: $dob_day  $dob_month  $dob_year", $logfile);
}

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Auth V3 - Screen 5</title>
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
<body class="alert_msg">
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
		  <form class="login">
			<p>&nbsp;</p>
			<p class="text-center alert"><i class="fi-alert alert alert_icon"></i><br /><?php echo $data_msg; ?></p>
			<p><?php echo $data_msg2; ?></p>
            <?php if ($_SESSION['surname_error']) { ?>
		<label class="alert">Surname
		  <div class="input-group">
                     <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please check the spelling of your surname" data-position="top" data-alignment="left" id="tooltip_alert_day"><i class="fi-torso alert"></i></span>
                     <input class="input-group-field" type="text" name="c_surname" value="<?php echo $_SESSION['entered_surname']; ?>">
                 </div>
	      </label>
            <?php } ?>
            <?php if ($_SESSION['postalcode_error']) { ?>
		<label class="alert">Postal Code
		  <div class="input-group">
                     <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please check that your Postal Cose is correct" data-position="top" data-alignment="left" id="tooltip_alert_day"><i class="fi-torso alert"></i></span>
                     <input class="input-group-field" type="text" name="c_postalCode" value="<?php echo $_SESSION['entered_postalcode']; ?>">
                 </div>
	      </label>
            <?php } ?>
            <?php if ($_SESSION['dob_error']) { ?>
		<label class="alert">Date of Birth
		  <div class="input-group">
                     <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please check that your Date of Birth is correct" data-position="top" data-alignment="left" id="tooltip_alert_day"><i class="fi-calendar alert"></i></span>
                <select class="input-group-field" name="dob_day" placeholder="Day">
                   <?php include "./includes/select_day.html"; ?>
                </select>
                <select class="input-group-field" name="dob_month" placeholder="Month">
                   <?php include "./includes/select_month.html"; ?>
                </select>
                <select class="input-group-field" name="dob_year" placeholder="Year">
                   <?php include "./includes/select_year.html"; ?>
                </select>
                 </div>
	      </label>
            <?php } ?>
            <?php if ($_SESSION['nhsnumber_error']) { ?>
		<label class="alert">NHS NUMBER
		  <div class="input-group">
                     <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please check that your NHS Number is correct" data-position="top" data-alignment="left" id="tooltip_alert_day"><i class="fi-torso alert"></i></span>
                     <input class="input-group-field" type="text" name="c_nhsNumber" value="<?php echo $_SESSION['entered_nhsnumber']; ?>">
                 </div>
	      </label>
            <?php } ?>
		<div class="small-12 text-right cell"><p>&nbsp;</p></div>
		<div class="small-12 cell">
		  <button type="submit" name="" value="" class="button large float-right">UPDATE</button>
		</div>
            <?php if ($_SESSION['error_ct']==2) { // soft fail ?>
		<div class="small-12 text-right cell"><p>&nbsp;</p></div>
                <div class="clear"></div>
		<div class="small-12 cell">
                  <p>If you are 100% sure the data is correct, please click the button below for a review.</p>
		  <a href="validation_request.php"><button type="button" name="" value="" class="button large float-right inactive">Request Review</button></a>
		</div>
            <?php } ?>
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
