<!doctype html>
<?php
// ***************************************
// superuser/users.php
// 2017 Copyright, Mesh Integration LLC
// 12/13/17 - WEL
// 03/13/18 - SD - add session for paggination
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"SUPERUSER")
{
   header("Location: /ui/verify/login.php");
   exit();
}
$return_to = "suu";
$home = "users.php";
$logfile = "superuser.log";

$mode = get_query_string('m');
$id = get_query_string('id');
// turn everything off
$add_hide = "hide";
$update_hide = "hide";
$userreset_hide = "hide";
$userdelete_hide = "hide";
$reset_hide = "hide";
$delete_hide = "hide";
$bulk_hide = "hide";
if ($mode == "" || $mode == "add") {
    $add_hide = "";
} else if ($mode == "update") {
    $update_hide = "";
    $user_id = $id;
} else if ($mode == "reset") {
    $reset_hide = "";
    $user_id = $id;
} else if ($mode == "delete") {
    $delete_hide = "";
    $user_id = $id;
} else if ($mode == "bulk") {
    $bulk_hide = "";
}
// need to change according to session
$script_name = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);

if ((isset($_GET['page']) && !empty($_GET['page']))){
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}else if (isset($_SESSION['page'][$script_name]['no']) && !empty($_SESSION['page'][$script_name]['no'])){
   $page = $_SESSION['page'][$script_name]['no'];
   $start = ($page - 1) * $row;
}
$_SESSION['page'][$script_name]['no'] = $page ;

$sql = "SELECT u.*
        FROM ".DB_PREFIX."user u, ".DB_PREFIX."user_role ur
        WHERE u.id=ur.userId
        AND ur.roleId='ROLE_ADMIN'
	AND u.active=1 LIMIT $start,$row";
$GetQuery = dbi_query($sql);
$i = 0;
$arr_users = array ();
while ( $qryResult = $GetQuery->fetch_assoc () ) {
    $arr_users [$i] = $qryResult;
    $i ++;
}
?>
<html class="no-js" lang="en" dir="ltr">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Superusers</title>
        <link rel="stylesheet" href="../css/foundation.css">
        <link rel="stylesheet" href="../css/dashboard.css">
        <link rel="stylesheet" href="../css/app.css">
        <link rel="stylesheet" href="../css/foundation-datepicker.min.css">
        <link rel="stylesheet" href="../css/timeline.css">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/ui/verify/css/icons/eido-icons.css" type="text/css" />
        <link rel="icon" type="image/png" href="../favicon.png">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="../css/eido.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li class="current"><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>  
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Left -->
      <div class="small-12 medium-6 large-6 cell content-left">
          <div class="su-table stack large-12">
              <?php include "../includes/admin_bulkActions.php"; ?>
              <div class="row small-12 grid-padding-x" style="margin-bottom:25px;">
                  <div class="float-left">
                      <label class="eido-checkbox">
                          <input class="eido-checkbox" style="margin-left:25px;" type="checkbox" name="actOnAll" id="actOnAll">
                          <span class="checkmark" style="left:0px;"></span>
                      </label>
                  </div>
                  <div class="small-offset-2">
                      <p>User&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSurgeon&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspAdmin</p>
                  </div>
              </div>
              <div class="row">
                  <ul class="patient-list">
                      <?php
                      for($i = 0; $i < count ( $arr_users ); $i ++) {
                          $uid = $arr_users [$i] ['id'];
                          $firstName = ucfirst ( strtolower ( $arr_users [$i] ['firstName'] ) );
                          $lastName = strtoupper ( $arr_users [$i] ['lastName'] );
                          $full_name = $lastName . ", " . $firstName;
                          $email = $arr_users [$i] ['email'];
                          logMsg ("$i $firstName $lastName", $logfile);
                          $isSelected = '';
                          if($uid == $user_id) {
                              $isSelected = ' class="selected"';
                          }
                          ?>
                          <li<?php echo $isSelected; ?>>
                              <a href=users.php?m=update&id=<?php echo $uid; ?>>
                                  <div>
                                      <label class="eido-checkbox">
                                          <input type="checkbox" name="performAction[]" id="performAction<?php echo $i; ?>" value="<?php echo $uid; ?>">
                                          <span class="checkmark"></span>
                                      </label>
                                  </div>
                                  <div id="container" class="clickable-row">
								        <span class="float-right right-arrow">
                                            <i class="eido-icon-chevron-right"></i>
                                        </span>
                                      <div class="medium-offset-1">
                                          <p>
                                              <span><?php echo $full_name; ?></span><br/>
                                              <?php echo $email; ?>
                                          </p>
                                      </div>
                                  </div>
                              </a>
                          </li>
                      <?php } ?>
                  </ul>
              </div>
              <?php
              $sql = "SELECT u.*
	                    FROM dir_user u, dir_user_role ur
	                    WHERE u.id = ur.userId
	                    AND ur.roleId='ROLE_ADMIN'";
              $GetQuery = dbi_query ( $sql );
              $totalRecord = $GetQuery->num_rows;
              $pagination = get_pagination ( $page, $totalRecord );
              ?>
              <div class="grid grid-x text-center">
                  <div class="small-12 pagination-btm-users"><?php echo $pagination; ?></div>
              </div>
          </div>
      </div>
	<!-- End Content-Left -->  
 	<!-- Start Content-Right -->  
 	<!-- ADD USER SECTION -->  
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h3>Add SuperUser</h3>
	  <form id="add_form"  action="users_a.php?m=add" method="post">
  		<div class="grid-container">
    	  <div class="grid-x">
		    <div class="small-12 cell field">
	                <?php if ($_SESSION['add_firstname_error']) echo "<div class='error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                        else if ($_SESSION['add_firstname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>	
			<label class="weight-normal">First Name <input type="text"
	 			value="<?php echo $_SESSION['add_firstname']; ?>" name="firstname">
			</label>
            </div>
	      	<div class="small-12 cell field">
 	               <?php if ($_SESSION['add_lastname_error']) echo "<div class='error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                       else if ($_SESSION['add_lastname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct the last name</strong> - no special characters are allowed</div>"; ?>
        	       <label class="weight-normal">Surname <input type="text" 
	               	       value="<?php echo $_SESSION['add_lastname']; ?>" name="lastname">
	               </label> </div>
		<div class="small-12 cell field">
                       <?php if ($_SESSION['add_email_error']) echo "<div class='error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                       else if ($_SESSION['add_bad_email_error']) echo "<div class='error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>"; 
                       else if ($_SESSION['add_email_duplicate_error']) echo "<div class='error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
		       <label class="weight-normal">Email Address <input type="text" 
		               value="<?php echo $_SESSION['add_email']; ?>" name="email"></label> </div></div>
        <div class="small-12 cell field password-field">
                <label>Password
                <input type="password" id="password" name="password" placeholder="" required>
              </label>
            </div>
            <div class="small-12 cell field password-confirmation-field">
                  <label>Retype Password
                <input type="password" required data-equalto="password">
 <small class="form-error">The password did not match</small>
              </label>
            </div><div class="small-12 cell text-center">
        	  <br /><input type="submit" id="add" class="button large" value="Add User">
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
    	  <div class="grid-x">
		<div class="small-12 cell field">
					    <?php if ($_SESSION['add_firstname_error']) echo "<div class='error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";else if ($_SESSION['add_firstname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
						<label class="weight-normal">First Name <input type="text"
						value="<?php echo $_SESSION['add_firstname']; ?>" name="firstname">
						</label></div>
					<div class="small-12 cell field">
					    <?php if ($_SESSION['add_lastname_error']) echo "<div class='error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                                                   else if ($_SESSION['add_lastname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct the last name</strong> - no special characters are allowed</div>"; ?>
					<label class="weight-normal">Surname <input type="text"value="<?php echo $_SESSION['add_lastname']; ?>" name="lastname"></label>
					</div>
					<div class="small-12 cell field">
					    <?php if ($_SESSION['add_email_error']) echo "<div class='error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                                                   else if ($_SESSION['add_bad_email_error']) echo "<div class='error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>"; 
                                                   else if ($_SESSION['add_email_duplicate_error']) echo "<div class='error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
					<label class="weight-normal">Email Address <input type="text" value="<?php echo $_SESSION['add_email']; ?>" name="email">
					</label>
					</div>
        <div class="small-12 medium-12 large-12 cell field">&nbsp;</div>
            <div class="small-3 medium-3 large-3 cell field">&nbsp;</div>
            <div class="small-6 medium-6 large-6 cell field text-center">
               <button type="submit"  class="button large expanded" />UPDATE USER</button><br />
               <a href="users_a.php?m=userreset&id=<?php echo $id; ?>" class="button large inactive expanded" />RESET PASSWORD</a><br />
               <a href="users_a.php?m=userdelete&id=<?php echo $id; ?>" class="button large red expanded" />DELETE USER</a>
            </div>
            <div class="small-3 medium-3 large-3 cell field">&nbsp;</div>
    	  </div>
  		</div>
	  </form>
	</div>
<!-- END UPDATE SECTION -->
<!-- SINGLE DELETEUSER SECTION -->
    <div class="small-12 medium-6 large-6 cell content-right  <?php echo $userdelete_hide; ?>">
        <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
          <div class="grid-container">
              <div class="grid-x grid-padding-x">
                 <div class="small-12 medium-12 large-12 cell text-center">
                   <h2>Are you sure you want to delete this user?</h2>
                   <p>This will not affect any patient data, but the user will no longer be able to access the system.</p>
              </div>
              <div class="small-12 medium-12 large-12 cell text-center">
                  <div class="grid-x">
                      <div class="small-3">&nbsp;</div>
                           <div class="small-6"><br>
                                 <a href="users_a.php?m=main" class="button large inactive expanded" name=""/>NO</a>
                                 <a href="users_a.php?m=userdelete&id=<?php echo $id; ?>" class="button large expanded red" name=""/>CONFIRM DELETE</a>
                           </div>
                       <div class="small-3">&nbsp;</div>
                     </div>
                     <p>&nbsp;</p>
                 </div>
              </div>
           </div>
          <div class="grid-x text-center">
          </div>
        </div>
<!-- End SINGLE DELETEUSER SECTION -->
<!-- SINGLE PW USERRESET SECTION -->
    <div class="small-12 medium-6 large-6 cell content-right  <?php echo $userreset_hide; ?>">
        <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
          <div class="grid-container">
              <div class="grid-x grid-padding-x">
                 <div class="small-12 medium-12 large-12 cell text-center">
                   <h2>Are you sure you wish to reset the password?</h2>
                   <p>The user will be asked to enter a new password the next time they login to Verify.</p>
              </div>
              <div class="small-12 medium-12 large-12 cell text-center">
                  <div class="grid-x">
                      <div class="small-3">&nbsp;</div>
                           <div class="small-6"><br>
                                 <a href="users_a.php?m=main" class="button large inactive expanded" name=""/>NO</a>
                                 <a href="users_a.php?m=userreset&id=<?php echo $id; ?>" class="button large expanded active" name=""/>CONFIRM RESET</a>
                           </div>
                       <div class="small-3">&nbsp;</div>
                     </div>
                     <p>&nbsp;</p>
                 </div>
              </div>
           </div>
          <div class="grid-x text-center">
          </div>
        </div>
    </div>
  </div>
<!-- End SINGLE PW USERRESET SECTION -->
<!-- End Content-Right -->

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
         $(document).ready(function(){
             $('#actOnAll').click(function () {
                 $("[id^=performAction]").prop('checked', this.checked);
             });
         });
         $(document).ready(function(){
            $("#addsu").on("click",function(){
		$.get("clearsession.php");
                $("form div").removeClass("error_message");
                $("form label").removeClass("error_message");
                $("form div").removeClass("fi-alert");
                $("form label").removeClass("fi-alert");
            });
        });
      </script>  
   </body>
</html>
