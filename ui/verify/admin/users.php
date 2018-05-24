<!doctype html>
<?php
// ***************************************
// admin/users.php
// 2017 Copyright, Mesh Integration LLC
// 12/19/17 - WEL
// 03/14/18 - SD - add paggination value in to session
// 4/26/18 - added bulk upload
// ***************************************
require_once '../utilities.php';
require_once "../alert_intruders.php";
session_start();
if ($user_role != "ADMIN") {
	header ( "Location: /ui/verify/login.php" );
	exit ();
}
$return_to = "adm";
$home = "users.php";
$logfile = "admin.log";
$mode = get_query_string ( 'm' );
$id = get_query_string ( 'id' );

logMsg("USERS Mode: $mode",$logfile);

// turn everythign off
$add_hide = "hide";
$update_hide = "hide";
$reset_hide = "hide";
$delete_hide = "hide";
$bulk_hide = "hide";
$bulkreset_hide = "hide";
$bulkdelete_hide = "hide";
if ($mode == "" || $mode == "add") {
	$add_hide = "";
} else if ($mode == "update") {
	$update_hide = "";
	$user_id = $id;
} else if ($mode == "view") {
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
} else if ($mode == "bulkreset") {
	$bulkreset_hide = "";
} else if ($mode == "bulkdelete") {
	$bulkdelete_hide = "";
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
        WHERE u.active=1
        AND u.id=ur.userid
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
        <?php include "../includes/admin_header.php"; ?>
        <!-- End Header -->
		<!-- Start Title Bar & Navigation -->
		        <div class="grid-x padding-x">
                    <div class="cell page-title">User Administration</div>
		        </div>
		<!-- End Title Bar & Navigation -->
		<!-- Start Content -->
    <div class="grid-x su">
        <!-- Start Content-Left -->
        <div class="small-12 medium-6 large-6 cell content-left">
            <div class="su-table stack large-12">
              
				<div class="grid-x grid-header" style="">
	                <div class="small-2 columns column-first" style="">
		                <label class="eido-checkbox">
			                <input class="eido-checkbox" style="margin-left:25px;" type="checkbox" name="actOnAll" id="actOnAll">
	                        <span class="checkmark" style="left:0px;"></span>
						</label>
	                </div>
					<div class="small-6 columns">
						<label style="margin-left: 10px;">User</label>
					</div>
					<div class="small-2 columns" style="left: -15px;">
						<label>Surgeon</label>
					</div>
					<div class="small-1 columns">
						<label style="left: -28px;">Admin</label>
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
					    $is_admin = $is_surgeon = false;
					    if (strtolower ( $arr_users [$i] ['groupid'] ) == "sitedivadmins") {
	                                       $is_admin = true;
	                                 }
					    if ( $arr_users [$i] ['isSurgeon'] == "1") {
						$is_surgeon = true;
					    }
					    $isSelected = '';
					    if($uid == $user_id) {
					        $isSelected = 'selected';
					    }
	                            ?>

	                            <li class="<?php echo $isSelected; ?>">
	                                <a href="users_a.php?m=gotoupdate&id=<?php echo $uid; ?>">
		                                <span class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
		                                <div class="grid-x">
                                            <div class="small-2 columns column-first">
                                                <label class="eido-checkbox">
                                                    <input type="checkbox" name="performAction[]" id="performAction<?php echo $i; ?>" value="<?php echo $uid; ?>">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
			                                <div class="small-6 columns">
				                                <p>
					                                <strong><?php echo $full_name; ?></strong><br/>
					                                <?php echo $email; ?>
				                                </p>
			                                </div>
			                                <div class="small-2 columns text-center">
				                                <label class="indicator-checkbox eido-checkbox">
					                                <input type="checkbox" name="is_surgeon"<?php if ($is_surgeon) echo "checked"; ?>>
					                                <span class="checkmark"></span>
				                                </label>
			                                </div>

			                                <div class="small-1 columns text-center">
				                                <label class="indicator-checkbox eido-checkbox">
					                                <input type="checkbox" name="is_admin"<?php if ($is_admin) echo "checked"; ?>>
					                                <span class="checkmark"></span>
				                                </label>
			                                </div>
		                                </div>

	                                </a>
	                            </li>
	                            <?php } ?>
	                        </ul>
	                </div>
		                    <?php
	                        $sql = "SELECT u.*, ug.groupId
		                    FROM dir_user u, dir_user_role ur, dir_user_group ug
		                    WHERE u.id=ur.userId
		                    AND u.id = ug.userId
                                    AND u.active=1
		                    AND ur.roleId='ROLE_USER'";

							$GetQuery = dbi_query ( $sql );
							$totalRecord = $GetQuery->num_rows;
							$pagination = get_pagination ( $page, $totalRecord );
	                        ?>
					        <div class="grid grid-x text-center row">
						        <div class="small-12 pagination-btm-users pagination-btm"><?php echo $pagination; ?></div>
					        </div>
	              </div>
            </div>
			<!-- End Content-Left -->
			<!-- Start Content-Right -->
	<!-- ADD USER SECTION -->
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
		<!--  <h3>Add Verify User</h3>  -->

		<form action="users_a.php?m=add" method="post">
            <div class="section-title">Add Verify User</div>
			<div class="grid-container">
				<div class="grid-x">
					<div class="small-12 cell field">
                                            <?php if ($_SESSION['add_firstname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                                                   else if ($_SESSION['add_firstname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
						<label class="weight-normal validated-field">First Name <input type="text" class="firstnamefield"
						value="<?php echo $_SESSION['add_afirstname']; ?>" name="firstname">
						</label>
					</div>
					<hr />
					<div class="small-12 cell field">
                                            <?php if ($_SESSION['add_lastname_error']) echo "<div class='lastnameval error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                                                   else if ($_SESSION['add_lastname_format_error']) echo "<div class='lastnameval error_message fi-alert'><strong>Please correct the last name</strong> - no special characters are allowed</div>"; ?>
						<label class="weight-normal validated-field">Surname <input type="text"  class="lastnamefield"
							value="<?php echo $_SESSION['add_alastname']; ?>" name="lastname">
						</label>
					</div>
					<div class="small-12 cell field">
                                            <?php if ($_SESSION['add_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                                                   else if ($_SESSION['add_bad_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                                                   else if ($_SESSION['add_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
						<label class="weight-normal validated-field">Email Address <input type="text" class="emailfield"
							value="<?php echo $_SESSION['add_aemail']; ?>" name="email">
						</label>
					</div>
					<div class="row small-12 grid-padding-x">
		<div class="float-left" style="margin-left:25px; margin-top:12px;">
			<label class="eido-checkbox">
			<input class="eido-checkbox user-checkbox1" type="checkbox" <?php if ($_SESSION['add_ais_surgeon']=="1") echo "checked"; ?> name="is_surgeon" value="1"><label class="weight-normal"
								for="checkbox1">Is a surgeon</label>
			<span class="checkmark"></span>
			</label>
		</div>
		<div class="float-right" style="margin-top:12px">
		
			<label class="eido-checkbox"> 
                        <input class="eido-checkbox user-checkbox2" type="checkbox" name="is_admin" value="1" <?php if ($_SESSION['add_ais_admin']=="1") echo "checked"; ?> >
                                        <label class="weight-normal" for="checkbox2" style="padding-right:25px;">Is a system administrator</label>
			<span class="checkmark"</span>
		</div>
					</div>
					<div class="small-12 cell field">
                                            <?php if ($_SESSION['add_gmc_number_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please enter the GMC Number</strong> - this is required for a surgeon</div>";
                                                   else if ($_SESSION['add_gmc_number_format_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please correct the GMC Number</strong> - no letters or special characters are allowed</div>";
                                                   else if ($_SESSION['add_gmc_number_length_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please correct the GMC Number</strong> - it should be 6 or 7 digits</div>";
                                                   else if ($_SESSION['add_gmc_number_duplicate_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please correct the GMC number</strong> - that GMC number already exists</div>"; ?>
						<label class="weight-normal validated-field">GMC Number <input type="text" class="gmcfield"
						value="<?php echo $_SESSION['add_agmc_number']; ?>" name="gmc_number">
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

				<div class="grid-container">
                    <div class="section-title" style="margin-left:20px;">Bulk Edit</div>
					<div class="grid-x">
						<div class="small-12 cell field">
							<div class="grid-x">
								<div class="small-2 cell">&nbsp;</div>
								<div class="small-8 cell text-center">
                                                                   <p>Add or remove users from the system using a CSV file</p>
                                    <a href="users.php?m=bulk" class="button large active expanded"><strong>Upload Users</strong></a>
								</div>
								<div class="small-2 cell">&nbsp;</div>
							</div>
						</div>
					</div>
				</div>
		    </div>
	    </div>
<!-- VIEW USER SECTION -->
        <?php
			if ($mode == "update") {
					$sql_u = "SELECT u.*, ug.groupId 
                       FROM dir_user u, dir_user_group ug
                       WHERE u.id = '$user_id'
                       AND ug.userId=u.id";
				$GetQuery_u = dbi_query ( $sql_u );
				$qryResult_u = $GetQuery_u->fetch_assoc ();
				$firstName = $qryResult_u ['firstName'];
				$lastName = $qryResult_u ['lastName'];
				$email = $qryResult_u ['username'];
				$gmc_number = $qryResult_u ['gmc_number'];
				$is_admin = $is_surgeon = false;
				if (strtolower ( $qryResult_u ['groupId'] ) == "sitedivadmins")
					$is_admin = true;
				if ( $qryResult_u ['isSurgeon'] == "1")
					$is_surgeon = true;

                if( !isset($_SESSION['update_firstname'])) {
                    $_SESSION['update_firstname'] = $firstName;
                }
                if( !isset($_SESSION['update_lastname'])) {
                    $_SESSION['update_lastname'] = $lastName;
                }
                if( !isset($_SESSION['update_email'])) {
                    $_SESSION['update_email'] = $email;
                }
                if( !isset($_SESSION['update_gmc_number'])) {
                    $_SESSION['update_gmc_number'] = $gmc_number;
                }
                if( !isset($_SESSION['update_is_surgeon'])) {
                    $_SESSION['update_is_surgeon'] = $is_surgeon;
                }
                if( !isset($_SESSION['update_is_admin'])) {
                    $_SESSION['update_is_admin'] = $is_admin;
                }

			/*	as surgeon can also be an admin, removed surgeon group from dir_user_group, surgeon role is now denoted by a boolean flag in dir_user table
				$is_admin = $is_surgeon = false;
				if (strtolower ( $qryResult_u ['groupid'] ) == "sitedivadmins")
					$is_admin = true;
				if (strtolower ( $qryResult_u ['groupid'] ) == "surgeon")
					$is_surgeon = true;
			*/
			}
			?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">

		<form action="users_a.php?m=update&id=<?php echo $user_id; ?>" method="post">
            <h3>View User</h3>
			<div class="grid-container">
				<div class="grid-x">
                    <div class="small-12 cell field">
                        <?php if ($_SESSION['add_firstname_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please enter your first name</strong> - this is required</div>";
                        else if ($_SESSION['add_firstname_format_error']) echo "<div class='firstnameval error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
                        <label class="weight-normal validated-field">First Name <input type="text" class="firstnamefield"
                                                                                       value="<?php echo $_SESSION['update_firstname']; ?>" name="firstname">
						</label>
				</div>
                    <div class="small-12 cell field">
                        <?php if ($_SESSION['add_lastname_error']) echo "<div class='lastnameval error_message fi-alert'><strong>Please enter the last name</strong> - this is required</div>";
                        else if ($_SESSION['add_lastname_format_error']) echo "<div class='lastnameval error_message fi-alert'><strong>Please correct the last name</strong> - no special characters are allowed</div>"; ?>
                        <label class="weight-normal validated-field">Surname <input type="text" class="lastnamefield"
                                                                                    value="<?php echo $_SESSION['update_lastname']; ?>" name="lastname">
				</label>
				</div>
                    <div class="small-12 cell field">
                    <?php if ($_SESSION['add_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please enter the email address</strong> - this is required</div>";
                    else if ($_SESSION['add_bad_email_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - enter a valid address</div>";
                    else if ($_SESSION['add_email_duplicate_error']) echo "<div class='emailval error_message fi-alert'><strong>Please correct the email address</strong> - that email address already exists</div>"; ?>
                    <label class="weight-normal validated-field">Email Address <input type="text" class="emailfield"
                                                                                      value="<?php echo $_SESSION['update_email']; ?>" name="email">
					</label>
				</div>
                		<div class="row small-12 grid-padding-x">
                		<div class="float-left" style="margin-left:25px; margin-top:12px;">
                        		<label class="eido-checkbox">
                        		<input class="eido-checkbox user-checkbox1" type="checkbox" <?php if ($_SESSION['update_gmc_number']) echo "checked"; ?> name="is_surgeon" value="1">
                        		<label class="weight-normal" for="checkbox1">Is a surgeon</label>
                        		<span class="checkmark"></span>
					</label>
                		</div>
                		<div class="float-right" style="margin-top:12px">
                        		<label class="eido-checkbox">
                        		<input class="eido-checkbox user-checkbox2" type="checkbox" name="is_admin" value="1" <?php if ($_SESSION['update_is_admin']) echo "checked"; ?> >
                        		<label class="weight-normal" for="checkbox2" style="padding-right:25px;">Is a system administrator</label>
                        		<span class="checkmark"</span>
                        		</label>
                		</div>
                		</div>

                    <div class="small-12 cell field">
                        <?php if ($_SESSION['add_gmc_number_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please enter the GMC Number</strong> - this is required for a surgeon</div>";
                        else if ($_SESSION['add_gmc_number_format_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please correct the GMC Number</strong> - no letters or special characters are allowed</div>";
                        else if ($_SESSION['add_gmc_number_length_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please correct the GMC Number</strong> - it should be 6 or 7 digits</div>";
                        else if ($_SESSION['add_gmc_number_duplicate_error']) echo "<div class='gmcval error_message fi-alert'><strong>Please correct the GMC number</strong> - that GMC number already exists</div>"; ?>
                        <label class="weight-normal validated-field">GMC Number <input type="text" class="gmcfield"
                                                                                       value="<?php echo $_SESSION['update_gmc_number']; ?>" name="gmc_number">
					</label>
				</div>
				<div class="small-12 cell field">&nbsp;</div>
				<div class="small-12 cell field text-center">
					<button type="submit" class="button large">Update User</button>
                </div>
			</div>
		</div>
	</form>
            <div class="small-12 cell field text-center" style="margin-top: -25px;">
                <br /> <br /> <a href="users.php?m=reset&id=<?php echo $user_id; ?>" class="button large inactive"><strong>Reset password</strong></a><br /> <br /> <a href="users.php?m=delete&id=<?php echo $user_id; ?>" class="button large red"><strong>Delete User</strong></a>
            </div>
        </div>
<!-- END VIEW USER -->
<!-- RESET USER PASSWORD SECTION -->
<div class="small-12 medium-6 large-6 cell content-right <?php echo $reset_hide; ?>">
        <form name="resetuserfrm">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <h3>Are you sure you wish to reset the password for this user?</h3>
                                        <p>The user will be asked to enter a new password the next time they log into Verify.</p>
                                </div>
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6">
                                                        <br> <a href="users.php?m=main"><input type="button" name="" value="No" class="button large expanded inactive" /></a>
                                                              <a href="users_a.php?m=reset&id=<?php echo $user_id; ?>"> <input type="button" name="" value="Confirm Reset" class="button large red expanded" /></a>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                        <p>&nbsp;</p>
                                </div>
                        </div>
                </div>
        </form>
</div>
<!-- End RESET USER PASSWORD SECTION -->
<!-- BULK DELETE USER SECTION (checkboxes) -->
<?php logMsg("USERS bulkdelete_hide: $bulkdelete_hide", $logfile); ?>
<div class="small-12 medium-6 large-6 cell content-right <?php echo $bulkdelete_hide; ?>">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <h3>Are you sure you wish to delete these users?</h3>
                                        <p>This will not affect any patient data, but the users will no
                                                longer be able to access the system.</p>
                                </div>
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6">
                                                        <br> <a href="users.php" class="button large expanded inactive" />No</a>
                                                              <a href="bulk_actions_a.php?actionRequested=delete"  class="button large red expanded" />Confirm Delete</a>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                    <div class="small-12 medium-8 cell">
                                        <form action="bulk_actions_a.php?actionRequested=reset" method="post" name="reset" class="bulk-action" id="ResetAction">
                                            <!--							<input type="hidden" name="users[]" value="" />-->
                                            <button class="button" type="submit">Force Password Reset</button>
                                        </form>
                                    </div>
                                    <div class="small-12 medium-8 cell">
                                        <form action="bulk_actions_a.php?actionRequested=delete" method="post" class="bulk-action" id="DeleteAction">
                                            <button class="button" type="submit">Delete User</button>
                                        </form>
                                    </div>
                                        <p>&nbsp;</p>
                                </div>
                        </div>
                </div>
</div>
<!-- End DELETE USER SECTION -->
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
			onclick="bulkaction(this)" data-close aria-label="Confirm reset"
			type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
</div>
<!-- End BULK RESET PW SECTION -->
<!-- DELETE USER SECTION -->
<div class="small-12 medium-6 large-6 cell content-right <?php echo $delete_hide; ?>">
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
								value="No" class="button large expanded inactive" /></a> 
                                                              <a href="users_a.php?m=delete&id=<?php echo $user_id; ?>"> <input type="button" name="" value="Confirm delete" class="button large red expanded" /></a>
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
<!-- BULK ADD/DELETE USER SECTION -->
<?php if ($mode=="bulk") $bulk_msg=$_SESSION['bulk_msg']; unset($_SESSION['bulk_msg']); ?>
<div class="small-12 medium-6 large-6 cell content-right <?php echo $bulk_hide; ?>">
      <div class="section-title">Bulk Edit Users</div>
               <form id="bulk_upload" name="bulk_upload" action="bulk_upload.php" method="post" enctype="multipart/form-data">
                <div class="grid-container">
                        <div class="grid-x grid-padding-x">
                                <div class="small-12 medium-12 large-12 cell text-center">
                                      <?php if (strlen($bulk_msg)) echo "<p><strong>$bulk_msg</strong></p>"; ?>
                                        <p>To add or remove users from the system in bulk, you may use a CSV from your admin system</p>
                                </div>
                                <div class="small-12 medium-12 large-12 cell text-center">
                                        <div class="grid-x">
                                                <div class="small-3">&nbsp;</div>
                                                <div class="small-6 text-center">
                                                    <label>CSV File
                                                    <input type="file" name="bulk_file" placeholder="">
                                                    </label>
                                                    <p>&nbsp;</p>
                                                    <input type="radio" name="action" value="A" checked />&nbsp;&nbsp;&nbsp;ADD USERS<br /><input type="radio" name="action" value="D" />&nbsp;&nbsp;&nbsp;REMOVE USERS
                                                    <p>&nbsp;</p>
                                                    <p><button type="submit" class="button active large expanded">Upload</button></p>
                                                </div>
                                                <div class="small-3">&nbsp;</div>
                                        </div>
                                </div>
                                <div class="small-12 medium-12 large-12 cell text-center">
                                     <p>Instructions:<br />
                                        Add/Update is one operation. Remove is another operation</p>
                                     <p>Create a CSV of all the users you would like to add.<br />
                                        Upload it in the field above.<br />
                                        Follow the instructions.</p>
                                     <p><a href="../includes/csv-sample.csv" target="_blank">Download a sample CSV file here</a></p>
                                </div>
                        </div>
                </div>
        </form>
</div>
<!-- End BULK ADD/DELETE USER SECTION -->
	<!-- End Content-Right -->
			</div>
		</div>
	<!-- End Content -->
	<!-- Start Footer -->
          	<?php include "../includes/footer.php"; ?>
  	<!-- End Footer -->

	<script src="../js/vendor/jquery.js"></script>
	<script src="../js/vendor/what-input.js"></script>
	<script src="../js/vendor/foundation.js"></script>
	<script src="../js/app.js"></script>
	<script src="../js/util.js"></script>
	<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
<!--	<script>-->
<!--      $( function() {-->
<!--      $( "#sortable" ).sortable({-->
<!--        placeholder: "ui-state-highlight"-->
<!--      });-->
<!--      $( "#sortable" ).disableSelection();-->
<!--      } );-->
<!--    </script>-->
	<script>
        jQuery(document).ready(function() {
          $(".clickable-row").on("click",function(){
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
	$(document).ready(function(){
		$('#actOnAll').click(function () {    
			$("[id^=performAction]").prop('checked', this.checked);    
		});
	});

       // $("#emailval div").attr("class", "");


        $(document).ready(function() {
                            $(".firstnamefield").on("focus",function () {
                                $(".firstnameval").removeClass("error_message");
                                $(".firstnameval").removeClass("fi-alert");
                                $(".firstnameval").hide();
                            })
                            $(".lastnamefield").on("focus",function () {
                                $(".lastnameval").removeClass("error_message");
                                $(".lastnameval").removeClass("fi-alert");
                                $(".lastnameval").hide();
                            })
                            $(".emailfield").on("focus",function () {
                                $(".emailval").removeClass("error_message");
                                $(".emailval").removeClass("fi-alert");
                                $(".emailval").hide();
                            })
                            $(".gmcfield").on("focus",function () {
                                $(".gmcval").removeClass("error_message");
                                $(".gmceval").removeClass("fi-alert");
                                $(".gmcval").hide();
            })
            $("#addpt").on("click",function () {
                $(".firstnameval").removeClass("error_message");
                $(".firstnameval").removeClass("fi-alert");
                $(".firstnameval").hide();
                $(".lastnameval").removeClass("error_message");
                $(".lastnameval").removeClass("fi-alert");
                $(".lastnameval").hide();
                $(".emailval").removeClass("error_message");
                $(".emailval").removeClass("fi-alert");
                $(".emailval").hide();
                $(".gmcval").removeClass("error_message");
                $(".gmceval").removeClass("fi-alert");
                $(".gmcval").hide();
            })

        });
	//$(document).ready(function(){
     //   	$("#adduser").on("click",function(){
	//	$.get("clearsession.php");
  	//	$("form div").removeClass("error_message");
  	//	$("form label").removeClass("error_message");
	//	$("form div").removeClass("fi-alert");
	//	$("form label").removeClass("fi-alert");
  	//	$("form")[0].reset();
	//	});
	// });
       // $(document).ready(function(){
        //    $(".validated-field").on("focus",function(){

           //     $(this).removeClass("error_message");
         //       $(this).removeClass("error_message");
           //     $(this).removeClass("fi-alert");
          //      $(this).removeClass("fi-alert");

         //   });
      //  });


     </script>
</body>
</html>
