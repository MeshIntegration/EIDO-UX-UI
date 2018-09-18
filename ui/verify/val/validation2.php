<!doctype html>
<?php

require_once '../utilities.php';
session_start();
$entered_nhsnumber = "";
if ( $_SESSION['entered_nhsnumber']<>"") {
    $entered_nhsnumber = $_SESSION['entered_nhsnumber'];
}
$dob_day = "";
if ( $_SESSION['entered_dob_day']<>"") {
    $dob_day = $_SESSION['entered_dob_day'];
}
$dob_month = "";
if ( $_SESSION['entered_dob_month']<>"") {
    $dob_month = $_SESSION['entered_dob_month'];
}
$dob_year = "";
if ( $_SESSION['entered_dob_year']<>"") {
    $dob_year = $_SESSION['entered_dob_year'];
}
if ($dob_day<>"" && $dob_month<>"" && $dob_year<>"") {
    $entered_dob = "$dob_day/$dob_month/$dob_year";
    $_SESSION['entered_dob'] = $entered_dob;
}
$arr_pt_info = get_pt_info($_SESSION['patientEpisodeId']);
$_SESSION['patientEpisodeId'] = $arr_pt_info['id'];
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
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.5">
  <title>EIDO Verify</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
  <script>
//	    $(function() {
//   $( "#tooltip_error_day" ).tooltip("open");
//  });
	  </script>
</head>
<body class="registration">
<div class="grid-container">
  <!-- Start Header -->
    <?php include "../includes/val_header.php"; ?>
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
		<div class="hide-for-small-only medium-3 large-3 cell">&nbsp;</div>
	    <div class="small-12 medium-6 align-center-middle cell">
		  <form class="login standard-padding two-x" action="validation2_a.php" method="post">
			  <p class='lead'>What is your date of birth?</p>
                            <?php if ($_SESSION['dob_error']) { ?>
                                  <div class='error_message fi-alert'><strong>Please enter your Date of Birth</strong> - this is required</div>
                            <?php } ?>
              <label class="<?php echo $date_class; ?> label-dob" style="margin-bottom: 0px !important; margin-top: 10px;">Date of Birth</label>
			  <div class="input-group" style="width: available;">
          <?php if ($date_class=="caution") { ?>
             <span class="input-group-label flex-auto has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please enter your date of birth - this is required" data-position="top" data-alignment="left" id="tooltip_error_day">
          <?php } else { ?>
             <span class="input-group-label flex-auto input-bg-white" style="margin-left: 0px; padding-left: 10px; padding-right: 10px;">
          <?php } ?>
             <i class="smallmediumicon eido-icon-calendar-o <?php echo $date_class; ?>"></i>
                    <span class="select float-left flex-container">
		             <select class="select input-group-field input-group-label flex-auto" name="dob_day" id="dob_day" style="margin-left: 10px !important;">
                   <?php include "../includes/select_day.html"; ?>
                </select>
                         </span>
                <span class="select float-center flex-container">
				<select class="select input-group-field input-group-label flex-auto" name="dob_month" id="dob_month">
                   <?php include "../includes/select_month.html"; ?>
                </select>
                    </span>
                  <span class="select float-right flex-container">
                  <select class="select input-group-field input-group-label flex-auto" name="dob_year" id="dob_year">
                      <?php include "../includes/select_year.html"; ?>
                  </select>
                  </span>
              <!--</div>-->
                 </span>
          </div>
			  <p class="lead">And finally your NHS number?</p>
                            <?php if ($_SESSION['nhsnumber_error']) { ?>
                                  <div class='error_message fi-alert'><strong>Please enter your NHS Number</strong> - this is required</div>
                            <?php } ?>
	<label style="margin-top: 10px;">NHS Number
	  <div class="input-group login float-center flex-container">
                <span class="input-group-label "><i class="mediumicon eido-icon-dot-circle-o"></i></span>
               <input class="input-group-field" type="text" pattern="[0-9]*" name="c_nhsNumber" placeholder="Enter your NHS number" value="<?php echo $entered_nhsnumber; ?>">
          </div>
        <p><span style="color: #0D2240; font-size:small;">You can find your NHS Number on a letter from your GP or hospital, or on a medical ID card.</span></p>
	</label>
	<div class="small-12 text-right cell"><p>&nbsp;</p></div>
		<div class="small-12 cell" style="width: available;">
			  <button type="submit" name="" value="" class="button large inactive float-left" style="display: none">Back</button>
			  <button type="submit" name="" value="" class="button large float-right">Next</button>
		</div>
	  </form>
            <div class="small-12 cell">
                <p><img src="../img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
            </div>
        </div>
          <div class="hide-for-small-only medium-3 large-3 cell">&nbsp;</div>
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
              if ($("#dob_day").val().length < 2) {
                  $("#dob_day").addClass("selectplaceholder");
                  $("#dob_day").removeClass("blue-text");
              }
          });
          $(document).ready(function () {
              $("#dob_day").change(function () {
                  if (this.value.length < 2) {
                      $("#dob_day").addClass("selectplaceholder");
                      $("#dob_day").removeClass("blue-text");
                      // $("#dob_day").click();
                  }
                  else {
                      $("#dob_day").removeClass("selectplaceholder");
                      $("#dob_day").addClass("blue-text");
                  }
              });
              $("#dob_day").focus(function () {
                  if (this.value.length < 2) {
                      $("#dob_day").addClass("blue-text");
                  }
              });
              $("#dob_day").blur(function () {
                  if (this.value.length < 2) {
                      $("#dob_day").addClass("selectplaceholder");
                      $("#dob_day").removeClass("blue-text");
                  }
              });
              $("#dob_day").mousedown(function () {
                  if (this.value.length < 2) {
                      if ($("#dob_day").hasClass("selectplaceholder")) {
                          if ($("#dob_day").is(":focus")) {
                              $("#dob_day").addClass("blue-text");
                              $("#dob_day").blur();
                              $("#dob_day").focus();
                          }
                          else {
                              $("#dob_day").removeClass("selectplaceholder");
                              $("#dob_day").addClass("blue-text");
                              $("#dob_day").blur();
                              $("#dob_day").focus();
                              //$("#dob_day").change();
                          }
                      }
                      else {
                          $("#dob_day").addClass("selectplaceholder");
                          $("#dob_day").removeClass("blue-text");
                      }
                  }
                  else {

                  }
              });
          });
          $(document).ready(function () {
              if ($("#dob_month").val().length < 2) {
                  $("#dob_month").addClass("selectplaceholder");
                  $("#dob_month").removeClass("blue-text");
              }
          });
          $(document).ready(function () {
              $("#dob_month").change(function () {
                  if (this.value.length < 2) {
                      $("#dob_month").addClass("selectplaceholder");
                      $("#dob_month").removeClass("blue-text");
                      // $("#dob_month").click();
                  }
                  else {
                      $("#dob_month").removeClass("selectplaceholder");
                      $("#dob_month").addClass("blue-text");
                  }
              });
              $("#dob_month").focus(function () {
                  if (this.value.length < 2) {
                      $("#dob_month").addClass("blue-text");
                  }
              });
              $("#dob_month").blur(function () {
                  if (this.value.length < 2) {
                      $("#dob_month").addClass("selectplaceholder");
                      $("#dob_month").removeClass("blue-text");
                  }
              });
              $("#dob_month").mousedown(function () {
                  if (this.value.length < 2) {
                      if ($("#dob_month").hasClass("selectplaceholder")) {
                          if ($("#dob_month").is(":focus")) {
                              $("#dob_month").addClass("blue-text");
                              $("#dob_month").blur();
                              $("#dob_month").focus();
                          }
                          else {
                              $("#dob_month").removeClass("selectplaceholder");
                              $("#dob_month").addClass("blue-text");
                              $("#dob_month").blur();
                              $("#dob_month").focus();
                              //$("#dob_month").change();
                          }
                      }
                      else {
                          $("#dob_month").addClass("selectplaceholder");
                          $("#dob_month").removeClass("blue-text");
                      }
                  }
                  else {

                  }
              });
          });
          $(document).ready(function () {
              if ($("#dob_year").val().length < 2) {
                  $("#dob_year").addClass("selectplaceholder");
                  $("#dob_year").removeClass("blue-text");
              }
          });
          $(document).ready(function () {
              $("#dob_year").change(function () {
                  if (this.value.length < 2) {
                      $("#dob_year").addClass("selectplaceholder");
                      $("#dob_year").removeClass("blue-text");
                      // $("#dob_year").click();
                  }
                  else {
                      $("#dob_year").removeClass("selectplaceholder");
                      $("#dob_year").addClass("blue-text");
                  }
              });
              $("#dob_year").focus(function () {
                  if (this.value.length < 2) {
                      $("#dob_year").addClass("blue-text");
                  }
              });
              $("#dob_year").blur(function () {
                  if (this.value.length < 2) {
                      $("#dob_year").addClass("selectplaceholder");
                      $("#dob_year").removeClass("blue-text");
                  }
              });
              $("#dob_year").mousedown(function () {
                  if (this.value.length < 2) {
                      if ($("#dob_year").hasClass("selectplaceholder")) {
                          if ($("#dob_year").is(":focus")) {
                              $("#dob_year").addClass("blue-text");
                              $("#dob_year").blur();
                              $("#dob_year").focus();
                          }
                          else {
                              $("#dob_year").removeClass("selectplaceholder");
                              $("#dob_year").addClass("blue-text");
                              $("#dob_year").blur();
                              $("#dob_year").focus();
                              //$("#dob_year").change();
                          }
                      }
                      else {
                          $("#dob_year").addClass("selectplaceholder");
                          $("#dob_year").removeClass("blue-text");
                      }
                  }
                  else {

                  }
              });
          });

      </script>  
   </body>
</html>
