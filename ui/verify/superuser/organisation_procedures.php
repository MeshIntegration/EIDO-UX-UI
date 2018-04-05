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
  <title>Organisation Procedures</title>
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
		<li><a href="add_user.php">Users</a></li>
		<li class="current"><a href="add_organisation.php">Organisations</a></li>
		<li><a href="add_procedure.php">Procedures</a></li>
		<li><a href="#">System status</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="add_user.php">Users</a></li>
		<li class="current"><a href="add_organisation.php">Organisations</a></li>
		<li><a href="add_procedure.php">Procedures</a></li>
		<li><a href="#">System status</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="100%" border="0"  class="su-table">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>BULK ACTIONS</span></a></span>
			  <span class="float-right"><a href="#"><span>SORT BY</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
          <tr>
            <td width="10%"><input type="checkbox" /></td>
            <td width="80%">&nbsp;</td>
			<td width="10%">&nbsp;</td>
          </tr>
		  <tr>
            <td width="10%"><input type="checkbox" /></td>
			<td width="80%"><p class="name">Nottingham Hospital<br /><span class="small">Hospital</span></p></td>
			<td width="10%"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td width="10%"><input type="checkbox" /></td>
			<td width="80%"><p class="name">Bristol General<br /><span class="small">Hospital</span></p></td>
			<td width="10%"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td width="10%"><input type="checkbox" /></td>
			<td width="80%"><p class="name">Insuracorp<br /><span class="small">Medical Insurer</span></p></td>
			<td width="10%"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></td>
		  </tr>
		  <tr>
            <td width="10%"><input type="checkbox" /></td>
			<td width="80%"><p class="name">Smith Medical<br /><span class="small">Private Hospital Group</span></p></td>
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
	  <h2>Organisation Procedures<br /><span class="small">Nottingham Hospital</span></h2>
	  <form>
  		<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
			<div class="small-12 medium-12 large-12 cell">
              <div class="grid-x">
                <div class="small-10 cell">
				  <label>Procedure Search	
                  <input type="text" placeholder="oscopy" class="search-left">
				  </label>
                </div>
                <div class="small-2 cell no-label">
                  <div class="search-right"><a href="#" class="button postfix expanded search-btn"><i class="fi-magnifying-glass"></i></a></div>
                </div>
              </div>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <table width="100%" border="0" class="hover">
  			    <tbody>
    			  <tr>
      			    <td>A01</td>
                    <td>Anaesthetic</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
				  <tr>
      			    <td>B06</td>
                    <td>Back Repair Operation</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
				  <tr>
      			    <td>UG07</td>
                    <td>Laparascopic Cholesystectomy</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
				  <tr>
      			    <td>E01</td>
                    <td>Endoscopy</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
				  <tr>
      			    <td>UG07</td>
                    <td>Laparascopic Cholesystectomy</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
				  <tr>
      			    <td>UG07</td>
                    <td>Laparascopic Cholesystectomy</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
				  <tr>
      			    <td>UG07</td>
                    <td>Laparascopic Cholesystectomy</td>
                    <td class="align-middle text-right"><i class="fi-trash"></i></td>
				  </tr>
                </tbody>
              </table>
            </div> 
			<div class="small-12 medium-12 large-12 cell text-center">
        	  <input type="submit" name="" value="Update Organisation" class="button large" href="#">
            </div>
    	  </div>
  		</div>
	  </form>
	</div>  
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
