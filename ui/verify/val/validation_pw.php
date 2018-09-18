<!doctype html>
<?php
require_once '../utilities.php';
$logfile = "validation.log";

session_start();
$patientEpisodeId = get_query_string('patientEpisodeId');
$arr_pt_info = get_pt_info($patientEpisodeId);
$goto_url = get_survey_url($arr_pt_info);
$_SESSION['arr_pt_info'] = $arr_pt_info;
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg'] = "";

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
		$(document).ready(function() {
		});
	</script>
	<style>
		.is-active {
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
	<?php include '../includes/val_header.php'; ?>
	<!-- End Header -->
	<!-- Start Content -->
	<div class="grid-x grid-margin-x su">
		<!-- Start Content-Full -->
		<div class="small-12 medium-12 large-12 cell content-full">
			<div class="grid-x">
				<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
				<div class="small-12 medium-6 align-center-middle cell">

                        <div class="hide-for-small-only"><p>&nbsp;</p></div>
						<p class="lead"><strong>Now create a password, to save you time on your next visit.</strong></p>
                    <form class="login" action="validation_pw_a.php?patientEpisodeId=<?php echo $patientEpisodeId; ?>" method="post" id="form_container">
						<p style="padding-bottom: 5px;margin-bottom:5px;" class="sub-text float-left"><strong>Password</strong>
							<div class="input-group">
								<span class="input-group-label"><i class="fi-lock"></i></span>
								<input class="input-group-field" autocomplete="new-password" id="form_password" name="password" type="password" placeholder="Enter your password"><br/>
							</div>
                        </p>
							<div style="margin-top: 40px;"class="progress password_strength_indicator" role="progressbar" tabindex="0" aria-valuemax="100">
                           <span class="progress-meter" style="width: 0%">
                              <p class="progress-meter-text" style="transform: translate(-0%, -50%)">Password Strength</p>
                           </span>
							</div>

                        <div class="hide-for-small-only small-12 text-right cell"><p>&nbsp;</p></div>
                                <div class="small-12 medium-6 text-center cell">
                                    <p><a href="<?php echo $goto_url; ?>" name="skip" style="margin-bottom:0px;" class="text-dim">Skip this step<br/>I will enter my details again next time.</a></p>
                                    <!-- removed from above href to accomadate new pt auth flow spec: "validation_pw_a.php?skip=Y&patientEpisodeId=" -->
                                </div>
						<div class="small-12 cell">
                            <span class="width-100">
							<button type="button" name="" value="" class="button large inactive float-left" style="display: none">Back</button>
							<button id="btn_next" type="submit" name="" value="" class="button large inactive float-right not-active">Next</button>
                                </span>
						</div>

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
<!--<script src="../js/vendor/jquery.js"></script>-->
<script src="../js/vendor/what-input.js"></script>
<script src="../js/vendor/foundation.js"></script>
<script src="../js/app.js"></script>
<script src="../js/vendor/pwstrength/pwstrength.js"></script>
<script>
	$(document).ready(function() {

		function allow_next(is_allow) {
			if(is_allow === true) {
				$("#btn_next").removeClass("inactive").addClass("active");
				$("#btn_next").removeClass("not-active").addClass("is-active");
			} else if(is_allow === false) {
				$("#btn_next").removeClass("active").addClass("inactive");
				$("#btn_next").removeClass("is-active").addClass("not-active");
			}
		}

		$("#skip").change(function() {
			if($(this).is(':checked')) {
				allow_next(true);
			} else {
				allow_next(false);
			}
		});

		var options = {};
		options.common = {
			minChar: 4, debug: false, onKeyUp: function(event, data) {
				// verdictLevel [-1 to 4] : ["Weak", "Normal", "Medium", "Strong", "Very Strong"]
				if(parseInt(data.verdictLevel) == -1) {
					data.verdictLevel = 0;
				}
				var verdictClass = ["alert", "", "warning", "success", "success"];
				var progressInitClass = "progress password_strength_indicator";
				var progressStatusClass = progressInitClass + " " + verdictClass[parseInt(data.verdictLevel)];
				var percent = (parseInt(data.verdictLevel) * 20) + 20;

				// set progress class, bar and text
				$(".password_strength_indicator").removeClass().addClass(progressStatusClass);
				$(".progress-meter").css({"width": +percent + "%"});
				$(".progress-meter-text").html(data.verdictText);
				// reset progress strength
				if($("#form_password").val().length == 0) {
					$(".password_strength_indicator").removeClass().addClass(progressInitClass);
					$(".progress-meter-text").html("Password Strength");
					$(".progress-meter").css({"width": "0%"});
				}
				// manage form submit
				if(parseInt(data.verdictLevel) > 2) {
					allow_next(true);
				} else {
					allow_next(false);
				}
			}
		};
		options.ui = {
			container: "#form_container", showProgressBar: false, showErrors: true, showVerdictsInsideProgressBar: false, viewports: {
				progress: ".password_strength_indicator"
			}
		};
		options.rules = {
			scores: {
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
			}, activated: {
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
		$(':password').pwstrength(options);
	});
</script>
</body>
</html>
