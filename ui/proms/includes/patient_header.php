<div class="grid-x hide-for-small-only show-for-medium">
  <div class="medium-8 large-8 cell">
    <span class="type float-left"><?php echo $user_initials; ?></span>
    <span class="float-left links one_link"><a href="/ui/patient/patients.php?m=add"><img src="/ui/img/add.png" alt="Add Patient" class="add_icon"/>Add Patient</a></span>
    <!-- extra links commented out for now
    <span class="float-left links one_link"><a href="#"><img src="/ui/img/add.png" alt="Add Patient" class="add_icon"/>Second Link</a></span>
    -->
  </div>
  <div class="medium-4 large-4 cell"><a href="/"><img src="/ui/img/eido_logo.png" alt="EIDO Logo" class="logo float-right"/></a></div>
</div>
<!-- Start Mobile Nav -->
<div class="grid-x hide-for-medium">
  <div class="small-12 cell text-center"><a href="/"><img src="/ui/img/eido_logo.png" alt="EIDO Logo" class="logo"/></a></div>  
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
	      <div class="small-12 cell text-center"><span class="type_mobile">AB</span></div>
	      <div class="small-12 cell text-center"><a href="#">My Account</a></div>
		  <hr />
		  <div class="small-12 cell text-center"><a href="#">Help</a></div>
		</div>	
      </div>
    </div>
  </div>  
</div>
<!-- End Mobile Nav -->
