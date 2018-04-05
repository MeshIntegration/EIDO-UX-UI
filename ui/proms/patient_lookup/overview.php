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
  <title>Patient Lookup - Patient Overview</title>
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
	  <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
	  <h5>Patient Overview<br /><span class="small">See a patient's progress through Verify</span></h5>
	  <p>&nbsp;</p>
	  <h5 class="ps_green">JONES, William<span class="small">Active</span></h5>
	  <h5 class="ps_red">JONES, William<span class="small">No Activity</span></h5>
	  <h5 class="ps_grey">JONES, William<span class="small">Complete</span></h5>
	  <table class="overview">
	    <tr>
		  <td class="no_left_pad">
		    HospNo: K123456<br />
			NHS No: 987 654 321<br />
			DOB: 01-03-1985
		  </td>
		  <td><a href="#"><img src="../img/icons/greater.png" alt="greater than icon" class="align-center-middle" /></a></td>
		</tr>
		<tr>
		  <td class="no_left_pad">
		    <p>
			  Procedure<br />
			  UG08 - Open Cholecystectomy
			</p>
		    <p>
			  Procedure Date<br />
			  28-06-2017
			</p>
		  </td>
		  <td><a href="#"><img src="../img/icons/greater.png" alt="greater than icon" class="align-center-middle" /></a></td>
		</tr>
		<tr>
		  <td colspan="2" class="no_left_pad">
		    <p>Status<br />
		      <img src="../img/icons/exclaimation_red.png" alt="attention needed" class="left_icon" />Missed Activity<br />
            </p>
		  </td>
		</tr>
		<tr>
		  <td colspan="2" class="no_left_pad">
		    <p>Tags<br />
              <span class="label tag">Diabetic</span><span class="label tag">Diabetic</span><span class="label tag">Diabetic</span>
	        </p>	
		  </td>
		</tr>
	  </table>
	  <p>
	    <table>
		  <tr>
		    <td colspan="5" class="no_left_pad">Patient Timeline</td>
		  </tr>
		  <tr>
		    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
		    <td><img src="../img/icons/envelope.png" alt="Envelope icon"></td>
		    <td class="upper">Email Received</td>
		    <td>2nd Oct</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
		    <td><img src="../img/icons/pointer.png" alt="Cursor icon"></td>
		    <td class="upper">URL INVITE CLICKED</td>
		    <td>2nd Oct</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
		    <td><img src="../img/icons/page_small.png" alt="Small page icon"></td>
			  <td class="upper">
			    SUBMISSION RECEIVED<br />
				UG08 - Pre Assessment
			  </td>
		    <td>2nd Oct</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="status action_needed"><img src="../img/icons/caution.png" alt="Circular checkmark icon" class="align-middle"></td>
		    <td class="action_needed"><img src="../img/icons/procedure.png" alt="Cursor icon"></td>
		    <td class="upper action_needed">PROCEDURE</td>
			<td class="action_needed">6th Oct</td>
		    <td class="action_needed"><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr>
		    <td class="action_needed">&nbsp;</td>
		    <td colspan="3" class="action_needed"><button type="button" name="" value="PROCEDURE COMPLETE" class="button expanded">PROCEDURE COMPLETE</button></td>
		    <td class="action_needed">&nbsp;</td>
	      </tr>
		  <tr class="space">
		    <td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="status"><img src="../img/icons/exclaimation_red.png" alt="Circular checkmark icon" class="align-middle"></td>
		    <td><img src="../img/icons/envelope_red.png" alt="Envelope icon in red"></td>
		    <td class="upper warning">Email Received</td>
		    <td class="warning">2nd Oct</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
		  <tr class="space">
		    <td colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
		    <td class="status"><img src="../img/icons/arrow.png" alt="Arrow icon" class="align-middle"></td>
		    <td><img src="../img/icons/page_small.png" alt="Small page icon"></td>
			  <td class="upper">
			    Survey<br />
				UG08 - Post Op
			  </td>
		    <td>2nd Oct</td>
		    <td><img src="../img/icons/greater.png" alt=""></td>
		  </tr>
	    </table>
	  </p>
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