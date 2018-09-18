<!doctype html>
<?php
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

$logfile = "validation.log";
require_once '../utilities.php';
session_start();
$arr_pt_info = get_pt_info($_SESSION['patientEpisodeId']);
$patientEpisodeId = $arr_pt_info['id'];
$_SESSION['patientEpisodeId'] = $arr_pt_info['id'];
if($arr_pt_info['c_preferenceSet'] == "YES" && $arr_pt_info['c_acceptedTC'] == "YES" && $arr_pt_info['c_password'] == "") {
	header("Location: validation_pw.php?patientEpisodeId=$patientEpisodeId");
	exit();
}
if($arr_pt_info['c_preferenceSet'] == "YES" && $arr_pt_info['c_acceptedTC'] == "YES" && $arr_pt_info['c_password'] <> "") {
    $goto_url = get_survey_url($arr_pt_info);
    $_SESSION = array();

    header ("Location: $goto_url");
    exit();
}


$showmobile = "hide";
$showemail = "hide";


if($arr_pt_info['c_mobileNumber'] == "") {
	$showmobile = "show";
	$showemail = "hide";
	$prompt = "mobile";
	$mobile_checked = "";
	$email_checked = "checked";
	$contactmethod = "hide";

}
if($arr_pt_info['c_emailAddress'] == "") {
	$showmobile = "hide";
	$showemail = "show";
	$prompt = "email";
	$mobile_checked = "checked";
	$email_checked = "";
	$contactmethod = "hide";
}
if($arr_pt_info['c_emailAddress'] <> "" && $arr_pt_info['c_mobileNumber'] <> "") {
	$showmobile = "show";
	$showemail = "hide";
	$prompt = "mobileconfirm";
	$mobile_checked = "";
	$email_checked = "checked";
	$contactmethod = "show";
}

$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg'] = "";
logMsg("Validation_Mobile page: " . $arr_pt_info['id'], $logfile);

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
	<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script>
	//	$(function() {
	//		$("#tooltip_error_day").tooltip().tooltip("open");
	//	});
	</script>
</head>
<body class="registration">
<div class="grid-container">
	<!-- Start Header -->
	<?php include '../includes/val_header.php'; ?>
	<!-- End Header -->
	<!-- Start Content -->
	<div class="grid-x grid-margin-x su">
		<!-- Start Content-Full -->
		<div class="small-12 medium-12 large-12 cell content-full">
			<div class="grid-x">
				<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
				<div class="small-12 medium-6 align-center-middle cell">
					<p>&nbsp;</p>
                    <h1 class="text-center">Contact Details</h1>
					<form class="login" action="validation_mobile_a.php" method="post">
						<?php if($arr_pt_info['c_mobileNumber'] == "") { ?>
							<p class="lead"  style="font-size: medium; padding-left: 20px; padding-right: 20px;">We don't have a mobile number for you.<br />If you add it, we can text you with updates...</p>
						<?php } ?>
                        <?php if($prompt == "mobileconfirm") { ?>
							<p class="lead" style="font-size: medium;">Do we have the correct mobile number for you?<br />
									</p>
						<?php } ?>
						<?php if($arr_pt_info['c_emailAddress'] == "") { ?>
                            <p class="lead" style="font-size: medium;">We don't have an email address for you.<br />
									If you add it, we can send you email with updates...</p>
						<?php } ?>
						<?php if($error_msg == 'NO_MOBILE') { ?>
							<div class='error_message fi-alert <?php echo $showmobile; ?>'><strong>Please enter a mobile number</strong></div>
						<?php } ?>
						<div id="mobilecontainer" class="<?php echo $showmobile; ?>">
                            <label style="font-size: large;">Mobile Number
								<div class="input-group">
									<span class="input-group-label"><i class=" largeicon eido-icon-mobile"></i></span>
									<input id="mobile" class="inputfield input-group-field" pattern="[0-9]*" name="mobile" type="text" value="<?php echo $arr_pt_info['c_mobileNumber']; ?>" placeholder="Enter your mobile number"><br/>
								</div>
							</label>
						</div>
						<?php if($error_msg == 'NO_EMAIL') { ?>
							<div class='error_message fi-alert'><strong>Please enter an email address</strong></div>
						<?php } ?>
						<div id="emailcontainer" class="<?php echo $showemail; ?>">
							<label style="font-size: 20px;">Email Address
								<div class="input-group">
									<span class="input-group-label"><i class="largeicon fi-mail"></i></span>
									<input class="inputfield input-group-field" id="email" name="email" type="text" value="<?php echo $arr_pt_info['c_emailAddress']; ?>" placeholder="Enter your email address"><br/>
								</div>
						</div>
						</label>

						<div id="contactdiv" class="<?php echo $contactmethod; ?> grid-x grid-padding-x">
							<div name="preferred" id="contactpreference" class="small-12">
								<p class="lead">Which contact method would you prefer?</p>
			                      <label class="fancy-eido-round-checkbox">
			                          <input type="radio" name="preferred" value="EMAIL" <?php echo $email_checked; ?> id="preferred_contact_email"/>
			                          <span for="preferred_contactRed" class="checkmark"></span>
				                      <span class="control-label">Email</span>
			                      </label>
			                      <label class="fancy-eido-round-checkbox">
			                          <input type="radio" name="preferred" value="MOBILE" <?php echo $mobile_checked; ?> id="preferred_contact_mobile"/>
			                          <span for="preferred_contactBlue" class="checkmark"></span>
				                      <span class="control-label">Mobile</span>
			                      </label>
							</div>
						</div>
						<div name="preferenceset" id="preference_set" class="hide small-12">
                            <input type="radio" name="preferenceset" value="NO" id="preference_set_no"><label for="NO">NO</label><br>
                            <input type="radio" name="preferenceset" value="YES" id="preference_set_yes"><label for="YES">YES</label><br>
						</div>
						<div class="small-12 text-right cell"><p>&nbsp;</p></div>
						<div class="grid-x row">
							<div class="small-6 cell text-left">
								<button type="button" name="" value="" class="button large inactive text-left" style="display: none">Back</button>
							</div>
							<div class="small-6 text-right">
								<button type="submit" name="" value="" class="button large float-right">Next</button>
							</div>
						</div>
                        <div class="small-12 text-right cell"><p>&nbsp;<br /></p></div>
					</form>
                    <img src="../img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/><!--</p>-->
                </div>
                <div class="hide-for-small-only medium-3 cell">&nbsp;</div>
            </div>

		</div>
		<?php include "../includes/val_footer.php"; ?>
		<!-- End Content-Full -->
	</div>
	<!-- End Content -->
</div>
<script src="../js/vendor/jquery.js"></script>
<script src="../js/vendor/what-input.js"></script>
<script src="../js/vendor/foundation.js"></script>
<script src="../js/app.js"></script>
<script>
    $(document).ready(function() {
//show contact preference div if email is entered and matches valid email format
        $("#email").keyup(function() {
            if( this.value.indexOf('@') > -1 && this.value.indexOf('.') > -1 && this.value.length > 4 && this.value.length < 64 && this.value.slice(-1) !== ('.') ) {
                $("#contactdiv").removeClass('hide').addClass('show');
                $('input[type="radio"][name="preferenceset"]').last().prop('checked', true);
            }
            else
            {
                $('input[type="radio"][name="preferenceset"]').first().prop('checked', true);
                $("#contactdiv").removeClass('show').addClass('hide');
            }
        })
//show contact preference div if mobile is entered and is greater that ten length
// NEEDS ADJUSTED TO ADD SOME TWILLIO COMPATIBLE PHONE NUMBER FORMAT VALIDATION
	$("#mobile").keyup(function() {
            if( this.value.length > 10 && this.value.length < 64 ) {
                $("#contactdiv").removeClass('hide').addClass('show');
                $('input[type="radio"][name="preferenceset"]').last().prop('checked', true);
            }
            else
            {
                $("#contactdiv").removeClass('show').addClass('hide');
                $('input[type="radio"][name="preferenceset"]').first().prop('checked', true);
            }
        })
    });
    $(document).ready(function() {
        $(function() {
            if ($("#mobile").val().length > 9) {
                $('input[type="radio"][name="preferenceset"]').last().prop('checked', true);
            }
        });
        $(function() {
            if ($("#email").val().length > 9) {
                $('input[type="radio"][name="preferenceset"]').last().prop('checked', true);
            }
        });
    });
</script>
</body>
</html>
