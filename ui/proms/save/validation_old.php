<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ob_start();
session_start();
require_once 'utilities.php';

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
   if (isset($_REQUEST['patientEpisodeId']) && !empty($_REQUEST['patientEpisodeId'])) {
      $id = $_REQUEST['patientEpisodeId'];
      $c_surname = $_REQUEST['surname'];

      $c_dateOfBirth = $_REQUEST['day'] . "/" . $_REQUEST['month'] . "/" . $_REQUEST['year'];

      $c_postalCode = $_REQUEST['postal_code'];

      $db->where('id', $id);
      $db->where('c_surname', $c_surname);
      $db->where('c_dateOfBirth', $c_dateOfBirth);
      $db->where('c_postalCode', $c_postalCode);
      $patientDetail = $db->get('app_fd_gov_patientEpisodes');
      if (count($patientDetail) > 0) {
         $_SESSION['alert']['message'] = "Detail is valid";
         $_SESSION['alert']['class'] = "success";
         $goto_url = "http://patientinfo.eidoverify.com/s3/Landing-Page?";
         $sid_param = $patientDetail['c_session1Survey1'] . "%3B" . $patientDetail['c_session2Survey2'] . "%3B" . $patientDetail['c_session3Survey3'] . "%3B" . $patientDetail['c_session4Survey4'] . "%3B" . $patientDetail['c_session5Survey5'];
         $goto_url .= "sid=" . $sid_param;
         $goto_url .= "&pid=" . $patientDetail['c_procedureId'];
         $goto_url .= "&session=" . $patientDetail['id'].$sid_param;
         $goto_url .= "&hid=" . $patientDetail['c_hospitalId'];
         $goto_url .= "&eid=" . $patientDetail['id'];
         header("Location: $goto_url");
         exit;
      } else {
         $_SESSION['alert']['message'] = "Invalid detail entered";
         $_SESSION['alert']['class'] = "alert";
      }
   } else {
      $_SESSION['alert']['message'] = "Invalid detail entered";
      $_SESSION['alert']['class'] = "alert";
   }
}
?>
<?php
if (isset($_SESSION['alert'])) {
   ?>
   <div class="<?php echo $_SESSION['alert']['class']; ?> callout" id="closable" data-closable>
       <?php echo $_SESSION['alert']['message']; ?>
      <button class="close-button" aria-label="Dismiss alert" type="button" id="close" data-close>
         <span aria-hidden="true">&times;</span>
      </button>
   </div>
   <?php
   unset($_SESSION['alert']);
}
?>
<div class="large-12 cell">
   <form method="POST" action="#" enctype="multipart/form-data">
      <p>Please enter your details so we can check your identity.</p>
      <div class="grid-x">
         <div class="small-12 cell">
            <label for="right-label" class="text-left">Surname </label>
            <input type="text" name="surname" placeholder="Enter Your Surname" required="">
         </div>
      </div>
      <div class="grid-x">
         <div class="small-4 cell">
            <label for="right-label" class="text-left">Date of Birth</label>
            <select name="day">
                <?php for ($day = 1; $day <= 31; $day++) { 
                  $day_val = str_pad($day,2,"0",STR_PAD_LEFT);  
                  ?>
                  <option value="<?php echo $day_val; ?>"><?php echo $day; ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="small-4 cell">        
            <label for="right-label" class="text-left">&nbsp;</label>
            <select name="month">
                <?php
                for ($month = 1; $month <= 12; $month++) {
                   $monthName = date("F", mktime(0, 0, 0, $month, 10));
                   $month_val = str_pad($month,2,"0",STR_PAD_LEFT);
                  ?>
                  <option value="<?php echo $month_val; ?>"><?php echo $month . " (" . $monthName . ")"; ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="small-4 cell">
            <label for="right-label" class="text-left">&nbsp;</label>
            <select name="year">
                <?php for ($year = date('Y'); $year >= 1920; $year--) { ?>
                  <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
               <?php } ?>
            </select>
            </label>
         </div>
      </div>
      <div class="grid-x">
         <div class="small-12 cell">
            <label for="right-label" class="text-left">Postal Code</label>
            <input type="text" name="postal_code" value="" placeholder="" required="">
         </div>
      </div>
      <div class="grid-x">
         <div class="small-12 cell"><input type="submit" name="submit" value="GET STARTED" class="button green expanded"></div>
      </div>
      <div class="grid-x">
         <div class="small-12 cell">
            <p><center>By accessing this system, you are agreeing to our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></p>
            <a href="#">Need any help?</a></center>
         </div>
      </div>
   </form>
</div>
<?Php
$center_content = ob_get_clean();
///////////////////////
// SCRIPT OF THE PAGE
//////////////////////

ob_start();
?>
<script>
   $(document).ready(function () {
       $("#close").on("click", function () {
           $("#closable").remove();
       })
   });
</script>
<?php
$script['bottom'] = ob_get_clean();
load_front_template($center_content, $script, new param("EIDO Verify"));
?>
