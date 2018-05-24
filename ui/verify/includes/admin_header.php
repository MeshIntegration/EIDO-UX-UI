<div class="grid-x hide-for-small-only show-for-medium">
    <div class="small-12 medium-2 large-2 cell">
        <button class="button type shift btn-header" style="" type="button" data-toggle="example-dropdown-bottom-left">
            <?php echo $user_initials; ?>
        </button>
        <?php include "admin_dropdown_pane.php"; ?>
    </div>

    <!--   /Removed per EIDO amend request/
  <div class="small-12 medium-6 large-6 cell">
    <span class="float-left links one_link">
       <?php if ($user_role=="ADMIN") { ?>
           <a href="../admin/users.php">Back to Administration</a>&nbsp;&nbsp;&nbsp;
       <?php } ?>
       <a href="patients_a.php?m=gotoaddpt"><img src="/ui/img/add.png" alt="Add Patient" class="add_icon"/>Add Patient</a>
   </span>
  </div>
-->



    <div class="small-12 medium-6 large-6 cell" style="padding-top: 26px;">
        <!--  <div class="grid" style="padding-top: 15px;"> -->

        <div id="addusr" class="row links" style="margin-top: -2px; margin-bottom: 4px; height: 20px; zoom: 1.14;">
            <a href="/ui/verify/admin/users_a.php?m=gotoadd" style="padding-bottom: 2px; vertical-align: center;">
                <i class="icon eido-icon-plus-circle" style="margin-right: 7px;"></i>Add User</a>
        </div>

    </div>

    <div class="medium-4 large-4 cell"><a href="/ui/verify/admin/users_a.php?m=gotoadd"><img src="/ui/img/eido_logo.png" alt="EIDO Logo" class="logo float-right"/></a></div>
</div>


<div class="small-12 medium-4 large-4 cell">
    <div class="back-row back-row-head">
        <a style="zoom: .8; margin-left: 13px" class="back-btn" href="/ui/verify/patient/patients.php?m=main">
            <i class="icon eido-icon-chevron-left"></i>&nbsp;&nbsp;Back To Dashboard
        </a>
    </div>
</div>


<!-- Start Mobile Nav -->
<div class="grid-x hide-for-medium">
    <div class="small-12 cell text-center"><a href="patients.php?m=main"><img src="/ui/img/eido_logo.png" alt="EIDO Logo" class="logo"/></a></div>
    <div class="small-12 cell toggle">
        <div class="grid-x">
            <div class="small-10 cell">&nbsp;</div>
            <div class="small-2 cell text-right">
                <div class="title-bar" data-responsive-toggle="mobile_menu" data-hide-for="medium">
                    <div class="title-bar-left"></div>
                    <div class="title-bar-right"><button class="menu-icon" type="button" data-toggle="mobile_menu"></button></div>
                </div>
            </div>
        </div>
        <div class="top-bar" id="mobile_menu" data-animate="hinge-in-from-top spin-out">
            <div class="top-bar-left"><button type="button" name="" value="" class="button expanded">Add Patient</button></div>
            <hr />
            <div class="top-bar-right">
                <div class="grid-x">
                    <div class="small-12 cell text-center"><span class="type_mobile"><?php echo $user_initials; ?></span></div>
                    <div class="small-12 cell text-center"><a href="#">My Account</a></div>
                    <hr />
                    <div class="small-12 cell text-center"><a href="#">Help</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Mobile Nav -->
