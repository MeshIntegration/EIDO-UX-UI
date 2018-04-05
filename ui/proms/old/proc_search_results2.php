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
        <div class="small-12 medium-2 cell header_left"><a href="/"><img src="img/eido_logo.jpg" alt="EIDO Logo"/></a></div>
        <div class="small-12 medium-8 cell header_mid"><img src="img/add.png" alt="Add Patient" class="add_icon"/> <span class="add_patient">ADD PATIENT</span></div>
        <div class="small-12 medium-2 show-for-large cell header_right"><span class="branding">AB</span></div>    
      </div>
      <!-- End Header -->
      <!-- Start Content -->
      <div class="grid-x content">
        <!-- Start Content_Left -->
        <div class="small-12 medium-2 large-2 show-for-large cell content_left">
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
        <div class="small-12 medium-6 large-5 cell content_mid">
		  <h1>Procedure Search</h1>
          <form action="#" method="POST" enctype="multipart/form-data">
            <div class="grid-x">
              <div class="input-group">
                <input class="input-group-field searchbox" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                  <div class="input-group-button"><input type="submit" class="button gold" value="GO" name="submit"></div>
              </div>
            </div>
          </form>
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
          <!-- End Filters Panel -->
          <br />
          <?php //echo $resp; ?>
          <?php foreach(array_slice($resp->data,0) as $data){?>
          <div class="grid-x rule">
            <div class="small-11 large-11 cell started patient">
			  <h3><?php echo $data->firstName.", ".$data->surname;?></h3>
              <p>HospNo: <?php echo $data->referenceNumberHospitalId; ?><br />
				 <?php echo $data->description; ?></p>
            </div>
            <div class="small-1 large-1 cell align-center"><a href=""><img src="img/icons/greater.png" alt="View Patient Information"/></a></div>
          </div> 
          <?php }
          ?>
          <div class="grid-x">
          	<div class="small-12 large-12 cell">
          	  <ul class="pagination text-center" role="navigation" aria-label="Pagination">
				<li class="pagination-previous"><a href="#" aria-label="Previous page">Previous</a></li>
                <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
                <li><a href="#" aria-label="Page 2">2</a></li>
                <li><a href="#" aria-label="Page 3">3</a></li>
                <li><a href="#" aria-label="Page 4">4</a></li>
                <li class="ellipsis"></li>
                <li><a href="#" aria-label="Page 12">12</a></li>
                <li class="pagination-next"><a href="#" aria-label="Next page">Next</a></li>
              </ul>
				<!--<?php echo $pagination; ?>-->
          	</div>
          </div> 
        </div>
        <!-- Start Content_Right -->
        <div class="small-12 medium-6 large-5 cell content_right overview">
          <!-- Start Overview -->
          <div class="grid-x">
            <div class="small-12 large-12 cell in">
              <h3>Patient Overview</h3>
              <p>See a patient's progress through Verify</p>
            </div>
            <div class="small-12 large-12 cell attention">
              <div class="grid-x rule_white">
				  <div class="small-9 large-9 cell"><h5><strong>JONES, William</strong></h5></div>
				  <div class="small-3 large-3 cell text-right align-self-bottom smaller">ACTIVE</div>
              </div>
              <div class="grid-x rule_white">
                <div class="small-9 large-9 cell">
                  <p>HospNo: K123456<br>
                     NHS No: 987 654 321<br>
					 DOB: 01-03-1985</p>
                </div>
                <div class="small-3 large-3 cell text-right"><a href=""><img src="img/icons/greater_white.png" alt="View Patient Information"/></a></div>
              </div>
              <div class="grid-x rule_white">
                <div class="small-9 large-9 cell">
                  <p>Procedure<br>
                     <span class="highlight">UG08 - Open Cholecystectomy</span></p>
                  <p>Procedure Date<br>
                     <span class="highlight">28-06-2017</span></p>
                </div>
                <div class="small-3 large-3 cell text-right"><a href=""><img src="img/icons/greater_white.png" alt="View Patient Information"/></a></div>
              </div>
              <div class="grid-x">
                <div class="small-12 large-12 cell">Status<br>
                  <img src="img/icons/exclaimation.png" alt="Missed Activity" class="status_img"/><span class="highlight">Missed Activity</span>
                </div>
              </div>
            </div>	
            <div class="small-12 large-12 cell in timeline">
              <p class="rule_white">Patient Timeline</p>
              <div class="grid-x">
                <div class="small-2 large-1 cell"><img src="img/icons/check.png" alt=""/></div>
                <div class="small-9 large-10 cell">
					<p>UG08 - Pre Assessment<br>
                       Last Update: 06-05-2017</p>
                </div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div>
              <div class="grid-x">
                <div class="small-2 large-1 cell"><img src="img/icons/exclaimation_red.png" alt=""/></div>
                <div class="small-9 large-10 cell">
					<p>UG08 - Pre Assessment<br>
                       Last Update: 06-05-2017</p>
                </div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div>
              <div class="grid-x">
                <div class="small-2 large-1 cell"><img src="img/icons/caution.png" alt=""/></div>
                <div class="small-9 large-10 cell">
				  <p><span class="highlight">Procedure</span><br>
                     Last Update: 06-05-2017</p>
                  <a class="button expanded green text-center" href="#"><strong>Complete</strong></a>
                </div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div>
              <p></p>
              <div class="grid-x rule_white">
                <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""/></div>
                <div class="small-9 large-10 cell"><p>UG08 - 7 Day Follow Up</p></div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div> 
               <div class="grid-x rule_white">
                <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""/></div>
                <div class="small-9 large-10 cell"><p>UG08 - 30 Day Follow Up</p></div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div> 
               <div class="grid-x rule_white">
                <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""/></div>
                <div class="small-9 large-10 cell"><p>UG08 - 60 Day Follow Up</p></div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div> 
               <div class="grid-x">
                <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""/></div>
                <div class="small-9 large-10 cell"><p>Friends & Family</p></div>
                <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""/></a></div>
              </div> 
            </div>
          </div>
          <!-- End of Overview --> 
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
