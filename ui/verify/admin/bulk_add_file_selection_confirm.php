<!doctype html>
<?php

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
  <title>Bulk Add/Update</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
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
  <div class="grid-x grid-margin-x bulk text-center" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Full -->
    <div class="small-12 medium-12 large-12 cell content-full">
	  <p>&nbsp;</p>
	  <h2>Bulk Edit</h2>
      <p>To add or remove users from the system in bulk, you may use a CSV from your admin system</p>
	  <div class="small-12 medium-12 large-12 cell">
	    <div class="grid-x">	
		  <div class="hide-for-small-only medium-3 large-3">&nbsp;</div>
	      <div class="small-12 medium-6 large-6 cell">
            <div class="grid-x">
		  <div class="hide-for-small-only medium-2 large-2">&nbsp;</div>	
		  <div class="small-12 medium-6 large-6 cell">
	        <label class="text-left">CSV	
              <input type="text"  name="" placeholder=" file/January_Subscribers.csv">
		    </label>
          </div>	
          <div class="small-12 medium-3 large-3 cell no-label">
            <input type="submit" name="" value="Browse" class="button grey csv">
          </div>
		  <div class="hide-for-small-only medium-1 large-1">&nbsp;</div>
		</div>
          </div>
	      <div class="hide-for-small-only medium-3 large-3">&nbsp;</div>
		</div>
		<div class="grid-x text-center">
		  <div class="hide-for-small-only medium-3 large-3 cell"></div>
		  <div class="small-12 medium-6 large-6 cell">
			<p>&nbsp;</p>
	        <h5 class="notification"><i class="icon fi-page-csv extra"></i>&nbsp;January_Subscribers.csv</h5>
		    <div class="grid-x grid-padding-x">
			  <div class="small-12 medium-12 large-12 cell text-center">
			    <p>This operation will ADD 19 users to the system.</p>
			  </div>
			  <div class="small-12 medium-12 large-12 cell text-center">
			    <p>&nbsp;</p>
				<input type="button" name="" value="Cancel" class="button inactive" />&nbsp;&nbsp;&nbsp;<input type="button" name="" value="Next" class="button" />
			  </div>
            </div>
		  </div>
		  <div class="hide-for-small-only medium-3 large-3 cell"></div>
	    </div>
	  </div>
      <p class="instructions">Instructions:<br />Add/Update is one operation. Remove is another operation.</p>
      <p>Create a CSV of all the users you would like to add.<br>Upload it in the field above.Follow the instructions.</p>
      <p><a href="#">Download a sample CSV file here</a></p>
	</div>
	<!-- End Content-Full -->  
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
      <script>
         $(document).ready(function () {

         });
      </script>  
   </body>
</html>