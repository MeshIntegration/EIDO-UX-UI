<!doctype html>
<?php 
   // need to change according to session
   $username = "admin" ;
   $password = "2NHMzey6ETDo" ;
   $hash = md5($username ."::". md5($password));
?>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EIDO :: Procedure Search 02</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/eido.css">
    <link rel="stylesheet" href="css/app.css">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="grid-container">
      <!-- Start Header -->
      <div class="grid-x grid-padding-x header">
        <div class="small-2 cell header_left"><a href="/"><img src="img/eido_logo.jpg" alt="EIDO Logo"/></a></div>
        <div class="small-8 cell text-right header_mid"><img src="img/add.png" alt="Add Patient" class="add_icon"/> <span class="add_patient">ADD PATIENT</span></div>
        <div class="small-2 cell header_right"><span class="branding">AB</span></div>    
      </div>
      <!-- End Header -->
      <!-- Start Content -->
      <div class="grid-x content">
        <!-- Start Content_Left -->
        <div class="small-2 medium-2 large-2 cell content_left">
          <div class="button-group stacked nav">
            <a class="button">Patients</a>
            <a class="button current">Procedures</a>
          </div>
          <div class="help">
            <div class="large-12"><a class="button expanded">Help</a></div>
            <ul class="info_links">
			  <li><a>Terms &amp; Conditions</a></li>
			  <li><a>Privacy Policy</a></li>	
            </ul>
		  </div>
        </div>
        <!-- Start Content_Mid -->
        <div class="small-5 medium-5 large-5 cell content_mid">
		  <h1>Procedure Search</h1>
          <form>
            <div class="row">
              <div class="input-group">
                <input class="input-group-field searchbox" type="">
                  <div class="input-group-button current"><input type="submit" class="button gold" value="GO"></div>
              </div>
            </div>
          </form>
        </div>
        <!-- Start Content_Right -->
        <div class="small-5 medium-5 large-5 cell content_right">
          <div class="cta large-12">
            <h2>Add Patient</h2>
            <p>Start a Verify session with a new patient</p>
            <a class="button large expanded green" href="#">Get Started</a>	
          </div>  	
          <div class="cta large-12">
            <h2>Lookup Patient</h2>
            <p>View progress of an existing patient</p>
            <form>
              <div class="row">
                <div class="large-12 cell"><input type="text" placeholder="Patient Name or Number"></div>
                <div class="large-12"><input type="submit" class="button gold go" value="GO"></div>
                <div class="clear"></div>
              </div>
            </form>
          </div>
        </div>    
      </div>
      <!-- End Content -->   
    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
   <script>
   $(document).ready(function(){
      var username = "<?php echo $username; ?>";
      var hash = "<?php echo $hash;?>";
      $.ajax({
         url: "http://eidoverify.com:8080/jw/web/json/data/list/gov/patientEpisodesAll",
         data:{
            j_username : username,
            hash : hash,
            dob:'1911-11-11'
         }
      }).done(function(response) {
         console.log(response);
      });
   });
   </script>  
</body>
</html>
