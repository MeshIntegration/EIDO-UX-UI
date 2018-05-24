<!-- **************************************************************
                         Start Bulk Actions Panel 
**************************************************************  -->


		<!--
	   <ul class="tabs" data-responsive-accordion-tabs="tabs medium-accordion large-tabs" data-active-collapse="true" id="bulk_action_tabs">
		 <li class="tabs-title button_users_bulk fc"><a href="#panel1">Bulk Actions<img src="../img/icons/add_light.png" alt="add icon" class="fc_add"/></a></li>
		 <li class="tabs-title button_users_bulk fc"><a href="#panel2">Sort By<img src="../img/icons/add_white.png" alt="add icon" class="fc_add"/></a></li>
	   </ul>
	   -->

		<div id="bulk_action_tabs" class="tabs tab-actions" data-tab data-active-collapse="true" data-responsive-accordion-tabs="tabs medium-accordion large-tabs">
			<div class="tabs-title" style="margin-left: -30px;">
				<a href="#panel1" class="btn btn-actions" role="tab" id="BulkActions">
					<i class="eido-icon-plus fc_add fc_plus "></i>
					<i class="eido-icon-minus fc_add fc_minus"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bulk Actions
				</a>
			</div>
			<div class="tabs-title" style="margin-left: 200px; padding-bottom: 230px;">
                <a href="#panel2" class="btn btn-actions" role="tab">
                    <label style="text-align: right; vertical-align: top">Sort & Search
					<i class="eido-icon-plus fc_add fc_plus" style="margin-top: ;: 30px;"></i>
					<i class="eido-icon-minus fc_add fc_minus"></i>
                </label>
                </a>

			</div>

		</div>

		<div class="tabs-content" data-tabs-content="bulk_action_tabs">
			<div class="tabs-panel" id="panel1">
				<div class="grid-x rule">
					<div class="small-12 medium-8 cell">
						<form action="bulk_confirm.php?actionRequested=reset" method="post" name="reset" class="bul-action" id="ResetAction">
<!--							<input type="hidden" name="users[]" value="" />-->
							<button class="button" type="submit">Force Password Reset</button>
						</form>
					</div>
					<div class="small-12 medium-8 cell">
						<form action="bulk_confirm.php?actionRequested=delete" method="post" name="delete" class="bul-action" id="DeleteAction">
							<button class="button" type="submit">Delete User</button>
						</form>
					</div>
				</div>
			</div>
			<div class="tabs-panel" id="panel2">
				<div class="grid-x rule">
					<div class="small-12 cell">
						<a href="clear_filter.php" class="float-right align-center-middle"><img src="../img/close-icon.png" alt="" style="margin:7px;"></a>
					</div>
				</div>
				<div class="grid-x rule">
					<div class="small-12 medium-4 cell">
						<label for="middle-label" class="middle">Time Added:</label>
					</div>
					<div class="small-12 medium-8 cell">
						<a href="users.php?filter=1&time_added=1" class="button<?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? "selected" : "inactive"; ?>" type="submit">
							Newest First</a>
						&nbsp;
						<a href="users.php?filter=1&time_added=2" class="button<?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">
							Oldest First</a>
						&nbsp;
						<a href="users.php?filter=1&time_added=1" class="button<?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">
							Last Import</a>
					</div>
				</div>
				<div class="grid-x rule">
					<div class="small-12 medium-4 cell">
						<label for="middle-label" class="middle">Name:</label>
					</div>
					<div class="small-12 medium-8 cell">
						<a href="users.php?filter=1&name=1" class="button<?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;
						<a href="users.php?filter=1&name=2" class="button<?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
					</div>
				</div>
				<div class="grid-x rule">
					<div class="small-12 medium-4 cell">
						<label for="middle-label" class="ml_label">Search:<br/>within results</label>
					</div>
					<div class="small-12 medium-8 cell">
						<div class="input-group">
							<input class="input-group-field searchbox" placeholder="Hobbs" type="text" name="query" value="<?php if(!empty($_POST['query']))
								echo $_POST['query']; ?>">
							<div class="input-group-button">
								<button type="submit" class="button" value="Go" name="submit">Go</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

<!-- **********************************************
            End Bulk Actions Panel 
********************************************** -->
