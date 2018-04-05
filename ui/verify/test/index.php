<!doctype html>
<?php
require_once '../utilities.php';
$logfile = "wel.log";

$mode = get_query_string('m');
$id = get_query_string('id');
if ($mode=="")
{
   $add_hide = "";
   $update_hide = "hide";
}
else if ($mode=="update")
{
   $add_hide = "hide";
   $update_hide = "";
}

if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}

// need to change according to session
if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}

$sql = "SELECT u.*
        FROM ".DB_PREFIX."user u, ".DB_PREFIX."user_role ur
        WHERE u.id=ur.userid
        AND ur.roleId='ROLE_ADMIN' LIMIT $start,$row";
$GetQuery = dbi_query($sql);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage SuperUsers</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link rel="stylesheet" href="./timeline.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
     <?php include "../includes/su_header.php"; ?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li class="current"><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li class="current"><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <div class="timeline">
        <div class="timeline-item">
		  <div class="float-left show-for-medium" style="width:30px;">date</div>
          <div class="timeline-icon">
            <img src="../img/icons/white_circle.png" width="30">
          </div>
          <div class="timeline-content right">
			<p class="show-for-small-only">date for mobile</p>
            <p>Default timeline.</p>
          </div>
        </div>
		  
		<div class="timeline-item">
		  <div class="float-left show-for-medium">date</div>
          <div class="timeline-icon-cs">
            <img src="../img/icons/attention.png" width="30">
          </div>
          <div class="timeline-content right">
			<p class="show-for-small-only">date for mobile</p>
            <p>Current Step</p>
          </div>
        </div>

  		<div class="timeline-item">
		  <div class="float-left show-for-medium">date</div>
    	  <div class="timeline-icon">
            <img src="../img/icons/white_circle.png" width="30">
          </div>
    	  <div class="timeline-content right underline">
			<p class="show-for-small-only">date for mobile</p>
            <p class="timeline-content-date">Default Heading</p>
            <p>Default timeline.
			  <img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle show-for-medium">
			</p>
          </div>
        </div>
		<div class="timeline-item">
		  <div class="float-left show-for-medium">date</div>
    	  <div class="timeline-icon-green">
            <img src="../img/icons/white_circle.png" width="30">
          </div>
    	  <div class="timeline-content right underline">
			<p class="show-for-small-only">date for mobile</p>
            <p class="timeline-content-date">Default Heading</p>
            <p>Default timeline.
			  <img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle show-for-medium">
			</p>
          </div>
        </div>
      </div>
    </div>
	<!-- End Content-Left -->  
 	<!-- Start Content-Right -->  
 	<!-- ADD USER SECTION -->  
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h3>Add SuperUser</h3>
	  <form action="users_a.php?m=add" method="post">
  		<div class="grid-container">
    	  <div class="grid-x">
      	    <div class="small-12 cell field">
        	  <label>First Name
                <input type="text" name="firstName" placeholder="">
              </label>
            </div>
      		<div class="small-12 cell field">
        	  <label>Surname
                <input type="text" name="lastName" placeholder="">
              </label>
            </div>
			<div class="small-12 cell field">
        	  <label>E-mail Address
                <input type="text" name="email" placeholder="">
              </label>
            </div>
			<div class="small-12 cell text-center">
        	  <br /><input type="submit" id="add" class="button large" value="Add User">
            </div>
    	  </div>
  		</div>
	  </form>
	</div>  

 	<!-- UPDATE USER SECTION -->  
        <?php
           if ($mode=="update")
           {
              $sql_u = "SELECT *  
                        FROM ".DB_PREFIX."user 
                        WHERE id='$id'";
               $GetQuery_u = dbi_query($sql_u);
               $qryResult_u = $GetQuery_u->fetch_assoc();
               $firstName = $qryResult_u['firstName'];
               $lastName = $qryResult_u['lastName'];
               $email = $qryResult_u['email'];
            }
            else
            {
               $firstName = "";
               $lastName = "";
               $email = "";
            }
        ?>
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
	  <h3>Update SuperUser</h3>
	  <form action="users_a.php?m=update&id=<?php echo $id; ?>" method="post">
  		<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
      	    <div class="small-12 medium-12 large-12 cell">
        	  <label>First Name
                <input type="text" name="firstName" value="<?php echo $firstName; ?>">
              </label>
            </div>
      		<div class="small-12 medium-12 large-12 cell">
        	  <label>Surname
                <input type="text" name="lastName" value="<?php echo $lastName; ?>">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>E-mail Address
                <input type="text" name="email" value="<?php echo $email; ?>">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell text-center">
        	  <br /><input type="submit" id="update" class="button large" value="Update User">
            </div>
    	  </div>
  		</div>
	  </form>
	</div>
	<!-- End Content-Right -->
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
     <?php include "../includes/footer.php"; ?>
  <!-- End Footer -->
</div>
      <script src="../js/vendor/jquery.js"></script>
      <script src="../js/vendor/what-input.js"></script>
      <script src="../js/vendor/foundation.js"></script>
      <script src="../js/app.js"></script>
      <script>
         $(document).ready(function () {
            $(".clickable-row").click(function() {
              window.location = $(this).data("href");
            });
         });
      </script>  
   </body>
</html>
