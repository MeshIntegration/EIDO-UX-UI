<div class="grid-x hide-for-small-only show-for-medium">
  
  <div class="small-12 medium-2 large-2 cell">
    <button class="button type shift" type="button" data-toggle="example-dropdown-bottom-left">
      <?php echo $user_initials; ?>
    </button>
    <?php include "admin_dropdown_pane.php"; ?>

  </div>
   <div class="small-12 medium-6 large-6 cell" style="padding-top: 15px;">
      <!--  <div class="grid" style="padding-top: 15px;"> -->
       <div class="row">
          <a href="/ui/verify/patient/patients.php?m=add">
            <img src="/ui/verify/img/add.png" alt="Add Patient" class="add_icon"/>Add Patient</a>
       </div>
      <div class="row">
          <a href="#">
            <img src="/ui/verify/img/add.png" 
             alt="User Administration" class="add_icon"/>User Administration</a>
      </div>
  </div>

  <div class="medium-4 large-4 cell">
    <a href="patients.php?m=main">
      <img src="/ui/verify/img/eido_logo.png" alt="EIDO Logo" class="logo float-right"/></a>
  </div>
</div>
<!-- Start Mobile Nav -->
<div class="grid-x hide-for-medium">
  <div class="small-12 cell text-center"><a href="users.php"><img src="/ui/verify/img/eido_logo.png" alt="EIDO Logo" class="logo"/></a></div>  
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
