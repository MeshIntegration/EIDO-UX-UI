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
  <title>Patient Lookup - Review Patient Access</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/app.css">  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <link rel="icon" type="image/png" href="../favicon.png">
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
		<li class="current"><a href="../superuser/add_user.php">Users</a></li>
		<li><a href="../superuser/add_organisation.php">Organisations</a></li>
		<li><a href="../superuser/add_procedure.php">Procedures</a></li>
		<li><a href="#">System status</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li class="current"><a href="../superuser/add_user.php">Users</a></li>
		<li><a href="../superuser/add_organisation.php">Organisations</a></li>
		<li><a href="../superuser/add_procedure.php">Procedures</a></li>
		<li><a href="#">System status</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="90%" border="0"  class="su-table">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>BULK ACTIONS</span></a></span>
			  <span class="float-right"><a href="#"><span>SORT BY</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
          <tr>
            <td width="10%"><input type="checkbox"></td>
            <td width="80%">&nbsp;</td>
			<td width="10%">&nbsp;</td>
          </tr>
		  <tr>
            <td width="10%"><input type="checkbox"></td>
			<td width="80%"><p class="name">HUGHES, Rob<br /><span class="small">rob@hughes.com</span></p></td>
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
	  <h3>Review Patient Access<br><span class="small">Update patient data after a failed login.</span></h3>
	  <h5 class="ps_red">JONES, William<span class="small">Sign In Review</span></h5>	
	  <p>The patient has attempted to sign in, but cannot match their data with the system.<br />
         The most likely cause is a data entry error in Verify.</p>
	  <p>Check the data the patient entered vs the data in your PAS system to see if there is a mis-match. Use this screen to update any errors.</p>
	  <h5>Date of Birth</h5>
	  <table class="review stack">
	    <tr>
		  <td class="aqua_bdr grey_bdr text-right" width="25%">Patient Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>  
		</tr>
		<tr class="space">
		  <td colspan="3">&nbsp;</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">Verify Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>
		<tr>
		  <td colspan="3" class="text-right small">Select which line is correct, or add a new data point below.</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">New</td>
		  <td>&nbsp;</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>  
	  </table>
	  <hr />
	  <h5>Surname</h5>
	  <table class="review stack">
	    <tr>
		  <td class="aqua_bdr grey_bdr text-right" width="25%">Patient Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>  
		</tr>
		<tr class="space">
		  <td colspan="3">&nbsp;</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">Verify Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>
		<tr>
		  <td colspan="3" class="text-right small">Select which line is correct, or add a new data point below.</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">New</td>
		  <td>&nbsp;</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>  
	  </table>
	  <hr />
	  <h5>Postcode</h5>
	  <table class="review stack">
	    <tr>
		  <td class="aqua_bdr grey_bdr text-right" width="25%">Patient Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>  
		</tr>
		<tr class="space">
		  <td colspan="3">&nbsp;</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">Verify Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>
		<tr>
		  <td colspan="3" class="text-right small">Select which line is correct, or add a new data point below.</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">New</td>
		  <td>&nbsp;</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>  
	  </table>
	  <hr />
	  <h5>NHS Number</h5>
	  <table class="review stack">
	    <tr>
		  <td class="aqua_bdr grey_bdr text-right" width="25%">Patient Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>  
		</tr>
		<tr class="space">
		  <td colspan="3">&nbsp;</td>
		</tr>
		<tr>
		  <td class="green_bdr grey_bdr text-right">Verify Entry</td>
		  <td>01-04-1985</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>
		<tr>
		  <td colspan="3" class="text-right small">Select which line is correct, or add a new data point below.</td>
		</tr>
		<tr>
		  <td class="plain_bdr grey_bdr text-right">New</td>
		  <td>&nbsp;</td>
		  <td class="text-right"><input type="radio" name="" value="" id=""></td>
		</tr>  
	  </table>
	  <hr />
	  <div class="grid-x">
		<div class="hide-for-small-only medium-3 large-3 cell"></div>		
	    <div class="small-12 medium-6 large-6 cell text-center">
	      <p>&nbsp;</p>
	      <button type="submit" name="" value="update patient" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Place text here" data-position="top" data-alignment="center" class="has-tip button large inactive expanded" />update patient</button>
	    </div>
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
	  </div>
	</div>
	<!-- End Content-Right --> 
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
  <div class="grid-x footer align-middle">
    <div class="small-6 medium-6 large-6 cell">
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
      <script>
         $(document).ready(function () {

         });
      </script>  
  </body>
</html>