<!doctype html>
<?php
// need to change according to session
require_once '../utilities.php';

if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}
$requestParam = array('start' => $start, 'row' => $row);
$resp = getCurlResponse("patientEpisodesSearch", $requestParam);
$totalRecord = $resp->total;
$pagination = get_pagination($page, $totalRecord);

?>
<html class="no-js" lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>EIDO :: Procedure Search 02</title>
      <link rel="stylesheet" href="../css/foundation.css">
      <link rel="stylesheet" href="../css/eido.css">
      <link rel="stylesheet" href="../css/app.css">
      <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
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
            <div class="small-5 medium-5 large-5 cell content_mid">
               <h1>Procedure Search</h1>
               <form action="#" method="POST" enctype="multipart/form-data">
                  <div class="grid-x">
                     <div class="input-group">
                        <input class="input-group-field searchbox" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                        <div class="input-group-button current"><input type="submit" class="button gold" value="GO" name="submit"></div>
                     </div>
                  </div>
                  <!-- Start Filters Panel -->
                  <div class="grid-x">
                     <div class="cell">
                        <div class="accordion" data-accordion data-allow-all-closed="true">
                           <div class="accordion-item" data-accordion-item>
                              <!-- Accordion tab title -->
                              <a href="#" class="accordion-title">Filters</a>
                              <div class="accordion-content" data-tab-content>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">TIME ADDED</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small selected">NEWEST FIRST</a>
                                          <a class="button small off">OLDEST FIRST</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">NAME</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small off">A-Z</a>
                                          <a class="button small selected">Z-A</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">ACTIVITY</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small selected">MOST ACTIVE</a>
                                          <a class="button small off">LEAST ACTIVE</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">GENDER</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small selected">ANY</a>
                                          <a class="button small off">MALE</a>
                                          <a class="button small off">FEMALE</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">PROCEDURE DATE</div>
                                    <div class="cell">
                                       <div class="input-group">
                                          <input class="input-group-field searchbox" placeholder="DD-MM-YYYY" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                                          <div class="input-group-button"><input type="submit" class="button gold" value="GO" name="submit"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">SEARCH WITHIN RESULTS</div>
                                    <div class="cell">
                                       <div class="input-group">
                                          <input class="input-group-field searchbox" placeholder="Hobbs" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                                          <div class="input-group-button"><input type="submit" class="button gold" value="GO" name="submit"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>  	
                     </div>
                  </div>
xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                  <br>
                  <?php foreach (array_slice($resp->data, 0) as $data) { ?>
                     <div class="grid-x rule">
                        <div class="small-11 large-11 cell started patient">
                           <h3><?php echo $data->firstName . ", " . $data->surname; ?></h3>
                           <p>HospNo: <?php echo $data->referenceNumberHospitalId; ?></p>
                           <p><?php echo $data->description; ?></p> 
                        </div>
                        <div class="small-1 large-1 cell align-center"><a href=""><img src="img/icons/greater.png" alt="View Patient Information"/></a></div>
                     </div>
                  <?php }
                  ?>
                  <div class='grid-x'>            
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
