<?php
/* * ***********************************
 * /html/ui/validation.php 
 * Copyright 2017, CyberAccess 
 * WEL/SD 7/30/17 
 * *********************************** */
ob_start();
session_start();
require_once 'utilities.php';
// we need to get process number from DB and put into URL - later
$_SESSION['alert']['message'] = "";
if (isset($_POST['submit']) && !empty($_POST['submit'])) {
   if (isset($_REQUEST['patientEpisodeId']) && !empty($_REQUEST['patientEpisodeId'])) {
      $id = $_REQUEST['patientEpisodeId'];
      $db->where('id', $id);
      $patientCheck = $db->getOne('app_fd_gov_patientEpisodes');
      // check to see if patientEpisodeId is invalid (count($patientCheck)==0)
      // if so put up a message to contact who?

      $c_surname = $_REQUEST['surname'];
      if ($c_surname == "" || $c_surname <> $patientCheck['c_surname']) {
         $_REQUEST['surname'] = "";
         $_SESSION['alert']['message'] .= "We don't have that Surname on our records. Please check and try again.<br />";
         $_SESSION['alert']['class'] = "alert";
      }

      $c_dateOfBirth = $_REQUEST['day'] . "/" . $_REQUEST['month'] . "/" . $_REQUEST['year'];
      if ($_REQUEST['day'] == "" || $_REQUEST['month'] == "" || $_REQUEST['year'] == "" || $c_dateOfBirth <> $patientCheck['c_dateOfBirth']) {
         $_REQUEST['day'] = "";
         $_REQUEST['month'] = "";
         $_REQUEST['year'] = "";
         $_SESSION['alert']['message'] .= "Your Date of Birth doesn't look right. Can you check and try again.<br />";
         $_SESSION['alert']['class'] = "alert";
      }

      $c_postalCode = $_REQUEST['postal_code'];
      if ($c_postalCode == "" || $c_postalCode <> $patientCheck['c_postalCode']) {
         $_REQUEST['postal_code'] = "";
         $_SESSION['alert']['message'] .= "The Post Code doesn't look right. Please try again.";
         $_SESSION['alert']['class'] = "alert";
      }

      $db->where('id', $id);
      $db->where('c_surname', $c_surname);
      $db->where('c_dateOfBirth', $c_dateOfBirth);
      $db->where('c_postalCode', $c_postalCode);
      $patientDetail = $db->getOne('app_fd_gov_patientEpisodes');
      if (count($patientDetail) > 0) {
         //$_SESSION['alert']['message'] .= "Detail is valid";
         //$_SESSION['alert']['class'] = "success";
         if (isset($_REQUEST['moreReminders']) && strtolower($_REQUEST['moreReminders']) == "true") 
         {
            $requestParam = array(
              'var_patientEpisodeId' => $id
            );
            // we need to get process number from DB and put into URL
            $URL = "http://eidoverify.com:8080/jw/web/json/workflow/process/list?packageId=gov";
            $response = getCurlResponse($URL, array(), 1, "POST", "BASIC_AUTH");
            if ($response->total > 0) {
               foreach (array_slice($response->data, 0) as $key => $value) {
                  if ($value->name == 'Patient Validation') {
                     $process_id = str_replace("#", ":", $value->id);
                  }
               }
            }else{
               // need to redirect to same page if process id not found   
            }
            if (isset($process_id)) {
               $URL = "http://eidoverify.com:8080/jw/web/json/workflow/process/start/" . $process_id;
               $resp = getCurlResponse($URL, $requestParam, 1, "POST");
            }
// print response
//print_r($resp);
//exit();
         }
         $goto_url = "http://patientinfo.eidoverify.com/s3/Landing-Page?";
         $current_session = $patientDetail['c_currentSessionNumber'];
         if ($current_session == "1")
            $sid_param = $patientDetail['c_session1Survey1'] . "%3B" . $patientDetail['c_session1Survey2'] . "%3B" . $patientDetail['c_session1Survey3'] . "%3B" . $patientDetail['c_session1Survey4'] . "%3B" . $patientDetail['c_session1Survey5'];
         else if ($current_session == "2")
            $sid_param = $patientDetail['c_session2Survey1'] . "%3B" . $patientDetail['c_session2Survey2'] . "%3B" . $patientDetail['c_session2Survey3'] . "%3B" . $patientDetail['c_session2Survey4'] . "%3B" . $patientDetail['c_session2Survey5'];
         else if ($current_session == "3")
            $sid_param = $patientDetail['c_session3Survey1'] . "%3B" . $patientDetail['c_session3Survey2'] . "%3B" . $patientDetail['c_session3Survey3'] . "%3B" . $patientDetail['c_session3Survey4'] . "%3B" . $patientDetail['c_session3Survey5'];
         else if ($current_session == "4")
            $sid_param = $patientDetail['c_session4Survey1'] . "%3B" . $patientDetail['c_session4Survey2'] . "%3B" . $patientDetail['c_session4Survey3'] . "%3B" . $patientDetail['c_session4Survey4'] . "%3B" . $patientDetail['c_session4Survey5'];
         else if ($current_session == "5")
            $sid_param = $patientDetail['c_session5Survey1'] . "%3B" . $patientDetail['c_session5Survey2'] . "%3B" . $patientDetail['c_session5Survey3'] . "%3B" . $patientDetail['c_session5Survey4'] . "%3B" . $patientDetail['c_session5Survey5'];
         $goto_url .= "sids=" . $sid_param;
         $goto_url .= "&pid=" . $patientDetail['c_procedureId'];
         $goto_url .= "&session=" . $patientDetail['id'] . $sid_param;
         $goto_url .= "&hid=" . urlencode($patientDetail['c_hospitalName']);
         $goto_url .= "&eid=" . $patientDetail['id'];
//echo $goto_url;
//exit();
         header("Location: $goto_url");
         exit;
      } else {
         // $_SESSION['alert']['message'] .= "Invalid data entered";
         // $_SESSION['alert']['class'] = "alert";
         ;
      }
   } else {
      $_SESSION['alert']['message'] .= "Invalid request ID";
      $_SESSION['alert']['class'] = "alert";
   }
}
?>
<?php
if (strlen($_SESSION['alert']['message'])) {
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
            <input type="text" name="surname" value="<?php if (!empty($_REQUEST['surname'])) echo $_REQUEST['surname']; ?>" placeholder="Enter Your Surname">
         </div>
      </div>
      <div class="grid-x">
         <div class="small-4 cell">
            <label for="right-label" class="text-left">Date of Birth</label>
            <select name="day">
               <option value="">Day</option>
               <?php
               for ($day = 1; $day <= 31; $day++) {
                  $day_val = str_pad($day, 2, "0", STR_PAD_LEFT);
                  ?>
               <option value="<?php echo $day_val; ?>" <?php if(isset($_REQUEST['day']) && $_REQUEST['day']==$day_val) echo "selected"; ?>><?php echo $day; ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="small-4 cell">        
            <label for="right-label" class="text-left">&nbsp;</label>
            <select name="month">
               <option value="">Month</option>
               <?php
               for ($month = 1; $month <= 12; $month++) {
                  $monthName = date("F", mktime(0, 0, 0, $month, 10));
                  $month_val = str_pad($month, 2, "0", STR_PAD_LEFT);
                  ?>
                  <option value="<?php echo $month_val; ?>" <?php if(isset($_REQUEST['month']) && $_REQUEST['month']==$month_val) echo "selected"; ?>><?php echo $month . " (" . $monthName . ")"; ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="small-4 cell">
            <label for="right-label" class="text-left">&nbsp;</label>
            <select name="year">
               <option value="">Year</option>
               <?php for ($year = date('Y'); $year >= 1910; $year--) { ?>
                  <option value="<?php echo $year; ?>" <?php if(isset($_REQUEST['year']) && $_REQUEST['year']==$year) echo "selected"; ?>><?php echo $year; ?></option>
               <?php } ?>
            </select>
            </label>
         </div>
      </div>
      <div class="grid-x">
         <div class="small-12 cell">
            <label for="right-label" class="text-left">Postal Code</label>
            <input type="text" name="postal_code" value="<?php if (!empty($_REQUEST['postal_code'])) echo $_REQUEST['postal_code']; ?>" placeholder="">
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
load_front_center_template($center_content, $script, new param("EIDO Verify"));
?>
