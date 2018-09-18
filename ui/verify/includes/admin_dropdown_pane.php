	<!-- Start includes admin_dropdown_pane.php -->
    <div class="dropdown-pane main_menu" 
     data-position="bottom" data-alignment="left" id="example-dropdown-bottom-left" 
     data-dropdown data-auto-focus="true">
      <div class="grid" style="padding-bottom: 0 !important;">
	      <div readonly class="small-12 text-center collapse" style="padding-bottom:15px;">
            <button class="button type btn-header" style="text-indent: -1px; cursor: default; color: #0d2240;" aria-disabled="true" type="button"><?php echo $user_initials; ?></button>
				<strong style=""><?php echo $user_fullname; ?></strong>
        </div>
	      <ul class="basic-list">
		      <li>
			      <a href="../change_password.php?rt=<?php echo $return_to; ?>"><i class="fi-widget"></i> Change Password</a>
		      </li>
		      <li>
			      <a href="../logout.php"><i class="fi-power"></i> Logout</a>
		      </li>
	      </ul>

      </div>
    </div>
    <!-- End includes admin_dropdown_pane.php -->
