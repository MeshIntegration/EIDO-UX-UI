<!doctype html>
<?php
// ***************************************
// superuser/organisations.php
// 2017 Copyright, Mesh Integration LLC
// 1/24/18 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
require_once "./superuser_functions.php";

if ($user_role<>"SUPERUSER")
{
   header("Location: /ui/login.php");
   exit();
}
session_start();
$logfile = "wel.log";

// turn eerything off
$add_hide = "hide";
$update_hide = "hide";
$orgproc_hide = "hide";
$procadd_hide = "hide";
$overview_hide = "hide";
$listdivs_hide = "hide";
$editdiv_hide = "hide";
$addhive_hide = "hide";

$mode = get_query_string('m');
$id = get_query_string('id');
logMsg("Organisations: mode: $mode OrgID: $id",$logfile);

if ($mode=="")
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

// need to change according to session
if (isset($_GET['page']) && !empty($_GET['page'])) 
{
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}

$sql = "SELECT * FROM app_fd_pro_organizations LIMIT $start,$row";
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
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
        <table width="100%" border="0"  class="su-table stack">
  	     <tbody>
              <tr>  
	         <td colspan="3">
                    <a class="button fc">Bulk Actions<img src="../img/icons/add_light.png" alt="add icon" class="fc_add"/></a>&nbsp;&nbsp;
		    <a class="button fc">Sort By<img src="../img/icons/add_white.png" alt="add icon" class="fc_add"/></a>
                 </td>
              </tr>
              <tr>
                 <td><input type="checkbox"></td>
                 <td colspan="2">&nbsp;</td>
              </tr>
               <?php while ($qryResult=$GetQuery->fetch_assoc())
                     {
                        $id=$qryResult['id'];
                        $c_name=$qryResult['c_name'];
                        $c_type=$qryResult['c_type'];
               ?>
	       <tr>
                  <td><input type="checkbox"></td>
	          <td class='clickable-row su_data' data-href='organisations.php?m=overview&id=<?php echo $id; ?>'>
		    <p>
		  <a href="organisations.php?m=overview&id=<?php echo $id; ?>"><span class="uc"><?php echo $c_name; ?></span><br />
		  <?php echo $c_type; ?></a>
		    </p>
		  </td>
	          <td><a href="organisations.php?m=overview&id=<?php echo $id; ?>"><img src="../img/icons/greater.png" alt="icon" class="align-right" /></a></td>
	       </tr>
              <?php } ?>
               <?php 
               // pagination 
                  $sql = "SELECT * FROM app_fd_pro_organizations";
                  $GetQuery = dbi_query($sql);
                  $totalRecord = $GetQuery->num_rows;
                  $pagination = get_pagination($page, $totalRecord);
               ?>   
  	    </tbody>
        </table>
     <div class="grid grid-x text-center">
        <div class="small-12 pagination-btm"><?php echo $pagination; ?></div>
     </div>
   </div>
<!-- End Content-Left -->  
<!-- Start Content-Right -->  
<!-- ADD SECTION -->  
<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h3>Add Organisation</h3>
  <form action="organisations_a.php?m=add" method="post" enctype="multipart/form-data">
    <div class="grid-container">
       <div class="grid-x">
      	    <div class="small-12 cell field">
        	  <label>Name
                <input type="text" name="name" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
        	  <label>Administrator Contact
                <input type="text" name="admin"  placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
        	  <label>E-mail Address
                <input type="text" name="email" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
       	      <label>Type
                <select name="type">
                  <option value=""></option>
                  <option value="Government Hospital" >Government Hospital</option>
                  <option value="Private Hospital">Private Hospital</option>
                  <option value="Medical Insurer" >Medical Insurer</option>
                </select>
              </label>
              <div class="small-6 medium-6 large-6 cell">
                <label>Organisation Header Logo
                  <img src="/ui/img/org_logos/blank.jpg">&nbsp;&nbsp;<input type="file" name="header_logo" placeholder="">
                </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
	       <fieldset class="large-6 cell">
               <label>Organisation has subdivisions?&nbsp;&nbsp;
               <input type="radio" name="subdivision"  value="Yes" id="subdivisionRed" required><label for="subdivisionRed">Yes</label>
               <input type="radio" name="subdivision"  value="No" id="subdivisionBlue"><label for="subdivisionBlue">No</label>
			     </label>
               </fieldset>
              </div>
            </div>
            <div class="small-12 cell field text-center">
        	  <br /><input type="submit" id="add" class="button large" value="Add Organization">
            </div>
    	  </div>
  	</div>
  </form>
</div>  
        <!-- END ADD SECTION -->
        <!-- OVERVIEW SECTION -->
        <?php
           if ($mode=="overview")
           {
              $sql_o = "SELECT *
                        FROM app_fd_pro_organizations
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
          <h5>Organisation Overview</h5>
          <h6>View and Edit an Organisation</h6><br />
             <div class="grid-container">
                <div class="grid-x grid-padding-x">
                     <div class="small-12 medium-12 large-12 cell">
                         <h4><?php echo $org_name; ?></h4>
                         <p>Administrative Contact<br /><?php echo $org_admin; ?> <br /><br />
                         Email<br /><?php echo $org_email; ?> <br /> <br />
                         Type<br /><?php echo $org_type; ?> Type</p>
                         Header Logo<br /> 
                          <?php if ($org_logo<>"") { ?>
                             <img src="<?php echo "/ui/img/org_logos/".$org_logo; ?>">
                         <?php } ?>
                         <a href="organisations.php?m=orgproc&id=<?php echo $org_id; ?>" class="no-u"><p class="directive">Manage Procedures<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a>
                     <?php if (strpos(strtoupper($org_type),"HOSPITAL")) { ?> 
                         <a href="organisations.php?m=listdivs&id=<?php echo $org_id; ?>" class="no-u"><p class="directive">Manage Divisions<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a>
                     <?php } else { ?>
                         <a href="organisations.php?m=listdivs&id=<?php echo $org_id; ?>" class="no-u"><p class="directive">Manage Customers<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a>
                     <?php } ?>
                         <center><a href="organisations.php?m=update&id=<?php echo $org_id; ?>"><button class="button large">Edit Organisation</button></a></center>
                     </div>
                  </div>
               </div>
            </div>
        </div>
        <!-- LISTDIVS SECTION -->
        <?php
           if ($mode=="listdivs")
           {
              $sql_ld="SELECT c_type FROM app_fd_pro_organizations
                       WHERE id='$org_id'";
              $GetQuery_ld = dbi_query($sql_ld);
              $qryReslt_ld=$GetQuery_ld->fetch_assoc();
              $div_type = $qryReslt_ld['c_type'];
              if (strpos(strtoupper($div_type),"HOSPITAL"))
                 $div_type = "Division"; 
              else
                 $div_type = "Customer";
              $sql_ld = "SELECT *
                        FROM dir_department
                        WHERE c_orgId='$org_id'";
              $GetQuery_ld = dbi_query($sql_ld);
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
                       <?php while ($qryResult_ld = $GetQuery_ld->fetch_assoc())
                        { $div_id = $qryResult_ld['id'];
                          $div_name = $qryResult_ld['name'];
                          $div_description = $qryResult_ld['description'];
                          $div_c_email = $qryResult_ld['c_email'];
                          $div_c_adminFirstName = $qryResult_ld['c_adminFirstName'];
                          $div_c_adminLastName = $qryResult_ld['c_adminLastName'];
                          $div_str = $div_c_adminFirstName." ".$div_c_adminLastName." - ".$div_c_email;
                       ?>
                              <tr>  
	                         <td class='clickable-row su_data' data-href='organisations.php?m=overview&id=<?php echo $id; ?>'><p><a href="organisations.php?m=editdiv=&div_id=<?php echo $div_id; ?>"><span class="uc"><?php echo $div_name; ?></span><br />
		                    <?php echo $div_str; ?></a></p>
		                 </td>
	                         <td>
                                     <a href="organisations.php?m=editdiv&id=<?php echo $div_id; ?>"><img src="../img/icons/greater.png" alt="icon" class="align-right" /></a>
                                 </td>
	                      </tr>
                            <?php } ?>
                           </tbody>
                        </table>
                     </div>
	             <div class="small-12 medium-12 large-12 cell text-center">
                        <a href="organisations.php?m=adddiv&id=<?php echo $org_id; ?>"><button class="large button">Add New <?php echo $div_type; ?></button></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
        <!-- END LISTDIVS SECTION -->
	<!-- UPDATE SECTION -->  
        <?php
           if ($mode=="update")
           {
              $sql_u = "SELECT *
                        FROM app_fd_pro_organizations 
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
        	  <label>Administrator Contact
                <input type="text" name="admin" value="<?php echo $org_admin; ?>">
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
                <img src="/ui/img/org_logos/<?php echo $org_logo; ?>">
           <?php } ?>
                <input type="file" name="header_logo" >
           </label>
        </div>
        <div class="small-12 medium-12 large-12 cell">
                <p>&nbsp;</p>
                YES&nbsp;&nbsp;<input type="radio" name="subdivision" value="YES" <?php if ($org_subdivision=="YES") echo "checked"; ?>>
                NO&nbsp;&nbsp;<input type="radio" name="subdivision" value="NO" <?php if ($org_subdivision=="NO") echo "checked"; ?>>
                &nbsp;&nbsp;<b>Organisation has subdivisions?i</b>
        </div>
	<div class="small-12 medium-12 large-12 cell text-center">
        	  <br /><input type="submit" id="update" class="button large" value="UPDATE ORGANISATION">
        </div>
        </div>
  </div>
  </form>
</div>  

	<!-- ORGPROC SECTION -->  
        <?php
           if ($mode=="orgproc")
           {
              $sql_op = "SELECT pe.*
                         FROM app_fd_pro_procEpisodes pe, app_fd_pro_procLicenses pl
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
                       FROM app_fd_pro_organizations";
               $GetQuery_op2 = dbi_query($sql_op2);
               $qryResult_op2=$GetQuery_op2->fetch_assoc();
               $org_name = $qryResult_op2['c_name'];
            }
        ?>

   <div class="small-12 medium-6 large-6 cell content-right <?php echo $orgproc_hide; ?>">
      <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back
      </div>
	  <h2 class="sub">Organisation Procedures<br /><span class="small"><?php echo $org_name; ?></span></h2>
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
                    <?php for ($i=0; $i<count($arr_op); $i++)
                      { $opid = $arr_op[$i]['opid'];
                        $c_procedureId = $arr_op[$i]['id'];
                        $c_description = $arr_op[$i]['c_description'];
                        $c_procedureId = $arr_op[$i]['c_procedureId'];
                    ?>
    		    <tr>
                      <td><?php echo $c_procedureId; ?> </td>
                      <td><?php echo $c_description; ?></td>
                      <td class="align-middle text-right"><a href="functions.php?f=delete_orgproc&opid=<?php echo $opid; ?>&m=orgproc&org_id=<?php echo $org_id; ?>"><i class="fi-trash sort-icon float-right"></i></a></td>
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

        <!-- PROCADD SECTION -->
        <?php
           if ($mode=="procadd")
           {
              // get procedures for this org so far
              $sql_pa = "SELECT pe.id
                         FROM app_fd_pro_procEpisodes pe, app_fd_pro_procLicenses pl
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
                        FROM app_fd_pro_procEpisodes pe
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

               // get org name
               $sql_pa2 = "SELECT *
                       FROM app_fd_pro_organizations";
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
                  <?php for ($i=0; $i<count($arr_all_procs); $i++)
                    { $pid = $arr_all_procs[$i]['id'];
                      $c_procedureId = $arr_all_procs[$i]['c_procedureId'];
                      $c_description = $arr_all_procs[$i]['c_description'];
                      if (!in_array($pid, $arr_org_procedures)) {
                  ?>
                  <tr>
                    <td><?php echo $c_procedureId; ?></td>
                    <td><?php echo $c_description; ?></td>
                    <td class="align-middle text-right"><a href="functions.php?m=add_proc_to_temp&pid=<?php echo $pid; ?>&org_id=<?php echo $org_id; logMsg(">>>>> $org_id <<<<<",$logfile); ?>&i=<?php echo $i; ?>"><font style="font-size:15;"> + </font></a></td>
                  </tr>
                  <?php } // end if
                  } // end for      
                  ?>
                  <tr>
                     <td colspan="3" class="text-left"><hr /></td>
                  </tr>
                     <?php $arr_add_procs = $_SESSION['arr_add_procs'];
                            for ($t=0; $t<count($arr_add_procs); $t++) {
                                 $arr_proc_info = get_proc_by_id($arr_add_procs[$t]['id']);
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
   <!-- End PROCADD SECTION Content --> 
  <!-- End Right-Content --> 
  <!-- </div> -->
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
      </script>  
   </body>
</html>
