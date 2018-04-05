<!doctype html>
<?php
// ***************************************
// superuser/procedures.php
// 2017 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"SUPERUSER")
{
   header("Location: /ui/login.php");
   exit();
}
require_once 'superuser_functions.php';
session_start();
$logfile = "wel.log";

$mode = get_query_string('m');
$id = get_query_string('id');

// turn everything off
$add_hide = "hide";
$update_hide = "hide";
$managesurveys_hide = "hide";
$addsurveys_hide = "hide";

if ($mode=="" || $mode=="add")
{
   $add_hide = "";
}
else if ($mode=="update")
{
   $update_hide = "";
   // save the procedure ID to comapare against next time
   $_SESSION['pe_id_prev']=$pe_id;
   $pe_id=$id;
}
else if ($mode=="managesurveys")
{
   $managesurveys_hide = "";
   $pe_id=$id;
}
else if ($mode=="addsurveys")
{
   $addsurveys_hide = "";
   $sess_id = get_query_string('sess_id');
   $pe_id=$id;
}

logMsg("Procedures: Mode: $mode", $logfile);
logMsg("Prev: ".$_SESSION['pe_id_prev']." Current: $pe_id", $logfile);

// need to change according to session
if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}
?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Procedure</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
      <?php include "../includes/header.php"; ?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li class="current"><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li class="current"><a href="procedures.php">Procedures</a></li>
		<li><a href="#">System Status</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="100%" border="0"  class="su-table">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>Bulk actions</span></a></span>
			  <span class="float-right"><a href="#"><span>Sort by</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
          <tr>
            <td width="10%"><input type="checkbox"></td>
            <td width="80%">&nbsp;</td>
			<td width="10%">&nbsp;</td>
          </tr>
          <?php 
              $sql = "SELECT * FROM app_fd_pro_procEpisodes ORDER BY c_procedureId";
              $GetQuery = dbi_query($sql);
              while ($qryResult=$GetQuery->fetch_assoc()) {
                 $id = $qryResult['id'];
          ?>
	  <tr>
            <td width="10%"><input type="checkbox"></td>
			<td width="80%"><p class="name"><?php echo $qryResult['c_procedureId']." - ".$qryResult['c_description']; ?><br />
                        <span class="small"><?php echo $qryResult['c_displayName']; ?></span></p></td>
			<td width="10%"><a href="procedures.php?m=update&id=<?php echo $id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></a></td>
		  </tr>
            <?php } ?>
		  <tr>
		    <td colspan="3">
			  <ul class="pagination text-center" role="navigation" aria-label="Pagination">
  			    <li class="pagination-previous disabled">Previous</li>
  			    <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
  			    <li><a href="#" aria-label="Page 2">2</a></li>
  			    <li><a href="#" aria-label="Page 3">3</a></li>
  			    <li><a href="#" aria-label="Page 4">4</a></li>
  			    <li class="ellipsis"></li>
  			    <li><a href="#" aria-label="Page 12">12</a></li>
  			    <li><a href="#" aria-label="Page 13">13</a></li>
  			    <li class="pagination-next"><a href="#" aria-label="Next page">Next</a></li>
  			  </ul>
			</td>
		  </tr>
  	    </tbody>
      </table>
	</div>
	<!-- End Content-Left -->
	<!-- ADD SECTION -->  
        <?php  if ($mode=="add")
               {
                 ;
               }
        ?>
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h2>Add Procedure</h2>
	  <form action="procedures_a.php?m=add" method="post">
          <div class="grid-container">
    	     <div class="grid-x grid-padding-x">
      	         <div class="small-12 medium-12 large-12 cell">
        	        <label>Procedure Name
                        <input type="text" name="c_description" placeholder="">
                        </label>
                </div>
		<div class="small-12 medium-12 large-12 cell">
        	  <label>EIDO Procedure Code
                  <input type="text" name="c_procedureId" placeholder="">
                  </label>
                </div>
		<div class="small-12 medium-12 large-12 cell">
        	  <label>Display Name
                  <input type="text" name="c_displayName" placeholder="">
                  </label>
                </div>
		<div class="small-12 medium-12 large-12 cell text-center">
		  <p><BR /><input type="submit" name="add" value="Add Procedure" class="button large" /></p>
                </div>
    	     </div>
	</div>
	</form>
     </div>  
    <!-- END ADD SECTION -->
    <!-- UPDATE SECTION -->
        <?php  if ($mode=="update")
               {
                 $sql_u="SELECT * FROM app_fd_pro_procEpisodes WHERE id='$pe_id'";
                 $GetQuery_u = dbi_query($sql_u);
                 $qryResult_u=$GetQuery_u->fetch_assoc();
                 $name=$qryResult_u['c_description'];
                 $code=$qryResult_u['c_procedureId'];
                 $dname=$qryResult_u['c_displayName'];
              
                 logMsg("update: resetting Survey Arrays", $logfile);
                 unset($_SESSION['arr_all_surveys']);
                 unset($_SESSION['arr_add_surveys']);
               }
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
          <h2>View Procedure</h2>
          <form action="procedures_a.php?m=update&id=<?php echo $pe_id; ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell">
                  <label>Procedure Name
                <input type="text" name="c_description" value="<?php echo $name; ?>">
              </label>
            </div>
                <div class="small-12 medium-12 large-12 cell">
                  <label>EIDO Procedure Code
                <input type="text" name="c_procedureId" value="<?php echo $code; ?>">
              </label>
            </div>
                <div class="small-12 medium-12 large-12 cell">
                  <label>Display Name
                <input type="text" name="c_displayName" value="<?php echo $dname; ?>">
              </label>
            </div>
                <div class="small-12 medium-12 large-12 cell text-center">
                  <a href="procedures.php?m=managesurveys&id=<?php echo $pe_id; ?>" class="no-u"><p class="directive">Manage Surveys<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a>
                  <input type="submit" name="update" value="Update Procedure" class="button large" />
            </div>
          </div>
        </div>
          </form>
        </div>
    <!-- END UPDATE SECTION -->
    <!-- MANAGESURVEYS SECTION -->
        <?php 
            if ($mode=="managesurveys")
            {
               $session_number = 1;
               $arr_proc_episode =  get_proc_episode($pe_id, $session_number);
               $num_surveys=get_num_surveys_by_proc($pe_id); // number of surveys in the current session
               logMsg("managesurveys: # of surveys: $num_surveys ",$logfile);
               if ($pe_id<>$_SESSION['pe_id_prev'])
               { 
                  $_SESSION['sessionSurvey1'] = $arr_proc_episode['sessionSurvey1'];
                  $_SESSION['sessionSurvey2'] = $arr_proc_episode['sessionSurvey2'];
                  $_SESSION['sessionSurvey3'] = $arr_proc_episode['sessionSurvey3'];
                  $_SESSION['sessionSurvey4'] = $arr_proc_episode['sessionSurvey4'];
                  $_SESSION['sessionSurvey5'] = $arr_proc_episode['sessionSurvey5'];
               }
               if ($_SESSION['session_type']=="PRE")
               {
                  $pre_color = "active";
                  $post_color = "inactive";
               }
               else if ($_SESSION['session_type']=="POST")
               {
                  $pre_color = "inactive";
                  $post_color = "active";
               }
               else
               {
                  $pre_color = "inactive";
                  $post_color = "inactive";
               }
            }
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $managesurveys_hide; ?>">
          <div class="back"><a href="procedures.php?m=update&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h2>Procedure Setup<br /><span class="small">Add surveys to the procedure session</span></h2>
          <form action="procedures_a.php?m=updateproc&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                        <div class="small-12 medium-12 large-12 cell">
              <h4><?php echo $arr_proc_episode['c_procedureId']." - ".$arr_proc_episode['c_description']; ?></h4>
            </div>
               <div class="small-12 medium-12 large-12 cell">
                 <div class="grid-x">
                   <div class="small-12 medium-6 large-4 cell">
                <label>Number of Sessions
                  <div class="input-group plus-minus-input">
                    <div class="input-group-button">
                      <button type="button" class="button" name="" data-quantity="minus" data-field="quantity"><i class="fi-minus" aria-hidden="true"></i></button>
                    </div>
                    <input class="input-group-field" type="text" name="quantity" value="<?php echo $num_surveys; ?>" width="40px">
                   <div class="input-group-button">
                       <button type="button" class="button" name="" data-quantity="plus" data-field="quantity"><i class="fi-plus" aria-hidden="true"></i></button>
                  </div>
                  </div>
                              </label>
                                </div>
                                <div class="small-12 medium-6 large-8 cell"></div>
                          </div>
                          <hr />
            </div>
            <div class="small-12 medium-12 large-12 cell">
              <div class="grid-x grid-padding-x align-middle">
                <div class="small-12 medium-5 large-5 cell">
                  <select name="session_number">
                        <option value="1">Session 01</option>
                        <option value="2">Session 02</option>
                        <option value="3">Session 03</option>
                        <option value="4">Session 04</option>
                        <option value="5">Session 05</option>
                        <option value="6">Session 06</option>
                  </select>
              </div>
              <div class="hide-for-small-only medium-2 large-2 cell">&nbsp;</div>
                <div class="small-12 medium-5 large-5 cell">
                  <a href="#">Show All</a>
                </div>
              </div>
              <hr />
            </div>
           <div class="small-12 medium-12 large-12 cell">
                          <h5>Session Name<br /><span class="small">This name will be used to identify the session to hospital staff.</span></h5>
            <div class="small-12 cell"><input type="text" name="sessionName" value="<?php echo $arr_proc_episode['sessionName']; ?>" placeholder=""><br /></div>
               <div class="input-group">
                   <span class="input-group-label">Type</span>
                      <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=pre"><button class="button <?php echo $pre_color; ?>" type="button">&nbsp;&nbsp;Pre&nbsp;&nbsp; </button></a>
                      &nbsp;&nbsp; <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=post"><button class="button <?php echo $post_color; ?>" type="button">Post</button></a>
               </div>
               <p><h5>Session Delay Time</h5><input type="text" size="4" name="session_delay"><b: /></p>
            </div>
            <div class="small-12 medium-12 large-12 cell">
                     <ul class="sort">
                          <?php for ($i=1; $i<=5; $i++) { 
                              $indx = "sessionSurvey".$i;
                              if ($_SESSION[$indx]<>"") {
                                 $arr_survey_info=get_survey_by_num($_SESSION[$indx]);
                          ?>
                              <li><i class="fi-list sort-icon move"></i><?php echo $_SESSION[$indx]." - ".$arr_survey_info['c_description']; ?><a href="functions.php?m=delete_proc_survey&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&survey=<?php echo $i; ?>"><i class="fi-trash sort-icon float-right"></i></a></li>
                          <?php    }
                               } ?>
                          <a href="procedures.php?m=addsurveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>"><li class="add"><button class="button expanded text-center add-survey" type="button"><i class="fi-plus"></i></button></li></a>
                     </ul>
                  </div>
                  <div class="small-12 medium-12 large-12 cell text-center">
                  <input type="submit" class="button large" name="updateproc" value="Update Procedure">
            </div>
          </div>
                </div>
          </form>
        </div>
    <!-- END MANAGESURVEYS SECTION -->
    <!-- ADDSURVEYS SECTION -->
        <?php if ($mode=="addsurveys")
            { 
               if ($pe_id<>$_SESSION['pe_id_prev'] || $sess_id<>$_SESSION['sess_id_prev'])
               {
                  $arr_all_surveys=get_all_surveys();
                  $_SESSION['arr_all_surveys']=$arr_all_surveys;
               }
               $arr_all_surveys=$_SESSION['arr_all_surveys'];
               $_SESSION['pe_id_prev']=$pe_id;
               $_SESSION['sess_id_prev']=$sess_id; 
            }
            // NOTE _ CHANGE THIS LATER
            $sess_id = 1;
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $addsurveys_hide; ?>">
          <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
          <h2>Add Surveys</h2>
          <form>
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                        <div class="small-12 medium-12 large-12 cell">
              <div class="grid-x">
                <div class="small-10 cell">
                  <input type="text" placeholder="oscopy" class="search-left">
                </div>
                <div class="small-2 cell">
                  <div class="search-right"><a href="#" class="button postfix expanded search-btn"><i class="fi-magnifying-glass"></i></a></div>
                </div>
              </div>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <table width="100%" border="0" class="hover">
                            <tbody>
                       <?php for ($s=0; $s<count($arr_all_surveys); $s++) {
                             if (!$arr_all_surveys[$s]['added']) {  
                       ?>
                          <tr>
                            <td class="text-left" width="90%"><?php echo $arr_all_surveys[$s]['c_surveyNumber']." - ".$arr_all_surveys[$s]['c_description']; ?></td>
                            <td class="text-right" width="10%"><a href="functions.php?m=add_survey_to_temp&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&s=<?php echo $s; ?>&sn=<?php echo $arr_all_surveys[$s]['c_surveyNumber']; ?>"><i class="fi-plus"></i></a></td>
                          </tr>
                        <?php     }  
                            }  
                        ?>
                                  <tr>
                            <td colspan="2" class="text-left"><hr /></td>
                                  </tr>
                             <?php $arr_add_surveys = $_SESSION['arr_add_surveys'];
                                   for ($t=0; $t<count($arr_add_surveys); $t++) {
                                       $arr_survey_info = get_survey_by_num($arr_add_surveys[$t]['c_surveyNumber']);
                             ?>
                                  <tr>
                            <td class="text-left" width="90%"><?php echo $arr_survey_info['c_surveyNumber']." - ".$arr_survey_info['c_description']; ?></td>
                    <td class="text-right" width="10%"><a href="functions.php?m=delete_survey_from_temp&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=<?php echo $t; ?>&sn=<?php echo $arr_add_surveys[$t]['c_surveyNumber']; ?>"><i class="fi-trash"></i></a></td>
                                  </tr>
                          <?php } ?>
                </tbody>
              </table>
                          <hr />
            </div>
                        <div class="small-12 medium-12 large-12 cell text-center">
                          <a href="functions.php?m=add_selected_surveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>" class="button large" name="" >Add Selected</button></a>
            </div>
          </div>
                </div>
          </form>
       </div>
        <!-- END ADDSURVEYS SECTION -->
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
  <div class="grid-x footer align-middle">
    <div class="small-12 medium-6 large-6 cell">
	  <p><a href="#" class="white">Need any help?</a><br />
	  <a href="#" class="white">FAQ</a><br />
	  <a href="#" class="white">Knowledgebase</a></p>	
	</div>
	<div class="small-12 medium-6 large-6 cell text-right">
		<p>Copyright EIDO Systems Ltd, 2017. All Rights Reserved<br />
		<p><a href="#">Terms &amp; Conditions</a> | <a href="#">Privacy Policy</a></p>
	</div>
  </div> 
  <!-- End Footer -->
</div>
    <script src="../js/vendor/jquery.js"></script>
    <script src="../js/vendor/what-input.js"></script>
    <script src="../js/vendor/foundation.js"></script>
    <script src="../js/app.js"></script>
    <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script>
      $( function() {
      $( "#sortable" ).sortable({
        placeholder: "ui-state-highlight"
      });
      $( "#sortable" ).disableSelection();
      } );
    </script>
	<script>
        jQuery(document).ready(function() {
          // This button will increment the value
          $('[data-quantity="plus"]').click(function(e){
          // Stop acting like a button
          e.preventDefault();
          // Get the field name
          fieldName = $(this).attr('data-field');
          // Get its current value
          var currentVal = parseInt($('input[name='+fieldName+']').val());
          // If is not undefined
          if (!isNaN(currentVal) && currentVal < 6){
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
          } else {
            // Otherwise put a 6 there
            $('input[name='+fieldName+']').val(6);
         }
         // remove previos select session option
         currentVal = $('input[name='+fieldName+']').val();
         $('select[name=session_number] > option').each(function(){
               $(this).remove();
         });
         // add select session option
         for (i=1;i<=currentVal;i++){
            $('select[name=session_number]').append($('<option>', {
               value: i,
               text: 'Session '+i
            }));
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
         if (!isNaN(currentVal) && currentVal > 1) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
         } else {
            // Otherwise put a 1 there
            $('input[name='+fieldName+']').val(1);
         }
         // remove previos select session option
         currentVal = $('input[name='+fieldName+']').val();
         $('select[name=session_number] > option').each(function(){
               $(this).remove();
         });
         // add select session option
         for (i=1;i<=currentVal;i++){
            $('select[name=session_number]').append($('<option>', {
               value: i,
               text: 'Session '+i
            }));
         }       
         });

         // set session value based on select session
         $('select[name=session_number]').change(function(){
            currentVal = $(this).val();
            $.ajax({
              url: "./ajax/set_session_data.php",
              method: "POST",
              data: {type: "procedures_save",sess_id: currentVal},
              dataType: "JSON",
            }).done(function(response){
               // once ajax is completed
	       console.log(response);
            });
         });
         
         $.ajax({url: "./ajax/get_session_data.php"}).done(function(response){
            console.log(response);
         });
          
     });

     </script>  
   </body>
</html>
