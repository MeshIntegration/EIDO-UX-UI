<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ob_start();
session_start();
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
   <br>
   <?php foreach (array_slice($resp->data, 0) as $data) { ?>
      <div class="grid-x rule">
         <div class="small-11 large-11 cell started patient">
            <h3><?php echo $data->firstName . ", " . $data->surname; ?></h3>
            <p>HospNo: <?php echo $data->referenceNumberHospitalId; ?></p>
            <p><?php echo $data->description; ?></p> 
         </div>
         <div class="small-1 large-1 cell align-center"><a class="view_patient_info" data-id="<?php echo $data->id; ?>"><img src="img/icons/greater.png" alt="View Patient Information"/></a></div>
      </div>
   <?php }
   ?>
   <div class='grid-x'>            
       <?php echo $pagination; ?>
   </div>
</form>
<?php
$center_content = ob_get_clean();
///////////////////////
// RIGHT CONTENT START
//////////////////////

ob_start();
$filename = explode('.', basename(__FILE__));
?>
<iframe id="column_right" src="<?php echo $filename[0] . '_right.php'; ?>" scrolling="no" style="border: none; width: 100%;"></iframe>
   <?Php
   $right_content = ob_get_clean();
///////////////////////
// SCRIPT OF THE PAGE
//////////////////////

   ob_start();
   ?>
<script>
   $(document).ready(function () {
       $(".view_patient_info").on("click", function () {
           var patient_id = $(this).data("id");
           var link = "patient_overview.php?id=" + patient_id;
           $("#column_right").attr("src", link);
       });
   });
</script>

<?php
$script['bottom'] = ob_get_clean();
load_template($center_content, $right_content, $script, new param("Procedure Search"));
?>
