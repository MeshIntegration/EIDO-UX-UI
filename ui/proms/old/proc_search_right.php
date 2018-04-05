<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ob_start();
session_start();
require_once 'utilities.php';
?>

<!-- Start Overview -->
<div class="grid-x" id="right_content">
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
<?php
$center_content = ob_get_clean();
ob_end_clean();
load_blank_template($center_content, new param("Procedure Search"));
?>
