<!doctype html>
<html class="no-js" lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo $param->title; ?></title>
      <link rel="stylesheet" href="css/foundation.css">
      <link rel="stylesheet" href="css/eido.css">
      <link rel="stylesheet" href="css/app.css">
      <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
      <?php if (isset($script['top'])) echo $script['top']; ?>
   </head>
   <body>
      <div class="grid-container">
         <!-- Start Header -->
         <div class="grid-x grid-padding-x header">
            <div class="small-12 medium-2 cell header_left"><a href="/"><img src="img/eido_logo.jpg" alt="EIDO Logo"/></a></div>
            <div class="small-12 medium-8 cell header_mid"><img src="img/add.png" alt="Add Patient" class="add_icon"/> <span class="add_patient">ADD PATIENT</span></div>
            <div class="small-12 medium-2 show-for-large cell header_right"><span class="branding">AB</span></div>    
         </div>
         <!-- End Header -->
         <!-- Start Content -->
         <div class="grid-x content">
            <!-- Start Content_Left -->
            <div class="small-2 medium-2 large-2 show-for-large cell content_left">
               <div class="button-group stacked nav">
                  <a class="button">Patients</a>
                  <a class="button page">Procedures</a>
               </div>
               <div class="help">
                  <div class="small-12 large-12"><a class="button expanded">Help</a></div>
                  <ul class="info_links">
                     <li><a>Terms &amp; Conditions</a></li>
                     <li><a>Privacy Policy</a></li>	
                  </ul>
               </div>
            </div>
            <!-- Start Content_Mid -->
            <div class="small-12 medium-6 large-5 cell content_mid" id="center_content">
                <?php echo $center_content; ?>
            </div>
            <!-- Right side start -->
            <!-- Start Content_Right -->
            <div class="small-12 medium-6 large-5 cell content_right">
               <?php echo $right_content; ?>
            </div> 
            <!-- Right side end -->
         </div>
         <!-- End Content -->   
      </div>

      <script src="js/vendor/jquery.js"></script>
      <script src="js/vendor/what-input.js"></script>
      <script src="js/vendor/foundation.js"></script>
      <script src="js/app.js"></script>
      <script>
         $(document).ready(function () {
             var window_size = $(window).height();
             $('iframe').css('height', window_size);
         });
      </script>
      <?php if (isset($script['bottom'])) echo $script['bottom']; ?>
   </body>
</html>
