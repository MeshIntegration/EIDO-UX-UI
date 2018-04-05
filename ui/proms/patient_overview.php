<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ob_start();
session_start();
require_once 'utilities.php';
$patient_id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "18276_gov_patientEpisode";
$requestParam = array('id' => $patient_id);
$resp = getCurlResponse("patientOverview", $requestParam);
$resp = $resp->data[0];
$DOB = "";
if (isset($resp->dateOfBirth) && !empty($resp->dateOfBirth)) {
   /*
    * Format the date
   $dobObj = DateTime::createFromFormat('m/d/Y', $resp->dateOfBirth);
   $DOB = $dobObj->format('d-m-Y');
   */
   $DOB = $resp->dateOfBirth;
}
?>
<!-- Start Overview -->
<div class="grid-x" id="right_content">
   <div class="small-12 large-12 cell in">
      <h3>Patient Overview</h3>
      <p>See a patient's progress through Verify</p>
   </div>	
   <div class="small-12 large-12 cell attention">
      <div class="grid-x rule_white">
         <div class="small-9 large-9 cell"><h5><strong><?php echo $resp->firstName . ", " . $resp->surname; ?></strong></h5></div>
         <div class="small-3 large-3 cell text-right align-self-bottom smaller">ACTIVE</div>
      </div>
      <div class="grid-x rule_white">
         <div class="small-9 large-9 cell">
            <p>HospNo: <?php echo $resp->referenceNumberHospitalId; ?><br>
               NHS No: <?php echo $resp->nhsNumber; ?><br>
               DOB: <?php echo $DOB; ?>
            </p>
         </div>
         <div class="small-3 large-3 cell text-right"><a href=""><img src="img/icons/greater_white.png" alt="View Patient Information"></a></div>
      </div>
      <div class="grid-x rule_white">
         <div class="small-9 large-9 cell">
            <p>Procedure<br>
               <span class="highlight">UG08 - Open Cholecystectomy</span></p>
            <p>Procedure Date<br>
               <span class="highlight">28-06-2017</span></p>
         </div>
         <div class="small-3 large-3 cell text-right"><a href=""><img src="img/icons/greater_white.png" alt="View Patient Information"></a></div>
      </div>
      <div class="grid-x">
         <div class="small-12 large-12 cell">Status<br>
            <img src="img/icons/exclaimation.png" alt="Missed Activity" class="status_img"><span class="highlight">Missed Activity</span>
         </div>
      </div>
   </div>
   <div class="small-12 large-12 cell in timeline">
      <p class="rule_white">Patient Timeline</p>
      <div class="grid-x">
         <div class="small-2 large-1 cell"><img src="img/icons/check.png" alt=""></div>
         <div class="small-9 large-10 cell">
            <p>UG08 - Pre Assessment<br>
               Last Update: 06-05-2017</p>
         </div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div>
      <div class="grid-x">
         <div class="small-2 large-1 cell"><img src="img/icons/exclaimation_red.png" alt=""></div>
         <div class="small-9 large-10 cell">
            <p>UG08 - Pre Assessment<br>
               Last Update: 06-05-2017</p>
         </div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div>
      <div class="grid-x">
         <div class="small-2 large-1 cell"><img src="img/icons/caution.png" alt=""></div>
         <div class="small-9 large-10 cell">
            <p><span class="highlight">Procedure</span><br>
               Last Update: 06-05-2017</p>
            <a class="button expanded green text-center" href="#"><strong>Complete</strong></a>
         </div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div>
      <p></p>
      <div class="grid-x rule_white">
         <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""></div>
         <div class="small-9 large-10 cell"><p>UG08 - 7 Day Follow Up</p></div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div> 
      <div class="grid-x rule_white">
         <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""></div>
         <div class="small-9 large-10 cell"><p>UG08 - 30 Day Follow Up</p></div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div> 
      <div class="grid-x rule_white">
         <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""></div>
         <div class="small-9 large-10 cell"><p>UG08 - 60 Day Follow Up</p></div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div> 
      <div class="grid-x">
         <div class="small-2 large-1 cell"><img src="img/icons/arrow.png" alt=""></div>
         <div class="small-9 large-10 cell"><p>Friends &amp; Family</p></div>
         <div class="small-1 large-1 cell text-right"><a href="#"><img src="img/icons/greater.png" alt=""></a></div>
      </div> 
   </div> 
</div>
<?php
$center_content = ob_get_clean();
ob_end_clean();
load_blank_template($center_content, new param("Procedure Search"));
?>
