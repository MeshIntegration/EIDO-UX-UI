<!doctype html>
<?php
   // need to change according to session
   $username = "admin" ;
   $password = "2NHMzey6ETDo" ;
   $hash = md5($username ."::". md5($password));
   $DOB = "1911-11-11" ;
   $row = 5 ;
   $page = 1;
   $start = 0 ;
   if(isset($_GET['page']) && !empty($_GET['page']))
   {
      $page = $_GET['page'] ;
      $start = ($page-1) * $row; 
   }
   $URL = "http://eidoverify.com:8080/jw/web/json/data/list/gov/patientEpisodesSearch?j_username=".$username."&hash=".$hash."&start=".$start."&rows=".$row ;
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
   $totalRecord = $resp->total ;
   if ($page){
      $totalPage = ceil($totalRecord / $row);
      $pagination = "<div class='pagination-centered'><ul class='pagination' role='menubar' aria-label='pagination'>";
      if ($page==1)
      {
         $pagination .= "<li class='unavailable'><a>&laquo; PREV</a></li>";
      }
      else
      {
         $link = "?page=".($page-1);
         $pagination .= "<li class='arrow'><a href='".$link."'>&laquo; PREV</a></li>";
      }
      for($i=1; $i <=$totalPage; $i++)
      {
         if ($page==$i)
         {
            $pagination .= "<li class='current'><a>".$i."</a></li>" ;
         }
         else
         {
            $pagination .= "<li><a href='?page=$i'>".$i."</a></li>" ;
         }
      }
      if ($page==$totalPage)
      {
         $pagination .= "<li class='unavailable'><a>NEXT &raquo;</a></li>";
      }
      else
      {
         $link = "?page=".($page+1) ;
         $pagination .= "<li class='arrow'><a href='".$link."'>NEXT &raquo;</a></li>";
      }
      $pagination .= "</ul></div>";

   }
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
          <form action="#" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="input-group">
                <input class="input-group-field searchbox" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                  <div class="input-group-button current"><input type="submit" class="button gold" value="GO" name="submit"></div>
              </div>
            </div>
            <div class="row">
            <?php //echo $resp; ?>
            <?php foreach(array_slice($resp->data,0) as $data){?>
               <div cass="large-12">
                  <h5><?php echo $data->firstName.", ".$data->surname;?></h5>
                  <p><b>HospNo: </b><?php echo $data->referenceNumberHospitalId; ?></p>
                  <p><?php echo $data->description; ?></p> 
                  <hr>
               </div>
            <?php }
            ?>
            </div>
            <div class='row'>            
               <?php echo $pagination; ?>
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
