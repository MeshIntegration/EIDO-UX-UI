	<!-- Start includes admin_dropdown_pane.php -->
    <div class="dropdown-pane main_menu" 
     data-position="bottom" data-alignment="left" id="example-dropdown-bottom-left" 
     data-dropdown data-auto-focus="true">
      <div class="grid">
			<div class="small-12 text-center collapse">
            <button class="button type" type="button">
               <?php echo $user_initials; ?>
            </button><?php echo $user_fullname; ?>
        </div>

		<div class="small-12"><a href="../change_password.php">
          <i class="fi-widget"></i>&nbsp;Change Password</a>
        </div>
        <div class="small-12 cell">
          <a href="../logout.php"><i class="fi-power"></i>&nbsp;Logout</a>
        </div>
      </div>
    </div>
    <!-- End includes admin_dropdown_pane.php -->