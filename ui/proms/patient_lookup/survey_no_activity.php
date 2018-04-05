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
  <title>Patient Lookup - Survey - No Activity</title>
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
	  <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
	  <h5>Summary<br /><span class="small">Check and confirm</span></h5>
	  <p>&nbsp;</p>
	  <h5 class="ps_green">JONES, William<span class="small">Active</span></h5>
	  <h5><img src="../img/icons/page.png" alt="Page icon" class="page_icon float-left align-middle"/>UR07 - Core<br><span class="small">Invite sent: 06-05-2017</span></h5>
	  <p>&nbsp;</p>	
	  <h5>Activity</h5>
	  <table class="activity">
	    <tr>
		  <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon"/></td>
		  <td><img src="../img/icons/envelope.png" alt="Envelope icon"/></td>
		  <td class="upper">Email Received</td>
		  <td>2nd Oct</td>
		  <td><img src="../img/icons/greater.png" alt=""/></td>
		</tr>
	  </table>
	  <div class="grid-x">
		<div class="hide-for-small-only medium-2 cell"></div>		
	    <div class="small-12 medium-8 cell text-center">
	      <p>&nbsp;</p>
	      <button type="button" name="" value="Resend Invite" class="button large expanded" />Resend Invite</button>
	    </div>
		<div class="hide-for-small-only medium-2 cell"></div>		
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