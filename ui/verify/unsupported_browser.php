<!doctype html>
<?php
require_once 'utilities.php';
session_start();
$logfile = "wel.log";
logMsg("Login Page - /ui/verify/unsupported_browser.php", $logfile);
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
  <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>

</head>
<body class="registration">

<div class="grid-container">
  <!-- Start Header -->
    <div class="grid-x hide-for-small-only show-for-medium">
        <div class="medium-8 large-8 cell">
           <!-- <span class="type float-left"><?php echo $user_initials; ?></span> -->
            <!-- extra links commented out for now
            <span class="float-left links one_link"><a href="#"><img src="/ui/verify/img/add.png" alt="Add Patient" class="add_icon"/>Add Patient</a></span>
            <span class="float-left links one_link"><a href="#"><img src="/ui/verify/img/add.png" alt="Add Patient" class="add_icon"/>Second Link</a></span>
            -->
        </div>
        <div class="medium-4 large-4 cell"><a href="/"><img src="/ui/verify/img/eido_logo.png" alt="EIDO Logo" class="logo float-right"/></a></div>
    </div>
    <!-- Start Mobile Nav -->
    <div class="grid-x hide-for-medium">
        <div class="small-12 cell text-right"><a href="<?php echo $home; ?>"><img src="/ui/verify/img/eido_logo.png" alt="EIDO Logo" class="logo"/></a></div>
        <div class="small-12 cell toggle">
            <div class="grid-x">
                <div class="small-10 cell">&nbsp;</div>
                <div class="small-2 cell text-right">
                   <!-- remove mobile nav from header -->
                  <!--  <div class="title-bar" data-responsive-toggle="mobile_menu" data-hide-for="medium">
                        <div class="title-bar-left"></div>
                       <!-- <div class="title-bar-right"><button class="menu-icon" type="button" data-toggle="mobile_menu"></button></div> -->
                    </div>
                </div>
            </div>
        <!-- remove mobile nav from header -->
           <!-- <div class="top-bar" id="mobile_menu" data-animate="hinge-in-from-top spin-out">
                <div class="top-bar-left"><button type="button" name="" value="" class="button expanded">Add Patient</button></div>
                <hr />
                <div class="top-bar-right">
                    <div class="grid-x">
                        <div class="small-12 cell text-center"><span class="type_mobile">AB</span></div>
                        <div class="small-12 cell text-center"><a href="#">My Account</a></div>
                        <hr />
                        <div class="small-12 cell text-center"><a href="#">Help</a></div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <!-- End Mobile Nav -->
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
          <div class="hide-for-small-only medium-3 cell">&nbsp;</div>
            <div class="small-12 medium-6 align-center-middle cell">
            <hr>
            <p>&nbsp;</p>
            <h1 class="text-center">Incompatible Browser</h1>
            <p>&nbsp;</p>
            <h5 class="text-center">This system is intended to be used on a tablet or desktop browser and will not function on a mobile device.</h5>
            <p>&nbsp;</p>
            <hr>
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
            <p>&nbsp;</p>
            <p><a href="#" class="aux_help" ><i class="icon eido-icon-chat"></i> <br><span style="font-size: large">Need any help?</span></a></p>
            <br>
            <p style="font-size: small;">Copyright EIDO Systems Ltd, 2018. All rights reserved. <a href="#" class="bluelink" style=""><span class="bluelink" style="font-family: 'Lato-Light', 'Lato Light', 'Lato', sans-serif; font-weight: 400; font-style: normal;">Legal</span></a></p>

        </div>
	</div>  
    <div class="hide-for-small-only medium-3 cell">&nbsp;</div>  
  </div>
  <!-- End Content --> 
</div>
<script src="./js/vendor/jquery.js"></script>
<script src="./js/vendor/what-input.js"></script>
<script src="./js/vendor/foundation.js"></script>
<script src="./js/app.js"></script>
<script>

</script>
</body>

</html>