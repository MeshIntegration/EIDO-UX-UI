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
session_start();

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
$delete_hide = "hide";
if ($mode == "" || $mode == "add" || $mode=="main") {
    $add_hide = "";
} else if ($mode == "update") {
    $update_hide = "";
    $user_id = $id;
} else if ($mode == "userreset") {
    $userreset_hide = "";
    $user_id = $id;
} else if ($mode == "userdelete") {
    $userdelete_hide = "";
    $user_id = $id;
} else if ($mode == "delete") {
    $elete_hide = "";
    // $user_id = $id;
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

// ***** filter/sorting
$_filter = [];
$_order = [];
if($filter = get_query_string('filter')) {
        $_filter['filter'] = 1;
}
if($time_added = get_query_string("time_added")) {
logMsg("TIME ADDED SET",$logfile);
        if ($time_added==1 && $_SESSION['filter']['time_added']==1)
           unset($_SESSION['filter']['time_added']);
        else if ($time_added==2 && $_SESSION['filter']['time_added']==2)
           unset($_SESSION['filter']['time_added']);
        else {
           $_SESSION['filter']['time_added'] = get_query_string('time_added');
           unset($_SESSION['filter']['name']);
        }
}

if($name = get_query_string("name")) {
logMsg("NAME SET",$logfile);
        if ($name==1 && $_SESSION['filter']['name']==1)
           unset($_SESSION['filter']['name']);
        else if ($name==2 && $_SESSION['filter']['name']==2)
           unset($_SESSION['filter']['name']);
        else {
           $_SESSION['filter']['name'] = get_query_string('name');
           unset($_SESSION['filter']['time_added']);
        }
}

if (isset($_SESSION['filter']['time_added'])) {
        switch($_SESSION['filter']['time_added']) {
                case "1": $_order[] = 'u.c_dateCreated DESC'; break;
                case "2": $_order[] = 'u.c_dateCreated ASC'; break;
        }
}
if (isset($_SESSION['filter']['name'])) {
        switch($_SESSION['filter']['name']) {
                case "1": $_order[] = 'u.lastName ASC'; break;
                case "2": $_order[] = 'u.lastName DESC'; break;
        }
}

//setting default
if(!$_order) {
	$_order[] = 'u.lastName ASC';
        $_SESSION['filter']['name'] = 1;
}
unset($time_added, $name);
/** END FILTER */

$sql = "SELECT u.*
        FROM ".DB_PREFIX."user u, ".DB_PREFIX."user_role ur
        WHERE u.id=ur.userId
        AND ur.roleId='ROLE_ADMIN' AND u.active=1 
        ORDER BY ".implode(',', $_order)." 
        LIMIT $start,$row";
logMsg(">>>>".$sql,$logfile);
$GetQuery = dbi_query($sql);
$i = 0;
$arr_users = array ();
while ( $qryResult = $GetQuery->fetch_assoc () ) {
    $arr_users [$i] = $qryResult;
    $i ++;
}

// no cache
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>
<html class="no-js" lang="en" dir="ltr">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EIDO Verify</title>
        <link rel="stylesheet" href="../css/foundation.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/dashboard.css">
        <link rel="stylesheet" href="../css/app.css">
        <link rel="stylesheet" href="../css/foundation-datepicker.min.css">
        <!--<link rel="stylesheet" href="../css/timeline.css">-->
        <link rel="stylesheet" href="../css/jquery-ui-1.12.1.min.css">
        <link rel="stylesheet" href="../css/eido.css">
        <link rel="stylesheet" href="/ui/verify/css/icons/eido-icons.css" type="text/css" />
        <link rel="icon" type="image/png" href="../favicon.png">
        <script src="../js/jquery-1.12.4.min.js"></script>
        <script src="../js/jquery-ui-1.12.1.min.js"></script>

        <!--<script src="../js/jquery.ui.touch-punch.min.js"></script>-->
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
  <?php include "../includes/su_header.php"; ?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser Dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li class="current"><a href="users_a.php?m=clearsession">Users</a></li>
		<li><a href="organisations_a.php?m=clearsession">Organisations</a></li>
		<li><a href="procedures_a.php?m=clearsession&g=add">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li class="current"><a href="users_a.php?m=clearsession">Users</a></li>
		<li><a href="organisations_a.php?m=clearsession">Organisations</a></li>
		<li><a href="procedures_a.php?m=clearsession&g=add">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>  
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
      <div class="small-12 medium-6 large-6 cell content-left" style="padding-top: 0;">
          <div class="grid-x row">
              <div class="small-12 medium-12 cell">

                  <div class="tabs tab-actions" data-accordion data-allow-all-closed="true" style="margin: 0;">
                      <div class="small-12 medium-12 cell accordion-item" data-accordion-item style="line-height: 1.46;">
                          <div class='grid-x row'>
                              <div class="small-6 medium-6 cell text-left padding-10">
                              </div>
                              <div class="small-6 medium-6 cell" style="padding-top:22px; padding-right: 20px; margin-bottom: -12px; visibility: hidden;">
                                  <?php if(isset($_SESSION['filter']['time_added']) || isset($_SESSION['filter']['name'])): ?>
                                      <span class="float-right">Filters Active | <a href="clear_filter.php?m=<?php echo $mode; ?>" class="float-right link-orange ">&nbsp; Reset</a></span>
                                  <?php else: ?>
                                      <span class="float-right">Filters Disabled</span>
                                  <?php endif; ?>
                              </div>
                          </div>
                          <a href="#" class="accordion-title sortonly">
                          </a><br />
                          <div class="accordion-content sort" data-tab-content style="border-bottom: 0px solid;">
                              <div class="grid-x rule" style="padding-left: 23px; padding-top: 15px; border-top: 1px solid #d3d1d1;">
                                  <div class="small-12 medium-4 cell">
                                      <label for="middle-label" class="middle">Time Added</label>
                                  </div>
                                  <div class="small-12 medium-8 cell">
                                      <a href="users.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? "selected" : "inactive"; ?>">Newest First</a>&nbsp;<a href="users.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">Oldest First</a>
                                  </div>
                              </div>
                              <div class="grid-x rule" style="padding-left: 23px; border-bottom: 0 solid; margin-bottom: 0px;">
                                  <div class="small-12 medium-4 cell">
                                      <label for="middle-label" class="middle">Name</label>
                                  </div>
                                  <div class="small-12 medium-8 cell">
                                      <a href="users.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="users.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="hide small-12 medium-12 cell hide accordion-item" data-accordion-item style="line-height: 1.46;">
                          <a href="#" class="hide accordion-title sort bulkactions eido icon">+
                          </a><br />
                          <div class="hide accordion-content sort" data-tab-content style="border-bottom: 0px solid;">
                              <div class="grid-x rule" style="padding-left: 23px; border-bottom: 0 solid; margin-bottom: 0px;">
                                  <div class="small-12 medium-4 cell">
                                      <label for="middle-label" class="middle">Name</label>
                                  </div>
                                  <div class="small-12 medium-8 cell">
                                      <a href="organisations.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="procedures.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
                        <!--  <div class="accordion-item" style="line-height: 1.45;">
                              <a href="#Sort" class="accordion-title sorttwo" role="tab" id="Sort" >
                              </a><br/>
                          </div>
                          <div class="accordion-item" style="line-height: 1.45;">
                          <a href="#BulkAction" class="accordion-title sortright" role="tab" id="BulkAction" >
                          </a><br/>
                          </div>
                      </div>
                      <div class="accordion-content" data-tabs-content="bulk_action_tabs" role="tabpanel" id="BulkActiondiv" style="padding-left: 0px; padding-right: 0px;">-->
                          <!--<div class="tabs-panel" id="panel1" style="padding: 0px;">-->
                         <!--     <div class="grid-x rule" style="padding-left: 23px; padding-top: 15px; border-top: 1px solid #d3d1d1;">
                                  <div class="small-12 medium-4 cell">
                                      <label for="middle-label" class="middle">Time Added</label>
                                  </div>
                                  <div class="small-12 medium-8 cell">
                                      <a href="users.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? "selected" : "inactive"; ?>">Newest First</a>&nbsp;<a href="users.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">Oldest First</a>
                                  </div>
                              </div>
                              <div class="grid-x rule" style="padding-left: 23px; border-bottom: 0 solid; margin-bottom: 0px;">
                                  <div class="small-12 medium-4 cell">
                                      <label for="middle-label" class="middle">Name</label>
                                  </div>
                                  <div class="small-12 medium-8 cell">
                                      <a href="users.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="users.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
                                  </div>
                              </div>
                          </div>
                      <div class="grid-x" id="Sortdiv" style="display:none;">
                          <div class="grid-x rule">
                              <div class="small-12 medium-12 cell">
                                  <form action="users.php?m=userreset" method="get" class="bulk-action" id="ResetAction">
                                      <!--							<-- <input type="hidden" name="users[]" value="" />-->
                                    <!--  <button class="button" type="submit">Force Password Reset</button>
                                  </form>
                              </div>
                          </div>
                          <div class="grid-x rule">
                              <div class="small-12 medium-12 cell">
                                  <form action="users.php?m=userdelete" class="bulk-action" id="DeleteAction">
                                      <button class="button" type="submit">Delete User</button>
                                  </form>
                              </div>
                          </div>
                      </div>

                  </div>
                  <div class="grid-x grid-header" style="border-bottom: 0px !important; display: none;">
                      <div class="small-2 columns column-first" style="">
                          <label class="eido-checkbox">
                              <input class="eido-checkbox" style="margin-left:25px;" type="checkbox" name="actOnAll" id="actOnAll">
                              <span class="checkmark" style="left:0px;"></span>
                          </label>
                      </div>
                  </div>
            </div>
          </div>-->
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
                              $isSelected = 'selected';
                          }
                          ?>
	                      <li class="<?php echo $isSelected; ?>">
		                      <a href="users_a.php?m=gotoupdate&id=<?php echo $uid; ?>">
			                      <span class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
			                      <div class="grid-x">
				                      <div class="small-2 columns column-first" style="display:none">
					                      <label class="eido-checkbox">
						                      <input type="checkbox" name="id[]" id="performAction<?php echo $i; ?>" value="<?php echo $uid; ?>">
						                      <span class="checkmark"></span>
					                      </label>
				                      </div>
				                      <div class="small-6 columns">
					                      <p style="margin-left:12px;">
						                      <strong><?php echo $full_name; ?></strong><br/>
						                      <?php echo $email; ?>
					                      </p>
				                      </div>
                                                      <div class="small-4 columns text-center">
                                                         &nbsp;
                                                      </div>
                                                      <!-- *****
                                                        <div class="small-2 columns text-center">
					                      <label class="indicator-checkbox eido-checkbox">
						                      <input type="checkbox" name="is_surgeon"<?php if ($is_surgeon) echo "checked"; ?>>
						                      <span class="checkmark"></span>
					                      </label>
				                      </div>

				                      <div class="small-2 columns text-center">
					                      <label class="indicator-checkbox eido-checkbox">
						                      <input type="checkbox" name="is_admin"<?php if ($is_admin) echo "checked"; ?>>
						                      <span class="checkmark"></span>
					                      </label>
				                        </div>
                                                      ***** -->
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
              $i = 0;
              $GetQuery = dbi_query ( $sql );
              $totalRecord = $GetQuery->num_rows;
              $pagination = get_pagination ( $page, $totalRecord );
              ?>
              <div class="grid grid-x text-center row">
                  <div class="small-12 pagination-btm-users pagination-btm"><?php echo $pagination; ?></div>
              </div>

      </div>
	<!-- End Content-Left -->  
 	<!-- Start Content-Right -->  
 	<!-- ADD USER SECTION -->  
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
        <div class="grid-container">
            <div class="grid-x">
                <div class="small-12 cell field">
                    <h3 class="padding-bottom-1">Add Superuser</h3>
                </div>
            </div>
        </div>
	  <form id="add_form"  action="users_a.php?m=add" method="post">
  		<div class="grid-container">
    	  <div class="grid-x">
		    <div class="small-12 cell field">
	                <?php if ($_SESSION['add_firstname_error']) echo "<div class='error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                        else if ($_SESSION['add_firstname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>	
			<label class="weight-normal">First Name 
                            <input type="text" value="<?php echo $_SESSION['add_firstname']; ?>" name="firstname">
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
                       <?php if ($_SESSION['add_password_error']) echo "<div class='error_message fi-alert'><strong>Please enter a password</strong> - this is required</div>"; ?>
            <label class="weight-normal">Password
                <input type="password" id="password" name="password" placeholder="" >
              </label>
            </div>
            <div class="small-12 cell field password-confirmation-field">
                <?php if ($_SESSION['add_password_match_error']) echo "<div class='error_message fi-alert'><strong>The passwords you entered do not match</strong></div>"; ?>
                <label class="weight-normal">Retype Password
                   <input type="password" name="password2">
                   <!-- <small class="form-error">The password did not match</small> -->
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
                        FROM dir_user 
                        WHERE id='$id'";
               $GetQuery_u = dbi_query($sql_u);
               $qryResult_u = $GetQuery_u->fetch_assoc();
               $_SESSION['add_firstname'] = $qryResult_u['firstName'];
               $_SESSION['add_lastname'] = $qryResult_u['lastName'];
               $_SESSION['add_email'] = $qryResult_u['email'];
            }
            else
            {
               $firstName = "";
               $lastName = "";
               $email = "";
            }
        ?>
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
        <div class="grid-container">
            <div class="grid-x">
        <div class="small-12 cell field">
	  <h3>View User</h3>
            </div>
            </div>
        </div>
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
               <button type="submit"  class="button large expanded" />Update User</button><br />
               <a href="users.php?m=userreset&id=<?php echo $id; ?>" class="button large inactive expanded" />Reset Password</a><br />
               <a href="users.php?m=userdelete&id=<?php echo $id; ?>" class="button large red expanded" />Delete User</a>
            </div>
            <div class="small-3 medium-3 large-3 cell field">&nbsp;</div>
    	  </div>
  		</div>
	  </form>
	</div>
<!-- END UPDATE SECTION -->
<!-- SINGLE DELETEUSER SECTION -->
	  <?php $id = ['id'=>$id]; ?>
    <div class="small-12 medium-6 large-6 cell content-right  <?php echo $userdelete_hide; ?>">
	    <div class="back clickable-row btn-back" data-href="patients.php?m=main">
		    <a href="users.php">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
		    </a>
	    </div>
          <div class="grid-container">
              <div class="grid-x grid-padding-x">
                 <div class="small-12 medium-12 large-12 cell text-center">
                   <h2>Are you sure you want to delete <?php echo count($id['id']) > 1 ? 'these users?' : 'this user?'; ?></h2>
                   <p>This will not affect any patient data, but the user will no longer be able to access the system.</p>
              </div>
              <div class="small-12 medium-12 large-12 cell text-center">
                  <div class="grid-x">
                      <div class="small-3">&nbsp;</div>
                           <div class="small-6"><br>
                                 <a href="users.php?m=main" class="button large inactive expanded" name=""/>No</a>
                                 <a href="users_a.php?m=userdelete&<?php echo http_build_query($id); ?>" class="button large expanded red" name=""/>Confirm Delete</a>
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
<!-- BULK RESET PW SECTION -->
      <div class="small-12 medium-6 large-6 cell content-right reveal"
           id="pwdResetModal" data-reveal>
          <!-- Password Reset Confirmation Reveal Modal -->
          <div class="reveal" id="pwdResetModa1" data-reveal>
              <h1>Are you sure you wish to reset the passwords?</h1>
              <p class="lead">The users slected to the left will be asked to
                  enter a new password the next time they login to Verify.</p>
              <button class="close-button" data-close aria-label="No"
                      type="button">
                  <span aria-hidden="true">&times;</span>
              </button>
              <button name="pwresetbulk" id="pwresetbulk" class="pwreset-button"
                      onclick="bulkaction(this)" data-close aria-label="Confirm Reset"
                      type="button">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      </div>
<!-- End BULK RESET PW SECTION -->
<!-- SINGLE PW USERRESET SECTION -->
	  <?php // $id = ['id'=>$id]; ?>
    <div class="small-12 medium-6 large-6 cell content-right  <?php echo $userreset_hide; ?>">
	    <div class="back clickable-row btn-back" data-href="users.php">
		    <a href="users.php">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
		    </a>
	    </div>
	    <div class="grid-container">
              <div class="grid-x grid-padding-x">
                 <div class="small-12 medium-12 large-12 cell text-center">
                   <h2>Are you sure you wish to reset the password(s)?</h2>
                   <p>The user(s) will be asked to enter a new password the next time they login to Verify.</p>
              </div>
              <div class="small-12 medium-12 large-12 cell text-center">
                  <div class="grid-x">
                      <div class="small-3">&nbsp;</div>
                           <div class="small-6"><br>
                                 <a href="users.php?m=main" class="button large inactive expanded" name=""/>No</a>
                                 <a href="users_a.php?m=userreset&id=<?php echo $user_id; ?>" class="button large expanded active" name=""/>Confirm Reset</a>
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
<!-- DELETE USER SECTION -->
<?php // $user_id = ['id'=>$user_id]; ?>
<div class="small-12 medium-6 large-6 cell content-right <?php echo $delete_hide; ?>">
    <h3>Are you sure you wish to delete <?php echo count($user_id['id']) > 1 ? 'these users?' : 'this user?'; ?>
        <br>
        <span class="small sub-text">This will not affect any patient data, but the user(s) will no
		longer be able to access the system.</span>
    </h3>
    <!--<h3 class="text-center">Are you sure you wish to delete <?php echo count($user_id['id']) > 1 ? 'these users?' : 'this user?'; ?></h3>
	<p class="standard-padding text-center padding-bottom-1">This will not affect any patient data, but the user will no
		longer be able to access the system.</p>-->
    <form name="deleteuserfrm">
        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell text-center">
                    <div class="grid-x">
                        <div class="small-3">&nbsp;</div>
                        <div class="small-6">
                            <br> <a href="users.php?m=main"><input type="button" name=""
                                                                   value="No" class="button large expanded inactive" /></a>
                            <a href="users_a.php?m=delete&id=<?php echo $user_id; ?>"> <input type="button" name="" value="Confirm Delete" class="button large red expanded" /></a>
                        </div>
                        <div class="small-3">&nbsp;</div>
                    </div>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End DELETE USER SECTION -->
<!-- End Content-Right -->

  <!-- End Content --> 
  <!-- Start Footer -->
     <?php include "../includes/footer.php"; ?>
  <!-- End Footer -->
      <script src="../js/vendor/what-input.js"></script>
      <script src="../js/vendor/jquery.js"></script>
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
