<!doctype html>
<?php
// ***************************************
// superuser/organisations.php
// 2017 Copyright, Mesh Integration LLC
// 12/14/17 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"SUPERUSER")
{
   header("Location: /ui/login.php");
   exit();
}
session_start();
$logfile = "wel.log";

$mode = get_query_string('m');
$id = get_query_string('id');
if ($mode=="")
{
   $add_hide = "";
   $update_hide = "hide";
   $orgproc_hide = "hide";
   $procadd_hide = "hide";
   $overview_hide = "hide";
}
else if ($mode=="update")
{
   $add_hide = "hide";
   $update_hide = "";
   $orgproc_hide = "hide";
   $overview_hide = "hide";
   $procadd_hide = "hide";
   $org_id=$id;
}
else if ($mode=="orgproc")
{
   $add_hide = "hide";
   $update_hide = "hide";
   $orgproc_hide = "";
   $overview_hide = "hide";
   $procadd_hide = "hide";
   $org_id = $id;
}
else if ($mode=="overview")
{
   $add_hide = "hide";
   $update_hide = "hide";
   $orgproc_hide = "hide";
   $overview_hide = "";
   $procadd_hide = "hide";
   $org_id = $id;
}
else if ($mode=="procadd")
{
   $add_hide = "hide";
   $update_hide = "hide";
   $orgproc_hide = "hide";
   $overview_hide = "hide";
   $procadd_hide = "";
   $org_id = $id;
}

$sql = "SELECT *
        FROM app_fd_pro_organizations";
$GetQuery = dbi_query($sql);

if (isset($_GET['page']) && !empty($_GET['page'])) 
{
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}

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
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
     <?php include "../includes/header.php"; ?> 
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li><a href="users.php">Users</a></li>
		<li class="current"><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System status</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
                <li><a href="users.php">Users</a></li>
		<li class="current"><a href="organisations.php">Organisations</a></li>
		<li><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
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
                 <td width="10%"><input type="checkbox"></td>
                 <td width="80%">&nbsp;</td>
		 <td width="10%">&nbsp;</td>
               </tr>
               <?php while ($qryResult=$GetQuery->fetch_assoc())
                     {
                        $id=$qryResult['id'];
                        $c_name=$qryResult['c_name'];
                        $c_type=$qryResult['c_type'];
               ?>
	       <tr>
                  <td width="10%"><input type="checkbox"></td>
	          <td width="80%"><p class="name"><a href="organisations.php?m=overview&id=<?php echo $id; ?>"><?php echo $c_name; ?><br /><span class="small"><?php echo $c_type; ?></span></a></p></td>
	          <td width="10%"><a href="organisations.php?m=overview&id=<?php echo $id; ?>"><img src="../img/icons/greater.png" alt="icon" class="float-right" /></a></td>
	       </tr>
              <?php } ?>
  	    </tbody>
         </table>
      </div>
      <!-- End Content-Left -->  
	<!-- Start Content-Right -->  
	<!-- ADD SECTION -->  
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h2>Add Organisation</h2>
	  <form action="organisations_a.php?m=add" method="post" enctype="multipart/form-data">
  	<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
      	    <div class="small-12 medium-12 large-12 cell">
        	  <label>Name
                <input type="text" name="name" placeholder="">
              </label>
            </div>
      		<div class="small-12 medium-12 large-12 cell">
        	  <label>Administrator Contact
                <input type="text" name="admin"  placeholder="">
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>E-mail Address
                <input type="text" name="email" placeholder="">
              </label>
            </div>
	<div class="small-12 medium-12 large-12 cell">
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
                <p>&nbsp;</p>
                YES&nbsp;&nbsp;<input type="radio" name="subdivision" value="YES" placeholder="">
                NO&nbsp;&nbsp;<input type="radio" name="subdivision" value="NO" placeholder="">
                &nbsp;&nbsp;<b>Organisation has subdivisions?i</b>
        </div>
         </div>
			<div class="small-12 medium-12 large-12 cell text-center">
        	  <br /><input type="submit" id="add" class="button large" value="ADD ORGANISATION">
            </div>
    	  </div>
  		</div>
	  </form>
	</div>  

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
                         <center><a href="organisations.php?m=update&id=<?php echo $org_id; ?>" class="button large">Edit Organisation</a></center>
                     </div>
                  </div>
               </div>
            </div>

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

			<div class="small-12 medium-12 large-12 cell option">
			  <p class="name"><a href="organisations.php?m=proc&id=<?php echo $org_id; ?>" class="no_u"><img src="../img/icons/greater.png" alt="icon" class="float-right" />Manage Procedures</a></p>
			</div>  
			<div class="small-12 medium-12 large-12 cell text-center">
        	  <br /><input type="submit" id="update" class="button large" value="UPDATE ORGANISATION">
            </div>
    	  </div>
  		</div>
	  </form>
	</div>  

	<!-- ORG PROCEDURE SECTION -->  
        <?php
           if ($mode=="orgproc")
           {
              $sql_op = "SELECT p.*, op.id as opid
                         FROM app_fd_pro_procedures p, app_fd_pro_org_procedures op
                         WHERE op.org_id='$org_id'
                         AND op.proc_id = p.id";
               $GetQuery_op = dbi_query($sql_op);
               $arr_op = array();
               $i=0;
               while ($qryResult=$GetQuery_op->fetch_assoc())
               {
                  $arr_op[$i]=$qryResult;
                  $i++;
               }
            }
        ?>

   <div class="small-12 medium-6 large-6 cell content-right <?php echo $orgproc_hide; ?>">
      <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back
      </div>
	  <h2 class="sub">Organisation Procedures<br /><span class="small"><?php echo $_SESSION['org_name']; ?></span></h2>
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
                        $c_procedureId = $arr_op[$i]['c_procedureId'];
                        $c_description = $arr_op[$i]['c_description'];
                    ?>
    		    <tr>
      		      <td><?php echo $c_procedureId; ?></td>
                      <td><?php echo $c_description; ?></td>
                      <td class="align-middle text-right"><a href="functions.php?f=delete_orgproc&opid=<?php echo $opid; ?>&m=orgproc&org_id=<?php echo $org_id; ?>"><i class="fi-trash sort-icon float-right"></i></a></td>
		    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
               </div> 
	       <div class="small-12 medium-12 large-12 cell text-center">
                    <a class="button large" href="organisations.php?m=procadd&id=<?php echo $org_id; ?>">>ADD PROCEDURES</a>
               </div>
    	    </div>
 	</div>
     </div>  

        <!-- PROCEDURE ADD SECTION -->
        <?php
           if ($mode=="procadd")
           {
              $sql_pa = "SELECT *
                        FROM app_fd_pro_procedures p
                        WHERE 1 ";
               $GetQuery_pa = dbi_query($sql_pa);
               $arr_pa = array();
               $i=0;
               while ($qryResult=$GetQuery_pa->fetch_assoc())
               {
                  $arr_pa[$i]=$qryResult;
                  $i++;
               }
            }
        ?>

   <div class="small-12 medium-6 large-6 cell content-right <?php echo $procadd_hide; ?>">
      <div class="back">
          <img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back
      </div>
      <h2 class="sub">Add Procedures<br /><span class="small"><?php echo $_SESSION['org_name']; ?></span></h2>
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
                  <?php for ($i=0; $i<count($arr_pa); $i++)
                    { $pid = $arr_pa[$i]['id'];
                      $c_procedureId = $arr_pa[$i]['c_procedureId'];
                      $c_description = $arr_pa[$i]['c_description'];
                  ?>
                  <tr>
                    <td><?php echo $c_procedureId; ?></td>
                    <td><?php echo $c_description; ?></td>
                    <td class="align-middle text-right"><a href="functions.php?f=delete_orgproc&opid=<?php echo $opid; ?>&m=orgproc&org_id=<?php echo $org_id; ?>"><font style="font-size:15;"> + </font></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
         </div>
         <div class="small-12 medium-12 large-12 cell text-center">
               <a class="button large" href="organisations.php?m=procadd&id=<?php echo $org_id; ?>">>ADD PROCEDURES</a>
         </div>
      </div>
   </div>
   <!-- End PROC ADD SECTION Content --> 
  <!-- End Right-Content --> 
  </div>
  </div>
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
