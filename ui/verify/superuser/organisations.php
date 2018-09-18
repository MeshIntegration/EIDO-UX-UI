<!doctype html>
<?php
// ***************************************
// superuser/organisations.php
// 2017 Copyright, Mesh Integration LLC
// 1/24/18 - WEL
// 03/13/18 - SD - add paggination value into session
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
require_once "./superuser_functions.php";

if ($user_role<>"SUPERUSER")
{
   header("Location: /ui/verify/login.php");
   exit();
}
session_start();
$return_to = "suo";
$home = "organisations.php";
$logfile = "organisation.log";

$mode = get_query_string('m');
$id = get_query_string('id');
// logMsg("Organisations: mode: $mode OrgID: $id","wel.log");

// turn everything off
$add_hide = "hide";
$update_hide = "hide";
$orgproc_hide = "hide";
$procadd_hide = "hide";
$overview_hide = "hide";
$listdivs_hide = "hide";
$editdiv_hide = "hide";
$adddiv_hide = "hide";
$addcust_hide = "hide";
$editcust_hide = "hide";
$deletecust_hide = "hide";
$deletediv_hide = "hide";
$addowner_hide = "hide";
$editowner_hide = "hide";
$deleteowner_hide = "hide";
$addadmin_hide = "hide";
$editadmin_hide = "hide";
$deleteadmin_hide = "hide";

//  set organisation subdivision and customer options to default show
$orgHasDivs_hide = "show";
$manageCustDivs_hide = "show";

if ($mode=="" || $mode=="add") {
   $add_hide = "";
}
else if ($mode=="update") {
   $update_hide = "";
   $org_id=$id;
}
else if ($mode=="orgproc") {
   $orgproc_hide = "";
   $org_id = $id;
   unset($_SESSION['proc_searchterm']);
}
else if ($mode=="overview") {
   $overview_hide = "";
   $org_id = $id;
}
else if ($mode=="procadd") {
   $procadd_hide = "";
   $org_id = $id;
   if (isset($_POST['proc_searchterm']) && $_POST['proc_searchterm']<>"")
   {  logMsg("Searchterm = ".$_POST['proc_searchterm'], "wel.log");
      $_SESSION['proc_searchterm']=$_POST['proc_searchterm'];
   }
}
else if ($mode=="listdivs") {
   $listdivs_hide = "";
   $org_id = $id;
}
else if ($mode=="adddiv") {
   $adddiv_hide = "";
   $org_id = $id;
}
else if ($mode=="addcust") {
   $addcust_hide = "";
   $org_id = $id;
}
else if ($mode=="editcust") {
   $editcust_hide = "";
}
else if ($mode=="deletecust") {
   $deletecust_hide = "";
}
else if ($mode=="editdiv") {
   $editdiv_hide = "";
   //$org_id = $id;
   $div_id = $id;
}
else if ($mode=="deletediv") {
   $deletediv_hide = "";
}
else if ($mode=="addowner") {
   $addowner_hide = "";
   $org_id = $id;
}
else if ($mode=="editowner") {
   $editowner_hide = "";
   $org_id = $id;
}
else if ($mode=="addadmin") {
   $addadmin_hide = "";
   $org_id = $id;
}
else if ($mode=="editadmin") {
   $editadmin_hide = "";
   $org_id = $id;
}
else if ($mode=="deleteowner") {
   $deleteowner_hide = "";
   $org_id = $id;
}
else if ($mode=="deleteadmin") {
   $deleteadmin_hide = "";
   $org_id = $id;
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
                case "1": $_order[] = 'dateCreated DESC'; break;
                case "2": $_order[] = 'dateCreated ASC'; break;
        }
}
if (isset($_SESSION['filter']['name'])) {
        switch($_SESSION['filter']['name']) {
                case "1": $_order[] = 'c_name ASC'; break;
                case "2": $_order[] = 'c_name DESC'; break;
        }
}
//setting default
if(!$_order) {
        $_order[] = 'c_name ASC';
        $_SESSION['filter']['name'] = 1;
}
unset($time_added, $name);

// ***** END FILTERS

$sql = "SELECT * 
        FROM $TBLORGANISATIONS 
        WHERE c_level='ORG'
        AND c_status='ACTIVE'
        ORDER BY ".implode(',', $_order)."
        LIMIT $start,$row";
$GetQuery = dbi_query($sql);
logMsg("Organisations: $sql",$logfile);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EIDO Verify</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <style>
     .clickable-row {cursor: pointer;}  
  </style>
	<link rel="stylesheet" href="../css/eido.css">

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
		<li><a href="users_a.php?m=clearsession">Users</a></li>
		<li class="current"><a href="organisations_a.php?m=clearsession&g=gotoaddorg">Organisations</a></li>
		<li><a href="procedures_a.php?m=clearsession&g=add">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="users_a.php?m=clearsession">Users</a></li>
		<li class="current"><a href="organisations_a.php?m=clearsession&g=gotoaddorg">Organisations</a></li>
		<li><a href="procedures_a.php?m=clearsession&g=add">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su" data-equalizer data-equalize-on="medium">
     <!-- Start Content-Left -->
     <div class="small-12 medium-6 large-6 cell content-left" style="padding-top: 0 !important;">
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
                         <!-- START SORT PANEL -->
                         <div class="accordion-content sort" data-tab-content style="border-bottom: 0px solid;">
                             <div class="grid-x rule" style="padding-left: 23px; padding-top: 15px; border-top: 1px solid #d3d1d1;">
                                 <div class="small-12 medium-4 cell">
                                     <label for="middle-label" class="middle">Time Added</label>
                                 </div>
                                 <div class="small-12 medium-8 cell">
                                     <a href="organisations.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? "selected" : "inactive"; ?>">Newest First</a>&nbsp;<a href="organisations.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">Oldest First</a>
                                 </div>
                             </div>
                             <div class="grid-x rule" style="padding-left: 23px; border-bottom: 0 solid; margin-bottom: 0px;">
                                 <div class="small-12 medium-4 cell">
                                     <label for="middle-label" class="middle">Name</label>
                                 </div>
                                 <div class="small-12 medium-8 cell">
                                     <a href="organisations.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="organisations.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
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
	     <div class="row">
		     <ul class="patient-list">
			     <?php while ($qryResult=$GetQuery->fetch_assoc()) {
			     $list_id=$qryResult['id'];
			     $c_name=$qryResult['c_name'];
			     $c_type=$qryResult['c_type'];

			     $isSelected = '';
			     if ($list_id == $id) {
				     $isSelected = 'selected';
			     }
			     ?>
			     <li class="<?php echo $isSelected; ?>">
				     <a href="organisations.php?m=overview&id=<?php echo $list_id; ?>">
					     <span class="float-right right-arrow"><i class="eido-icon-chevron-right"></i></span>
					     <div class="grid-x">
						     <div class="small-2 columns column-first" style="display: none;">
							     <label class="eido-checkbox">
								     <input type="checkbox" name="performAction[]" id="performAction<?php echo $i; ?>" value="<?php echo $uid; ?>">
								     <span class="checkmark"></span>
							     </label>
						     </div>
						     <div class="small-11 columns">
							     <p style="margin-left: 12px;">
								     <strong><?php echo $c_name; ?></strong><br/>
								     <?php echo $c_type; ?>
							     </p>
						     </div>
					     </div>
				     </a>
			     </li>
			     <?php } ?>
		     </ul>
	     </div>
               <?php
               // pagination 
                  $sql = "SELECT * FROM $TBLORGANISATIONS
                          WHERE c_level='ORG'
                          AND c_status='ACTIVE'";
                  $GetQuery = dbi_query($sql);
                  $totalRecord = $GetQuery->num_rows;
                  $pagination = get_pagination($page, $totalRecord);
               ?>
     <div class="grid grid-x text-center row">
        <div class="small-12 pagination-btm"><?php echo $pagination; ?></div>
     </div>
   </div>
<!-- End Content-Left -->  
<!-- Start Content-Right -->  
<!-- ADD SECTION -->
<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	<h3 class="" style="padding-bottom: 11px;">Add Organisation</h3>
	<div class="grid-container">
        <div class="grid-x">
            <div class="small-12 cell field">
            </div>
        </div>
    </div>
          <form action="organisations_a.php?m=add" method="post" enctype="multipart/form-data">
    <div class="grid-container">
       <div class="grid-x">
            <div class="small-12 cell field">
                	   <?php if ($_SESSION['add_orgname_error']) echo "<div class='error_message fi-alert'><strong>Please enter the oranisation name</strong> - this is required</div>";
                                 else if ($_SESSION['add_orgname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct organization name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Name
                <input type="text" name="name" value="<?php echo $_SESSION['name']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_type_error']) echo "<div class='error_message fi-alert'><strong>Please select a type</strong> - this is required</div>"; ?>
                <label class="weight-normal">Type
                <select name="type" id="orgTypeAdd">
                  <option value=""></option>
                  <option value="Government Hospital" <?php if ($_SESSION['type']=="Government Hospital") echo 'selected'; ?> >Government Hospital</option>
                  <option value="Private Hospital" <?php if ($_SESSION['type']=="Private Hospital") echo 'selected'; ?>>Private Hospital</option>
                  <option value="Medical Malpractice Insurer" <?php if ($_SESSION['type']=="Medical Malpractice Insurer") echo 'selected'; ?>>Medical Malpractice Insurer</option>
                </select>
              </label>
              <div class="small-6 medium-6 large-6 cell">
                 <?php if ($_SESSION['add_logo_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please upload your file again</strong> - there was an error during upload</div>";
                  if ($_SESSION['add_logo_type_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please upload an image file</strong> - the file must be a JPG PNG or GIF</div>"; ?>
                  <label class="weight-normal">Organisation Header Logo
                  <img src="/ui/verify/img/org_logos/blank.jpg">&nbsp;&nbsp;<input type="file" name="header_logo" placeholder="">
                </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
	              <div class="row grid-x grid-padding-15 <?php echo $orgHasDivs_hide; ?>" id="orgHasDivsAdd">
		              <div class="small-5" style="padding-right:20px;">
			              <label class="eido-radio">
				              <input type="radio" name="subdivision"  value="Yes" id="subdivisionRed" <?php if ($_SESSION['subdivision']=="Yes") echo 'checked'; ?>/>
				              <span class="checkmark"></span>
				              <span class="text">Yes</span>
			              </label>
			              <label class="eido-radio">
				              <input type="radio" name="subdivision" value="No" id="subdivisionBlue" checked <?php if ($_SESSION['subdivision']=="No") echo 'checked'; ?>/>
				              <span class="checkmark"></span>
				              <span class="text">No</span>
			              </label>
		              </div>
		              <div class="small-7">
			              <label class="weight-normal">Organisation has divisions?</label>
		              </div>
	              </div>
              </div>
            </div>
            <div class="small-12 cell field">
               <hr>
               <strong>Account Owner Information</strong>
            </div>
            <div class="small-12 cell field">
	         <?php if ($_SESSION['add_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter the first name</strong> - this is required</div>";
                 else if ($_SESSION['add_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="fname" value="<?php echo $_SESSION['fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
	         <?php if ($_SESSION['add_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                 else if ($_SESSION['add_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="lname" value="<?php echo $_SESSION['lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_bad_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                 else if ($_SESSION['add_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="email" value="<?php echo $_SESSION['email']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <hr>
               <strong>Primary Admin User</strong>
            </div>
            <div class="small-12 cell field">
	         <?php if ($_SESSION['add_admin_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter the first name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="admin_fname" value="<?php echo $_SESSION['admin_fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
	         <?php if ($_SESSION['add_admin_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="admin_lname" value="<?php echo $_SESSION['admin_lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_bad_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                 else if ($_SESSION['add_admin_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="admin_email" value="<?php echo $_SESSION['admin_email']; ?>">
              </label>
            </div>
            <div class="small-12 cell field text-center">
                  <br /><input type="submit" id="add" class="button large" value="Add Organisation">
            </div>
          </div>
        </div>
  </form>
</div>
        <!-- END ADD SECTION -->
        <!-- OVERVIEW SECTION -->
        <?php
           if ($mode=="overview") {
              $sql_o = "SELECT *
                        FROM $TBLORGANISATIONS
                        WHERE id='$org_id'";
               $GetQuery_o = dbi_query($sql_o);
               $qryResult_o = $GetQuery_o->fetch_assoc();
               $org_id = $qryResult_o['id'];
               $org_name = $qryResult_o['c_name'];
               $org_type = $qryResult_o['c_type'];
               $org_admin = $qryResult_o['c_admin'];
               $org_email = $qryResult_o['c_email'];
               $org_logo = $qryResult_o['c_logo'];
               $org_subdivision = $qryResult_o['c_subdivision'];
               $org_firstuser_id = $qryResult_o['c_firstUser'];
               $sql_fu = "SELECT firstName, lastName, email FROM dir_user WHERE id='$org_firstuser_id'";
               $GetQuery_fu = dbi_query($sql_fu);
               $qryResult_fu = $GetQuery_fu->fetch_assoc();
               $org_firstuser_fname = $qryResult_fu['firstName'];
               $org_firstuser_lname = $qryResult_fu['lastName'];
               $org_firstuser_email = $qryResult_fu['email'];
               logMsg("update: resetting Procedure Arrays", $logfile);
               unset($_SESSION['arr_all_procs']);
               unset($_SESSION['arr_add_procs']);

                $orgHasDivs_hide = "show";
                $manageCustDivs_hide = "show";
                if ($org_subdivision=="No") {
                    $orgHasDivs_hide = "hide";
                    $manageCustDivs_hide = "hide";
                }
                if ($org_type=="Medical Malpractice Insurer") {
                    $orgHasDivs_hide = "hide";
                    $manageCustDivs_hide = "show";
                }
            }
         ?>

   <div class="small-12 medium-6 large-6 cell content-right <?php echo $overview_hide; ?>">
     <h3 class="h3-fix">Organisation Overview<span class="small sub-text">View and edit an Organisation</span></h3>
         <div class="grid-container content-container row">
             <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 padding-right-column">
	            <h5 class="ps_green" style="margin:20px 0px;"><?php echo $org_name; ?></h5>
	             <div class="grid-x grid-padding-x row grid-padding-15" style="padding-top:0;">
	                  <div class="small-12 medium-12 large-12 cell">
		                  <label>Type:</label>
		                  <h5><?php echo $org_type; ?></h5>
	                  </div>
	              </div>
	              <hr class="standard-hr" />
	              <!-- spacer -->
	              <div class="grid-x grid-padding-x row grid-padding-15" style="">
		            <div class="small-12 cell">
		                  <label>Header Logo:</label>
		                  <?php if($org_logo != ""): ?>
			                  <div class="row grid-x no-border-bottom">
				                  <div class="medium-9" style="">
					                  <div class="org-logo position-relative">
						                  <img src="<?php echo "/ui/verify/img/org_logos/".$org_logo; ?>" />
					                  </div>
				                  </div>
				                  <div class="medium-3">
					                  <a class="button inactive position-relative btn-block" href="organisations_a.php?m=removelogo&id=<?php echo $org_id; ?>" style="top:30%;">Remove</a>
				                  </div>
			                  </div>
			                  <?php else: ?>
				                  <h5>None</h5>
			                  <?php endif; ?>
		                  </div>

	                  </div>
                          <div class="grid grid-padding-x grid-padding-15 no-border-top border-bottom-grid-padding" style="">
                                  <span style="text-align: right" class="display-block"><a href="organisations_a.php?m=clearsession&g=update&id=<?php echo $org_id; ?>"><button class="button">Edit Organisation</button></a></span>
                          </div>
	                  <hr class="standard-hr no-border-top-bottom" />
	                  <div class="grid grid-padding-x grid-padding-15 padding-bottom-0 no-border-top-bottom" style="padding-top:0;">
		                  <ul class="patient-list" style="margin:0;">
			                  <li style="border-top: 0px;">
				                  <a href="organisations.php?m=orgproc&id=<?php echo $org_id; ?>" class="no-border-top no-u">
					                  <span class="float-right right-arrow" style="padding-bottom: 20px"><i class="eido-icon-chevron-right" style="top: 15px"></i></span>
					                  <p class="">Manage Procedures</p>
				                  </a>
			                  </li>
			                  <li>
                                  <div class=" <?php echo $manageCustDivs_hide; ?>">
				             <?php if(strpos(strtoupper($org_type),"HOSPITAL")): ?>
					          <a href="organisations.php?m=listdivs&id=<?php echo $org_id; ?>" class="no-border-top border-bottom-grid-padding no-u">
					             <span class="float-right right-arrow" style="padding-bottom: 20px"><i class="eido-icon-chevron-right" style="top: 15px"></i></span>
						            <p class="">Manage Divisions</p>
					            </a>
				               <?php else: ?>
					             <a href="organisations.php?m=listdivs&id=<?php echo $org_id; ?>" class="no-border-top border-bottom-grid-padding no-u">
					                  <span class="float-right right-arrow" style="padding-bottom: 20px"><i class="eido-icon-chevron-right" style="top: 15px"></i></span>
						                <p class="">Manage Customers</p>
					              </a>
				                <?php endif; ?>
			                  </li>
		                  </ul>
		                  <!--<hr class="standard-hr"/>-->
	                  </div>
	                  <div class="grid grid-padding-x grid-padding-15 padding-top-3 no-border-top" style="">
                              <div class="small-12 medium-12 large-12 cell">
                                  <label>Account Owner:</label>
                                  <h5><?php echo $org_admin ? $org_admin : 'None'; ?></h5>
                              </div>
                              <div class="small-12 medium-12 large-12 cell">
                                  <label>Email Address:</label>
                                  <h5><?php echo $org_email; ?></h5>
                              </div>
                              <div class="small-12 medium-12 large-12 cell">
                                <?php if ($org_admin<>"") { ?>
                                   <span style="text-align: right" class="display-block">
                                          <a href="organisations_a.php?m=clearsession&g=deleteowner&id=<?php echo $org_id; ?>">
                                               <button class="button red small">Delete</button>
                                           </a>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <a href="organisations_a.php?m=clearsession&g=editowner&id=<?php echo $org_id; ?>">
                                               <button class="button active small">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                                           </a>
                                    </span>
                                 <?php } else { ?>
                                   <span style="text-align: center" class="display-block">
                                          <a href="organisations_a.php?m=clearsession&g=addowner&id=<?php echo $org_id; ?>">
                                               <button class="button  active small">Add Account Owner</button>
                                           </a>
                                    </span>
                                 <?php } ?>
                              </div>
		              <hr class="standard-hr"/>
                           </div>
                          <div class="grid grid-padding-x grid-padding-15" style="">
                              <div class="small-12 medium-12 large-12 cell">
                                  <label>Primary Admin User:</label>
                                  <h5><?php $firstuser_name = $org_firstuser_fname." ".$org_firstuser_lname; if ($org_firstuser_lname=="") echo 'None'; else echo $firstuser_name; ?></h5>
                              </div>
                              <div class="small-12 medium-12 large-12 cell">
                                  <label>Email Address:</label>
                                  <h5><?php echo $org_firstuser_email ? $org_firstuser_email : 'None'; ?></h5>
                              </div>
                              <div class="small-12 medium-12 large-12 cell">
                                <?php if ($org_firstuser_lname<>"") { ?>
                                   <span style="text-align: right" class="display-block">
                                          <a href="organisations_a.php?m=clearsession&g=deleteadmin&id=<?php echo $org_id; ?>">
                                               <button class="button red small">Delete</button>
                                           </a>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <a href="organisations_a.php?m=clearsession&g=editadmin&id=<?php echo $org_id; ?>">
                                               <button class="button active small">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                                           </a>
                                    </span>
                                 <?php } else { ?>
                                   <span style="text-align: center" class="display-block">
                                          <a href="organisations_a.php?m=clearsession&g=addadmin&id=<?php echo $org_id; ?>">
                                               <button class="button  active small">Add Primary User</button>
                                           </a>
                                    </span>
                                 <?php } ?>
                              </div>
                              <hr class="standard-hr"/>
                           </div>

                  </div>
               </div>
            </div>
         </div>
        <!-- END OVERVIEW SECTION -->
        <!-- LISTDIVS SECTION -->
        <?php
           if ($mode=="listdivs")
           {
              $sql_ld="SELECT c_type FROM $TBLORGANISATIONS
                       WHERE id='$org_id'";
              $GetQuery_ld = dbi_query($sql_ld);
              $qryResult_ld=$GetQuery_ld->fetch_assoc();
              $div_type = $qryResult_ld['c_type'];
              if (strpos(strtoupper($div_type),"HOSPITAL"))
                 $div_type = "Division";
              else
                 $div_type = "Customer";
              if ($div_type=="Division") {
                 $sql_ld = "SELECT o.id, o.c_name, u.email, u.firstName, u.lastName  
                           FROM $TBLORGANISATIONS o, dir_user u 
                           WHERE o.c_parentId='$org_id'
                           AND o.c_firstUser=u.id
                           AND o.c_status='ACTIVE'";
                 logMsg($sql_ld,$logfile);
                 $GetQuery_ld = dbi_query($sql_ld);
                 $d=0;
                 while ($qryResult_ld=$GetQuery_ld->fetch_assoc())
                 {
                    $arr_ld[$d]=$qryResult_ld;
                    $d++;
                 }
              }
              else {
                 $sql_ld = "SELECT s.*, u.email, u.firstName, u.lastName
                           FROM $TBLSURGEONS s, dir_user u
                           WHERE u.c_organizationId='$org_id'
                           AND s.c_userId=u.id";
                 logMsg($sql_ld,$logfile);
                 $GetQuery_ld = dbi_query($sql_ld);
                 $d=0;
                 while ($qryResult_ld=$GetQuery_ld->fetch_assoc())
                 {
                    $arr_ld[$d]['id']=$qryResult_ld['c_userId'];  // user ID
                    $arr_ld[$d]['c_gmcNumber']=$qryResult_ld['c_gmcNumber'];
                    $arr_ld[$d]['email']=$qryResult_ld['email'];
                    $arr_ld[$d]['firstName']=$qryResult_ld['firstName'];
                    $arr_ld[$d]['lastName']=$qryResult_ld['lastName'];
                    $d++;
                 }
              }
            }
       ?>
   <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $listdivs_hide; ?>">
            <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
                    <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
                    </a>
            </div>
       <h3>Manage <?php echo $div_type; ?>s<br />
          <span class="small sub-text">Add <?php echo $div_type; ?>s to your organisation</span></h3>
          <div class="grid-container">
               <div class="grid-x grid-padding-x">
                   <div class="small-12 medium-12 large-12 cell standard-padding">
                        <table width="100%" border="0"  class=" su-table stack ">
                           <tbody>
                               <?php if ($mode=="listdivs") {
                                        for ($d=0; $d<count($arr_ld); $d++)
                                        { $div_id = $arr_ld[$d]['id'];
                                          $div_name = $arr_ld[$d]['c_name'];
                                          $div_description = $arr_ld[$d]['description'];
                                          $div_email = $arr_ld[$d]['email'];
                                          $div_firstName = $arr_ld[$d]['firstName'];
                                          $div_lastName = $arr_ld[$d]['lastName'];
                                          if ($div_type=="Division") {
                                             $div_str = "$div_firstName $div_lastName - $div_email";
                                             $div_goto = "clearsession&g=editdiv&id=$div_id";
                                          } else {
                                             $div_str = "$div_email";
                                             $div_name =  "$div_firstName $div_lastName";
                                             $div_goto = "clearsession&g=editcust&id=$div_id";
                                          }
                               ?>
                              <tr class="timeline-table" style="border-top: 0px !important;">
                                 <td class='clickable-row su_data' style="padding-left: 20px; padding-right: 20px;" data-href='organisations_a.php?m=<?php echo $div_goto; ?>'><p><a href="organisations_a.php?m=<?php echo $div_goto; ?>"><span class="uc"><?php echo $div_name; ?></span><br />
                                    <?php echo $div_str; ?></a></p>
                                 </td>
                                 <td style=" padding-right: 20px; padding-top: 15px;">
                                     <a href="organisations_a.php?m=<?php echo $div_goto; ?>"><span style="padding-top: 10px:" class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span></a>
                                 </td>
                              </tr>
                            <?php       }
                                  } ?>
                           </tbody>
                        </table>
                  </div>
                  <div class="small-12 medium-12 large-12 cell text-center">
                     <?php if ($div_type=="Division")
                              $btn_div_mode="clearsession&g=adddiv";
                           else
                              $btn_div_mode="clearsession&g=addcust";
                     ?>
                         <a href="organisations_a.php?m=<?php echo $btn_div_mode; ?>&id=<?php echo $org_id; ?>"><button class="large button">Add New <?php echo $div_type; ?></button></a>
                  </div>
               </div>
            </div>
         </div>
  <!-- END LISTDIVS SECTION -->
  <!-- ADDDIV SECTION -->
       <?php if ($mode=="adddiv" || $mode=="addcust")
             {
                $sql_div = "SELECT * FROM $TBLORGANISATIONS WHERE id='$org_id'";
                logMsg($sql_div,$logfile);
                $GetQuery_div = dbi_query($sql_div);
                $qryResult_div=$GetQuery_div->fetch_assoc();
                $org_name = $qryResult_div['c_name'];
                $org_type = $qryResult_div['c_type'];
             }
       ?>
 <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $adddiv_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3>Manage Divisions
      <span class="small sub-text">Add Divisions to your organisation - <?php echo $org_name; ?></span></h3>
      <form action="organisations_a.php?m=adddiv&id=<?php echo $org_id; ?>" method="post" >
         <input type="hidden" name="type" value="<?php echo $org_type; ?>">
         <input type="hidden" name="subdivision" value="No">
         <input type="hidden" name="fname" value="fname">
         <input type="hidden" name="lname" value="lname">
         <input type="hidden" name="email" value="email@example.com">
    <div class="grid-container">
       <div class="grid-x">
            <div class="small-12 cell field">
                  <?php if ($_SESSION['add_orgname_error']) echo "<div class='error_message fi-alert'><strong>Please enter the oranisation name</strong> - this is required</div>";
                        else if ($_SESSION['add_orgname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct organisation name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Name
                <input type="text" name="name" placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
               <hr>
               <strong>Primary Admin User</strong>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter the first name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="admin_fname"  placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="admin_lname"  placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_bad_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                 else if ($_SESSION['add_admin_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="admin_email" placeholder="">
              </label>
            </div>
            <div class="small-12 cell field text-center">
                 <br /><input type="submit" id="adddiv" class="button large" value="Add Division">
            </div>
          </div>
         </div>
        </form>
      </div>
  <!-- END ADDDIV SECTION -->
  <!-- ADDCUST SECTION -->
       <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $addcust_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
          <h3>Manage Customers
          <span class="small sub-text">Add customers to your organisation</span></h3>
          <form action="organisations_a.php?m=addcust&id=<?php echo $org_id; ?>" method="post" >
          <div class="grid-container">
             <div class="grid-x">
                  <div class="small-12 cell field">
                      <label>First Name
                      <input type="text" name="fname"  placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>Surname
                      <input type="text" name="lname"  placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>E-mail Address
                      <input type="text" name="email" placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>GMC Number
                      <input type="text" name="gmcnumber" placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field text-center">
                      <br /><input type="submit" id="addcust" class="button large" value="Add Customer">
                  </div>
                </div>
             </div>
        </form>
      </div>
  <!-- END ADDCUST SECTION -->
  <!-- EDITCUST SECTION -->
       <?php if ($mode=="editcust") {
                $sql_ec="SELECT * FROM dir_user WHERE id='$id'";
                $GetQuery_ec=dbi_query($sql_ec);
                $qryResult_ec=$GetQuery_ec->fetch_assoc();
                $_SESSION['firstName']=$qryResult_ec['firstName'];
                $_SESSION['lastName']=$qryResult_ec['lastName'];
                $_SESSION['email']=$qryResult_ec['email'];
                $_SESSION['gmc_number']=$qryResult_ec['gmc_number'];
                $c_organization_id=$qryResult_ec['c_organizationId'];
             }
       ?>
       <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $editcust_hide; ?>">

      <div class="back clickable-row btn-back" data-href="organisations.php?m=listdivs&id=<?php echo $c_organization_id; ?>">
              <a href="organisations.php?m=listdivs&id=<?php echo $c_organization_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
           <h3>View Customer</h3>
          <form action="organisations_a.php?m=editcust&id=<?php echo $id; ?>" method="post" >
          <div class="grid-container">
             <div class="grid-x">
                  <div class="small-12 cell field">
                      <label>First Name
                      <input type="text" name="fname" value="<?php echo $_SESSION['firstName']; ?>">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>Surname
                      <input type="text" name="lname" value="<?php echo $_SESSION['lastName']; ?>">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>E-mail Address
                      <input type="text" name="email" value="<?php echo $_SESSION['email']; ?>">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>GMC Number
                      <input type="text" name="gmcnumber" value="<?php echo $_SESSION['gmc_number']; ?>">
                      </label>
                  </div>
                  <div class="small-12 cell field text-center">
                      <br /><input type="submit" id="addcust" class="button large" value="Update Customer">
                      <br />
                      <a href="organisations.php?m=deletecust&id=<?php echo $id; ?>" class="button large red">Delete Customer</a>
                  </div>
                </div>
             </div>
        </form>
      </div>
<!-- END EDITCUST SECTION -->
<!-- DELETECUST SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $deletecust_hide; ?>">

      <div class="back clickable-row btn-back" data-href="organisations.php?m=editcust&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=editcust&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
    <h3 class="text-left">Delete Customer</h3>
        <p>&nbsp;</p>
        <h5 class="text-center">Are you sure you wish to delete this customer?</h5>
        <p class="standard-padding text-center padding-bottom-1">This will not affect any patient data, but the user will no longer be able to access the system.</p>
        <form name="deleteuserfrm">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6">
                                                        <br> <a href="organisations.php"><input type="button" name=""
                                                                value="No" class="button large expanded inactive" /></a>
                                                              <a href="organisations_a.php?m=deletecust&id=<?php echo $id; ?>" class="button large red expanded" />Confirm Delete</a>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                        <p>&nbsp;</p>
                                </div>
                        </div>
                </div>
        </form>
</div>
<!-- End DELETECUST SECTION -->
<!-- DELETEDIV SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $deletediv_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=editdiv&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=editdiv&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
        <h3 class="text-left">Delete Division</h3>
        <p>&nbsp;</p>
        <h5 class="text-center">Are you sure you wish to delete this division?</h5>
        <p class="standard-padding text-center padding-bottom-1">This will not affect any patient data, but the user(s) will no longer be able to access the system.</p>
        <form name="deleteuserfrm">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6">
                                                        <br> <a href="organisations.php"><input type="button" name=""
                                                                value="No" class="button large expanded inactive" /></a>
                                                              <a href="organisations_a.php?m=deletediv&id=<?php echo $id; ?>" class="button large red expanded" />Confirm Delete</a>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                        <p>&nbsp;</p>
                                </div>
                        </div>
                </div>
        </form>
</div>
<!-- End DELETEDIV SECTION -->
<!-- EDITDIV SECTION -->
       <?php if ($mode=="editdiv")
             {
                $sql_div = "SELECT o.c_name, firstName, lastName, email, c_parentId
                            FROM $TBLORGANISATIONS o, dir_user u 
                            WHERE o.id='$div_id'
                            AND o.c_firstUser=u.id";
                logMsg($sql_div,$logfile);
                $GetQuery_div = dbi_query($sql_div);
                $qryResult_div=$GetQuery_div->fetch_assoc();
                $_SESSION['name'] = $qryResult_div['c_name'];
                $_SESSION['admin_fname'] = $qryResult_div['firstName'];
                $_SESSION['admin_lname'] = $qryResult_div['lastName'];
                $_SESSION['admin_email'] = $qryResult_div['email'];
                $parent_id = $qryResult_div['c_parentId'];
                $org_type = $qryResult_div['c_type'];
             }
       ?>
 <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $editdiv_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=listdivs&id=<?php echo $parent_id; ?>">
              <a href="organisations.php?m=listdivs&id=<?php echo $parent_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3>Manage Divisions
      <span class="small sub-text">View and edit your division - <?php echo $_SESSION['name']; ?></span></h3>
      <form action="organisations_a.php?m=adddiv&id=<?php echo $org_id; ?>" method="post" >
         <input type="hidden" name="type" value="<?php echo $org_type; ?>">
         <input type="hidden" name="subdivision" value="No">
         <input type="hidden" name="fname" value="fname">
         <input type="hidden" name="lname" value="lname">
         <input type="hidden" name="email" value="email@example.com">
    <div class="grid-container">
       <div class="grid-x">
            <div class="small-12 cell field">
                  <?php if ($_SESSION['add_orgname_error']) echo "<div class='error_message fi-alert'><strong>Please enter the oranization name</strong> - this is required</div>";
                        else if ($_SESSION['add_orgname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct organization name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Name
                <input type="text" name="name" value="<?php echo $_SESSION['name']; ?>"> 
              </label>
            </div>
            <div class="small-12 cell field">
               <hr>
               <strong>Primary Admin User</strong>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="admin_fname" value="<?php echo $_SESSION['admin_fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your last name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="admin_lname" value="<?php echo $_SESSION['admin_lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_bad_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                 else if ($_SESSION['add_admin_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="admin_email"  value="<?php echo $_SESSION['admin_email']; ?>">
              </label>
            </div>
            <div class="small-12 cell field text-center">
                 <br /><input type="submit" id="adddiv" class="button large" value="Update Division">
                 <br />
                 <a href="organisations.php?m=deletediv&id=<?php echo $id; ?>" class="button large red">Delete Division</a>
            </div>
          </div>
         </div>
        </form>
      </div>
<!-- END EDITDIV SECTION -->
<!-- UPDATE SECTION -->
        <?php
// logMsg("Mode: $mode","wel.log");

           if ($mode=="update") {
               $sql_u = "SELECT *
                        FROM $TBLORGANISATIONS
                        WHERE id='$org_id'";
//                logMsg($sql_u, "wel.log");
               $GetQuery_u = dbi_query($sql_u);
               $qryResult_u = $GetQuery_u->fetch_assoc();
               $org_id = $qryResult_u['id'];
               $_SESSION['name'] = $qryResult_u['c_name'];
               $_SESSION['type'] = $qryResult_u['c_type'];
               $admin = $qryResult_u['c_admin'];
               $loc = strpos($admin, " ");
               $_SESSION['fname'] = substr($admin, 0, $loc);
               $_SESSION['lname'] = substr($admin, $loc + 1);
               $_SESSION['email'] = $qryResult_u['c_email'];
               $_SESSION['subdivision'] = $qryResult_u['c_subdivision'];
               $_SESSION['logo'] = $qryResult_u['c_logo'];
               $_SESSION['admin_email'] = $qryResult_u['c_firstUser'];
               $_SESSION['org_name'] = $org_name;
               $_SESSION['org_id'] = $org_id;

               $sql_u2 = "SELECT * FROM dir_user WHERE id='" . $_SESSION['admin_email'] . "'";
               $GetQuery_u2 = dbi_query($sql_u2);
               $qryResult_u2 = $GetQuery_u2->fetch_assoc();
               $_SESSION['admin_fname'] = $qryResult_u2['firstName'];
               $_SESSION['admin_lname'] = $qryResult_u2['lastName'];

               $orgHasDivs_hide = "show";
               $manageCustDivs_hide = "show";
               if ($_SESSION['subdivision'] == "No") {
                   $orgHasDivs_hide = "show";
                   $manageCustDivs_hide = "hide";
               }
               if ($_SESSION['type'] == "Medical Malpractice Insurer") {
                   $orgHasDivs_hide = "hide";
                   $manageCustDivs_hide = "show";
               }
           }

            else
            {
               $org_name = "";
               $org_type = "";
               $org_admin = "";
               $org_email = "";
               $org_logo = "";
               $_SESSION['subdivision'] = "No";
            }
        ?>

        <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
          <h3>View Organisation</h3>
          <form action="organisations_a.php?m=update&id=<?php echo $org_id; ?>" method="post" enctype="multipart/form-data">
          <?php if ($_SESSION['logo']<>"") { ?>
             <input type="hidden" name="existing_header_logo" value="<?php echo $_SESSION['logo']; ?>">
          <?php } ?>
    <div class="grid-container">
       <div class="grid-x">
            <div class="small-12 cell field">
                           <?php if ($_SESSION['add_orgname_error']) echo "<div class='error_message fi-alert'><strong>Please enter the oranisation name</strong> - this is required</div>";
                                 else if ($_SESSION['add_orgname_format_error']) echo "<div class='error_message fi-alert'><strong>Please correct organisation name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Name
                <input type="text" name="name" placeholder="" value="<?php echo $_SESSION['name']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_type_error']) echo "<div class='error_message fi-alert'><strong>Please select a type</strong> - this is required</div>"; ?>
                <label class="weight-normal">Type
                <select name="type" id="orgTypeEdit">
                  <option value=""></option>
                  <option value="Government Hospital" <?php if ($_SESSION['type']=="Government Hospital") echo 'selected'; ?> >Government Hospital</option>
                  <option value="Private Hospital" <?php if ($_SESSION['type']=="Private Hospital") echo 'selected'; ?>>Private Hospital</option>
                  <option value="Medical Malpractice Insurer" <?php if ($_SESSION['type']=="Medical Malpractice Insurer") echo 'selected'; ?>>Medical Malpractice Insurer</option>
                </select>
              </label>
              <div class="small-6 medium-6 large-6 cell">
                 <?php if ($_SESSION['add_logo_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please upload your file again</strong> - there was an error during upload</div>";
                  if ($_SESSION['add_logo_type_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please upload an image file</strong> - the file must be a JPG PNG or GIF</div>"; ?>
                  <label class="weight-normal">Organisation Header Logo
                  <?php if ($_SESSION['logo']=="") { ?>
                     <img src="/ui/verify/img/org_logos/blank.jpg">&nbsp;&nbsp;<input type="file" name="header_logo" placeholder="">
                  <?php } else { ?>
                     <img src="/ui/verify/img/org_logos/<?php echo $_SESSION['logo']; ?>" width="35%" />&nbsp;&nbsp;<input type="file" name="header_logo" placeholder="">
                  <?php } ?>
                </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
                      <div class="row grid-x grid-padding-15 <?php echo $orgHasDivs_hide; ?>" id="orgHasDivsEdit">
                              <div class="small-5" style="padding-right:20px;" >
                                      <label class="eido-radio">
                                              <input type="radio" name="subdivision"  value="Yes" id="subdivisionRed" <?php if ($_SESSION['subdivision']=="Yes") echo 'checked'; ?> />
                                              <span class="checkmark"></span>
                                              <span class="text">Yes</span>
                                      </label>
                                      <label class="eido-radio">
                                              <input type="radio" name="subdivision" value="No" id="subdivisionBlue" <?php if ($_SESSION['subdivision']=="No") echo 'checked'; ?> />
                                              <span class="checkmark"></span>
                                              <span class="text">No</span>
                                      </label>
                              </div>
                              <div class="small-7">
                                      <label class="weight-normal">Organisation has subdivisions?</label>
                              </div>
                      </div>
              </div>
            </div>
          <div class="small-12 medium-12 large-12 cell text-center">
	        <br /><input type="submit" id="update" class="button large" value="Update Organisation">
          </div>
          </form>
        </div>
    </div>
  </div>
<!-- END UPDATE SECTION --> 
<!-- ADDOWNER SECTION -->
   <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $addowner_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3>Edit Organisation</h3>
      <form action="organisations_a.php?m=addowner&id=<?php echo $id; ?>" method="post" />
            <div class="small-12 cell field">
               <strong>Account Owner Information</strong>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                 else if ($_SESSION['add_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="fname" value="<?php echo $_SESSION['fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your last name</strong> - this is required</div>";
                 else if ($_SESSION['add_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="lname" value="<?php echo $_SESSION['lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="email" value="<?php echo $_SESSION['email']; ?>">
              </label>
          <div class="small-12 medium-12 large-12 cell text-center">
                <br /><input type="submit" id="update" class="button large" value="Add Account Owner">
          </div>
          </form>
       </div>
   </div>
<!-- END ADDOWNER SECTION -->
<!-- EDITOWNER SECTION -->
   <?php if ($mode=="editowner") {
            $sql="SELECT c_admin, c_email
                  FROM $TBLORGANISATIONS 
                  WHERE id='$org_id'";
logMsg("editowner: $sql",$logfile);
            $GetQuery=dbi_query($sql);
            $qryResult=$GetQuery->fetch_assoc();
            $admin_name=$qryResult['c_admin'];
            $loc=strpos($admin_name, " ");
            $_SESSION['fname']=substr($admin_name,0,$loc);
            $_SESSION['lname']=substr($admin_name,$loc+1);
            $_SESSION['email']=$qryResult['c_email'];
          }
   ?>
   <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $editowner_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3>Edit Organisation</h3>
      <form action="organisations_a.php?m=editowner&id=<?php echo $id; ?>" method="post" />
            <div class="small-12 cell field">
               <strong>Account Owner Information</strong>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                 else if ($_SESSION['add_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="fname" value="<?php echo $_SESSION['fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your last name</strong> - this is required</div>";
                 else if ($_SESSION['add_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="lname" value="<?php echo $_SESSION['lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="email" value="<?php echo $_SESSION['email']; ?>">
              </label>
          <div class="small-12 medium-12 large-12 cell text-center">
                <br /><input type="submit" id="update" class="button large" value="Update Account Owner">
          </div>
          </form>
       </div>
   </div>
<!-- END EDITOWNER SECTION -->
<!-- DELETEOWNER SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $deleteowner_hide; ?>">

      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
    <h3>Delete Account Owner</h3>
        <p>&nbsp;</p>
        <h5 class="text-center">Are you sure you wish to delete the account owner?</h5>
        <p class="standard-padding text-center padding-bottom-1">This will not affect any patient data, but the account owner information will be removed from this organisation.</p>
        <form name="deleteownerfrm">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6">
                                                        <br> <a href="organisations.php"><input type="button" name=""
                                                                value="No" class="button large expanded inactive" /></a>
                                                              <a href="organisations_a.php?m=deleteowner&id=<?php echo $id; ?>" class="button large red expanded" />Confirm Delete</a>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                        <p>&nbsp;</p>
                                </div>
                        </div>
                </div>
        </form>
</div>
<!-- END DELETEOWNER SECTION -->
<!-- ADDADMIN SECTION -->
   <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $addadmin_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3 class="">Edit Organisation</h3>
      <form action="organisations_a.php?m=addadmin&id=<?php echo $id; ?>" method="post" />
            <div class="small-12 cell field">
               <strong>Primary Admin User</strong>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="admin_fname" value="<?php echo $_SESSION['admin_fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your last name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="admin_lname" value="<?php echo $_SESSION['admin_lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_bad_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                 else if ($_SESSION['add_admin_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="admin_email" value="<?php echo $_SESSION['admin_email']; ?>">
              </label>
          <div class="small-12 medium-12 large-12 cell text-center">
                <br /><input type="submit" id="update" class="button large" value="Add Primary Admin">
          </div>
          </form>
       </div>
    </div>
<!-- END ADDADMIN SECTION -->
<!-- EDITADMIN SECTION -->
   <?php if ($mode=="editadmin") {
            $sql="SELECT u.id, u.firstName, u.lastName, u.email 
                  FROM dir_user u, $TBLORGANISATIONS o 
                  WHERE u.id=o.c_firstUser 
                  AND o.id='$org_id'";
logMsg("editadmin: $sql",$logfile);
            $GetQuery=dbi_query($sql);
            $qryResult=$GetQuery->fetch_assoc();
            $_SESSION['admin_fname']=$qryResult['firstName'];
            $_SESSION['admin_lname']=$qryResult['lastName'];
            $_SESSION['admin_email']=$qryResult['email'];
          }
   ?>
   <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $editadmin_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3 class="">Edit Organisation</h3>
      <form action="organisations_a.php?m=editadmin&id=<?php echo $id; ?>" method="post" />
            <div class="small-12 cell field">
               <strong>Primary Admin User</strong>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_fname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_fname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">First Name
                <input type="text" name="admin_fname" value="<?php echo $_SESSION['admin_fname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
                 <?php if ($_SESSION['add_admin_lname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your last name</strong> - this is required</div>";
                 else if ($_SESSION['add_admin_lname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
                <label class="weight-normal">Surname
                <input type="text" name="admin_lname" value="<?php echo $_SESSION['admin_lname']; ?>">
              </label>
            </div>
            <div class="small-12 cell field">
               <?php if ($_SESSION['add_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                 else if ($_SESSION['add_bad_admin_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                 else if ($_SESSION['add_admin_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                <label class="weight-normal">E-mail Address
                <input type="text" name="admin_email" value="<?php echo $_SESSION['admin_email']; ?>">
              </label>
          <div class="small-12 medium-12 large-12 cell text-center">
                <br /><input type="submit" id="update" class="button large" value="Update Primary Admin">
          </div>
          </form>
       </div>
    </div>
<!-- END EDITADMIN SECTION -->
<!-- DELETEADMIN SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $deleteadmin_hide; ?>">

      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
    <h3>Delete Primary Admin User</h3>
        <p>&nbsp;</p>
        <h5 class="text-center">Are you sure you wish to delete this administrator / user?</h5>
        <p class="standard-padding text-center padding-bottom-1">This will not affect any patient data, but the user will no longer be able to access the system.</p>
        <form name="deleteuserfrm">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6">
                                                        <br> <a href="organisations.php"><input type="button" name=""
                                                                value="No" class="button large expanded inactive" /></a>
                                                              <a href="organisations_a.php?m=deleteadmin&id=<?php echo $id; ?>" class="button large red expanded" />Confirm Delete</a>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                        <p>&nbsp;</p>
                                </div>
                        </div>
                </div>
        </form>
</div>
<!-- END DELETEADMIN SECTION -->
<!-- ORGPROC SECTION -->
        <?php
           if ($mode=="orgproc")
           {
              $sql_op = "SELECT pe.*
                         FROM $TBLPROCEPISODES pe, $TBLPROCLICENSES pl
                         WHERE pl.c_organization='$org_id'
                         AND pl.c_procedure=pe.id";
               // logMsg("OrgProc: $sql_op", $logfile);
               $GetQuery_op = dbi_query($sql_op);
               $arr_op = array();
               $i=0;
               while ($qryResult=$GetQuery_op->fetch_assoc())
               {
                  $arr_op[$i]=$qryResult;
                  $i++;
               }
               $sql_op2 = "SELECT *
                           FROM $TBLORGANISATIONS
                           WHERE id='$org_id'";
               $GetQuery_op2 = dbi_query($sql_op2);
               $qryResult_op2=$GetQuery_op2->fetch_assoc();
               $org_name = $qryResult_op2['c_name'];

               // clear the temp array of arr_add_procs
               unset($_SESSION['arr_add_procs']);
            }
        ?>

   <div style="padding-top: 0px !important;" style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $orgproc_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=overview&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
          <h3 class="margin-bottom-1">Available Procedures<br /><span class="small sub-text"><?php echo $org_name; ?></span></h3>
          <div class="grid-container">
             <div class="grid-x grid-padding-x padding-right-column">
                <div class="small-12 medium-12 large-12 cell">
                    <table width="100%" border="0" class="hover">
                    <tbody>
                    <?php for ($i=0; $i<count($arr_op); $i++)
                      { $opid = $arr_op[$i]['id'];
                        $c_description = $arr_op[$i]['c_description'];
                        $c_procedureId = $arr_op[$i]['c_procedureId'];
                    ?>
                    <tr>
                      <td><?php echo $c_procedureId; ?> </td>
                      <td><?php echo $c_description; ?></td>
                      <td class="align-middle text-right"><a href="functions.php?m=delete_orgproc&pid=<?php echo $opid; ?>&org_id=<?php echo $org_id; ?>"><i class="eido-icon-trash-o sort-icon float-right"></i></a></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
               </div>
               <div class="small-12 medium-12 large-12 cell text-center">
                    <a href="organisations.php?m=procadd&id=<?php echo $org_id; ?>"><button class="large button">Add Procedures</button></a>
               </div>
            </div>
        </div>
     </div>
  <!-- END ORGPROC SECTION --> 
  <!-- PROCADD SECTION -->
        <?php
           if ($mode=="procadd")
           {
              // get procedures for this org so far
              $sql_pa = "SELECT pe.id
                         FROM $TBLPROCEPISODES pe, $TBLPROCLICENSES pl
                         WHERE pl.c_organization='$org_id'
                         AND pl.c_procedure=pe.id";
// logMsg("AddProc: $sql_pa", $logfile);
               $GetQuery_pa = dbi_query($sql_pa);
               $arr_org_procedures = array();
               while ($qryResult_pa=$GetQuery_pa->fetch_assoc())
               {
                  $arr_org_procedures[]=$qryResult_pa['id'];
               }

              // get the list of ALL procedures
              if (isset($_SESSION['proc_searchterm']) && $_SESSION['proc_searchterm']<>"")
                 $search_str = "c_description LIKE '%".$_SESSION['proc_searchterm']."%' OR c_procedureId LIKE '%".$_SESSION['proc_searchterm']."%'";
              else
                 $search_str = " 1 ";
              $sql_pa = "SELECT *
                         FROM $TBLPROCEPISODES pe
                         WHERE $search_str ";
// logMsg("AddProc: $sql_pa", $logfile);
               $GetQuery_pa = dbi_query($sql_pa);
               $arr_all_procs = array();
               $i=0;
               while ($qryResult=$GetQuery_pa->fetch_assoc())
               {
                  $arr_all_procs[$i]=$qryResult;
                  $i++;
               }
               if (!isset($_SESSION['arr_all_procs']))
                  $_SESSION['arr_all_procs']=$arr_all_procs;
               if (!isset($_SESSION['arr_add_procs']))
                  $_SESSION['arr_add_procs']=array(); 

               // get org name
               $sql_pa2 = "SELECT *
                           FROM $TBLORGANISATIONS
                           WHERE id='$org_id'";
               $GetQuery_pa2 = dbi_query($sql_pa2);
               $qryResult_pa2=$GetQuery_pa2->fetch_assoc();
               $org_name = $qryResult_pa2['c_name'];
            }
        ?>
 <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $procadd_hide; ?>">
      <div class="back clickable-row btn-back" data-href="organisations.php?m=orgproc&id=<?php echo $org_id; ?>">
              <a href="organisations.php?m=orgproc&id=<?php echo $org_id; ?>">
              <span><i class="icon eido-icon-chevron-left"></i>
              Back</span>
              </a>
      </div>
      <h3>Add Procedures<br /><span class="small sub-text"><?php echo $org_name; ?></span></h3>
      <div class="grid-container">
         <div class="grid-x grid-padding-x">
            <div class="small-12 medium-12 large-12 cell padding-top-2">
               <div class="grid-x">
                <form action="organisations.php?m=procadd&id=<?php echo $org_id; ?>" method="post">
                    <div class="small-7 medium-7 large-7 cell gh_form basic" style="padding-left: 0px !important; padding-right: 0px !important;">
                        <div class="input-group tb-search">
                            <div class="input-group-button left-append">
                                <i class="fi-magnifying-glass"></i>
                            </div>
                            <input type="text" name="proc_searchterm" value="<?php echo $_SESSION['proc_searchterm']; ?>" class="input-group-field">
                            <div class="input-group-button right-append">
                                <a href="organisations_a.php?m=clear_proc_search&id=<?php echo $org_id; ?>" class="clear-icon">
                                    <i class="eido-icon-x-altx-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="small-2 medium-2 large-2 cell">
                        <div class="flex-auto" style="padding-right: 0px !important; width: fit-content"><input type="submit" value="Search" class="button" style="font-size: 1em; margin-right: 5px;"></div>
                    </div>

                </form>
            </div>
         </div>
         <div class="small-12 medium-12 large-12 cell">
                <table width="100%" border="0" class="hover">
                  <tbody>
                  <?php $arr_add_procs = $_SESSION['arr_add_procs'];
                           for ($xxx=0;$xxx<count($arr_add_procs); $xxx++)
                               logMsg("arr_add_procs: ProcID: ".$arr_add_procs[$xxx],$logfile);
                        // ********************
                        //  list all procedures
                        // ********************
                        for ($i=0; $i<count($arr_all_procs); $i++)
                        { // list procedures that are NOT already in the org_procedures array
                          $pid = $arr_all_procs[$i]['id'];
                          $c_procedureId = $arr_all_procs[$i]['c_procedureId'];
                          $c_description = $arr_all_procs[$i]['c_description'];
                          if (!in_array($pid, $arr_org_procedures) && !in_array($pid, $arr_add_procs)) {
                  ?>
                  <tr>
                    <td><?php echo $c_procedureId; ?></td>
                    <td><?php echo $c_description; ?></td>
                      <td class="align-middle text-right"><a href="functions.php?m=add_proc_to_temp&pid=<?php echo $pid; ?>&org_id=<?php echo $org_id;?>&i=<?php echo $i; ?>"><i class="eido-icon-plus"></i></a></td>
                  </tr>
                  <?php   } // end if
                        } // end for
                  ?>
                  <tr>
                     <td colspan="3" class="text-left"><hr /></td>
                  </tr>
                     <?php // *******************
                           // list arr_add_procs
                           // *******************
                           for ($t=0; $t<count($arr_add_procs); $t++) {
                             $arr_proc_info = get_proc_by_id($arr_add_procs[$t]);
                     ?>
                   <tr>
                      <td class="text-left" ><?php echo $arr_proc_info['c_procedureId']; ?></td>
                      <td class="text-left" ><?php echo $arr_proc_info['c_description']; ?></td>
                      <td class="text-right" ><a href="functions.php?m=delete_proc_from_temp&id=<?php echo $org_id; ?>&t=<?php echo $t; ?>&pid=<?php echo $arr_add_procs[$t]['id']; ?>"><i class="eido-icon-trash-o"></i></a></td>
                   </tr>
              <?php } ?>
                  <tr>
                     <td colspan="3" class="text-left"><hr /></td>
                  </tr>

                </tbody>
              </table>
         </div>
         <div class="small-12 medium-12 large-12 cell text-center">
               <a href="functions.php?m=add_selected_procs&org_id=<?php echo $org_id; ?>"><button class="button large">Add Procedures</button></a>
         </div>
      </div>
   </div>
</div>
<!-- END PROCADD SECTION  -->
<!-- End Right-Content --> 
  </div>
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
	         $('#orgTypeAdd').change(function () {
	             if (this.value === 'Medical Malpractice Insurer') {
                     $('#orgHasDivsAdd').removeClass('show');
                     $('#orgHasDivsAdd').addClass('hide');
                 }
                 else {
                     $('#orgHasDivsAdd').removeClass('hide');
                     $('#orgHasDivsAdd').addClass('show');
                 }
             });
             $('#orgTypeEdit').change(function () {
                 if (this.value === 'Medical Malpractice Insurer') {
                     $('#orgHasDivsEdit').removeClass('show');
                     $('#orgHasDivsEdit').addClass('hide');
                 }
                 else {
                     $('#orgHasDivsEdit').removeClass('hide');
                     $('#orgHasDivsEdit').addClass('show');
                 }
             });
         });
      </script>  
   </body>
</html>
