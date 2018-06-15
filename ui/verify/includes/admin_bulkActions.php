<!-- **************************************************************
                         Start Bulk Actions Panel 
**************************************************************  -->


<div id="bulk_action_tabs" class="tabs tab-actions" data-tab data-active-collapse="true" data-responsive-accordion-tabs="tabs medium-accordion large-tabs">
			<span class="tabs-title">
				<a href="#panel1" class="btn btn-actions" role="tab" id="BulkActions">Bulk Actions
					<i class="eido-icon-plus fc_add fc_plus "></i>
					<i class="eido-icon-minus fc_add fc_minus"></i>
				</a>
			</span>
	<span class="tabs-title">
				<a href="#panel2" class="btn btn-actions" role="tab">Sort &amp; Search
					<i class="eido-icon-plus fc_add fc_plus"></i>
					<i class="eido-icon-minus fc_add fc_minus"></i>
				</a>
			</span>

</div>

<div class="tabs-content" data-tabs-content="bulk_action_tabs">
	<div class="tabs-panel" id="panel1">
		<div class="grid-x rule">
			<div class="small-12 medium-8 cell">
				<form action="users.php?m=userreset" method="get" class="bulk-action" id="ResetAction">
					<!--							<-- <input type="hidden" name="users[]" value="" />-->
					<button class="button" type="submit">Force Password Reset</button>
				</form>
			</div>
			<div class="small-12 medium-8 cell">
				<form action="users.php?m=userdelete" class="bulk-action" id="DeleteAction">
					<button class="button" type="submit">Delete User</button>
				</form>
			</div>
		</div>
	</div>
	<div class="tabs-panel" id="panel2">

		<div class="grid-x rule" style="margin-top: 0.875rem;">
			<div class="small-12 medium-3 cell">
				<label for="middle-label" class="middle">Time Added</label>
			</div>
			<div class="small-12 medium-9 cell">
				<a href="users.php?<?php echo http_build_query(array_merge($_filter, ['time_added'=>1])); ?>" class="button <?php echo (isset($_filter['time_added']) && $_filter['time_added'] == 1) ? "selected" : "inactive"; ?>">Newest</a>&nbsp;
				<a href="users.php?<?php echo http_build_query(array_merge($_filter, ['time_added'=>2])); ?>" class="button <?php echo (isset($_filter['time_added']) && $_filter['time_added'] == 2) ? "selected" : "inactive"; ?>">Oldest</a>
				<a href="users.php?<?php echo http_build_query(array_merge($_filter, ['time_added'=>3])); ?>" class="button <?php echo (isset($_filter['time_added']) && $_filter['time_added'] == 3) ? "selected" : "inactive"; ?>">Last Import</a>
			</div>
		</div>
		<div class="grid-x rule">
			<div class="small-12 medium-3 cell">
				<label for="middle-label" class="middle">Name</label>
			</div>
			<div class="small-12 medium-9 cell">
				<a href="users.php?<?php echo http_build_query(array_merge($_filter, ['name'=>1])); ?>" class="button <?php echo (isset($_filter['name']) && $_filter['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;
				<a href="users.php?<?php echo http_build_query(array_merge($_filter, ['name'=>2])); ?>" class="button <?php echo (isset($_filter['name']) && $_filter['name'] == 2) ? "selected" : "inactive"; ?>"">Z-A</a>
			</div>
		</div>
		<!--<div class="grid-x rule">
                            <div class="small-12 medium-4 cell">
                                <label for="middle-label" class="ml_label">Search:<br/>within results</label>
                            </div>
                            <div class="small-12 medium-8 cell">
                                <div class="input-group">
                                    <input class="input-group-field searchbox" placeholder="Hobbs" type="text" name="query" value="<?php /*if(!empty($_POST['query']))
                                        echo $_POST['query']; */?>">
                                    <div class="input-group-button">
                                        <button type="submit" class="button" value="Go" name="submit">Go</button>
                                    </div>
                                </div>
                            </div>
                        </div>-->

	</div>
</div>
<div class='grid-x row'>
	<div class="small-6 medium-6 cell text-left padding-10">

	</div>
	<div class="small-6 medium-6 cell  padding-10">
		<?php if(isset($_GET['time_added']) || isset($_GET['name'])): ?>
			<span class="float-right">Filters Active | <a href="users.php" class="float-right link-orange ">&nbsp; Reset</a></span>
		<?php else: ?>
			<span class="float-right">Filters Disabled</span>

		<?php endif; ?>
	</div>
</div>

<!-- **********************************************
			End Bulk Actions Panel
********************************************** -->
