<!doctype html>
<?php
// change_password.php - for users
// Copyright 2018, Mesh Integration LLC
// WEL 2/28/18

require_once 'utilities.php';
require_once 'lib/validation.php';
$logfile = "validation.log";

session_start();
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";
if ($error_msg=="NO_PASSWORD")
   $msg = "You must enter a new password.";
if ($error_msg=="NO_OLD_PASSWORD")
   $msg = "You must enter your current password.";
if ($error_msg=="WRONG_OLD_PASSWORD")
   $msg = "The current password password you entered does not match our records.";

$user_id = $_COOKIES['user_id'];
$arr_user_info = get_user_info($user_id);
$return_to = get_query_string('rt');

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EIDO Verify</title>
  <link rel="stylesheet" href="css/foundation.css">
  <link rel="stylesheet" href="css/eido.css">
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="css/app.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="js/jquery-1.12.4.min.js"></script>
  <script>
  $(document).ready(function(){
  });
  </script>
  <style>
     .is-active{
        pointer-events: auto;
        cursor: auto;
     }
     .not-active {
        pointer-events: none;
        cursor: default;
     }
  </style>
</head>
<body class="registration">
<div class="grid-container">
  <!-- Start Header -->
  <?php include 'includes/val_header.php';?>
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
            <div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	    <div class="small-12 medium-6 align-center-middle cell">
		  <p>&nbsp;</p>
		  <form class="login" action="change_password_a.php?rt=<?php echo $return_to; ?>" method="post" id="form_container" >

			<p></p>
			<h1>Change Password</h1>
			<p>Create a new password for your account.</p>
			<!--<div class="grid-x">
			  <div class="small-11 cell"><label class="adjust">Current Password</label></div>
                          <input type ="password" name="old_password">
                          </label>
			</div>-->



              <div class="grid-x hide">
                  <label class="hide">Email
                  <input type="email" class="hide">
                  </label>
              </div>
              <div class="small-12 cell field padding-bottom-1 padding-top-1 padding-left-0 padding-right-0">
                  <?php if ($error_msg<>"") { ?><div class='oldpassval error_message fi-alert'><strong><?php echo $msg; ?></strong></div> <?php } ?>
              <label class="">Current Password
                  <div class="input-group">
                      <span class="input-group-label"><i class="fi-lock"></i></span>
                      <input class="input-group-field" id="old_password"  name="old_password" type="password" placeholder="Enter your current password" autocomplete="password"><br />
                  </div>
              </label>
              </div>
              <div class="grid-x hide">
                  <label class="hide">Email
                      <input type="email" class="hide">
                  </label>
              </div>
			<label>New Password
			  <div class="input-group">
                <span class="input-group-label"><i class="fi-lock"></i></span>
                <input class="input-group-field" id="form_password"  name="password" type="password" placeholder="Create a new password" autocomplete="new-password"><br />
              </div>
                        <div class="progress password_strength_indicator" role="progressbar" tabindex="0" aria-valuemax="100">
                           <span class="progress-meter" style="width: 0%">
                              <p class="progress-meter-text" style="transform: translate(-0%, -50%)">&nbsp;Password Strength</p>
                           </span>
                        </div>
                        </label>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="button" name="" value="" class="hide button large inactive text-left">Back</button>
			  <button id="btn_next" type="submit" name="" value="" class="button large inactive not-active float-right">Save</button>
			</div>
		  </form>
		    <div class="small-12 cell">
			  <p><img src="img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
		    </div>
		  </div>
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	  </div>	  
    </div>
	<!-- End Content-Full -->
  </div>
  <?php include "includes/footer.php"; ?>
  <!-- End Content --> 
</div>
      <script src="js/vendor/jquery.js"></script>
      <script src="js/vendor/what-input.js"></script>
      <script src="js/vendor/foundation.js"></script>
      <script src="js/app.js"></script>
      <script src="js/vendor/pwstrength/pwstrength.js"></script>
      <script>
         $(document).ready(function () {

            function allow_next(is_allow){
               if(is_allow === true){
                  $("#btn_next").removeClass("inactive").addClass("active");
                  $("#btn_next").removeClass("not-active").addClass("is-active");
               }else if(is_allow === false){
                  $("#btn_next").removeClass("active").addClass("inactive");
                  $("#btn_next").removeClass("is-active").addClass("not-active");
               }
            }
      
            $("#skip").change(function(){
               if($(this).is(':checked')){
                  allow_next(true);
               }else{
                  allow_next(false);
               }
            });

            var options = {};
            options.common = {
               minChar: 4,           
               debug: false,
               onKeyUp: function (event,data) {
                  // verdictLevel [-1 to 4] : ["Weak", "Normal", "Medium", "Strong", "Very Strong"]
                  if (parseInt(data.verdictLevel) == -1){
                     data.verdictLevel = 0;
                  }
                  var verdictClass = ["alert","","warning","success","success"] ;
                  var progressInitClass = "progress password_strength_indicator";
                  var progressStatusClass = progressInitClass + " " + verdictClass[parseInt(data.verdictLevel)];
                  var percent = (parseInt(data.verdictLevel) * 20)+20 ; 
                  
                  // set progress class, bar and text
                  $(".password_strength_indicator").removeClass().addClass(progressStatusClass);
                  $(".progress-meter").css({"width": + percent + "%"});
                  $(".progress-meter-text").html(data.verdictText);
                  // reset progress strength
                  if ($("#form_password").val().length == 0){
                     $(".password_strength_indicator").removeClass().addClass(progressInitClass);
                     $(".progress-meter-text").html("Password Strength");
                     $(".progress-meter").css({"width": "0%"});      
                  }
                  // manage form submit
                  if (parseInt(data.verdictLevel) > 2){
                     allow_next(true); 
                  }else{
                     allow_next(false);
                  }
               }
            };
            options.ui = {
               container: "#form_container",
               showProgressBar: false,
               showErrors: true,
               showVerdictsInsideProgressBar: false,
               viewports: {
                  progress: ".password_strength_indicator"
               }
            };
            options.rules = {
               scores:{
                  wordNotEmail: -100,
                  wordLength: -10,
                  wordSimilarToUsername: -100,
                  wordSequences: -50,
                  wordTwoCharacterClasses: 2,
                  wordRepetitions: -25,
                  wordLowercase: 1,
                  wordUppercase: 3,
                  wordOneNumber: 3,
                  wordThreeNumbers: 5,
                  wordOneSpecialChar: 5,
                  wordTwoSpecialChar: 5,
                  wordUpperLowerCombo: 2,
                  wordLetterNumberCombo: 2,
                  wordLetterNumberCharCombo: 2
               },
               activated: {
                  wordNotEmail: true,
                  wordLength: true,
                  wordSimilarToUsername: true,
                  wordSequences: true,
                  wordTwoCharacterClasses: false,
                  wordRepetitions: false,
                  wordLowercase: true,
                  wordUppercase: true,
                  wordOneNumber: true,
                  wordThreeNumbers: true,
                  wordOneSpecialChar: true,
                  wordTwoSpecialChar: true,
                  wordUpperLowerCombo: true,
                  wordLetterNumberCombo: true,
                  wordLetterNumberCharCombo: true
               }
            };
            $('#form_password').pwstrength(options);
         });
      </script>  
   </body>
</html>
