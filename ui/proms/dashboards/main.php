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
  <title>Dashboard - Main Dashboard</title>
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
	<div class="hide-for-small-only medium-3 large-3 cell links">
	  <a href="#"><img src="../img/add.png" alt="Add Patient" class="add_icon"/>ADD PATIENT</a><br />
	</div>
	<div class="small-4 medium-2 large-2 cell"><span class="divider float-left">&nbsp;</span><span class="type">AB</span></div>
  </div> 
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <form>
    <div class="grid-x grid-padding-x grey_header">
	  <div class="small-12 medium-2 cell gh_form">
        <label>I'm looking for a:
          <select>
    	    <option value="Patient">Patient</option>
  		  </select>
        </label>
      </div>
	  <div class="small-12 medium-5 cell gh_form basic">
        <label>Search by:
          <input type="text" placeholder="Jones">
        </label>
      </div>
	  <div class="small-12 medium-3 cell gh_form">
        <label>Procedure Date:
          <input type="text" placeholder=".medium-6.cell">
        </label>
      </div>
	  <div class="small-12 medium-2 cell gh_form no-label">
        <button type="submit" name="" class="button">Search</button>
      </div>
    </div>
  </form>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="90%" border="0" class="lookup">
  	    <tbody>
          <tr>
		    <td colspan="3" class="top_bdr btm_bdr">
			  <div class="grid-x">
			    <div class="small-12 medium-6">
				  <div class="button-group"> 
                    <button type="button" name="" value="" class="button lu_btn inactive">Pre-Op</button>
				    <button type="button" name="" value="" class="button lu_btn inactive">Post-Op</button>
                  </div>  
				</div>
				<div class="small-12 medium-6 text-right"><a href="#">SORT BY&nbsp;<i class="icon fi-plus fade-right"></i></a></div>  
			  </div>
			</td>
          </tr>
		  <tr>
		    <td colspan="3" class="btm_bdr"># results</td>
		  </tr>
          <tr>
            <td colspan="2"><p class="name on_status">HUGHES, Rob<br /><span class="small">rob@hughes.com</span></p></td>
			<td><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td colspan="2"><p class="name off_status">HUGHES, Rob<br /><span class="small">rob@hughes.com</span></p></td>
			<td><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td colspan="2"><p class="name pending_status">HUGHES, Rob<br /><span class="small">rob@hughes.com</span></p></td>
			<td><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
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
	  <h2>Add Patient</h2>
      <p>Start a Verify session with a new patient</p>
	  <div class="grid-x">
	    <div class="hide-for-small-only medium-2">&nbsp;</div>
        <div class="small-12 medium-8"><button class="button large expanded" href="#">Get Started</button></div>	  	
		<div class="hide-for-small-only medium-2">&nbsp;</div>
	  </div>
	  <hr class="gap" />
	  <h2>Recent Notifications</h2>
	  <table class="notifications">
	    <tbody>
		  <tr>
		    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
		    <td class="upper">EMAIL BOUNCED<br />
			  PATIENT <strong>JAMES HIGSON</strong>
			</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td>&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
		    <td class="upper">EMAIL BOUNCED<br />
			  PATIENT <strong>JAMES HIGSON</strong>
			</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td>&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
		    <td class="upper">EMAIL BOUNCED<br />
			  PATIENT <strong>JAMES HIGSON</strong>
			</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td>&nbsp;</td>
		  </tr>
	    </tbody>
	  </table>
	  <div class="grid-x">
	    <div class="hide-for-small-only medium-2">&nbsp;</div>
        <div class="small-12 medium-8"><button type="button" name="" value="" class="button large expanded">View All</button></div>	  	
		<div class="hide-for-small-only medium-2">&nbsp;</div>
	  </div>
	  <hr class="gap" />
	  <h2>Stats</h2>
      <div class="grid-x">
	    <div class="small-12 auto cell text-center grey_bdr">
		  <h6>ACTIVE<br /> PATIENTS</h6>
		  <h2 class="yes">1246</h2>
		  <p class="smaller">TOTAL</p>
		</div>
		<div class="small-12 auto cell text-center grey_bdr">
		  <h6>INACTIVE<br /> PATIENTS</h6>
		  <h2 class="yes">214</h2>
		  <p class="smaller">TOTAL</p>
		</div>
		<div class="small-12 auto cell text-center">
		  <h6>UNRESOLVED<br />ALERTS</h6>
		  <h2 class="yes">22</h2>
		  <p class="smaller">TOTAL</p>
		</div>
	  </div>
	  <p>&nbsp;</p>
	  <div class="grid-x">
	    <div class="hide-for-small-only medium-2">&nbsp;</div>
        <div class="small-12 medium-8"><button type="button" name="" value="" class="button large expanded">View Stats</button></div>	  	
		<div class="hide-for-small-only medium-2">&nbsp;</div>
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