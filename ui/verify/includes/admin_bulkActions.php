<!-- **************************************************************
                         Start Bulk Actions Panel 
**************************************************************  -->
<div id="bulk_action_tabs" class="tabs tab-actions" data-allow-all-closed=true" data-tab data-active-collapse="true" data-responsive-accordion-tabs="tabs medium-accordion large-tabs" style="margin: 0;">
<!--<div class="tabs tab-actions" data-accordion data-allow-all-closed="true" style="margin: 0;">-->
    <div class="tabs-title sortonly" style="line-height: 1.46;">
        <div class='grid-x row'>
            <div class="small-6 medium-6 cell text-left padding-10">
            </div>
            <div class="small-6 medium-6 cell" style="padding-top:22px; padding-right: 20px; margin-bottom: -12px;">
                <?php if(isset($_SESSION['filter']['time_added']) || isset($_SESSION['filter']['name'])): ?>
                    <span class="float-right">Filters Active | <a href="clear_filter.php?m=<?php echo $mode; ?>" class="float-right link-orange ">&nbsp; Reset</a></span>
                <?php else: ?>
                    <span class="float-right">Filters Disabled</span>
                <?php endif; ?>
            </div>
        </div>


    <a href="#panel1" class="sortonly" role="tab" id="BulkActions">
    </a><br/>
    </div>
    <div class="tabs-content" data-tabs-content="bulk_action_tabs" id="BulkActions" style="padding: 0px;">

    <div class="tabs-panel" id="panel1" style="padding: 0px;">
   <div class="grid-x rule">
     <div class="small-12 medium-12 cell">
         <form action="users.php?m=userreset" method="get" class="bulk-action" id="ResetAction">
           <input type="hidden" name="users[]" value="" />
           <button class="button" type="submit">Force Password Reset</button>
         </form>
     </div>
    </div>
     <div class="grid-x rule">
         <div class="small-12 medium-12 cell">
             <form action="users.php?m=userdelete" class="bulk-action" id="DeleteAction">
                 <button class="button" type="submit">Delete User</button>
             </form>
         </div>
     </div>
    </div>
    </div>
</div>

<!-- **********************************************
			End Bulk Actions Panel
********************************************** -->
