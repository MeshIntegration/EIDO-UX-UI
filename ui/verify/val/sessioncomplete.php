<!doctype html>
<?php
require_once '../utilities.php';
session_start();

$patientEpisodeId = get_query_string('id');
$session = get_query_string('sess');
$arr_pt_info = get_pt_info($patientEpisodeId);
$c_firstName=$arr_pt_info['c_firstName'];

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Authenication</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
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
		  <p>&nbsp;</p>
		  <h1><?php echo $arr_pt_info['c_firstName']; ?>,</h1>
		  <p>You have completed your survey and have successfully logged out. Thank you for your participation.</p>
                  <p>&nbsp;</p>
             </div>	  
          </div>
    <!-- End Content-Full -->
     </div>
    <!-- Start Footer -->
      <?php include '../includes/val_footer.php';?>
    <!-- End Footer -->
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
