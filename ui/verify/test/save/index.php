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
    <div class="timeline-icon">
       <img src="../img/icons/exclaimation.png" width="30">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2008</h2>
      <p>The ZURB Style Guide is created to help us speed up our work. It’s a handy collection of HTML, CSS and Javascript that we use on every client project. </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
        <img src="../img/icons/caution.png" width="30">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2010</h2>
      <p>ZURB style guide was solidified and named Foundation. </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill-rule="evenodd" clip-rule="evenodd"><path d="M17 24h-10l-2-14h14l-2 14zm-.592-10h-8.816l.571 4h7.674l.571-4zm1.631-8c0 .922 1.092 1.618 1.961 1.618v1.382h-16v-1.382c.87 0 2-.697 2-1.618h12.039zm-7.73-.691c2.819-2.143-.594-2.353.077-3.868-2.361 2.113.85 2.169-.077 3.868zm2.486-.001c4.236-3.238-.877-3.067.105-5.308-3.382 2.895 1.259 2.959-.105 5.308z"/></svg>
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2011</h2>
      <p>
        <a href="http://foundation.zurb.com/sites/docs/v/2.2.1/" target="_blank">Foundation 2.0</a> is released to the public as an open source project! 
      </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill-rule="evenodd" clip-rule="evenodd"><path d="M24 11.5c0 3.613-2.951 6.5-6.475 6.5-2.154 0-4.101-1.214-5.338-3h-2.882l-1.046-1.013-1.302 1.019-1.362-1.075-1.407 1.081-4.188-3.448 3.346-3.564h8.841c1.145-1.683 3.104-3 5.339-3 3.497 0 6.474 2.866 6.474 6.5zm-10.691 1.5c.98 1.671 2.277 3 4.217 3 2.412 0 4.474-1.986 4.474-4.5 0-2.498-2.044-4.5-4.479-4.5-2.055 0-3.292 1.433-4.212 3h-9.097l-1.293 1.376 1.312 1.081 1.38-1.061 1.351 1.066 1.437-1.123 1.715 1.661h3.195zm5.691-3.125c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5z"/></svg>
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2012</h2>
      <p>
        <a href="http://foundation.zurb.com/sites/docs/v/3.2.5/" target="_blank">Foundation 3.0</a> is released! This version comes with Sass.
      </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill-rule="evenodd" clip-rule="evenodd"><path d="M7.467 0c1.102.018 5.555 2.549 6.386 8.558.905-.889 1.409-3.664 1.147-4.843 3.952 2.969 6 6.781 6 11.034 0 5.094-3.43 9.251-8.963 9.251-5.728 0-9.037-3.753-9.037-8.276 0-6.26 5.052-7.62 4.467-15.724zm3.262 19.743c-.749.848-.368 1.945.763 2.045 1.035.093 1.759-.812 2.032-1.792.273-.978.09-2.02-.369-2.893-.998 1.515-1.52 1.64-2.426 2.64zm4.42 1.608c2.49-1.146 3.852-3.683 3.852-6.58 0-2.358-.94-4.977-2.5-7.04-.743 2.867-2.924 3.978-4.501 4.269.05-3.219-.318-6.153-2.602-8.438-.296 4.732-4.321 7.63-4.398 12.114-.029 1.511.514 3.203 1.73 4.415.491.489 1.054.871 1.664 1.16-.121-.608-.062-1.254.195-1.848.911-2.106 3.333-2.321 4.202-5.754.952.749 3.275 3.503 2.778 6.358-.082.469-.224.923-.42 1.344z"/></svg>
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2013 - <span class="timeline-content-month">february</span></p>
      <p>This year saw not one, but three releases to Foundation! <a href="http://foundation.zurb.com/sites/docs/v/4.3.2/index.html" target="_blank">Version 4</a> went <a href="http://zurb.com/word/mobile-first" target="_blank">mobile first</a>, added many new components, and came with a visual update. Our responsive image plugin called <a href="http://foundation.zurb.com/sites/docs/v/4.3.2/components/interchange.html" target="_blank">Interchange</a> was added to Foundation to make sites built with it load even faster.</p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <img src="https://cdns.iconmonstr.com/wp-content/assets/preview/2017/96/iconmonstr-candy-26.png" class="" height="21" width="21" alt="">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2013 - <span class="timeline-content-month">november</span></p>
      <p>Foundation 5 is all about speed. It made learning, using and developing with the framework faster than ever! More new components like <a href="http://foundation.zurb.com/docs/components/equalizer.html" target="_blank">Equalizer</a>, <a href="http://foundation.zurb.com/docs/components/offcanvas.html" target="_blank">Off-canvas</a>, and Icon-bar </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <img src="https://cdns.iconmonstr.com/wp-content/assets/preview/2017/96/iconmonstr-fast-food-10.png" class="" height="21" width="21" alt="">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2013 - <span class="timeline-content-month">november</span></p>
      <p><a href="http://foundation.zurb.com/emails.html" target="_blank">Foundation for Emails (formerly Ink)</a>, our responsive email framework is launched at the end of the year and helps designers </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <img src="https://cdns.iconmonstr.com/wp-content/assets/preview/2017/96/iconmonstr-fast-food-12.png" class="" height="21" width="21" alt="">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2014 - <span class="timeline-content-month">november</span></p>
      <p>We launched <a href="http://zurb.com/article/1362/foundation-for-apps-is-here" target="_blank">Foundation for Apps</a>, the first .</p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <img src="https://cdns.iconmonstr.com/wp-content/assets/preview/2017/96/iconmonstr-fast-food-5.png" class="" height="21" width="21" alt="">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2015</h2>
      <p><a href="http://zurb.com/article/1416/foundation-6-is-here" target="_blank">Foundation for Sites 6 is released</a>. Faster, lighter, </p>
    </div>
  </div>

  <div class="timeline-item">
    <div class="timeline-icon">
      <img src="https://cdns.iconmonstr.com/wp-content/assets/preview/2017/96/iconmonstr-check-mark-18.png" class="" height="21" width="21" alt="">
    </div>
    <div class="timeline-content right">
      <p class="timeline-content-date">2016</h2>
      <p><a href="http://zurb.com/article/1432/foundation-for-emails-2-is-here" target="_blank">Foundation for Emails 2</a> is launched, test web technologies.</p>
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
