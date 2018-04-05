<!doctype html>
<?php
// ***************************************
// admin/users.php
// 2017 Copyright, Mesh Integration LLC
// 12/19/17 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"ADMIN")
{
   header("Location: /ui/login.php");
   exit();
}

$logfile = "wel.log";

$mode = get_query_string('m');
$id = get_query_string('id');
if ($mode=="")
{
   $add_hide = "";
   $update_hide = "hide";
   $reset_hide = "hide";
   $delete_hide = "hide";
}
else if ($mode=="update")
{
   $add_hide = "hide";
   $update_hide = "";
   $reset_hide = "hide";
   $delete_hide = "hide";
   $user_id = $id;
}
else if ($mode=="reset")
{
   $add_hide = "hide";
   $update_hide = "hide";
   $reset_hide = "";
   $delete_hide = "hide";
   $user_id = $id;
}
else if ($mode=="delete")
{
   $add_hide = "hide";
   $update_hide = "hide";
   $reset_hide = "hide";
   $delete_hide = "";
   $user_id = $id;
}

$sql = "SELECT u.*, ug.groupid 
        FROM dir_user u, dir_user_role ur, dir_user_group ug
        WHERE u.id=ur.userid
        AND u.id = ug.userid
        AND ur.roleId='ROLE_USER'";
$GetQuery = dbi_query($sql);
$i=0;
$arr_users = array();
while ($qryResult=$GetQuery->fetch_assoc())
{
   $arr_users[$i]=$qryResult;
   $i++;
}

// need to change according to session
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
  <title>Add Verify User</title>
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
         <?php include "../includes/admin_header.php"; ?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell return">
	  <div><a href="users.php"><img src="../img/icons/back_white.png" alt="less than icon" class="" />Back to Dashboard</a></div>
  	</div>
	<div class="cell page-title-blue">USER ADMINISTRATION</div>
  </div>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="100%" border="0"  class="su-table stack">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>Bulk actions</span></a></span>
			  <span class="float-right"><a href="#"><span>Sort by</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
          <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width="">User</td>
			<td width="">Admin</td>
			<td width="">Surgeon</td>
			<td width=""></td>
          </tr>
          
          <?php for ($i=0; $i<count($arr_users); $i++)
                {
                   $uid=$arr_users[$i]['id'];
                   $firstName=$arr_users[$i]['firstName'];
                   $lastName=$arr_users[$i]['lastName'];
                   $full_name = strtoupper($lastName.", ".$firstName);
                   $email = $arr_users[$i]['email'];
                   $is_admin = $is_surgeon = false;
                   if (strtolower($arr_users[$i]['groupid'])=="admin")
                      $is_admin = true;
                   if (strtolower($arr_users[$i]['groupid'])=="surgeon")
                      $is_surgeon = true;
          ?>
		  <tr>
            <td width=""><input type="checkbox" name=""></td>
            <td width=""><p class="name"><?php echo $full_name; ?><br /><span class="small"><?php echo $email; ?></span></p></td>
			<td width=""><input type="checkbox" name="is_admin" <?php if ($is_admin) echo "checked"; ?>></td>
			<td width=""><input type="checkbox" name="is_surgeon" <?php if ($is_surgeon) echo "checked"; ?>></td>
			<td width=""><a href="users.php?m=update&id=<?php echo $uid; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></a></td>
          </tr>
          <?php } ?>
		  <tr>
		    <td colspan="5">
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
	<!-- ADD USER SECTION -->  
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h2>Add Verify User</h2>
	  <form action="users_a.php" method="post">
  		<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
      		<div class="small-12 medium-12 large-12 cell">
        	  <label>First Name
                <input type="text" name="fname" >
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>Surname
                <input type="text" name="lname" >
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>Email Address
                <input type="text" name="email" >
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">
			  <div class="grid-x grid-padding-x">
                <fieldset class="small-12 medium-12 large-12 cell">
    			  <input id="checkbox1" type="checkbox" name="is_surgeon" value="1"><label for="checkbox1">Is a surgeon</label>
    			  <input id="checkbox2" type="checkbox" name="is_admin" value="1"><label for="checkbox2">Is a system administrator</label>
  				</fieldset>
			  </div>
			</div>
			<div class="small-12 medium-12 large-12 cell">
        	  <label>GMC Number
                <input type="text" name="gmc_number" >
              </label>
            </div>
			<div class="small-12 medium-12 large-12 cell">&nbsp;</div>
			<div class="small-12 medium-12 large-12 cell text-center">
			  <input type="submit" name="" value="Add User" class="button large" />
            </div>
    	  </div>
  		</div>
	  </form>
	  <div class="divide">
	  <h2>Bulk Edit</h2>
	  <form>
  		<div class="grid-container">
    	  <div class="grid-x grid-padding-x">
      	    <div class="small-12 medium-12 large-12 cell">
              <div class="grid-x">
                <div class="small-7 cell">
				  <label>CSV File	
                  <input type="text" placeholder="">
				  </label>
                </div>
				<div class="small-2 cell">&nbsp;</div>
                <div class="small-3 cell no-label">
                  <input type="button" name="" value="browse" class="button postfix expanded grey"></a>
                </div>
              </div>
            </div>
    	  </div>
  		</div>
	  </form>
	  </div>
	</div>  
    <!-- UPDATE USER SECTION -->
        <?php if ($mode=="update")
              {
                 $sql_u = "SELECT u.*, ug.groupid 
                           FROM dir_user u, dir_user_group ug
                           WHERE u.id = '$user_id'
                           AND ug.userid=u.id";
                 $GetQuery_u = dbi_query($sql_u);
                 $qryResult_u=$GetQuery_u->fetch_assoc();
                 $firstName=$qryResult_u['firstName'];
                 $lastName=$qryResult_u['lastName'];
                 $email = $qryResult_u['email'];
                 $gmc_number = $qryResult_u['gmc_number'];
                 $is_admin = $is_surgeon = false;
                 if (strtolower($qryResult_u['groupid'])=="admin")
                      $is_admin = true;
                 if (strtolower($qryResult_u['groupid'])=="surgeon")
                      $is_surgeon = true;
              }
         ?>

        <div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
          <h2>View User</h2>
          <form action="users_a.php?m=update&id=<?php echo $user_id; ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell">
                  <label>First Name
                <input type="text" name="fname" value="<?php echo $firstName; ?>" >
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Surname
                <input type="text" name="lname" value="<?php echo $lastName; ?>" >
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Email Address
                <input type="text" name="email" value="<?php echo $email; ?>" >
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                          <div class="grid-x grid-padding-x">
                <fieldset class="small-12 medium-12 large-12 cell">
                          <input id="checkbox1" type="checkbox" value="1" name="is_surgeon" <?php if ($is_surgeon) echo "checked"; ?>><label for="checkbox1">Is a surgeon</label>
                          <input id="checkbox2" type="checkbox" value="1" name="is_admin" <?php if ($is_admin) echo "checked"; ?>><label for="checkbox2">Is a system administrator</label>
                                </fieldset>
                          </div>
                        </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>GMC Number
                <input type="text" name="gmc_number" value="<?php echo $gmc_number; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">&nbsp;</div>
                        <div class="small-12 medium-12 large-12 cell text-center">
                        <button type="submit"  class="button large" />Update user</button><br /><br />
                          <a href="users.php?m=reset&id=<?php echo $user_id; ?>" class="button large inactive" />Reset Password</a><br /><br />
                          <a href="users.php?m=delete&id=<?php echo $user_id; ?>" class="button large red" />Delete User</a>
            </div>
          </div>
                </div>
          </form>
        </div>
    <!-- END VIEW USER -->
    <!-- RESET PW SECTION -->
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $reset_hide; ?>">
          <form>
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                        <div class="small-12 medium-12 large-12 cell text-center">
                          <h3>Are you sure you wish to reset the password?</h3>
                          <p>The user will be asked to enter a new password the next time they login to Verify.</p>   
            </div>
                        <div class="small-12 medium-12 large-12 cell text-center">
                          <div class="grid-x">
                            <div class="small-3">&nbsp;</div>
                                <div class="small-6"><br>
                  <input type="button" name="" value="No" class="button large expanded inactive" />
                                  <input type="button" name="" value="CONFIRM RESET" class="button large expanded" />
                                </div>
                                <div class="small-3">&nbsp;</div>
                          </div>
                          <p>&nbsp;</p>
                        </div>
          </div>
                </div>
          </form>
        </div>
    <!-- End RESET PW SECTION -->
    <!-- DELETE USER SECTION -->
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $delete_hide; ?>">
          <form>
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                        <div class="small-12 medium-12 large-12 cell text-center">
                          <h3>Are you sure you wish to delete this user?</h3>
                          <p>This will not affect any patient data, but the user will no longer be able to access the system.</p>
            </div>
                        <div class="small-12 medium-12 large-12 cell text-center">
                          <div class="grid-x">
                            <div class="small-3">&nbsp;</div>
                                <div class="small-6"><br>
                  <input type="button" name="" value="No" class="button large expanded inactive" />
                  <input type="button" name="" value="Confirm Delete" class="button large red expanded" />
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
    <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script>
      $( function() {
      $( "#sortable" ).sortable({
        placeholder: "ui-state-highlight"
      });
      $( "#sortable" ).disableSelection();
      } );
    </script>
	<script>
        jQuery(document).ready(function() {
          // This button will increment the value
          $('[data-quantity="plus"]').click(function(e){
          // Stop acting like a button
          e.preventDefault();
          // Get the field name
          fieldName = $(this).attr('data-field');
          // Get its current value
          var currentVal = parseInt($('input[name='+fieldName+']').val());
          // If is not undefined
          if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
          } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
         }
    });
         // This button will decrement the value till 0
         $('[data-quantity="minus"]').click(function(e) {
         // Stop acting like a button
         e.preventDefault();
         // Get the field name
         fieldName = $(this).attr('data-field');
         // Get its current value
         var currentVal = parseInt($('input[name='+fieldName+']').val());
         // If it isn't undefined or its greater than 0
         if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
         } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
         }
       });
     });
     </script>  
   </body>
</html>
