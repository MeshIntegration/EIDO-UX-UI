<!doctype html>
<?php
// need to change according to session
if ((isset($_GET['page']) && !empty($_GET['page']))){
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Verify User</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
  <div class="grid-x lr-border">
    <div class="small-8 medium-2 large-2 cell text-center"><a href="/"><img src="../img/eido_logo.png" alt="EIDO Logo" class="logo"/></a></div>
	<div class="hide-for-small-only medium-5 large-5 cell">&nbsp;</div>
	<div class="hide-for-small-only medium-3 large-3 cell links">
	  <a href="#"><img src="../img/add.png" alt="Add Patient" class="add_icon"/>ADD PATIENT</a><br />
	  <a href="#"><img src="../img/forward.png" alt="User Administration" class="add_icon"/>USER ADdministration</a>
	</div>
	<div class="small-4 medium-2 large-2 cell"><span class="divider float-left">&nbsp;</span><span class="type">AB</span></div>
  </div> 
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell return">
	  <div><img src="../img/icons/back_white.png" alt="less than icon" class="" />Back to Dashboard</div>
  	</div>
	<div class="cell page-title-blue">USER ADMINISTRATION</div>
  </div>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="100%" border="0"  class="su-table stack">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>Bulk actions</span></a></span>
			  <span class="float-right"><a href="#"><span>Sort by</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
          <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width="">User</td>
			<td width="">Admin</td>
			<td width="">Surgeon</td>
			<td width=""></td>
          </tr>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name">RANDALL, George<br /><span class="small">george.randall@hospital.com</span></p></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
          </tr>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name">ROGERS, Betty<br /><span class="small">betty.rogers@hospital.com</span></p></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
          </tr>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name">KENT, Clarke<br /><span class="small">clarke.kent@hospital.com</span></p></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
          </tr>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name">RANDALL, George<br /><span class="small">george.randall@hospital.com</span></p></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
          </tr>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name">ROGERS, Betty<br /><span class="small">betty.rogers@hospital.com</span></p></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
          </tr>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name">KENT, Clarke<br /><span class="small">clarke.kent@hospital.com</span></p></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><input type="checkbox" name=""></td>
			<td width=""><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
          </tr>
		  <tr>
		    <td colspan="5">
			  <ul class="pagination text-center" role="navigation" aria-label="Pagination">
  			    <li class="pagination-previous disabled">Previous</li>
  			    <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
  			    <li><a href="#" aria-label="Page 2">2</a></li>
  			    <li><a href="#" aria-label="Page 3">3</a></li>
  			    <li><a href="#" aria-label="Page 4">4</a></li>
  			    <li class="ellipsis"></li>
  			    <li><a href="#" aria-label="Page 12">12</a></li>
  			    <li><a href="#" aria-label="Page 13">13</a></li>
  			    <li class="pagination-next"><a href="#" aria-label="Next page">Next</a></li>
  			  </ul>
			</td>
		  </tr>
  	    </tbody>
      </table>
	</div>
	<!-- End Content-Left -->
	<!-- Start Content-Right -->  
	<div class="small-12 medium-6 large-6 cell content-right">
	  <h2>View User</h2>
	  <form>
  		<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
      		<div class="small-12 medium-12 large-12 cell">
        	  <label>First Name
                <input type="text" name="" placeholder="George">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>Surname
                <input type="text" name="" placeholder="RANDALL">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>Email Address
                <input type="text" name="" placeholder="george.randall@hospital.com">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
			  <div class="grid-x grid-padding-x">
                <fieldset class="small-12 medium-12 large-12 cell">
    			  <input id="checkbox1" type="checkbox"><label for="checkbox1">Is a surgeon</label>
    			  <input id="checkbox2" type="checkbox"><label for="checkbox2">Is a system administrator</label>
  				</fieldset>
			  </div>
			</div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>GMC Number
                <input type="text" name="" placeholder="">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">&nbsp;</div>
			<div class="small-12 medium-12 large-12 cell text-center">
			  <input type="submit" name="" value="Update Procedure" class="button large" />
			  <input type="submit" name="" value="Update Procedure" class="button large inactive" />
			  <input type="submit" name="" value="Update Procedure" class="button large red" />
            </div>
    	  </div>
  		</div>
	  </form>
	</div>  
    <!-- End Content-Right -->
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
  <div class="grid-x footer align-middle">
    <div class="small-12 medium-6 large-6 cell">
	  <p><a href="#" class="white">Need any help?</a><br />
	  <a href="#" class="white">FAQ</a><br />
	  <a href="#" class="white">Knowledgebase</a></p>	
	</div>
	<div class="small-12 medium-6 large-6 cell text-right">
		<p>Copyright EIDO Systems Ltd, 2017. All Rights Reserved<br />
		<p><a href="#">Terms &amp; Conditions</a> | <a href="#">Privacy Policy</a></p>
	</div>
  </div> 
  <!-- End Footer -->
</div>
	<script src="../js/vendor/jquery.js"></script>
    <script src="../js/vendor/what-input.js"></script>
    <script src="../js/vendor/foundation.js"></script>
    <script src="../js/app.js"></script>
    <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script>
      $( function() {
      $( "#sortable" ).sortable({
        placeholder: "ui-state-highlight"
      });
      $( "#sortable" ).disableSelection();
      } );
    </script>
	<script>
        jQuery(document).ready(function() {
          // This button will increment the value
          $('[data-quantity="plus"]').click(function(e){
          // Stop acting like a button
          e.preventDefault();
          // Get the field name
          fieldName = $(this).attr('data-field');
          // Get its current value
          var currentVal = parseInt($('input[name='+fieldName+']').val());
          // If is not undefined
          if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
          } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
         }
    });
         // This button will decrement the value till 0
         $('[data-quantity="minus"]').click(function(e) {
         // Stop acting like a button
         e.preventDefault();
         // Get the field name
         fieldName = $(this).attr('data-field');
         // Get its current value
         var currentVal = parseInt($('input[name='+fieldName+']').val());
         // If it isn't undefined or its greater than 0
         if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
         } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
         }
       });
     });
     </script>  
   </body>
</html>
