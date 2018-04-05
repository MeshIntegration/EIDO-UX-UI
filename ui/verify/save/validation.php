<!doctype html>
<?php
// need to change according to session

require_once './utilities.php';
require_once './lib/validation.php';
session_start();
$logfile = "validation.log";

$patientEpisodeId = get_query_string('patientEpisodeId');
$moreReminders = get_query_string('moreReminders');

$arr_pt_info = get_pt_info($patientEpisodeId);
if ($arr_pt_info['surname']<>"ERROR")
{
   $browser = $_SERVER['HTTP_USER_AGENT'];
   $ip_address = $_SERVER['REMOTE_ADDR'];
   add_to_timeline($patientEpisodeId, "Survey Email Clicked", "Open", "Event", $browser, $ip_address, "Validation");
   $_SESSION['arr_pt_info'] = $arr_pt_info;
}

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Auth V3 - Screen 1</title>
  <link rel="stylesheet" href="./css/foundation.css">
  <link rel="stylesheet" href="./css/eido.css">
  <link rel="stylesheet" href="./css/dashboard.css">
  <link rel="stylesheet" href="./css/app.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
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
		  <form class="login" action="validation_a.php?patientEpisodeId=<?php echo $patientEpisodeId; ?>&moreReminders=<?php echo $moreReminders; ?>" method="post">
		    <h6>Hello <?php echo $arr_pt_info['c_firstName']; ?>,</h6>
			<p>To help us check your identity, please enter your surname and address below.</p>
			<p>&nbsp;</p>
			<label>Surname
			  <div class="input-group">
                <span class="input-group-label"><i class="fi-torso"></i></span>
                <input class="input-group-field" type="text" name="c_surname" placeholder="Enter your surname">
              </div>
			</label>
			<label>Postcode
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-marker"></i></span>
                <input class="input-group-field" type="text" name="c_postalCode" placeholder="Enter your postcode">
              </div>
			</label>
			<label>Address
			  <div class="input-group login">
                <span class="input-group-label"><i class="fi-marker"></i></span>
				<select class="input-group-field" name="c_address" placeholder="Select your postcode">
                  <option value="postcode_1">82 Prentice Way, Bristol, BS7</option>
                  <option value="postcode_2">82 Prentice Way, Bristol, BS7</option>
                  <option value="postcode_3">82 Prentice Way, Bristol, BS7</option>
                  <option value="postcode_4">82 Prentice Way, Bristol, BS7</option>
                </select>  
              </div>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell"><button type="submit" name="" value="" class="button large float-right">NEXT</button></div>
		  </form>
		    <div class="small-12 cell">
			  <p><img src="./img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
		    </div>
		  </div>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	  </div>	  
    </div>
	<!-- End Content-Full -->
    <!-- Start Header -->
      <?php include './includes/val_footer.php';?>
    <!-- End Header -->
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
