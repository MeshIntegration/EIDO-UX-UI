<!doctype html>
<?php
require_once '../utilities.php';
$logfile="validation.log";
session_start();
$entered_postalcode = "";
if ( $_SESSION['entered_postalcode']<>"") {
    $entered_postalcode = $_SESSION['entered_postalcode'];
}
$entered_surname = "";
if ( $_SESSION['entered_surname']<>"") {
    $entered_surname = $_SESSION['entered_surname'];
}
if ($_SESSION['surname_error']) {
    $surname_error = $_SESSION['surname_error'];
}
if ($_SESSION['postalcode_error']) {
    $postalcode_error = $_SESSION['postalcode_error'];
}
// initialize
$_SESSION = array();
$_SESSION['hard_fail']=false;

if ( $entered_postalcode<>"") {
    $_SESSION['entered_postalcode'] = $entered_postalcode;
}
if ( $entered_surname<>"") {
    $_SESSION['entered_surname'] = $entered_surname;
}

$patientEpisodeId = get_query_string('patientEpisodeId');
$_SESSION['patientEpisodeId'] = $patientEpisodeId;
$moreReminders = get_query_string('moreReminders');
$_SESSION['moreReminders'] = $moreReminders;
$dev = get_query_string('dev');

$arr_pt_info = get_pt_info($patientEpisodeId);
logMsg("Logo: ".$arr_pt_info['logo'],$logfile);

if ($arr_pt_info['c_surname']<>"ERROR") {
   $browser = $_SERVER['HTTP_USER_AGENT'];
   if (strpos(strtolower($browser), "bit.ly")) {
      logMsg("Validation: bitlybot detected", $logfile);
      exit();
   }
   $ip_address = $_SERVER['REMOTE_ADDR'];
   add_to_timeline($patientEpisodeId, "Survey Email Clicked", "Open", "Event", 
                   $browser, $ip_address, "Validation", $arr_pt_info['c_currentSessionNumber']);

   $_SESSION['arr_pt_info'] = $arr_pt_info;
   $_SESSION['moreReminders'] = $moreReminders;
   $_SESSION['dev'] = $dev;
   logMsg("validation: DB pw: ".$arr_pt_info['c_password'],"validation.log");
   logMsg("validation: Session login_failed: ".$_SESSION['login_failed'], "validation.log");
   if ($arr_pt_info['c_password']<>"" && !isset($_SESSION['login_failed'])) {
      logMsg("Going to Pt Login...",$logfile);
      $_SESSION['pe_id']=$patientEpisodeId;
      header("Location:login.php");
      exit();
   }
} else {
   logMsg("Validation: got a bad PatientEpisondeId in the URL: $patientEpisodeId", $logfile);
   ; // got a bad PatientEpisondeId in the URL - how to notify and who?
}

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

		    <h1 class="text-center">Hello, <?php echo $arr_pt_info['c_firstName']; ?>.</h1>
			<p class="lead text-center">To help us check your identity, please enter your surname and address below.</p>
		    <form class="login standard-padding two-x" action="validation_a.php?patientEpisodeId=<?php echo $patientEpisodeId; ?>&moreReminders=<?php echo $moreReminders; ?>" method="post">
			<p>&nbsp;</p>
                        <?php if ($surname_error) { ?>
                              <div class='error_message fi-alert'><strong>Please enter your Surname</strong> - this is required</div>
                        <?php } ?>
			<label>Surname
			  <div class="input-group">
                <span class="input-group-label"><i class="icon eido-icon-user2"></i></span>
                <input class="input-group-field" type="text" name="c_surname" placeholder="Enter your surname" value="<?php echo $entered_surname; ?>">
              </div>
			</label>
                        <?php if ($postalcode_error) { ?>
                              <div class='error_message fi-alert'><strong>Please enter your Postcode</strong> - this is required</div>
                        <?php } ?>
			<label>Postcode
			  <div class="input-group login">
                <span class="input-group-label"><i class="icon eido-icon-location23"></i></span>
                <input class="input-group-field" type="text" name="c_postalCode" placeholder="Enter your postcode" value="<?php echo $entered_postalcode; ?>">
              </div>
			</label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell"><button type="submit" name="" value="" class="button large float-right">Next</button></div>
		  </form>
		    <div class="small-12 cell">
			  <p><img src="../img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
		    </div>
		  </div>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	  </div>	  
    </div>
	<!-- End Content-Full -->
    <!-- Start Header -->
      <?php include '../includes/val_footer.php';?>
    <!-- End Header -->
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
