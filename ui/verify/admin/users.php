<!doctype html>
<?php
// ***************************************
// admin/users.php
// 2017 Copyright, Mesh Integration LLC
// 12/19/17 - WEL
// 03/14/18 - SD - add paggination value in to session
// ***************************************
require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role != "ADMIN") {
	header ( "Location: /ui/verify/login.php" );
	exit ();
}

$logfile = "admin.log";

$mode = get_query_string ( 'm' );
$id = get_query_string ( 'id' );

// turn everythign off
$add_hide = "hide";
$update_hide = "hide";
$reset_hide = "hide";
$delete_hide = "hide";

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
}

$script_name = substr ( strrchr ( $_SERVER ['PHP_SELF'], "/" ), 1 );

if ((isset ( $_GET ['page'] ) && ! empty ( $_GET ['page'] ))) {
	$page = $_GET ['page'];
	$start = ($page - 1) * $row;
} else if (isset ( $_SESSION ['page'] [$script_name] ['no'] ) && ! empty ( $_SESSION ['page'] [$script_name] ['no'] )) {
	$page = $_SESSION ['page'] [$script_name] ['no'];
	$start = ($page - 1) * $row;
}
$_SESSION ['page'] [$script_name] ['no'] = $page;

$sql = "SELECT u.*, ug.groupid
        FROM dir_user u, dir_user_role ur, dir_user_group ug
        WHERE u.id=ur.userid
        AND u.id = ug.userid
        AND ur.roleId='ROLE_USER' 
        ORDER BY u.lastName
        LIMIT $start, $row";
$GetQuery = dbi_query ( $sql );
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
<title>Add Verify User</title>
<link rel="stylesheet" href="../css/foundation.css">
<link rel="stylesheet" href="../css/dashboard_admin.css">
<link rel="stylesheet" href="../css/eido_admin.css">
<link rel="stylesheet" href="../css/app.css">
<link
	href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css"
	rel="stylesheet">
<link
	href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css"
	rel="stylesheet" type="text/css">
<link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
	<div class="grid-container">
		<!-- Start Header -->
  <?php include "../includes/admin_header.php"; ?>
  <!-- End Header -->
		<!-- Start Title Bar & Navigation -->
		<div class="grid-x padding-x">
			<div class="cell page-title">User Administration</div>
		</div>
		<!-- End Title Bar & Navigation -->
		<!-- Start Content 
		<div class="grid-x su" data-equalizer data-equalize-on="medium">
		-->
			<!-- Start Content-Left -->
			<div class="content-left">
				<table style="border: 0" class="su-table stack">
					<tbody>
  	   <?php include "../includes/admin_bulkActions.php"; ?>
	  	</tbody>
				</table>
			</div>
		<!-- </div>  -->
		<div class="grid-x su" data-equalizer data-equalize-on="medium">
			<!-- Start Content-Left -->
			<div class="small-12 medium-6 large-6 cell content-left">
				<form name="userlistfrm" id="userlistfrm"
					action="bulk_pwd_delete_a.php.php?m=pwd" method="post">
					<table style="width: 100%" class="su-table stack">
						<tbody>
							<tr>
								<td><input type="checkbox" name="actOnAll" id="actOnAll"></td>
								<td>User</td>
								<td>Admin</td>
								<td>Surgeon</td>
								<td>&nbsp;</td>
							</tr>
		          <?php
												
					for($i = 0; $i < count ( $arr_users ); $i ++) {
						$uid = $arr_users [$i] ['id'];
						
						$firstName = ucfirst ( strtolower ( $arr_users [$i] ['firstName'] ) );
						$lastName = strtoupper ( $arr_users [$i] ['lastName'] );
						$full_name = $lastName . ", " . $firstName;
						
						$email = $arr_users [$i] ['email'];
						$is_admin = $is_surgeon = false;
						if (strtolower ( $arr_users [$i] ['groupid'] ) == "admin")
							$is_admin = true;
						if (strtolower ( $arr_users [$i] ['groupid'] ) == "surgeon")
							$is_surgeon = true;
					?>
				  <tr>
						<td><input type="checkbox"  name="performAction<?php $i?>"id="performAction<?php $i?>"></td>
						<td class="clickable-row su_data"
							data-href="users.php?m=update&id=<?php echo $uid; ?>"><p>
								<span class="uc"><?php echo $full_name; ?></span><br /><?php echo $email; ?></p></td>
						<td class="clickable-row"
							data-href="users.php?m=update&id=<?php echo $uid; ?>"><input
							type="checkbox" name="is_admin"
							<?php if ($is_admin) echo "checked"; ?>></td>
						<td class="clickable-row"
							data-href="users.php?m=update&id=<?php echo $uid; ?>"><input
							type="checkbox" name="is_surgeon"
							<?php if ($is_surgeon) echo "checked"; ?>></td>
						<td><a href="users.php?m=update&id=<?php echo $uid; ?>"><img
								src="../img/icons/greater.png" alt="greater than icon"
								class="align-right" /></a></td>
					</tr>
		          <?php } ?>
	               <?php
						 $sql = "SELECT u.*, ug.groupid
	                     FROM dir_user u, dir_user_role ur, dir_user_group ug
	                     WHERE u.id=ur.userid
	                     AND u.id = ug.userid
	                     AND ur.roleId='ROLE_USER'";
						$GetQuery = dbi_query ( $sql );
						$totalRecord = $GetQuery->num_rows;
						$pagination = get_pagination ( $page, $totalRecord );
					?>
		  	    		</tbody>
					</table>
				</form>

				<div class="grid grid-x text-center">
					<div class="small-12 pagination-btm-users"><?php echo $pagination; ?></div>
				</div>
			</div>

			<!-- End Content-Left -->
			<!-- Start Content-Right -->
			<!-- ADD USER SECTION -->
			<div
				class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
				<!--  <h3>Add Verify User</h3>  -->
				<div class="section-title">Add Verify User</div>
				<form action="users_a.php?m=add" method="post">
					<div class="grid-container">
						<div class="grid-x">
							<div class="small-12 cell field">
								<label class="weight-normal">First Name <input type="text"
									name="fname">
								</label>
							</div>
							<hr />
							<div class="small-12 cell field">
								<label class="weight-normal">Surname <input type="text"
									name="lname">
								</label>
							</div>
							<div class="small-12 cell field">
								<label class="weight-normal">Email Address <input type="text"
									name="email">
								</label>
							</div>
							<div class="small-12 cell field">
								<div class="grid-x grid-padding-x">
									<fieldset class="small-12 medium-12 large-12 cell">
										<input class="user-checkbox1" type="checkbox"
											name="is_surgeon" value="1"><label class="weight-normal"
											for="checkbox1">Is a surgeon</label> <input
											class="user-checkbox2" type="checkbox" name="is_admin"
											value="1"><label class="weight-normal" for="checkbox2">Is a
											system administrator</label>
									</fieldset>
								</div>
							</div>
							<div class="small-12 cell field">
								<label class="weight-normal">GMC Number <input type="text"
									name="gmc_number">
								</label>
							</div>
							<div class="small-12 cell">&nbsp;</div>
							<div class="small-12 cell field text-center">
								<input type="submit" name="" value="Add User"
									class="button addusr" />
							</div>
						</div>
					</div>
				</form>
				<div class="divide">
					<div class="section-title">Bulk Edit</div>
					<form>
						<div class="grid-container">
							<div class="grid-x">
								<div class="small-12 cell field">
									<div class="grid-x">
										<div class="small-7 cell">
											<label>CSV File <input type="text" placeholder="">
											</label>
										</div>
										<div class="small-2 cell">&nbsp;</div>
										<div class="small-3 cell">
											<!--  <input type="button" name="" value="browse" class="a.button_users postfix expanded grey"></a> -->
											<input type="button" name="" value="browse"
												class="button_users_browse postfix expanded">
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- UPDATE USER SECTION -->
        <?php
								
								if ($mode == "update") {
									$sql_u = "SELECT u.*, ug.groupid 
                           FROM dir_user u, dir_user_group ug
                           WHERE u.id = '$user_id'
                           AND ug.userid=u.id";
									$GetQuery_u = dbi_query ( $sql_u );
									$qryResult_u = $GetQuery_u->fetch_assoc ();
									$firstName = $qryResult_u ['firstName'];
									$lastName = $qryResult_u ['lastName'];
									$email = $qryResult_u ['email'];
									$gmc_number = $qryResult_u ['gmc_number'];
									$is_admin = $is_surgeon = false;
									if (strtolower ( $qryResult_u ['groupid'] ) == "admin")
										$is_admin = true;
									if (strtolower ( $qryResult_u ['groupid'] ) == "surgeon")
										$is_surgeon = true;
								}
								?>

        <div
				class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
				<h3>View User</h3>
				<form action="users_a.php?m=update&id=<?php echo $user_id; ?>"
					method="post">
					<div class="grid-container">
						<div class="grid-x">
							<div class="small-12 cell field">
								<label>First Name <input type="text" name="fname"
									value="<?php echo $firstName; ?>">
								</label>
							</div>
							<div class="small-12 cell field">
								<label>Surname <input type="text" name="lname"
									value="<?php echo $lastName; ?>">
								</label>
							</div>
							<div class="small-12 cell field">
								<label>Email Address <input type="text" name="email"
									value="<?php echo $email; ?>">
								</label>
							</div>
							<div class="small-12 cell field">
								<div class="grid-x grid-padding-x">
									<fieldset class="small-12 medium-12 large-12 cell">
										<input id="user-checkbox1" type="checkbox" value="1"
											name="is_surgeon" <?php if ($is_surgeon) echo "checked"; ?>>
										<label for="checkbox1">Is a surgeon</label> <input
											id="user-checkbox2" type="checkbox" value="1" name="is_admin"
											<?php if ($is_admin) echo "checked"; ?>> <label
											for="checkbox2">Is a system administrator</label>
									</fieldset>
								</div>
							</div>
							<div class="small-12 cell field">
								<label>GMC Number <input type="text" name="gmc_number"
									value="<?php echo $gmc_number; ?>">
								</label>
							</div>
							<div class="small-12 cell field">&nbsp;</div>
							<div class="small-12 cell field text-center">

								<button type="submit" class="button large">Update User</button>
								<br /> <br /> <a
									href="users.php?m=reset&id=<?php echo $user_id; ?>"
									class="button large inactive">Reset Password</a><br /> <br /> <a
									href="users.php?m=delete&id=<?php echo $user_id; ?>"
									class="button large red">Delete User</a>

							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- END VIEW USER -->

			<!-- RESET PW SECTION -->
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
				<!-- End RESET PW SECTION -->
				<!-- DELETE USER SECTION -->
				<div
					class="small-12 medium-6 large-6 cell content-right <?php echo $delete_hide; ?>">
					<form name="deleteuserfrm">
						<div class="grid-container">
							<div class="grid-x grid-padding-x">
								<div class="small-12 medium-12 large-12 cell text-center">
									<h3>Are you sure you wish to delete this user?</h3>
									<p>This will not affect any patient data, but the user will no
										longer be able to access the system.</p>
								</div>
								<div class="small-12 medium-12 large-12 cell text-center">
									<div class="grid-x">
										<div class="small-3">&nbsp;</div>
										<div class="small-6">
											<br> <a href="users.php?m=main"><input type="button" name=""
												value="No" class="button large expanded inactive" /></a> <a
												href="users_a.php?m=delete&id=<?php echo $user_id; ?>"> <input
												type="button" name="" value="Confirm Delete"
												class="button large red expanded" /></a>
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
	<script src="../js/util.js"></script>
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
          $(".clickable-row").click(function() {
            window.location = $(this).data("href");
          });

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
