<!doctype html>
<?php
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

$logfile = "validation.log";
require_once '../utilities.php';
session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
if($arr_pt_info['c_mobilePageDone'] == "YES" && $arr_pt_info['c_acceptedTC'] == "YES") {
	header("Location: validation_pw");
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
	$contactmethod = "readonly";

}
if($arr_pt_info['c_emailAddress'] == "") {
	$showmobile = "hide";
	$showemail = "show";
	$prompt = "email";
	$mobile_checked = "checked";
	$email_checked = "";
	$contactmethod = "readonly";
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
	<title>Eido Verify - Patient Auth V3 - Screen 4</title>
	<link rel="stylesheet" href="../css/foundation.css">
	<link rel="stylesheet" href="../css/eido.css">
	<link rel="stylesheet" href="../css/dashboard.css">
	<link rel="stylesheet" href="../css/app.css">
	<link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script>
		$(function() {
			$("#tooltip_error_day").tooltip().tooltip("open");
		});
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
					<form class="login" action="validation_mobile_a.php" method="post">
						<?php if($arr_pt_info['c_mobileNumber'] == "") { ?>
							<p class="lead"><strong>We don't have a mobile number for you<br/>
							If you add it, we can text you with updates...<strong</p>
						<?php } else { ?>
							<p class="lead"><strong>Do we have the latest mobile number for you?<br />
									Please check the number below and change it if necessary.</strong></p>
						<?php } ?>
						<?php if($arr_pt_info['c_emailAddress'] == "") { ?>
							<p class="lead"><strong>We don't have an email address for you.
									If you add it, we can send you email with updates...</strong></p>
						<?php } ?>
						<?php if($error_msg == 'NO_MOBILE') { ?>
							<div class='error_message fi-alert'><strong>Please enter a mobile number</strong></div>
						<?php } ?>
						<div id="mobilecontainer" class="<?php echo $showmobile; ?>">
							<label>Mobile Number
								<div class="input-group">
									<span class="input-group-label"><i class="fi-telephone"></i></span>
									<input id="mobile" class="inputfield input-group-field" name="mobile" type="text" value="<?php echo $arr_pt_info['c_mobileNumber']; ?>" placeholder="Enter your mobile number"><br/>
								</div>
							</label>
						</div>
						<?php if($error_msg == 'NO_EMAIL') { ?>
							<div class='error_message fi-alert'><strong>Please enter an email address</strong></div>
						<?php } ?>
						<div id="emailcontainer" class="<?php echo $showemail; ?>">
							<label>Email
								<div class="input-group">
									<span class="input-group-label"><i class="fi-mail"></i></span>
									<input class="inputfield input-group-field" id="email" name="email" type="text" value="<?php echo $arr_pt_info['c_emailAddress']; ?>" placeholder="Enter your email"><br/>
								</div>
						</div>
						</label>

						<div id="contactdiv" class=" grid-x grid-padding-x">
							<div name="preferred" id="contactpreference" class="<?php echo $contactmethod; ?> small-12">
								<p class="lead">Which contact method would you prefer?</p>
			                      <label class="fancy-eido-round-checkbox">
				                      Email
			                          <input type="radio" name="preferred" value="EMAIL" <?php echo $email_checked; ?> id="preferred_contact_email"/>
			                          <span for="preferred_contactRed"></span>
			                      </label>
			                      <label class="fancy-eido-round-checkbox">
				                      Mobile
			                          <input type="radio" name="preferred" value="MOBILE" <?php echo $mobile_checked; ?> id="preferred_contact_mobile"/>
			                          <span for="preferred_contactBlue"</span>
			                      </label>
							</div>
						</div>
						<div class="small-12 text-right cell"><p>&nbsp;</p></div>
						<div class="grid-x row">
							<div class="small-6 cell text-left">
								<button type="button" name="" value="" class="button large inactive text-left">Back</button>
							</div>
							<div class="small-6 text-right">
								<button type="submit" name="" value="" class="button large float-right">Next</button>
							</div>
						</div>

					</form>
					<div class="small-12 cell">
						<p><img src="../img/org_logos/<?php echo $arr_pt_info['logo']; ?>" alt="" class="vendor"/></p>
					</div>
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
		$('[name="preferred"]:radio[readonly]:not(:checked)').attr('disabled', true);

		$("#mobile").onkeyup(function() {
			//          if (var acceptedmobile = 'true') {
			//     if   ( $('#mobile').val().length > 7 ) {
			$(function() {
				$("#contactpreference").removeClass('hide').addClass('show');
				$("#contactdiv").addClass('show');
			})
			//        }
		});


		$(function() {
			var acceptedemail = 'false';
			var acceptedmobile = 'false';
		});
	});
	$(document).ready(function() {

		$('#email').bind('focus blur keyup click hover change', function() {
			if(($(this).val().indexOf('@') = 0) && ($(this).val().indexOf('.') > -1) && ($(this).val().length > 4) && ($(this).val().length < 64) && ($(this).val().trim().indexOf(' ') < 1)) {
				$(function() {
					var acceptedemail = true;
				})
			} else {
				$(function() {
					var acceptedemail = false;
				})
			}
		});

		$('#mobile').bind('focus blur keyup click hover change', function() {

			var mobiletext = $("#mobile").text();
			var mobileDigitCount = (mobiletext).replace(/\D/g, '');
			var Regex = '/^[- +()]*[0-9][- +()0-9]*$/';

			if((Regex.test($("#mobilefield").val())) && (mobileDigitCount.length() > 7) && (mobileDigitCount.length() < 14)) {
				$(function() {
					var acceptedmobile = true;
				})
			} else {
				$(function() {
					var acceptedmobile = false;
				})
			}
		});

		$('#email').bind('focus blur keyup change', function() {
			if(var acceptedemail = 'true'
		)
			{
				$("#contactpreference").show();
			}
		});


	});
</script>
</body>
</html>
