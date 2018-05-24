<!doctype html>
<?php
require_once '../utilities.php';
$logfile = "validation.log";

session_start();
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";
$arr_pt_info = $_SESSION['arr_pt_info'];
$ip_address = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];

// if we are here they must of just accepted TC
set_accepted_tc($arr_pt_info['id']);

// check the data that was entered against what is in PatientEpisodes
$error_ct = 0;
$_SESSION['surname_error'] = false;
$_SESSION['postalcode_error'] = false;
$_SESSION['dob_error'] = false;
$_SESSION['nhsnumber_error'] = false;

logMsg("validation_review: Surname Entered: ".$_SESSION['entered_surname']." - DB: ".$arr_pt_info['c_surname'],$logfile);
if (strtoupper($_SESSION['entered_surname']) <> strtoupper($arr_pt_info['c_surname']))
{
   logMsg("Surname Error",$logfile);
   $_SESSION['surname_error'] = true;
   $error_ct++;
}
logMsg("validation_review: PostalCode Entered: ".$_SESSION['entered_postalcode']." - DB: ".$arr_pt_info['c_postalCode'],$logfile);
// in the DB it is stored with no space so ceck it that way
$entered_postalcode=strtoupper(str_replace(" ", "", $_SESSION['entered_postalcode']));
if ($entered_postalcode <> $arr_pt_info['c_postalCode'])
{
   logMsg("PostalCode Error",$logfile);
   $_SESSION['postalcode_error'] = true;
   $error_ct++;
}
logMsg("validation_review: DOB Entered: ".$_SESSION['entered_dob']." - DB: ".$arr_pt_info['c_dateOfBirth'],$logfile);
if ($_SESSION['entered_dob'] <> $arr_pt_info['c_dateOfBirth'])
{
   logMsg("DOB Error",$logfile);
   $_SESSION['dob_error'] = true;
   $error_ct++;
}
logMsg("validation_review: NHS Entered: ".$_SESSION['entered_nhsnumber']." - DB: ".$arr_pt_info['c_nhsNumber'],$logfile);
if ($_SESSION['entered_nhsnumber'] <> $arr_pt_info['c_nhsNumber'])
{
   logMsg("NHS Error",$logfile);
   $_SESSION['nhsnumber_error'] = true;
   $error_ct++;
}

$_SESSION['error_ct']=$error_ct;
logMsg("validation_review: Error Count: $error_ct", $logfile);

// Check moreReminders and handle if true
if ($_SESSION['moreReminders']=="true")
{
   $requestParam = array( 'var_patientEpisodeId' => $id);
   // we need to get process number from DB and put into URL
   //Change the server URL for live vs. dev
   $URL = "http://verify.eidosystems.com:8080/jw/web/json/workflow/process/list?packageId=".$packageId;
   $response = getCurlResponse($URL, array(), 1, "POST", "BASIC_AUTH");
   if ($response->total > 0)
   {
      foreach (array_slice($response->data, 0) as $key => $value)
      {
         if ($value->name == 'Patient Validation')
         {
            $process_id = str_replace("#", ":", $value->id);
         }
      }
   }
   else
   {
      ; // need to redirect to some page if process id not found
   }
   //Change the server URL for live vs. dev
   if (isset($process_id)) {
      $URL = "http://verify.eidosystems.com:8080/jw/web/json/workflow/process/start/" . $process_id;
      $resp = getCurlResponse($URL, $requestParam, 1, "POST");
   }
// print response
//print_r($resp);
//exit();
}

// this code can only be hit if they get 0 or 1 error first try
// otherwise this gets done in review_a.php
if ($error_ct==0 || $error_ct==1) 
{
   // Need to save the entered data so it can be checked in the request review section 3/8/18
   //     last two params are mobile and preferred which we don't have yet after flow change 
   //     so just rewrite what is in db for now
   $mobile=$arr_pt_info['c_mobileNumber'];
   $preferred=$arr_pt_info['c_preferredContactMethod'];
   save_pt_info($arr_pt_info['id'], $_SESSION['entered_surname'], $_SESSION['entered_postalcode'], $_SESSION['entered_dob'], $_SESSION['entered_nhsnumber'], $_SESSION['entered_password'], $mobile, $preferred, $arr_pt_info['c_emailAddress']);



    // Ammends to validation flow, now we go to mobile page and badger the patient for more peices of contact information.
    // - Andrew


   if ( $arr_pt_info['c_emailAddress']<>"" && $arr_pt_info['c_mobilePageDone']=="YES") {
       logMsg("Mobile page done already - pt has email - going to validate_pw, then on to survey...",$logfile);
      // patient has an email so can create a password to login
       // and they have completed mobile/email contact preferences page,
       // so take them to the validation_pw screen (patient can create a new password, or they can skip and go straight to survey)
       // patients with contact preference set to mobile, but who also have an email will be directed here.
       // they can create a password and then login with email & password, or skip through the screen and go straight to surveys.
       //  This may need modified to pass patients set to mobile phone preference directly to surveys.
      header ("Location: validation_pw.php");
      exit();
   } else {
       if ($arr_pt_info['c_mobilePageDone']=="YES") {
           logMsg("Mobile page done already - no email - go directly to survey...",$logfile);
           // WE HAVE LIFT OFF - take them to the correct survey

      // no email - go to survey if the mobile page was already done


         $goto_url = get_survey_url($arr_pt_info);
         $_SESSION = array();
         session_destroy();
         header ("Location: $goto_url");
         exit();
      } else {
         logMsg("Going to mobile.php...",$logfile);
         header ("Location: validation_mobile.php");
         exit();
      }
   }
}
// else if ($error_ct==2)
//   add_to_timeline($arr_pt_info['id'], "Patient validation error (soft fail)", "Open", "Info", 
//                   $browser, $ip_address, "Validation", $arr_pt_info['c_currentSessionNumber']);
//else if ($error_ct>=3)
//   add_to_timeline($arr_pt_info['id'], "Patient validation error (hard fail)", "Open", "Alert", 
//                   $browser, $ip_address, "Validation", $arr_pt_info['c_currentSessionNumber']);

if (false)   // ($_SESSION['error_ct']==1)  just hanging onto this for the messages
{
   $data_msg = "Data Check";
   if ($_SESSION['surname_error']) $data_msg2 = "Please check the spelling of your Surname.";
   if ($_SESSION['postalcode_error']) $data_msg2 = "Please check that your Post Code is correct.";
   if ($_SESSION['dob_error']) $data_msg2 = "Please check that your Date of Birth is correct.";
   if ($_SESSION['nhsnumber_error']) $data_msg2 = "Please check that your NHS Number is correct.";
}

$data_msg = "We couldn't match your data";
$data_msg2 = "Please check the data you entered below.";

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
<body class="alert_msg registration">
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
			<p class="text-center alert lead" style="margin-bottom: 0;"><i class="fi-alert alert alert_icon"></i><br /><?php echo $data_msg; ?></p>
			<p class="text-center lead"><?php echo $data_msg2; ?></p>
		    <form class="login standard-padding two-x" action="validation_review_a.php" method="post">

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
		<label class="alert label-dob" style="margin-bottom:20px !important;">Date of Birth
		  <div class="input-group">
                     <span class="input-group-label has-tip input-bg-white" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please check that your Date of Birth is correct" data-position="top" data-alignment="left" id="tooltip_alert_day"><i class="fi-calendar alert"></i></span>
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
            <?php } ?>
            <?php if ($_SESSION['nhsnumber_error']) { ?>
		<label class="alert">NHS NUMBER
		  <div class="input-group">
                     <span class="input-group-label has-tip" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Please check that your NHS Number is correct" data-position="top" data-alignment="left" id="tooltip_alert_day"><i class="fi-torso alert"></i></span>
                     <input class="input-group-field" type="text" name="c_nhsNumber" value="<?php echo $_SESSION['entered_nhsnumber']; ?>">
                 </div>
                 <p><span style="font-color:red;font-size:small;">You can find your NHS Number on a letter from your GP or hospital or on a medical ID card.</span><p>
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
		  <a href="validation_goto_request.php"><button type="button" name="" value="" class="button large float-right inactive">Request Review</button></a>
		</div>
            <?php } ?>
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
        <?php include "../includes/val_footer.php"; ?>  
  <!-- End Content --> 
</div>
      <script src="../js/vendor/jquery.js"></script>
      <script src="../js/vendor/what-input.js"></script>
      <script src="./js/vendor/foundation.js"></script>
      <script src="../js/app.js"></script>
      <script>
         $(document).ready(function () {
		   
         });
      </script>  
   </body>
</html>
