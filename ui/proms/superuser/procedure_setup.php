<!doctype html>
<?php
// need to change according to session
if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}
?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Procedure Setup</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
  <div class="grid-x lr-border">
    <div class="small-8 medium-2 large-2 cell text-center"><a href="/"><img src="../img/eido_logo.png" alt="EIDO Logo" class="logo"/></a></div>
	<div class="hide-for-small-only medium-5 large-5 cell">&nbsp;</div>
	<div class="hide-for-small-only medium-3 large-3 cell links"></div>
	<div class="small-4 medium-2 large-2 cell"><span class="divider float-left">&nbsp;</span><span class="type">AB</span></div>
  </div> 
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li class="current"><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li class="current"><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="100%" border="0"  class="su-table">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>Bulk actions</span></a></span>
			  <span class="float-right"><a href="#"><span>Sort by</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
          <tr>
            <td width="10%"><input type="checkbox"></td>
            <td width="80%">&nbsp;</td>
			<td width="10%">&nbsp;</td>
          </tr>
		  <tr>
            <td width="10%"><input type="checkbox"></td>
			<td width="80%"><p class="name">UG07 - Lap Choly NUH<br /><span class="small">Laparascopic Cholescstectomy</span></p></td>
			<td width="10%"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td width="10%"><input type="checkbox"></td>
			<td width="80%"><p class="name">UG07 - Lap Choly Master<br /><span class="small">Laparascopic Cholescstectomy</span></p></td>
			<td width="10%"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td width="10%"><input type="checkbox"></td>
			<td width="80%"><p class="name">E01 - Endoscopy Master<br /><span class="small">Endoscopy</span></p></td>
			<td width="10%"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
		    <td colspan="3">
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
	  <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
	  <h2>Procedure Setup<br /><span class="small">Add surveys to the procedure session</span></h2>
	  <form>
  		<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
			<div class="small-12 medium-12 large-12 cell">
              <h4>E01 - Endoscopy Master</h4>
            </div>
			<div class="small-12 medium-12 large-12 cell">
			  <div class="grid-x">
				<div class="small-12 medium-6 large-4 cell">
				  <label>Number of Sessions
                  <div class="input-group plus-minus-input">
                    <div class="input-group-button">
                      <button type="button" class="button" name="" data-quantity="minus" data-field="quantity"><i class="fi-minus" aria-hidden="true"></i></button>
                    </div>
  				    <input class="input-group-field" type="number" name="quantity" value="0" width="40px">
                    <div class="input-group-button">
    				  <button type="button" class="button" name="" data-quantity="plus" data-field="quantity"><i class="fi-plus" aria-hidden="true"></i></button>
  				    </div>
                  </div>
			      </label>
				</div>
				<div class="small-12 medium-6 large-8 cell"></div>
			  </div>
			  <hr />	
            </div>
			<div class="small-12 medium-12 large-12 cell">
			  <div class="grid-x grid-padding-x align-middle">
				<div class="small-12 medium-5 large-5 cell">
                  <select>
                	<option value="session_01">Session 01</option>
                	<option value="session_02">Session 02</option>
                	<option value="session_03">Session 03</option>    
                	<option value="session_04">Session 04</option>
                	<option value="session_05">Session 05</option>					
  			  	  </select>
				</div>
				<div class="hide-for-small-only medium-2 large-2 cell">&nbsp;</div>
				<div class="small-12 medium-5 large-5 cell">
                  <a href="#">Show All</a>
				</div>
			  </div>
			  <hr />
			</div>
			<div class="small-12 medium-12 large-12 cell">
			  <h5>Session Name<br /><span class="small">This name will be used to identify the session to hospital staff.</span></h5>
              <div class="small-12 cell"><input type="text" name="" value="" placeholder=""><br /></div>
			  <div class="input-group">
  			    <span class="input-group-label">Type</span>
  				<div class="input-group-button">
                  <input type="submit" class="button tiny stretch" name="" value="Pre">
                </div>
				<div class="input-group-button default">
                  <input type="submit" class="button tiny inactive stretch" name="" value="Post">
                </div>
              </div>
            </div>
			<div class="small-12 medium-12 large-12 cell">
			  <div class="grid-x grid-padding-x">
				<div class="small-12 medium-2 large-2 cell fake_label">
                  Type
				</div>
				<div class="small-12 medium-10 large-10 cell">
                  <div class="switch large">
  				    <input class="switch-input" id="pre-post" type="checkbox" name="exampleSwitch">
  				    <label class="switch-paddle" for="pre-post">
    			      <span class="show-for-sr">Pre or Post?</span>
                      <span class="switch-active" aria-hidden="true">Pre</span>
                      <span class="switch-inactive" aria-hidden="true">Post</span>
                    </label>	  
				  </div>
				</div>
			  </div>
			</div>  
			<div class="small-12 medium-12 large-12 cell">
			  <ul class="sort">
			    <li><i class="fi-list sort-icon move"></i>GEN Pre Screening (1)<i class="fi-trash sort-icon float-right"></i></li>
				<li><i class="fi-list sort-icon move"></i>E01 Core<i class="fi-trash sort-icon float-right"></i></li>
				<a href="procedure_setup_add_survey.php"><li class="add"><button class="button expanded text-center add-survey" type="button"><i class="fi-plus"></i></button></li></a>
			  </ul>
			</div>
			<div class="small-12 medium-12 large-12 cell text-center">
        	  <input type="submit" class="button large" name="" value="Update Procedure">
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
