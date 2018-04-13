
<!-- **************************************************************
                         Start Bulk Actions Panel 
**************************************************************  -->
<tr>  
  <td colspan="5">
      
 <!--          
<ul class="tabs" data-responsive-accordion-tabs="tabs medium-accordion large-tabs" id="bulk_action_tabs">
  <li class="tabs-title button_users_bulk fc"><a href="#panel1">Bulk Actions<img src="../img/icons/add_light.png" alt="add icon" class="fc_add"/></a></li>
  <li class="tabs-title button_users_bulk fc"><a href="#panel2">Sort By<img src="../img/icons/add_white.png" alt="add icon" class="fc_add"/></a></li>
</ul>
-->

<div id="bulk_action_tabs" class="tabs" data-responsive-accordion-tabs="tabs medium-accordion large-tabs" >
  <div class="tabs-title button_users_bulk fc"><a href="#panel1">Bulk Actions<img src="../img/icons/add_light.png" alt="add icon" class="fc_add"/></a></div>
  <div class="tabs-title button_users_bulk fc"><a href="#panel2">Sort By<img src="../img/icons/add_white.png" alt="add icon" class="fc_add"/></a></div>
</div>

<div class="tabs-content" data-tabs-content="bulk_action_tabs">
  <div class="tabs-panel" id="panel1">          
    <div class="grid-x rule">
        <div class="small-12 medium-8 cell">
           <a href="#" data-open="pwdResetModa1" class="button" type="submit">Force Password Reset</a>
        </div>
        <div class="small-12 medium-8 cell">
           <a href="#" class="button">Delete User</a>
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
           <a href="users.php?filter=1&time_added=1" class="button<?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added']==1)?"selected":"inactive";?>" type="submit">
              Newest First</a>
              &nbsp;
           <a href="users.php?filter=1&time_added=2" class="button<?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added']==2)?"selected":"inactive";?>">
              Oldest First</a>
              &nbsp;
           <a href="users.php?filter=1&time_added=1" class="button<?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added']==2)?"selected":"inactive";?>">
              Last Import</a>
        </div>
    </div>
    <div class="grid-x rule">
        <div class="small-12 medium-4 cell">
           <label for="middle-label" class="middle">Name:</label>
         </div>
         <div class="small-12 medium-8 cell">
           <a href="users.php?filter=1&name=1" class="button<?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name']==1)?"selected":"inactive";?>">A-Z</a>&nbsp;
           <a href="usersdmin_bulkActions.php.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name']==2)?"selected":"inactive";?>">Z-A</a>
         </div>
    </div>
    <div class="grid-x rule">
        <div class="small-12 medium-4 cell">
           <label for="middle-label" class="ml_label">Search:<br />within results</label>
        </div>
        <div class="small-12 medium-8 cell">
            <div class="input-group">
               <input class="input-group-field searchbox" placeholder="Hobbs" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
               <div class="input-group-button"><button type="submit" class="button" value="Go" name="submit">Go</button></div>
            </div>
        </div>
    </div>

  </div>
</div>
</td>
</tr>          
               
<!-- **********************************************
            End Bulk Actions Panel 
********************************************** -->
