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
$logfile = "superuser.log";

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
  <link rel="stylesheet" href="../css/new_eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
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
      <table width="90%" border="0"  class="su-table stack">
  	    <tbody>
		  <tr>  
		    <td colspan="3">
			  <a class="button fc">Bulk Actions<img src="../img/icons/add_light.png" alt="add icon" class="fc_add"/></a>&nbsp;&nbsp;
			  <a class="button fc">Sort By<img src="../img/icons/add_white.png" alt="add icon" class="fc_add"/></a>
			</td>
          </tr>
          <tr>
            <td><input type="checkbox"></td>
            <td colspan="2">Procedure</td>
          </tr>
          <?php
            while ($qryResult=$GetQuery->fetch_assoc()) {
               $list_id = $qryResult['id'];
               $firstName = $qryResult['firstName'];
               $lastName = $qryResult['lastName'];
               $email = $qryResult['email'];
			   
			   $isSelected = '';
			   if ($list_id == $id) {
				   $isSelected = ' class="selected"';
			   }
          ?>
	  <tr<?php echo $isSelected; ?>>
        <td><input type="checkbox"></td>
	    <td class='clickable-row su_data' data-href='users.php?m=update&id=<?php echo $list_id; ?>'><p><span class="uc"><?php echo $lastName; ?>, <?php echo $firstName; ?></span><br /><?php echo $email; ?></p></td>
	    <td><a href="users.php?m=update&id=<?php echo $list_id; ?>"><img src="../img/icons/greater.png" alt="icon" class="align-right" /></a></td>
	  </tr>
         <?php } ?>
         <?php 
            $sql = "SELECT u.*
                    FROM ".DB_PREFIX."user u, ".DB_PREFIX."user_role ur
                    WHERE u.id=ur.userid
                    AND ur.roleId='ROLE_ADMIN'";
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
 	<!-- ADD USER SECTION -->  
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h3>Add SuperUser</h3>
	  <form id="add_form"  action="users_a.php?m=add" method="post" data-abide novalidate>
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
                   <input type="text" id="add_email" name="email" placeholder="">
                   <span class="form-error">Email allready exist</span>
                </label>
            </div>          
            <div class="small-12 cell field password-field">
                <label>Password
                <input type="password" id="password"  name="password" placeholder="" required>
              </label>
            </div>
            <div class="small-12 cell field password-confirmation-field">
                  <label>Retype Password
                <input type="password" placeholder="" required data-equalto="password">
 <small class="form-error">The password did not match</small>
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
    	  <div class="grid-x">
      	    <div class="small-12 cell field">
        	  <label>First Name
                <input type="text" name="firstName" value="<?php echo $firstName; ?>">
              </label>
            </div>
      	    <div class="small-12 cell field">
        	  <label>Surname
                <input type="text" name="lastName" value="<?php echo $lastName; ?>">
              </label>
            </div>
      	    <div class="small-12 cell field">
        	  <label>E-mail Address
                <input type="text" name="email" value="<?php echo $email; ?>">
              </label>
            </div>
			<div class="small-12 cell field text-center">
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
            $("#add_email").change(function(){
               var email_val = $("#add_email").val();
               if(email_val.length==0){              
                  $("#add_email").next("span").html("Email must not be empty");
                  return false;
               }
 
               $.ajax({ 
                  url: "./ajax/validate_data.php",
                  method: "POST",
                  data: {type: 'validate_email', email: 'email_val'},
                  dataType: "HTML",
               }).done(function(response){
                  // once ajax is completed
                  if(response){
                     // email exist
                     $("#add_form").foundation("addErrorClasses",$("#add_email"));
                     return false;
                  }else{
                     // email not exist
                     console.log("email does not exitst");
                     return true;
                  }
               });
            });
         });
      </script>  
   </body>
</html>
