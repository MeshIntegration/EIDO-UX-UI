<!doctype html>
<?php
require_once '../utilities.php';
require_once '../lib/validation.php';
$logfile = "validation.log";

session_start();
$arr_pt_info = $_SESSION['arr_pt_info'];
$error_msg = $_SESSION['error_msg'];
$_SESSION['error_msg']="";

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eido Verify - Patient Auth V3 - Screen 2a</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script>
         $(document).ready(function () {
            $("#img_checkbox").click(function(){
               var checked = "../img/checked.png";
               var unchecked = "../img/unchecked.png";
               var imgElement = $(this).attr("src");
               if (imgElement === checked){
                  var url =  unchecked ;
                  $("#btn_next").removeClass("active").addClass("inactive");
                  $("#btn_next").parent("a").removeClass("is-active").addClass("not-active");
               }else{
                  var url = checked ;
                  $("#btn_next").removeClass("inactive").addClass("active");
                  $("#btn_next").parent("a").removeClass("not-active").addClass("is-active");
               }
               $(this).attr("src",url);
            });
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
  <?php include '../includes/val_header.php';?>
  <!-- End Header -->
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <div class="grid-x">
		<div class="hide-for-small-only medium-3 cell">&nbsp;</div>
	    <div class="small-12 medium-6 align-center-middle text-center cell">
		  <p>&nbsp;</p>
		  <h2>Terms &amp; Conditions</h2>
	      <p>Please read our <a href="#">Terms &amp; Conditions</a> of use.</p>
		  <div class="grid-x" style="background:#efefef; padding:0.875rem;">
		    <div class="small-12 cell text-center">	
		      <p><strong>Some important points:</strong></p>
              <ul>
			    <li>We never ever share email addresses with 3rd parties.</li>
			    <li>We will only contact you about EIDO Verify.</li>
			  </ul>
		    </div>
		  </div>
		  <br />
		  <p>To indicate you understand everything, please check the box below.</p>
		  <div class="grid-x">
		    <div class="small-2 align-middle"><img id="img_checkbox" src="../img/unchecked.png"></div>
			<div class="small-10"><label for="checkbox1" class="adjust text-left">I have read and agree to the Terms &amp; Conditions, Privacy and Cookie Policies.</label></div>
		  </div>
		  <form class="login">
			<p>&nbsp;</p>
			<div class="small-12 text-right cell"><p>&nbsp;</p></div>
			<div class="small-12 cell">
			  <button type="button" name="" value="" class="button large inactive float-left">Back</button>
			  <a href="validation_pw.php" class="not-active"><button type="button" name=""  id="btn_next" value="" class="button large inactive float-right">Next</button></a>
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
