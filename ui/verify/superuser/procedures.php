<!doctype html>
<?php
// ***************************************
// superuser/procedures.php
// 2017 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// 03/14/19 - SD - add paggination value into session  
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"SUPERUSER")
{
   header("Location: /ui/verify/login.php");
   exit();
}
require_once 'superuser_functions.php';
session_start();
$return_to = "sup";
$home = "procedures.php";
$logfile = "superuser.log";

$mode = get_query_string('m');
$id = get_query_string('id');
$error_msg = $_SESSION['error_msg'];
if($error_msg){
echo "<center>".$error_msg."</center>";
unset($_SESSION['error_msg']);
}
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
   $sess_id = $_SESSION['session_number'];
   $pe_id=$id;
   // generate empty session variable to hold each variable 
   for($i=1;$i<=$MAX_SESSIONS;$i++){
      if(!isset($_SESSION["pe_id".$pe_id]["sess_id".$i])){
         for($j=0;$j<$MAX_SURVEYS;$j++){
            $_SESSION["pe_id".$pe_id]["sess_id".$i][$j] = "";
         }
      }
      if(isset($_GET['gfdb']) && !empty($_GET['gfdb'])){
      // set all surveys into SESSION variable from database
         get_proc_episode($pe_id, $i,true);
      }
   }
   // get all the session surveys details in SESSION variable.
}
else if ($mode=="addsurveys")
{
   $addsurveys_hide = "";
   $sess_id = $_SESSION['session_number'] = get_query_string('sess_id');
   $pe_id=$id;
}

logMsg("Procedures: Mode: $mode", $logfile);
if ($_SESSION['prev_session_number']<>$_SESSION['session_number'] && false)
{
   // the session number dropdown must of changed in managesurveys - reload page
   $_SESSION['prev_session_number']=$_SESSION['session_number'];
   header("Location: procedures.php?m=managesurveys&id=$pe_id");
   exit();
}

// need to change according to session
$script_name = substr(strrchr($_SERVER['PHP_SELF'],"/"),1);

if ((isset($_GET['page']) && !empty($_GET['page']))){
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}else if (isset($_SESSION['page'][$script_name]['no']) && !empty($_SESSION['page'][$script_name]['no'])){
   $page = $_SESSION['page'][$script_name]['no'];
   $start = ($page - 1) * $row;
}
$_SESSION['page'][$script_name]['no'] = $page ;

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
      <?php include "../includes/su_header.php"; ?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li class="current"><a href="procedures.php">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="users.php">Users</a></li>
		<li><a href="organisations.php">Organisations</a></li>
		<li class="current"><a href="procedures.php">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
	    <div class="grid-x row">
		    <div class="medium-12 cell" style="min-height:40px;">
			    <div class="accordion clearfix" data-accordion data-allow-all-closed="true">
				    <div class="accordion-item" data-accordion-item>
					    <a href="#" class="accordion-title sort"></a>
					    <!-- Accordion tab title -->

					    <div class="accordion-content sort" data-tab-content>
						    <?php
						    // Reset Filter button is not shown if filter is default
						    if(!$is_default_filter) {
							    ?>
							    <div class="grid-x rule">
								    <div class="small-12 cell">
									    <!--<a href="clear_filter.php" class="float-right align-center-middle"><img src="../img/close-icon.png" alt="" style="margin:7px;"></a>-->
									    <a href="clear_filter.php" class="align-center-middle" id="ResetFilter">Reset Filters</a>
								    </div>
							    </div>
						    <?php } ?>
						    <div class="grid-x rule">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Time Added</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="patients.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? "selected" : "inactive"; ?>">Newest First</a>&nbsp;<a href="patients.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">Oldest First</a>
							    </div>
						    </div>
						    <div class="grid-x rule">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Name</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="patients.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="patients.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
							    </div>
						    </div>
						    <div class="grid-x rule">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Activity</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="patients.php?filter=1&activity=1" class="button <?php echo (isset($_SESSION['filter']['activity']) && $_SESSION['filter']['activity'] == 1) ? "selected" : "inactive"; ?>">Most Active</a>&nbsp;<a href="patients.php?filter=1&activity=2" class="button <?php echo (isset($_SESSION['filter']['activity']) && $_SESSION['filter']['activity'] == 2) ? "selected" : "inactive"; ?>">Least Active</a>
							    </div>
						    </div>
						    <div class="grid-x rule">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Status</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="patients.php?filter=1&status=1" class="button off_status <?php echo (isset($_SESSION['filter']['status']) && $_SESSION['filter']['status'] == 1) ? "selected" : "inactive"; ?>">Red</a>&nbsp;<a href="patients.php?filter=1&status=2" class="button on_status <?php echo (isset($_SESSION['filter']['status']) && $_SESSION['filter']['status'] == 2) ? "selected" : "inactive"; ?>">Green</a>
							    </div>
						    </div>

						    <div class="grid-x rule">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Gender:</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="patients.php?filter=1&gender=1" class="button <?php echo (isset($_SESSION['filter']['gender']) && $_SESSION['filter']['gender'] == 1) ? "selected" : "inactive"; ?>">Any</a>&nbsp;<a href="patients.php?filter=1&gender=2" class="button <?php echo (isset($_SESSION['filter']['gender']) && $_SESSION['filter']['gender'] == 2) ? "selected" : "inactive"; ?>">Male</a>&nbsp;<a href="patients.php?filter=1&gender=3" class="button <?php echo (isset($_SESSION['filter']['gender']) && $_SESSION['filter']['gender'] == 3) ? "selected" : "inactive"; ?>">Female</a>
							    </div>
						    </div>
						    <div class="grid-x rule">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="ml_label">Search:<br/>within results</label>
							    </div>
							    <form method="post" enctype="multipart/form-data" action="patients.php?filter=1">
								    <div class="small-12 medium-8 cell">
									    <div class="input-group">
										    <input class="input-group-field searchbox" placeholder="Hobbs" type="text" name="search_within_query" value="<?php if(!empty($_SESSION['filter']['search_within_query']))
											    echo $_SESSION['filter']['search_within_query']; ?>">
										    <div class="input-group-button">
											    <button type="submit" class="button" value="Go" name="search_within_submit">Go</button>
										    </div>
									    </div>
								    </div>
							    </form>
						    </div>
					    </div>
				    </div>
			    </div>
		    </div>

	    </div>


	    <ul class="patient-list">
		    <?php
		    $sql = "SELECT * FROM $TBLPROCEPISODES ORDER BY c_procedureId LIMIT $start,$row";
		    $GetQuery = dbi_query($sql);
		    while ($qryResult=$GetQuery->fetch_assoc()) {
		    $list_id = $qryResult['id'];

		    $isSelected = '';
		    if ($list_id == $id) {
			    $isSelected = ' class="selected"';
		    }
		    ?>
		    <li<?php echo $isSelected; ?>>
			    <a href="procedures.php?m=update&id=<?php echo $list_id; ?>">
				    <span class="float-right right-arrow"><i class="eido-icon-chevron-right"></i></span>
				    <p>
					    <span class="uc"><?php echo $qryResult['c_procedureId']." - ".$qryResult['c_description']; ?></span><br />
					    <?php echo $qryResult['c_displayName']; ?>
				    </p>
			    </a>
		    </li>
		    <?php } ?>

	    </ul>

<?php
$sql = "SELECT * FROM $TBLPROCEPISODES ORDER BY c_procedureId";
$GetQuery = dbi_query($sql);
$totalRecord = $GetQuery->num_rows;
$pagination = get_pagination($page, $totalRecord);
?>
<?php /*
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
<?php */ ?>
	  <div class="grid grid-x text-center">
	    <div class="small-12 pagination-btm"><?php echo $pagination; ?></div>
	  </div>
	</div>
	<!-- End Content-Left -->
	<!-- ADD SECTION -->  
        <?php  if ($mode=="add")
               {
                 ;
               }
        ?>
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h3>Add Procedure</h3>
	  <form action="procedures_a.php?m=add" method="post">
        <div class="grid-container">
    	  <div class="grid-x">
      	    <div class="small-12 cell field">
        	  <label>Procedure Name
                <input type="text" name="c_description" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
        	  <label>EIDO Procedure Code
                <input type="text" name="c_procedureId" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
        	  <label>Display Name
                <input type="text" name="c_displayName" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field text-center">
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
                 logMsg("---------------Update Procedure --------------",$logfile);
                 $sql_u="SELECT * FROM $TBLPROCEPISODES WHERE id='$pe_id'";
                 $GetQuery_u = dbi_query($sql_u);
                 $qryResult_u=$GetQuery_u->fetch_assoc();
                 $name=$qryResult_u['c_description'];
                 $code=$qryResult_u['c_procedureId'];
                 $dname=$qryResult_u['c_displayName'];
              
                 logMsg("update: resetting Survey Arrays", $logfile);
                 unset($_SESSION['arr_all_surveys']);
                 unset($_SESSION['arr_add_surveys']);
                 $_SESSION['session_number'] = 1;
                 $_SESSION['prev_session_number'] = 1;
                 logMsg("PEID: $pe_id  - SN: $session_number ",$logfile);
                 logMsg("SESSION SN: ".$_SESSION['session_number'] ,$logfile);
               }
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $update_hide; ?>">
          <h3>View Procedure</h3>
          <form action="procedures_a.php?m=update&id=<?php echo $pe_id; ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell field">
                  <label>Procedure Name
                <input type="text" name="c_description" value="<?php echo $name; ?>">
              </label>
            </div>
                <div class="small-12 medium-12 large-12 cell field">
                  <label>EIDO Procedure Code
                <input type="text" name="c_procedureId" value="<?php echo $code; ?>">
              </label>
            </div>
                <div class="small-12 medium-12 large-12 cell field">
                  <label>Display Name
                <input type="text" name="c_displayName" value="<?php echo $dname; ?>">
              </label>
            </div>
                <div class="small-12 medium-12 large-12 cell text-center">
                  <a href="procedures.php?m=managesurveys&gfdb=1&id=<?php echo $pe_id; ?>" class="no-u"><p class="directive">Manage Surveys<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a>
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
               logMsg("--------------- ManageSurveys --------------",$logfile);
               $session_number = $_SESSION['session_number'];
               logMsg("SESSION SN: ".$_SESSION['session_number'] ,$logfile);
               $arr_proc_episode =  get_proc_episode($pe_id, $session_number,$get_from_db);
               // set default session if number of session is not set
               if(!empty($arr_proc_episode['c_numberOfSessions'])){
                  $noOfSession = $arr_proc_episode['c_numberOfSessions'];
               }elseif(!empty($_SESSION['pe_id'.$pe_id]['numberofsession'])){
                  $noOfSession = $_SESSION['pe_id'.$pe_id]['numberofsession'];
               }else{
                  $noOfSession = 2; // default session number
               }

               // set session name
               if(!empty($arr_proc_episode['c_session'.$sess_id.'Name'])){
                  $sessionName = $arr_proc_episode['c_session'.$sess_id.'Name'];
               }elseif(!empty($_SESSION['pe_id'.$pe_id]['sessionName'.$sess_id])){
                  $sessionName = $_SESSION['pe_id'.$pe_id]['sessionName'.$sess_id];
               }else{
                  $sessionName = "Session ".$sess_id; // default session name
               }

               logMsg("numberOfSessions: ".$arr_proc_episode['c_numberOfSesssions'],$logfile);
               $num_surveys=get_num_surveys_by_proc($pe_id, $session_number); // number of surveys in the current session
               logMsg("managesurveys: # of surveys: $num_surveys ",$logfile);
               if ($pe_id<>$_SESSION['pe_id_prev'])
               { 
                  $_SESSION['sessionSurvey1'] = $arr_proc_episode['sessionSurvey1'];
                  $_SESSION['sessionSurvey2'] = $arr_proc_episode['sessionSurvey2'];
                  $_SESSION['sessionSurvey3'] = $arr_proc_episode['sessionSurvey3'];
                  $_SESSION['sessionSurvey4'] = $arr_proc_episode['sessionSurvey4'];
                  $_SESSION['sessionSurvey5'] = $arr_proc_episode['sessionSurvey5'];
               }
               if ($_SESSION["pe_id".$pe_id]['session_type'.$sess_id]=="PRE")
               {
                  $pre_color = "active";
                  $post_color = "inactive";
               }
               else if ($_SESSION["pe_id".$pe_id]['session_type'.$sess_id]=="POST")
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
		  <hr />
          <form action="procedures_a.php?m=updateproc&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                        <div class="small-12 medium-12 large-12 cell">
              <h4 class="vertical-middle"><?php echo $arr_proc_episode['c_procedureId']." - ".$arr_proc_episode['c_description']; ?></h4>
			  <hr />
            </div>
               <div class="small-12 medium-12 large-12 cell">
                 <div class="grid-x">
                   <div class="small-12 medium-6 large-4 cell">
                <label>Number of Sessions
                  <div class="input-group plus-minus-input">
                    <a class="button fc" data-quantity="minus" data-field="quantity"><img src="../img/icons/minus_white.png" alt="minus icon" /></a>
                    <input class="input-group-field" type="text" name="quantity" value="<?php echo $noOfSession; ?>" width="40px">
                       <a class="button fc" data-quantity="plus" data-field="quantity"><img src="../img/icons/add_white.png" alt="add icon" /></a>
                  </div>
                              </label>
                                </div>
                                <div class="small-12 medium-6 large-8 cell"></div>
                          </div>
                          <hr />
            </div>
            <div class="small-12 medium-12 large-12 cell">
              <div class="grid-x grid-padding-x align-middle">
                <div class="small-12 medium-8 large-8 cell">
                  <select name="session_number">
                        <option value="1" <?php if ($session_number==1) echo "selected"; ?>>Session 1</option>
                        <option value="2" <?php if ($session_number==2) echo "selected"; ?>>Session 2</option>
                        <option value="3" <?php if ($session_number==3) echo "selected"; ?>>Session 3</option>
                        <option value="4" <?php if ($session_number==4) echo "selected"; ?>>Session 4</option>
                        <option value="5" <?php if ($session_number==5) echo "selected"; ?>>Session 5</option>
                        <option value="6" <?php if ($session_number==6) echo "selected"; ?>>Session 6</option>
                  </select>
              </div>
              <div class="hide-for-small-only medium-1 large-1 cell">&nbsp;</div>
                <div class="small-12 medium-3 large-3 cell">
                  <a href="#">Show All</a>
                </div>
              </div>
              <hr />
            </div>
           <div class="small-12 medium-12 large-12 cell">
                          <h5>Session Name<br /><span class="small">This name will be used to identify the session to hospital staff.</span></h5>
            <div class="small-12 cell"><input type="text" name="sessionName" value="<?php echo $sessionName; ?>" placeholder=""><br /></div>
               <div class="input-group">
                   <span class="input-group-label">Type</span>
                      <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=pre"><button class="button <?php echo $pre_color; ?> btn-pre" type="button">&nbsp;&nbsp;Pre&nbsp;&nbsp; </button></a>
                      &nbsp;&nbsp; <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=post"><button class="button <?php echo $post_color; ?> btn-pre" type="button">Post</button></a>
               </div>
            </div>
            <div class="small-12 medium-12 large-12 cell" id="session_delay_time">
               <p><h5>Session Delay Time</h5>
               <input type="text" size="4" name="session_delay" value="<?php echo isset($arr_proc_episode['sessionDelay']) ? $arr_proc_episode['sessionDelay'] : ''; ?>"><b: /></p>
            </div>
            <div class="small-12 medium-12 large-12 cell">
                     <ul class="sort" id="sortable" style="margin-bottom:40px;" >
                          <?php 
                           $survey_ids = get_surveys_by_proc($pe_id,$sess_id); // $_SESSION['pe_id'.$pe_id]["sess_id".$sess_id];
                           for ($i=0; $i<count($survey_ids); $i++) { 
                              $arr_survey_info = get_survey_by_num($survey_ids[$i]);
                              if(!empty($arr_survey_info)){
                          ?>
                              <li data-survey="<?php echo $survey_ids[$i]; ?>">
	                              <i class="fi-list sort-icon move"></i>
	                              <span class="not-allow-move">
		                              <a href="functions.php?m=delete_proc_survey&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&survey=<?php echo ($i+1); ?>" class="float-right"><i class="fi-trash sort-icon float-right"></i></a>
		                              <?php echo $survey_ids[$i]." - ".$arr_survey_info['c_description']; ?>

	                              </span>
                              </li>
                          <?php    }
                               } ?>
                          <a href="procedures.php?m=addsurveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>"><li class="add"><button class="button expanded text-center add-survey" type="button"><img src="../img/icons/add_white.png" alt="add icon" /></button></li></a>
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
// added 4/8
               $arr_all_surveys=get_all_surveys();
               $_SESSION['arr_all_surveys']=$arr_all_surveys;             
// $arr_all_surveys=$_SESSION['arr_all_surveys'];
               $_SESSION['pe_id_prev']=$pe_id;
               $_SESSION['sess_id_prev']=$sess_id; 
            }
            // NOTE _ CHANGE THIS LATER
            // commented below line on 3/26/2018 - to make sess_id variable
            // $sess_id = 1;
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
                       <?php 
                          for ($s=0; $s<count($arr_all_surveys); $s++) {
                             if (!$arr_all_surveys[$s]['added']) {  
                       ?>
                          <tr>
                            <td class="text-left" width="90%"><?php echo $arr_all_surveys[$s]['c_surveyNumber']." - ".$arr_all_surveys[$s]['c_description']; ?></td>
                            <td class="text-right"><a href="functions.php?m=add_survey_to_temp&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&s=<?php echo $s; ?>&sn=<?php echo $arr_all_surveys[$s]['c_surveyNumber']; ?>"><i class="fi-plus"></i></a></td>
                          </tr>
                        <?php     }  
                            }  
                        ?>
                                  <tr>
                            <td colspan="2" class="text-left"><hr /></td>
                                  </tr>
                                  <?php
                                     $arr_add_surveys = $_SESSION['arr_add_surveys'][$pe_id];
                                     $arr_survey_list = get_survey_by_num($arr_add_surveys);
                                     $t = 0;
                                     if(count($arr_survey_list)){ 
                                        foreach ($arr_survey_list as $arr_survey_info){
                                  ?>
                                  <tr>
                                     <td class="text-left" width="90%"><?php echo $arr_survey_info['c_surveyNumber']." - ".$arr_survey_info['c_description']; ?></td>
                                     <td class="text-right"><a href="functions.php?m=delete_survey_from_temp&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=<?php echo $t; ?>&sn=<?php echo $arr_survey_info['c_surveyNumber']; ?>"><i class="fi-trash"></i></a></td>
                                  </tr>
                                  <?php
                                        $t++;} 
                                     } 
                                 ?>
                </tbody>
              </table>
                          <hr />
            </div>
            <div class="small-12 medium-12 large-12 cell text-center">
                <a href="functions.php?m=add_selected_surveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>" class="button large" name="" >Add Selected</a>
            </div>
          </div>
                </div>
          </form>
       </div>
        <!-- END ADDSURVEYS SECTION -->
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
     <?php include "../includes/footer.php"; ?>
  <!-- End Footer -->
</div>
    <script src="../js/vendor/jquery.js"></script>
    <script src="../js/vendor/what-input.js"></script>
    <script src="../js/vendor/foundation.js"></script>
    <script src="../js/app.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!--

    <script>
      $( function() {
      $( "#sortable" ).sortable({
        placeholder: "ui-state-highlight"
      });
      $( "#sortable" ).disableSelection();
      } );
    </script>

    -->
	<script>
        jQuery(document).ready(function() {
          $(".clickable-row").click(function() {
            window.location = $(this).data("href");
          });

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
            $('input[name='+fieldName+']').val(currentVal + 1).change();
          } else {
            // Otherwise put a 6 there
            $('input[name='+fieldName+']').val(6).change();
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
            $('input[name='+fieldName+']').val(currentVal - 1).change();
         } else {
            // Otherwise put a 1 there
            $('input[name='+fieldName+']').val(1).change();
         }
         });

         // get session number
         var session_number = <?php echo $_SESSION['session_number'];?>
  
         // change the session dropdown
         $('input[name=quantity]').change(function(){
            // remove previos select session option
          
            currentVal = $('input[name=quantity]').val();
            $('select[name=session_number] > option').each(function(){
               $(this).remove();
            });
            // add select session option
            var option_tag = "";
            for (i=1;i<=currentVal;i++){
               if(i==session_number){
                  option_tag = '<option value="'+i+'" selected>Session '+i+'</option>';
               }else{
                  option_tag = "<option>" ;  
               }
               $('select[name=session_number]').append($(option_tag, {
                  value: i,
                  text: 'Session '+i
               }));
            }
           // commented to stop reload loop - sd - 2/22/18
           //  $('select[name=session_number]').trigger('change');
         }).trigger("change");
         
         // hide the session delay time when session is 1
         if(session_number==1){
            $("#session_delay_time").addClass("hide");
         }else{
            $("#session_delay_time").removeClass("hide");
         }

         // set session value based on select session
         $('select[name=session_number]').change(function(){
            currentVal = $(this).val();
            if(currentVal==1){
               // hide the Session Delay field
               $("#session_delay_time").addClass("hide");
            }else{
               $("#session_delay_time").removeClass("hide");
            }
            quantVal = $('input[name=quantity]').val();
            $.ajax({
              url: "./ajax/set_session_data.php",
              method: "POST",
              data: {type: "procedures_save",sess_id: currentVal, num_sess: quantVal, pe_id: '<?php echo $pe_id;?>'},
              dataType: "text",
            }).done(function(response){
               // once ajax is completed
	       console.log(response);
               window.location.reload(true);
               //window.location.assign("procedures.php?m=managesurveys&id=<?php echo $pe_id; ?>&r="+Math.rando());
            });
         });

         $('input[name=sessionName]').keyup(function(){
            currentVal = $('select[name=session_number]').val();
            currentName = $(this).val();
            $.ajax({
              url: "./ajax/set_session_data.php",
              method: "POST",
              data: {type: "sessionname_save",sess_id: currentVal, sess_name: currentName, pe_id: '<?php echo $pe_id;?>'},
              dataType: "text",
            });
         });
        
         $('input[name=quantity]').change(function(){
           var currentVal = $(this).val();
           $.ajax({
              url: "./ajax/set_session_data.php",
              method: "POST",
              data: {type: "numberofsession_save",noofsession: currentVal, pe_id: '<?php echo $pe_id;?>'},
              dataType: "text",
            }).done(function(response){
               // once ajax is completed
               console.log(response);
            });

         });

        // add sortable feature
           $("#sortable").sortable({
               axis:"y",
               cancel: "span",
	           update: function(e, ui) {
		           //update our id based on our id
		           var order = [],
			           currentSession = $('select[name=session_number]').val();
		           $(this).find("li").each(function() {
		           	    var $li = $(this);
		           	    order.push($li.attr('data-survey'));
		           });
		           $.post("./ajax/set_session_data.php", { type: 'sort_surveys', pe_id: '<?php echo $pe_id; ?>', sess_id: currentSession, surveys: order });
	           }
           });
           $("#sortable").disableSelection();

           $("input[name=sessionName]").on("change", function(e) {
           	    var $this = $(this),
	                currentSession = $('select[name=session_number]').val();
           	    $.post("./ajax/set_session_data.php", { type: 'update_session_name', pe_id: '<?php echo $pe_id; ?>', sess_id: currentSession, session_name: $this.val()});
           });
	        $("input[name=session_delay]").on("change", function(e) {
		        var $this = $(this),
			        currentSession = $('select[name=session_number]').val();
		        $.post("./ajax/set_session_data.php", { type: 'update_session_delay', pe_id: '<?php echo $pe_id; ?>', sess_id: currentSession, session_delay: $this.val()});
	        });

     });

     </script>  
   </body>
</html>
