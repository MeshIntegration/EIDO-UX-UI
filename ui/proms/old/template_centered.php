<!doctype html>
<?php 
   // need to change according to session
   $username = "admin" ;
   $password = "2NHMzey6ETDo" ;
   $hash = md5($username ."::". md5($password));
   $DOB = "1911-11-11" ;
   $row = 10 ;
   $URL = "http://eidoverify.com:8080/jw/web/json/data/list/gov/patientEpisodesSearch?j_username=".$username."&hash=".$hash."&row=".$row ;
   if(isset($_POST['submit']) && !empty($_POST['submit']))
   {
      if(!empty($_POST['query']))
      {
         $URL .= "&";
         $param['description'] = "description=".$_POST['query'] ;
         $URL .= implode("&",$param); 
      }
   }
   //echo $URL;
   // Get cURL resource
   $curl = curl_init();
   // Set some options - we are passing in a useragent too here
   curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $URL,
      CURLOPT_USERAGENT => 'All Patient Episode Datalist',
   ));
   // Send the request & save response to $resp
   $resp = curl_exec($curl);
   $resp = json_decode($resp);
   
   // Close request to clear up some resources
   curl_close($curl);
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
	    <div class="small-5 cell"></div>
        <div class="small-2 cell header_center"><a href="/"><img src="img/eido_logo.jpg" alt="EIDO Logo"/></a></div>
        <div class="small-5 cell"></div>  
      </div>
      <!-- End Header -->
      <!-- Start Content -->
      <div class="grid-x content">
        <!-- Start Content_Mid -->
        <div class="small-12 medium-12 large-12 cell content_mid">
          <div class="grid-x rule">
            <div class="small-3" large-3 cell></div>
            <div class="small-6 large-6 cell">
              <h1>Heading</h1>
			  Content<br>
              <p>Note:  The framework uses the "XY Grid": <a href="#">http://foundation.zurb.com/sites/docs/xy-grid.html</a>.<br>
				  Please create new countent accordingly.</p>
            </div>
            <div class="small-3" large-3 cell></div>
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
