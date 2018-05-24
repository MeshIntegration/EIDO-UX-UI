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
$logfile = "superuser.log";

// turn eerything off
$add_hide = "hide";
$update_hide = "hide";
$orgproc_hide = "hide";
$procadd_hide = "hide";
$overview_hide = "hide";
$listdivs_hide = "hide";
$editdiv_hide = "hide";
$adddiv_hide = "hide";
$addcust_hide = "hide";

$mode = get_query_string('m');
$id = get_query_string('id');
logMsg("Organisations: mode: $mode OrgID: $id",$logfile);

if ($mode=="" || mode=="add")
{
   $add_hide = "";
}
else if ($mode=="update")
{
   $update_hide = "";
   $org_id=$id;
}
else if ($mode=="orgproc")
{
   $orgproc_hide = "";
   $org_id = $id;
}
else if ($mode=="overview")
{
   $overview_hide = "";
   $org_id = $id;
}
else if ($mode=="procadd")
{
   $procadd_hide = "";
   $org_id = $id;
}
else if ($mode=="listdivs")
{
   $listdivs_hide = "";
   $org_id = $id;
}
else if ($mode=="adddiv")
{
   $adddiv_hide = "";
   $org_id = $id;
}
else if ($mode=="addcust")
{
   $addcust_hide = "";
   $org_id = $id;
}
else if ($mode=="editdiv")
{
   $editdiv_hide = "";
   $org_id = $id;
   $div_id = $div_id;
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

$sql = "SELECT * FROM $TBLORGANISATIONS LIMIT $start,$row";
$GetQuery = dbi_query($sql);
logMsg("Organisations: $sql",$logfile);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Organizations</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <style>
     .clickable-row {cursor: pointer;}  
  </style>
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
		<li><a href="users.php">Users</a></li>
		<li class="current"><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="users.php">Users</a></li>
		<li class="current"><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su" data-equalizer data-equalize-on="medium">
     <!-- Start Content-Left -->
     <div class="small-12 medium-6 large-6 cell content-left">

	     <div id="bulk_action_tabs" class="tabs tab-actions" data-tab data-active-collapse="true" data-responsive-accordion-tabs="tabs medium-accordion large-tabs">
			<span class="tabs-title">
				<a href="#panel1" class="btn btn-actions" role="tab" id="BulkActions">Bulk Actions
					<i class="eido-icon-plus fc_add fc_plus "></i>
					<i class="eido-icon-minus fc_add fc_minus"></i>
				</a>
			</span>
		     <span class="tabs-title">
				<a href="#panel2" class="btn btn-actions" role="tab">Sort &amp; Search
					<i class="eido-icon-plus fc_add fc_plus"></i>
					<i class="eido-icon-minus fc_add fc_minus"></i>
				</a>
			</span>

	     </div>

	     <!-- INSERT ACTIONS DIV .tabs-panel HERE -->

	     <div class="grid-x grid-header" style="">
		     <div class="small-2 columns column-first" style="">
			     <label class="eido-checkbox">
				     <input class="eido-checkbox" style="margin-left:25px;" type="checkbox" name="actOnAll" id="actOnAll">
				     <span class="checkmark" style="left:0px;"></span>
			     </label>
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
						     <div class="small-2 columns column-first">
							     <label class="eido-checkbox">
								     <input type="checkbox" name="performAction[]" id="performAction<?php echo $i; ?>" value="<?php echo $uid; ?>">
								     <span class="checkmark"></span>
							     </label>
						     </div>
						     <div class="small-6 columns">
							     <p>
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
                  $sql = "SELECT * FROM $TBLORGANISATIONS";
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
    <div class="grid-container">
        <div class="grid-x">
            <div class="small-12 cell field">
                <h3>&nbsp;&nbsp;Add Organisation</h3>
            </div>
        </div>
    </div>
          <form action="organisations_a.php?m=add" method="post" enctype="multipart/form-data">
    <div class="grid-container">
       <div class="grid-x">
            <div class="small-12 cell field">
                <label class="weight-normal">Name
                <input type="text" name="name" placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
                <label class="weight-normal">Administrator Contact First Name
                <input type="text" name="fname"  placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
                <label class="weight-normal">Administrator Contact Surname
                <input type="text" name="lname"  placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
                <label class="weight-normal">E-mail Address
                <input type="text" name="email" placeholder="">
              </label>
            </div>
            <div class="small-12 cell field">
                <label class="weight-normal">Type
                <select name="type">
                  <option value=""></option>
                  <option value="Government Hospital" >Government Hospital</option>
                  <option value="Private Hospital">Private Hospital</option>
                  <option value="Medical Insurer" >Medical Insurer</option>
                </select>
              </label>
              <div class="small-6 medium-6 large-6 cell">
                  <label class="weight-normal">Organisation Header Logo
                  <img src="/ui/verify/img/org_logos/blank.jpg">&nbsp;&nbsp;<input type="file" name="header_logo" placeholder="">
                </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
	              <div class="row grid-x grid-padding-15">
		              <div class="small-5" style="padding-right:20px;">
			              <label class="eido-radio">
				              <input type="radio" name="subdivision"  value="Yes" id="subdivisionRed" required />
				              <span class="checkmark"></span>
				              <span class="text">Yes</span>
			              </label>
			              <label class="eido-radio">
				              <input type="radio" name="subdivision" value="No" id="subdivisionBlue" />
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
            <div class="small-12 cell field text-center">
                  <br /><input type="submit" id="add" class="button large" value="Add organization">
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

               logMsg("update: resetting Procedure Arrays", $logfile);
               unset($_SESSION['arr_all_procs']);
               unset($_SESSION['arr_add_procs']);
            }
         ?>
     <div class="small-12 medium-6 large-6 cell content-right <?php echo $overview_hide; ?>">
	     <h3>Patient Overview<span class="small display-block">View and edit an Organisation</span></h3>

         <div class="grid-container content-container row">
             <div class="grid-x grid-padding-x">
                  <div class="small-12 medium-12 large-12 padding-right-column">
	                  <h5 class="ps_grey"><?php echo $org_name; ?></h5>
	                  <div class="grid-x grid-padding-x row grid-padding-15" style="padding-top:0;">
		                  <div class="small-12 medium-12 large-12 cell">
			                  <label>Administrative Contact</label>
			                  <h5><?php echo $org_admin ? $org_admin : 'None'; ?></h5>
		                  </div>
		                  <div class="small-12 medium-12 large-12 cell">
			                  <label>Email</label>
			                  <h5><?php echo $org_email; ?></h5>
		                  </div>
		                  <div class="small-12 medium-12 large-12 cell">
			                  <label>Type</label>
			                  <h5><?php echo $org_type; ?></h5>
		                  </div>

	                  </div>
	                  <hr class="standard-hr" />
	                  <!-- spacer -->
	                  <div class="grid-x grid-padding-x row grid-padding-15" style="">
		                  <div class="small-12 cell">
			                  <label>Header Organization Logo</label>
			                  <?php if($org_logo != ""): ?>
				                  <div class="row grid-x">
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
	                  <hr class="standard-hr" />
	                  <div class="grid grid-padding-x grid-padding-15" style="padding-top:0;">
		                  <ul class="patient-list" style="margin:0px -15px;">
			                  <li>
				                  <a href="organisations.php?m=orgproc&id=<?php echo $org_id; ?>" class="no-u">
					                  <span class="float-right right-arrow"><i class="eido-icon-chevron-right"></i></span>
					                  <p class="">Manage Procedures</p>
				                  </a>
			                  </li>
			                  <li>
				                  <?php if(strpos(strtoupper($org_type),"HOSPITAL")): ?>
					                  <a href="organisations.php?m=listdivs&id=<?php echo $org_id; ?>" class="no-u">
						                  <span class="float-right right-arrow"><i class="eido-icon-chevron-right"></i></span>

						                  <p class="">Manage Divisions</p>
					                  </a>
				                  <?php else: ?>
					                  <a href="organisations.php?m=listdivs&id=<?php echo $org_id; ?>" class="no-u">
						                  <span class="float-right right-arrow"><i class="eido-icon-chevron-right"></i></span>

						                  <p class="">Manage Customers</p>
					                  </a>

				                  <?php endif; ?>
			                  </li>
		                  </ul>
		                  <hr class="standard-hr"/>
	                  </div>
	                  <div class="grid grid-padding-x grid-padding-15" style="">
		                  <span style="text-align: center" class="display-block"><a href="organisations.php?m=update&id=<?php echo $org_id; ?>"><button class="button large">Edit Organisation</button></a></span>
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
              if ($div_type=="Division")
              {
                 $sql_ld = "SELECT d.name, d.description, u.id, u.email, u.firstName, u.lastName
                           FROM dir_department d, dir_employment e, dir_user u
                           WHERE d.organizationId='$org_id'
                           AND e.organizationId='$org_id'
                           AND e.userid=u.id";
                 logMsg($sql_ld,$logfile);
                 $GetQuery_ld = dbi_query($sql_ld);
                 $d=0;
                 while ($qryResult_ld=$GetQuery_ld->fetch_assoc())
                 {
                    $arr_ld[$d]=$qryResult_ld;
                    $d++;
                 }
              }
              else
              {
                 $sql_ld = "SELECT s.*, u.email, u.firstName, u.lastName
                           FROM $TBLSURGEONS s, dir_employment e, dir_user u
                           WHERE e.organizationId='$org_id'
                           AND e.userid=u.id
                           AND u.id=s.id";
                 logMsg($sql_ld,$logfile);
                 $GetQuery_ld = dbi_query($sql_ld);
                 while ($qryResult_ld=$GetQuery_ld->fetch_assoc())
                 {
                    $arr_ld[$d]['id']=$qryResult_ld['id'];
                    $arr_ld[$d]['name']=$qryResult_ld['c_surgeonName'];
                    $arr_ld[$d]['description']=$qryResult_ld['description'];
                    $arr_ld[$d]['email']=$qryResult_ld['email'];
                    $arr_ld[$d]['firstName']=$qryResult_ld['firstName'];
                    $arr_ld[$d]['lastName']=$qryResult_ld['lastName'];
                    $d++;
                 }
              }
            }
       ?>
   <div class="small-12 medium-6 large-6 cell content-right <?php echo $listdivs_hide; ?>">
       <div class="back"><a href="#"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h4>Manage <?php echo $div_type; ?>s</h4>
          <h7>Add <?php echo $div_type; ?>s to your organisation</h7><br />
          <hr>
          <div class="grid-container">
               <div class="grid-x grid-padding-x">
                   <div class="small-12 medium-12 large-12 cell">
                        <table width="100%" border="0"  class="su-table stack">
                           <tbody>
                               <?php if ($mode=="listdivs") {
                                        for ($d=0; $d<count($arr_ld); $d++)
                                        { $div_id = $arr_ld[$d]['id'];
                                          $div_name = $arr_ld[$d]['name'];
                                          $div_description = $arr_ld[$d]['description'];
                                          $div_email = $arr_ld[$d]['email'];
                                          $div_firstName = $arr_ld[$d]['firstName'];
                                          $div_lastName = $arr_ld[$d]['lastName'];
                                          $div_str = $div_firstName." ".$div_lastName." - ".$div_email;
                               ?>
                              <tr>
                                 <td class='clickable-row su_data' data-href='organisations.php?m=overview&id=<?php echo $id; ?>'><p><a href="organisations.php?m=editdiv=&div_id=<?php echo $div_id; ?>"><span class="uc"><?php echo $div_name; ?></span><br />
                                    <?php echo $div_str; ?></a></p>
                                 </td>
                                 <td>
                                     <a href="organisations.php?m=editdiv&id=<?php echo $div_id; ?>"><img src="../img/icons/greater.png" alt="icon" class="align-right" /></a>
                                 </td>
                              </tr>
                            <?php       }
                                  } ?>
                           </tbody>
                        </table>
                  </div>
                  <div class="small-12 medium-12 large-12 cell text-center">
                     <?php if ($div_str=="Division")
                              $btn_div_mode="adddiv";
                           else
                              $btn_div_mode="addcust";
                     ?>
                         <a href="organisations.php?m=<?php echo $btn_div_mode; ?>&id=<?php echo $org_id; ?>"><button class="large button">Add New <?php echo $div_type; ?></button></a>
                  </div>
               </div>
            </div>
         </div>
  <!-- END LISTDIVS SECTION -->
  <!-- ADDDIV SECTION -->
       <?php if ($mode="adddiv" || $mode=="addcust")
             {
                $sql_div = "SELECT * FROM $TBLORGANISATIONS WHERE id='$org_id'";
                logMsg($sql_div,$logfile);
                $GetQuery_div = dbi_query($sql_div);
                $qryResult_div=$GetQuery_div->fetch_assoc();
                $org_name = $qryResult_div['c_name'];
             }
       ?>
       <div class="small-12 medium-6 large-6 cell content-right <?php echo $adddiv_hide; ?>">
          <h3>Manage Divisions</h3>
          <h5>Add Subdivisions to your organisation - <?php echo $org_name; ?></h5>
          <form action="organisations_a.php?m=adddiv&id=<?php echo $org_id; ?>" method="post" >
          <div class="grid-container">
             <div class="grid-x">
                  <div class="small-12 cell field">
                      <label>Name
                      <input type="text" name="name" placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>Administrator Contact First Name
                      <input type="text" name="fname"  placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>Administrator Contact Surname 
                      <input type="text" name="lname"  placeholder="">
                      </label>
                  </div>
                  <div class="small-12 cell field">
                      <label>E-mail Address
                      <input type="text" name="email" placeholder="">
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
       <div class="small-12 medium-6 large-6 cell content-right <?php echo $addcust_hide; ?>">
          <h3>Manage Customers</h3>
          <h5>Add Customers to your organisation - <?php echo $org_name; ?></h5>
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
                  <div class="small-12 cell field text-center">
                      <br /><input type="submit" id="addcust" class="button large" value="Add Customer">
                  </div>
                </div>
             </div>
        </form>
      </div>
  <!-- END ADDCUST SECTION -->
  <!-- EDITDIV SECTION -->

  <!-- END EDITDIV SECTION -->
  <!-- UPDATE SECTION -->
        <?php
           if ($mode=="update")
           {
              $sql_u = "SELECT *
                        FROM $TBLORGANISATIONS
                        WHERE id='$org_id'";
               $GetQuery_u = dbi_query($sql_u);
               $qryResult_u = $GetQuery_u->fetch_assoc();
               $org_id = $qryResult_u['id'];
               $org_name = $qryResult_u['c_name'];
               $org_type = $qryResult_u['c_type'];
               $org_admin = $qryResult_u['c_admin'];
               $org_email = $qryResult_u['c_email'];
               $org_subdivision = $qryResult_u['c_subdivision'];
               $org_logo = $qryResult_u['c_logo'];
               $loc=strpos($org_admin, " "); 
               $org_fname=substr($org_admin,0,$loc);
               $org_lname=substr($org_admin,$loc+1);
               $_SESSION['org_name']=$org_name;
               $_SESSION['org_id']=$org_id;
            }
            else
            {
               $org_name = "";
               $org_type = "";
               $org_admin = "";
               $org_email = "";
               $org_logo = "";
            }
        ?>

        <div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
          <h2>View Organisation</h2>
          <form action="organisations_a.php?m=update&id=<?php echo $org_id; ?>" method="post" enctype="multipart/form-data">
          <?php if ($org_logo<>"") { ?>
             <input type="hidden" name="existing_header_logo" value="<?php echo $org_logo; ?>">
          <?php } ?>
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
            <div class="small-12 medium-12 large-12 cell">
                  <label>Name
                <input type="text" name="name" value="<?php echo $org_name; ?>">
              </label>
            </div>
            <div class="small-12 medium-12 large-12 cell">
              <label>Administrator Contact First Name
                <input type="text" name="fname" value="<?php echo $org_fname; ?>">
              </label>
            </div>
            <div class="small-12 medium-12 large-12 cell">
              <label>Administrator Contact Last Name
                <input type="text" name="lname" value="<?php echo $org_lname; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>E-mail Address
                <input type="text" name="email" value="<?php echo $org_email; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Type
                      <select name="type">
                      <option value="Private Hospital" <?php if ($org_type=="Private Hospital") echo "selected"; ?>>Private Hospital</option>
                      <option value="Government Hospital" <?php if ($org_type=="Government Hospital") echo "selected"; ?>>Government Hospital</option>
                      <option value="Medical Malpractice Insurer"  <?php if ($org_type=="Medical Malpractice Insurer") echo "selected"; ?>>Medical Malpractice Insurer</option>
                      </select>
              </label>
            </div>
        <div class="small-12 medium-12 large-12 cell">
           <label>Organisation Header Logo
           <?php if ($org_logo<>"") { ?>
                <img src="/ui/verify/img/org_logos/<?php echo $org_logo; ?>">
           <?php } ?>
                <input type="file" name="header_logo" >
           </label>
        </div>
        <div class="small-12 medium-12 large-12 cell">
	        <div class="row grid-x grid-padding-15">
		        <div class="small-5" style="padding-right:20px;">
			        <label class="eido-radio">
				        <input type="radio" name="subdivision"  value="Yes" id="subdivisionRed" required <?php if ($org_subdivision=="YES") echo "checked"; ?> />
				        <span class="checkmark"></span>
				        <span class="text">Yes</span>
			        </label>
			        <label class="eido-radio">
				        <input type="radio" name="subdivision" value="No" id="subdivisionBlue" <?php if ($org_subdivision=="NO") echo "checked"; ?> />
				        <span class="checkmark"></span>
				        <span class="text">No</span>
			        </label>
		        </div>
		        <div class="small-7">
			        <label class="weight-normal">Organisation has subdivisions?</label>
		        </div>
	        </div>
        </div>
        <div class="small-12 medium-12 large-12 cell text-center">
	        <br /><input type="submit" id="update" class="button large" value="UPDATE ORGANISATION">
        </div>
        </div>
  </div>
  </form>
</div>
  <!-- END UPDATE SECTION --> 
        <!-- ORGPROC SECTION -->
        <?php
           if ($mode=="orgproc")
           {
              $sql_op = "SELECT pe.*
                         FROM $TBLPROCEPISODES pe, $TBLPROCLICENSES pl
                         WHERE pl.c_organization='$org_id'
                         AND pl.c_procedure=pe.id";
               logMsg("OrgProc: $sql_op", $logfile);
               $GetQuery_op = dbi_query($sql_op);
               $arr_op = array();
               $i=0;
               while ($qryResult=$GetQuery_op->fetch_assoc())
               {
                  $arr_op[$i]=$qryResult;
                  $i++;
               }
               $sql_op2 = "SELECT *
                       FROM $TBLORGANISATIONS";
               $GetQuery_op2 = dbi_query($sql_op2);
               $qryResult_op2=$GetQuery_op2->fetch_assoc();
               $org_name = $qryResult_op2['c_name'];
            }
        ?>

   <div class="small-12 medium-6 large-6 cell content-right <?php echo $orgproc_hide; ?>">
      <div class="back row back-row clickable-row">
	      <a href="organisations_a.php" class="btn-back"><i class="eido-icon-chevron-left"></i> Back</a>
      </div>
          <h2 class="sub">Organisation Procedures<br /><span class="small"><?php echo $org_name; ?></span></h2>
          <div class="grid-container">
             <div class="grid-x grid-padding-x padding-right-column">
                <div class="small-12 medium-12 large-12 cell">
                   <div class="grid-x">
                      <div class="small-10 cell">
                         <label>Procedure Search
                                <input type="text" placeholder="oscopy" class="search-left">
                          </label>
                      </div>
                      <div class="small-2 cell">
                         <label>&nbsp;</label>
                         <div class="search-right">
                            <a href="#" class="button postfix expanded search-btn"><i class="fi-magnifying-glass"></i></a>
                         </div>
                      </div>
                   </div>
                </div>
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
                      <td class="align-middle text-right"><a href="functions.php?m=delete_orgproc&pid=<?php echo $opid; ?>&org_id=<?php echo $org_id; ?>"><i class="fi-trash sort-icon float-right"></i></a></td>
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
logMsg("AddProc: $sql_pa", $logfile);
               $GetQuery_pa = dbi_query($sql_pa);
               $arr_org_procedures = array();
               while ($qryResult_pa=$GetQuery_pa->fetch_assoc())
               {
                  $arr_org_procedures[]=$qryResult_pa['id'];
               }

              // get the list of ALL procedures
              $sql_pa = "SELECT *
                        FROM $TBLPROCEPISODES pe
                        WHERE 1 ";
logMsg("AddProc: $sql_pa", $logfile);
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
                       FROM $TBLORGANISATIONS";
               $GetQuery_pa2 = dbi_query($sql_pa2);
               $qryResult_pa2=$GetQuery_pa2->fetch_assoc();
               $org_name = $qryResult_pa2['c_name'];
            }
        ?>
 <div class="small-12 medium-6 large-6 cell content-right <?php echo $procadd_hide; ?>">
    <div class="back">
          <img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back
    </div>
      <h2 class="sub">Add Procedures<br /><span class="small"><?php echo $org_name; ?></span></h2>
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
                   <div class="search-right">
                         <a href="#" class="button postfix expanded search-btn"><i class="fi-magnifying-glass"></i></a>
                  </div>
               </div>
            </div>
         </div>
         <div class="small-12 medium-12 large-12 cell">
                <table width="100%" border="0" class="hover">
                  <tbody>
                  <?php $arr_add_procs = $_SESSION['arr_add_procs'];
                           for ($xxx=0;$xxx<count($arr_add_procs); $xxx++)
                               logMsg("arr_add_procs: ProcID: ".$arr_add_procs[$xxx],$logfile);
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
                    <td class="align-middle text-right"><a href="functions.php?m=add_proc_to_temp&pid=<?php echo $pid; ?>&org_id=<?php echo $org_id; logMsg(">>>>> $org_id <<<<<",$logfile); ?>&i=<?php echo $i; ?>"><font style="font-size:15;"> + </font></a></td>
                  </tr>
                  <?php   } // end if
                        } // end for
                  ?>
                  <tr>
                     <td colspan="3" class="text-left"><hr /></td>
                  </tr>
                     <?php for ($t=0; $t<count($arr_add_procs); $t++) {
                             $arr_proc_info = get_proc_by_id($arr_add_procs[$t]);
                     ?>
                   <tr>
                      <td class="text-left" ><?php echo $arr_proc_info['c_procedureId']; ?></td>
                      <td class="text-left" ><?php echo $arr_proc_info['c_description']; ?></td>
                      <td class="text-right" ><a href="functions.php?m=delete_proc_from_temp&id=<?php echo $org_id; ?>&t=<?php echo $t; ?>&pid=<?php echo $arr_add_procs[$t]['id']; ?>"><i class="fi-trash"></i></a></td>
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
         });
      </script>  
   </body>
</html>
