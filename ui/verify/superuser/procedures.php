<!doctype html>
<?php
// ***************************************
// superuser/procedures.php
// 2017 Copyright, Mesh Integration LLC
// 1/14/18 - WEL
// 03/14/18 - SD - add pagination value into session  
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
$msall_hide = "hide";

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
else if ($mode=="msall")
{
   $msall_hide = "";
   // save the procedure ID to comapare against next time
   $_SESSION['pe_id_prev']=$pe_id;
   $pe_id=$id;
}
else if ($mode=="managesurveys")
{
   $managesurveys_hide = "";
   $sess_id = $_SESSION['session_number'];
logMsg("managesurveys at Top: SESSION[sn]= ".$_SESSION['session_number'], "wel.log");
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
      $get_from_db=$_GET['gfdb'];
   }
   // get all the session surveys details in SESSION variable.
}
else if ($mode=="addsurveys")
{
   $addsurveys_hide = "";
   $ms = get_query_string('ms');      // manage survey mode - "all" or "one"
   $sess_id = $_SESSION['session_number'] = get_query_string('sess_id');
logMsg("Add surveys at Top: SESSION[sn]= ".$_SESSION['session_number'], "wel.log");
   $pe_id=$id;
   if (isset($_POST['survey_searchterm']))
      $searchterm=$_POST['survey_searchterm'];
   else 
      $searchterm=get_query_string('sst');
      // $searchterm="";
//logMsg("SURVEY SEARCH - P: ".$_POST['survey_searchterm']." V: $searchterm","wel.log");
}

logMsg("Procedures: Mode: $mode", $logfile);
if ($_SESSION['prev_session_number']<>$_SESSION['session_number'] && false)
{
   // the session number dropdown must of changed in managesurveys - reload page
   $_SESSION['prev_session_number']=$_SESSION['session_number'];
   header("Location: procedures.php?m=managesurveys&id=$pe_id");
   exit();
}

/** START SORT & FILTER */
$_order = [];

if($time_added = get_query_string("time_added")) {
        if ($time_added==1 && $_SESSION['filter']['time_added']==1)
           unset($_SESSION['filter']['time_added']);
        else if ($time_added==2 && $_SESSION['filter']['time_added']==2)
           unset($_SESSION['filter']['time_added']);
        else
           $_SESSION['filter']['time_added'] = get_query_string('time_added');
           unset($_SESSION['filter']['name']);
}
if($name = get_query_string("name")) {
        if ($name==1 && $_SESSION['filter']['name']==1)
           unset($_SESSION['filter']['name']);
        else if ($name==2 && $_SESSION['filter']['name']==2)
           unset($_SESSION['filter']['name']);
        else
           $_SESSION['filter']['name'] = get_query_string('name');
           unset($_SESSION['filter']['time_added']);
}

if (isset($_SESSION['filter']['time_added'])) {
        switch($_SESSION['filter']['time_added']) {
                case "1": $_order[] = 'dateCreated DESC'; break;
                case "2": $_order[] = 'dateCreated ASC'; break;
        }
}

if (isset($_SESSION['filter']['name'])) {
        switch($_SESSION['filter']['name']) {
                case "1": $_order[] = 'c_procedureId ASC'; break;
                case "2": $_order[] = 'c_procedureId DESC'; break;
        }
}

//setting default
if(!$_order) {
        $_order[] = 'c_procedureId ASC';
        $_SESSION['filter']['name'] = 1;
}
unset($time_added, $name);
/** END FILTER */

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
  <title>EIDO Verify</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <!-- was having trouble resolving this font-awesome css file from the below link, added reference to use the locally stored one instead-->
  <!--<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/fontawesome-all.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/app.css">
    <link rel="stylesheet" href="../css/jquery-ui-1.12.1.min.css">
    <link rel="stylesheet" href="../css/eido.css">
    <script src="../js/jquery-1.12.4.min.js"></script>
    <script src="../js/jquery-ui-1.12.1.min.js"></script>
    <!--<script src="../js/jquery.ui.touch-punch.min.js"></script>-->
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
      <?php include "../includes/su_header.php"; ?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <div class="grid-x padding-x">
    <div class="cell page-title">Superuser Dashboard</div>
    <div class="cell navigation-bar">
	  <ul class="menu simple show-for-medium">
		<li><a href="users_a.php?m=clearsession">Users</a></li>
		<li><a href="organisations_a.php?m=clearsession">Organisations</a></li>
		<li class="current"><a href="procedures_a.php?m=clearsession&g=add">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
	  </ul>
	  <ul class="vertical menu align-center hide-for-medium">
        <li><a href="users_a.php?m=clearsession">Users</a></li>
		<li><a href="organisations_a.php?m=clearsession">Organisations</a></li>
		<li class="current"><a href="procedures_a.php?m=clearsession&g=add">Procedures</a></li>
		<li><a href="https://p.datadoghq.com/sb/58e98b188-f2dbe0e7169491992f629b07c0d075c1" target="_blank">System Health &amp; Logs</a></li>
		<li><a href="http://piwik.cyberacc.net/index.php?module=CoreHome&action=index&idSite=2&period=day&date=yesterday&updated=1#?idSite=2&period=day&date=yesterday&category=Dashboard_Dashboard&subcategory=1" target="_blank">System Analytics</a></li>
      </ul>
  	</div>
  </div>		
  <!-- End Title Bar & Navigation -->  
  <!-- Start Content -->
  <div class="grid-x su" data-equalizer data-equalize-on="medium">
    <!-- Start Content-Left -->
      <div class="small-12 medium-6 large-6 cell content-left" style="padding-top: 0 !important;">
	    <div class="grid-x row">
		    <div class="small-12 medium-12 cell">
			    <div class="tabs tab-actions" data-accordion data-allow-all-closed="true" style="margin: 0;">
				    <div class="small-12 medium-12 cell accordion-item" data-accordion-item style="line-height: 1.46;">
                        <div class='grid-x row'>
                            <div class="small-6 medium-6 cell text-left padding-10">
                            </div>
                            <div class="small-6 medium-6 cell" style="padding-top:22px; padding-right: 20px; margin-bottom: -12px;visibility: hidden;">
                                <?php if(isset($_SESSION['filter']['time_added']) || isset($_SESSION['filter']['name'])): ?>
                                    <span class="float-right">Filters Active | <a href="clear_filter.php?m=<?php echo $mode; ?>" class="float-right link-orange ">&nbsp; Reset</a></span>
                                <?php else: ?>
                                    <span class="float-right">Filters Disabled</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="#" class="accordion-title sort sortonly">
                        </a><br />
<!-- START SORT PANEL -->
					    <div class="accordion-content sort" data-tab-content style="border-bottom: 0px solid;">
						    <div class="grid-x rule" style="padding-left: 23px; padding-top: 15px; border-top: 1px solid #d3d1d1;margin-bottom: 0px;border-bottom: 0px;">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Time Added</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="procedures.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? "selected" : "inactive"; ?>">Newest First</a>&nbsp;<a href="procedures.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? "selected" : "inactive"; ?>">Oldest First</a>
							    </div>
						    </div>
                            <hr style="margin-top: 0;margin-bottom: 15px;margin-right: 0px;margin-left: 0px;"></hr>
						    <div class="grid-x rule" style="padding-left: 23px; border-bottom: 0 solid; margin-bottom: 0px;border-top: 0px;">
							    <div class="small-12 medium-4 cell">
								    <label for="middle-label" class="middle">Name</label>
							    </div>
							    <div class="small-12 medium-8 cell">
								    <a href="procedures.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="procedures.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
							    </div>
                            </div>
						 </div>
				     </div>
                    <div class="hide small-12 medium-12 cell hide accordion-item" data-accordion-item style="line-height: 1.46;">
                    <a href="#" class="hide accordion-title sort bulkactions eido icon">+
                    </a><br />
                    <div class="hide accordion-content sort" data-tab-content style="border-bottom: 0px solid;">
                    <div class="grid-x rule" style="padding-left: 23px; border-bottom: 0 solid; margin-bottom: 0px;">
                        <div class="small-12 medium-4 cell">
                            <label for="middle-label" class="middle">Name</label>
                        </div>
                        <div class="small-12 medium-8 cell">
                            <a href="procedures.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 1) ? "selected" : "inactive"; ?>">A-Z</a>&nbsp;<a href="procedures.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name'] == 2) ? "selected" : "inactive"; ?>">Z-A</a>
                        </div>
                    </div>
                        </div>
                    </div>
			    </div>
		    </div>
        </div>
	    <ul class="patient-list">
		    <?php
		    $sql = "SELECT * 
                            FROM $TBLPROCEPISODES 
                            ORDER BY ".implode(',', $_order)." 
                            LIMIT $start,$row";
// logMsg(">>>> $sql","wel.log");
		    $GetQuery = dbi_query($sql);
		    while ($qryResult=$GetQuery->fetch_assoc()) {
		    $list_id = $qryResult['id'];
            $c_procedure_id = strtoupper($qryResult['c_procedureId']);
		    $isSelected = '';
		    if ($list_id == $id) {
			    $isSelected = ' class="selected"';
		    }
		    ?>
		    <li<?php echo $isSelected; ?> >
			    <a href="procedures.php?m=update&id=<?php echo $list_id; ?>" >
				    <span class="float-right right-arrow"><i class="eido-icon-chevron-right"></i></span>
                    <div class="grid-x">
                            <div class="small-11 column">
				                <p style="margin-left:13px !important;">
					             <strong><?php echo $c_procedure_id." - ".$qryResult['c_description']; ?></strong><br />
					             <?php echo $qryResult['c_displayName']; ?>
				                </p>
                            </div>

                    </div>
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
        <?php  if ($mode=="add" || $mode=="")
               {
                  $survey_msg=$_SESSION['survey_msg'];
                  $_SESSION['survey_msg']="";
               }
        ?>
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $add_hide; ?>">
	  <h3 class="" style="padding-bottom: 11px !important;">Add Procedure</h3>
	  <form action="procedures_a.php?m=add" method="post">
        <div class="grid-container">
    	  <div class="grid-x">
      	    <div class="small-12 cell field">
                  <?php if ($_SESSION['description_error']) echo "<div class='error_message fi-alert'><strong>Please enter the procedure name</strong> - this is required</div>"; ?>
        	  <label class="weight-normal">Procedure Name
                <input type="text" name="c_description" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
                  <?php if ($_SESSION['procedureid_error']) echo "<div class='error_message fi-alert'><strong>Please enter the procedure code</strong> - this is required</div>"; ?>
              <label class="weight-normal">EIDO Procedure Code
                <input type="text" name="c_procedureId" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field">
                  <?php if ($_SESSION['procedureid_error']) echo "<div class='error_message fi-alert'><strong>Please enter the display name</strong> - this is required</div>"; ?>
               <label class="weight-normal">Display Name
                <input type="text" name="c_displayName" placeholder="">
              </label>
            </div>
      	    <div class="small-12 cell field text-center">
		      <p style="padding-top: 15px;"><BR /><input type="submit" name="add" value="Add Procedure" class="button large" style="padding-right: 25px;padding-left: 25px;"/></p>
            </div>
    	  </div>
	    </div>
	  </form>
<hr class="gap">
          <h3 class="padding-bottom-1">Refresh Survey List</h3>
          <p>&nbsp;</p>
          <p class="standard-padding text-center padding-bottom-1">The Survey Gizmo surveys update once per day. If you need to manually refresh, use the button below.</p>
          <div class="standard-padding">
            <div class="small-12 cell field text-center" style="padding-top:15px;">
              <a href="get_all_surveys.php"><span class="button large">Refresh Surveys</span></a>
              <?php if (strlen($survey_msg)){
                       echo "<br /><strong>$survey_msg</strong>";
                    }
              ?>
          </div>
          </div>
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
          <h3 class="padding-bottom-1">View Procedure</h3>
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
                <div class="small-12 medium-12 large-12 cell text-center grid-padding-15">
                  <a href="procedures.php?m=managesurveys&gfdb=1&id=<?php echo $pe_id; ?>" class="no-u"><p class="directive">Manage Surveys<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a>
	                <br>
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
               logMsg("--------------- ManageSurveys --------------","wel.log");
               $session_number = $_SESSION['session_number'];
               logMsg("SESSION SN: ".$_SESSION['session_number'] ,"wel.log");
               $arr_proc_episode =  get_proc_episode($pe_id, $session_number,$get_from_db);
//echo "<PRE>";
//print_r($arr_proc_episode);
//echo "</PRE>";
//exit();
               // set default session if number of session is not set
               if(!empty($arr_proc_episode['c_numberOfSessions'])){
                  $noOfSession = $arr_proc_episode['c_numberOfSessions'];
               }elseif(!empty($_SESSION['pe_id'.$pe_id]['numberofsession'])){
                  $noOfSession = $_SESSION['pe_id'.$pe_id]['numberofsession'];
               }else{
                  $noOfSession = 2; // default session number
               }
               logMsg("number of sessions: $noOfSession" ,"wel.log");

               // set session name
               if(!empty($arr_proc_episode['sessionName'])){
                  $sessionName = $arr_proc_episode['sessionName'];
               }elseif(!empty($_SESSION['pe_id'.$pe_id]['sessionName'.$sess_id])){
                  $sessionName = $_SESSION['pe_id'.$pe_id]['sessionName'.$sess_id];
               }else{
                  $sessionName = "Session ".$sess_id; // default session name
               }

               logMsg("numberOfSessions from arr_proc_episode: ".$arr_proc_episode['c_numberOfSesssions'],"wel.log");
               $num_surveys=get_num_surveys_by_proc($pe_id, $session_number); // number of surveys in the current session
               logMsg("managesurveys: # of surveys (from get_num_surveys_by_proc): $num_surveys ","wel.log");

        // WTF?? WEL
               if ($pe_id<>$_SESSION['pe_id_prev'] || true)
               { 
                  $_SESSION['sessionSurvey1'] = $arr_proc_episode['sessionSurvey1'];
                  $arr_add_surveys = array(); 
                  if ($arr_proc_episode['sessionSurvey1']<>"")
                     $arr_add_surveys[] = $arr_proc_episode['sessionSurvey1']; 
                  $_SESSION['sessionSurvey2'] = $arr_proc_episode['sessionSurvey2'];
                  if ($arr_proc_episode['sessionSurvey2']<>"")
                     $arr_add_surveys[] = $arr_proc_episode['sessionSurvey2']; 
                  $_SESSION['sessionSurvey3'] = $arr_proc_episode['sessionSurvey3'];
                  if ($arr_proc_episode['sessionSurvey3']<>"")
                     $arr_add_surveys[] = $arr_proc_episode['sessionSurvey3']; 
                  $_SESSION['sessionSurvey4'] = $arr_proc_episode['sessionSurvey4'];
                  if ($arr_proc_episode['sessionSurvey4']<>"")
                     $arr_add_surveys[] = $arr_proc_episode['sessionSurvey4']; 
                  $_SESSION['sessionSurvey5'] = $arr_proc_episode['sessionSurvey5'];
                  if ($arr_proc_episode['sessionSurvey5']<>"")
                     $arr_add_surveys[] = $arr_proc_episode['sessionSurvey5']; 
                  // WEL 5/29/18 - we need to pre-populate SESSION['arr_add_surveys']
                  $_SESSION['arr_add_surveys'][$pe_id] = array_unique($arr_add_surveys); // WEL
               }
               if ($arr_proc_episode['prePost']=="Pre")
               // if ($_SESSION["pe_id".$pe_id]['session_type'.$sess_id]=="Pre")
               {
                  $pre_color = "active";
                  $post_color = "inactive";
               }
               else if ($arr_proc_episode['prePost']=="Post")
               // else if ($_SESSION["pe_id".$pe_id]['session_type'.$sess_id]=="Post")
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
        <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $managesurveys_hide; ?>">
            <div style="padding-top: 15px;" class="back clickable-row btn-back" data-href="procedures.php?m=update&id=<?php echo $pe_id; ?>">
                    <a href="procedures.php?m=update&id=<?php echo $pe_id; ?>">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
                    </a>
            </div>
          <h3>Procedure Setup<br /><span class="small sub-text">Add surveys to the procedure session</span></h3>
          <form action="procedures_a.php?m=updateproc&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&ms=one" method="post"><div class="grid-container">
          <div class="grid-x grid-padding-x">
              <div class="small-12 medium-12 large-12 cell">
              <h4 class="vertical-middle" style="margin-left:0"><?php echo $arr_proc_episode['c_procedureId']." - ".$arr_proc_episode['c_description']; ?></h4>
			  <hr />
              </div>
              <div class="small-4 medium-4 large-4 cell">
                  <div class="small-offset-3 medium-offset-3 large-offset-3 cell">
                      <label style="margin-top: 5px">Number of Sessions
                          <div class="input-group plus-minus-input">

                              <a style="height: 40px !important;" class="button2 fc" data-quantity="minus" data-field="quantity"><i class="largeicon eido-icon-minus-circle"></i></a>
                              <input style="height: 40px !important;" class="input-group-field" type="text" name="quantity" value="<?php echo $noOfSession; ?>" style="width: 40px;">
                              <a style="height: 40px !important;" class="button2 fc" data-quantity="plus" data-field="quantity"><i class="largeicon eido-icon-plus-circle"></i></a>

                          </div>
                      </label>
                  </div>
              </div>
          </div>
          <div class="small-12 medium-12 large-12 cell"></div>
              <div class="grid-x grid-padding-x search_bar small-hide">
                 <!--<div class="grid-x">-->

                  <!-- <div class="small-12 medium-4 margin-vertical-2"> -->
                   <div class="small-6 medium-6 large-6 cell">
                       <select name="session_number" >
                           <option value="1" <?php if ($session_number==1) echo "selected"; ?>>Session 1</option>
                           <option value="2" <?php if ($session_number==2) echo "selected"; ?>>Session 2</option>
                           <option value="3" <?php if ($session_number==3) echo "selected"; ?>>Session 3</option>
                           <option value="4" <?php if ($session_number==4) echo "selected"; ?>>Session 4</option>
                           <option value="5" <?php if ($session_number==5) echo "selected"; ?>>Session 5</option>
                           <option value="6" <?php if ($session_number==6) echo "selected"; ?>>Session 6</option>
                       </select>
                   </div>
                   <div class="small-3 medium-3 large-3 cell cell">
                      &nbsp;
                   </div>
                   <div class="small-3 medium-3 large-3 cell">
                      <a href="procedures.php?m=msall&id=<?php echo $pe_id; ?>"><button class="button align-right" type="button" style="height: 39px !important; "><strong style="color: white">Show All</strong></button></a>
                   </div>
              </div>
          </div>
                            <!--<div class="small-12 medium-6 large-8 cell"></div>-->
                          <!--</div>-->
            <div class="small-12 medium-12 large-12 cell">
              <div class="grid-x grid-padding-x align-middle">
                <div class="small-12 medium-8 large-8 cell">

              </div>
              </div>
              <hr />
            </div>
           <div class="small-12 medium-12 large-12 cell">
                          <h5>Session Name<br /><span class="small">This name will be used to identify the session to hospital staff.</span></h5>
            <div class="small-12 cell"><input type="text" id="sn1" name="sessionName" value="<?php echo $sessionName; ?>" placeholder=""><br /></div>
               <div class="input-group">
                   <span class="input-group-label">Type</span>
                      <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=Pre&ms=one"><button class="button <?php echo $pre_color; ?> btn-pre" type="button">&nbsp;&nbsp;Pre&nbsp;&nbsp; </button></a>
                      &nbsp;&nbsp; <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=Post&ms=one"><button class="button <?php echo $post_color; ?> btn-pre" type="button">Post</button></a>
               </div>
            </div>
            <div class="small-12 medium-12 large-12 cell" id="session_delay_time">
               <p><h5>Session Delay Time</h5>
               <input type="text" size="4" name="session_delay" value="<?php echo isset($arr_proc_episode['sessionDelay']) ? $arr_proc_episode['sessionDelay'] : ''; ?>"><b: /></p>
            </div>
            <div class="small-12 medium-12 large-12 cell">
                     <ul class="sort" id="sortable" style="margin-bottom:40px;" >
                          <?php 
                           // ************************************
                           // display the list of surveys so far
                           // ************************************
                           $survey_ids = array();
                           $survey_ids = get_surveys_by_proc($pe_id,$sess_id); // $_SESSION['pe_id'.$pe_id]["sess_id".$sess_id];
//logMsg("procId: $pe_id - sessId: $sess_id - numOfSurveys: ".count($survey_ids), "wel.log");
                           // $_SESSION['arr_add_surveys'][$pe_id] = $survey_ids; // HACK WEL
                           for ($i=0; $i<count($survey_ids); $i++) { 
                              $arr_survey_info = get_survey_by_num($survey_ids[$i]);
                              if(!empty($arr_survey_info)){
                          ?>
                              <li data-survey="<?php echo $survey_ids[$i]; ?>">
	                              <i class="fi-list sort-icon move"></i>
	                              <span class="not-allow-move">
		                              <a href="functions.php?m=delete_proc_survey&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&indx=<?php echo $i; ?>&ms=one" class="float-right"><i class="fi-trash sort-icon float-right"></i></a>
		                              <?php echo $survey_ids[$i]." - ".$arr_survey_info['c_description']; ?>
	                              </span>
                              </li>
                          <?php  }
                           } ?>
                          <a href="procedures.php?m=addsurveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&ms=one"><li class="add"><button class="button expanded text-center add-survey" type="button"><i class="eido-icon-plus-circle"></i></button></li></a>
                         <!--<img src="../img/icons/add_white.png" alt="add icon" />  removed and replaced with svg icon in above line-->
                     </ul>
                  </div>
                  <div class="small-12 medium-12 large-12 cell text-center">
                       <!-- <input type="submit" class="button large" name="updateproc" value="Update Session"><br /> -->
                       <input type="submit" class="button large" name="updateproc" value="Update Procedure">
                  </div>
            </form>
          </div>
        <!-- </div>
         </div>  -->
    <!-- END MANAGESURVEYS SECTION -->
    <!-- ADDSURVEYS SECTION -->
      <?php if ($mode=="addsurveys")
      {
          $as_proc_name = get_proc_name($pe_id);
          $as_session_name = get_session_name($pe_id, $sess_id);
          
          if ($pe_id<>$_SESSION['pe_id_prev'] || $sess_id<>$_SESSION['sess_id_prev'])
          {
              $arr_all_surveys=get_all_surveys($searchterm);
              $_SESSION['arr_all_surveys']=$arr_all_surveys;
          }
// added 4/8
          $arr_all_surveys=get_all_surveys($searchterm);
          $_SESSION['arr_all_surveys']=$arr_all_surveys;
// $arr_all_surveys=$_SESSION['arr_all_surveys'];
          $_SESSION['pe_id_prev']=$pe_id;
          $_SESSION['sess_id_prev']=$sess_id;

          if ($ms=="one") {
             $back_str = "managesurveys&id=$pe_id&sess_id=$sess_id";
             $arr_add_surveys = $_SESSION['arr_add_surveys'][$pe_id];
          } else {
             $back_str = "msall&id=$pe_id&sess_id=$sess_id";
             // for ALL can't use session
             $arr_add_surveys = $_SESSION['arr_add_surveys_all'][$sess_id]; // get_surveys_by_proc($pe_id,$sess_id);
          }
      }
      // NOTE _ CHANGE THIS LATER
      // commented below line on 3/26/2018 - to make sess_id variable
      // $sess_id = 1;
      ?>
      <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $addsurveys_hide; ?>">
          <div style="padding-top: 15px;" class="back clickable-row btn-back" data-href="procedures.php?m=<?php echo $back_str; ?>">
              <a href="procedures.php?m=<?php echo $back_str; ?>">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
              </a>
          </div>
          <h2>Add Surveys</h2>
          <form action="procedures.php?m=addsurveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&ms=<?php echo $ms; ?>" method="post">
              <div class="grid-container">
                  <div class="grid-x grid-padding-x">
                      <div class="small-12 medium-12 large-12 cell">
                          <div class="grid-x grid-padding-x search_bar" style="padding-right: 0px !important;">
                             <!-- <div class="small-8 cell">
                                  <input type="text" name="survey_searchterm" value="<?php echo $searchterm; ?>"placeholder="" class="search-left">
                              </div> -->
                              <div class="small-8 medium-8 large-8 cell gh_form basic" style="padding-left: 0px !important; padding-right: 0px !important;">
                                  <div class="input-group tb-search">
                                      <div class="input-group-button left-append">
                                          <i class="fi-magnifying-glass"></i>
                                      </div>
                                      <input type="text" name="survey_searchterm" value="<?php echo $searchterm; ?>" placeholder="" class="input-group-field">
                                      <div class="input-group-button right-append">
                                          <a href="procedures.php?m=addsurveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&ms=<?php echo $ms; ?>" class="clear-icon">
                                              <i class="eido-icon-x-altx-alt"></i>
                                          </a>
                                      </div>
                                  </div>
                              </div>
                              <div class="small-3 medium-3 large-3 cell gh_form" style="padding-right: 5px !important; padding-left: 15px !important; right: -15px;">
                                  <div class="" style="padding-right: 0px !important;"><input type="submit" value="Search" class="button" style="font-size: 1em; margin-right: 5px;"></a></div>
                              </div>
                          </div>
                      </div>
                      <div class="small-12 medium-12 large-12 cell" style="overflow-y: auto; height: 500px;">
                          <table width="100%" border="0" class="hover" id="availablesurveys">
                              <tbody>
                              <?php
                              for ($s=0; $s<count($arr_all_surveys); $s++) {
                                  if (!$arr_all_surveys[$s]['added']) {
                                      ?>
                                      <tr>
                                          <td class="text-left" width="90%" id="content"><?php echo $arr_all_surveys[$s]['c_surveyNumber']." - ".$arr_all_surveys[$s]['c_description']; ?></td>
                                          <td class="text-left"><a href="functions.php?m=add_survey_to_temp&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&s=<?php echo $s; ?>&sn=<?php echo $arr_all_surveys[$s]['c_surveyNumber']; ?>&ms=<?php echo $ms; ?>&sst=<?php echo $searchterm; ?>"><i class="fi-plus"></i></a></td>
                                      </tr>
                                  <?php     }
                              }
                              ?>

                              </tbody>
                          </table>
                      </div>
                      <div class="small-12 medium-12 large-12 cell">
                          <hr />
                          <table width="100%" border="0" class="" id="addedsurveys">
                              <tbody>
                                 <tr>
                                    <td class="text-left" colspan="2">
                                       <strong><?php echo "$as_proc_name | $as_session_name ($sess_id)"; ?></strong>
                                    </td>
                                 </tr>
                              <?php
                              // ******************************************
                              // list the surveys selected for this session
                              // ******************************************
                  //            logMsg("mode: $mode - AS arraddsurveys ct: ".count($arr_add_surveys),"wel.log");
                              //  arr_add_surveys=session was here  
                              //$arr_survey_list = get_survey_by_num($arr_add_surveys);
                              $t = 0;
                              for ($j=0; $j<count($arr_add_surveys); $j++) {
                                  $arr_survey_info=get_survey_by_num($arr_add_surveys[$j]);
                                  // if(count($arr_survey_list)){
                                  //  foreach ($arr_survey_list as $arr_survey_info){
                              ?>
                                  <tr>
                                      <td class="text-left" width="90%" id="content"><?php echo $arr_survey_info['c_surveyNumber']." - ".$arr_survey_info['c_description']; ?></td>
                                      <td class="text-right"><a href="functions.php?m=delete_survey_from_temp&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&t=<?php echo $t; ?>&sn=<?php echo $arr_survey_info['c_surveyNumber']; ?>&ms=<?php echo $ms; ?>&sst=<?php echo $searchterm; ?>"><i class="fi-trash sort-icon"></i></a></td>
                                  </tr>
                                  <?php
                                  $t++;
                              }
                              // }
                              ?>
                              </tbody>
                          </table>
                      <hr />
                      </div>
                      <div class="small-12 medium-12 large-12 cell text-center">
                          <a href="functions.php?m=add_selected_surveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $sess_id; ?>&ms=<?php echo $ms; ?>" class="button large" name="" >Add Selected</a>
                      </div>
                  </div>
              </div>
          </form>
      </div>
<!-- END ADDSURVEYS SECTION -->
<!-- MSALL SECTION -->
      <?php // manage surveys ALL
         if ($mode=="msall") {
            $arr_proc_info = get_proc_info($pe_id);
            $noOfSession = $arr_proc_info[0]['c_numberOfSessions'];
            // fill the special SESSION array for the ALL add survey list
            for ($s=1;$s<=$MAX_SURVEYS;$s++) {
               $survey_ids = array();
               $survey_ids = get_surveys_by_proc($pe_id,$s); 
  //             logMsg("procId: $pe_id - sessId: $sess_id - numOfSurveys: ".count($survey_ids), "wel.log");
               $_SESSION['arr_add_surveys_all'][$s] = $survey_ids;
            }
         }
      ?>
        <div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $msall_hide; ?>">
            <div style="padding-top: 15px;" class="back clickable-row btn-back" data-href="procedures.php?m=managesurveys&id=<?php echo $pe_id; ?>">
                    <a href="procedures.php?m=update&id=<?php echo $pe_id; ?>">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
                    </a>
            </div>
          <h3>Procedure Setup<br /><span class="small sub-text">Add surveys to the procedure session</span></h3>
          <form action="procedures_a.php?m=msall&id=<?php echo $pe_id; ?>" method="post"><div class="grid-container">
          <div class="grid-x grid-padding-x">
              <div class="small-12 medium-12 large-12 cell">
                  <h4 class="vertical-middle" style="margin-left:0"><?php echo $arr_proc_info[0]['c_procedureId']." - ".$arr_proc_info[0]['c_description']; ?></h4>
                  <hr />
               </div>
               <div class="small-12 medium-12 large-12 cell">
                 <div class="grid-x">
                       <!--<div class="hide-for-small-only medium-1 large-1 cell">&nbsp;</div>-->
                        <div class="small-8 medium-8 large-8 cell">
                               <label style="margin-top: 5px">Number of Sessions
                               <div class="input-group plus-minus-input">
                                   <a style="height: 40px !important;" class="button2 fc" data-quantity="minus_all" data-field="quantity_all"><i class="largeicon eido-icon-minus-circle"></i></a>
                                   <input style="height: 40px !important;" class="input-group-field" type="text" name="quantity_all" value="<?php echo $noOfSession; ?>" style="width: 40px;">
                                   <a style="height: 40px !important;" class="button2 fc" data-quantity="plus_all" data-field="quantity_all"><i class="largeicon eido-icon-plus-circle"></i></a>

                               </div>
                               </label>
                        </div>
                        <div class="small-1 medium-1 large-1 cell">
                           &nbsp;
                        </div>
                        <div class="small-3 medium-3 large-3">
                              <span style="vertical-align: -43px;">
                                  <a href="procedures.php?m=managesurveys&id=<?php echo $pe_id; ?>"><button class="button" type="button" style="height: 39px !important; "><strong style="color: white; ">Hide All</strong></button></a>
                              </span>
                        </div>
                 </div>
              </div>
              <!--<div class="small-12 medium-6 large-8 cell"></div>-->
          </div>
     <!--  </div> -->
        <?php for ($s=1; $s<=$noOfSession; $s++) 
         {  // *********************************
            // start loop over each session
            // *********************************
        ?>
            <div class="small-12 medium-12 large-12 cell">
              <div class="grid-x grid-padding-x align-middle">
                <div class="small-12 medium-8 large-8 cell">

              </div>
              </div>
              <hr />
            </div>
           <div class="small-12 medium-12 large-12 cell">
                   <?php if ($_SESSION['session_name_error'][$s]) echo "<div class='error_message fi-alert'><strong>Please enter a session name</strong> - this is required</div>"; ?>
                          <h5><?php echo $s; ?>. Session Name<br /><span class="small">This name will be used to identify the session to hospital staff.</span></h5>
        <?php $varname = "c_session".$s."Name"; ?>
            <div class="small-12 cell"><input type="text" name="<?php echo $varname; ?>" value="<?php echo $arr_proc_info[$s][$varname]; ?>" class="sessionName" placeholder=""><br /></div>
        <?php 
               $varname = "c_prePost".$s;
               if ($arr_proc_info[$s][$varname]=="Pre")
               {
                  $pre_color = "active";
                  $post_color = "inactive";
               }
               else if ($arr_proc_info[$s][$varname]=="Post")
               {
                  $pre_color = "inactive";
                  $post_color = "active";
               }
               else
               {
                  $pre_color = "inactive";
                  $post_color = "inactive";
               }
         ?>
                   <?php if ($_SESSION['session_type_error'][$s]) echo "<div class='error_message fi-alert'><strong>Please indicate if this is a Pre or Post session</strong> - this is required</div>"; 
                         else if ($_SESSION['session_type_first_error'][$s]) echo "<div class='error_message fi-alert'><strong>The first session must be type PRE</strong></div>";
                         else if ($_SESSION['session_type_order_error'][$s]) echo "<div class='error_message fi-alert'><strong>You cannot have a PRE session after a POST</strong></div>";
                         else if ($_SESSION['session_type_nopost_error'][$s]) echo "<div class='error_message fi-alert'><strong>You must have at least one POST-OP session</strong></div>";
                   ?>
               <div class="input-group">
                   <span class="input-group-label">Type</span>
                      <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $s; ?>&t=Pre&ms=all"><button class="button <?php echo $pre_color; ?> btn-pre" type="button">&nbsp;&nbsp;Pre&nbsp;&nbsp; </button></a>
                      &nbsp;&nbsp; <a href="functions.php?m=prepost&id=<?php echo $pe_id; ?>&sess_id=<?php echo $s; ?>&t=Post&ms=all"><button class="button <?php echo $post_color; ?> btn-pre" type="button">Post</button></a>
               </div>
            </div>
          <?php if ($s>1) { 
            $varname = "c_session".$s."Delay";
          ?>
            <div class="small-12 medium-12 large-12 cell" id="session_delay_time">
                <?php if ($_SESSION['session_delay_error'][$s]) echo "<div class='error_message fi-alert'><strong>Please enter the delay time for this session</strong> - this is required</div>"; ?>
               <p><h5>Session Delay Time</h5>
               <input type="text" size="4" class="sessionDelay" name="<?php echo $varname; ?>" value="<?php echo isset($arr_proc_info[$s][$varname]) ? $arr_proc_info[$s][$varname] : ''; ?>"><b: /></p>
            </div>
          <?php } ?>
            <div class="small-12 medium-12 large-12 cell">
                <?php if ($_SESSION['session_survey_error'][$s]) echo "<div class='error_message fi-alert'><strong>You must have at least one survey in a session</strong> - this is required</div>"; ?>
                     <ul class="sort" id="sortable_all" style="margin-bottom:40px;" >
                          <?php
                           // ************************************
                           // display the list of surveys so far
                           // ************************************
                           $survey_ids = array();
                           $survey_ids = get_surveys_by_proc($pe_id,$s); // $_SESSION['pe_id'.$pe_id]["sess_id".$sess_id];
                           for ($i=0; $i<count($survey_ids); $i++) {
                              $arr_survey_info = get_survey_by_num($survey_ids[$i]);
                              if(!empty($arr_survey_info)){
                          ?>
                                 <li data-survey="<?php echo $survey_ids[$i]; ?>" data-session="<?php  echo $s; ?>">
                                      <i class="fi-list sort-icon move"></i>
                                      <span class="not-allow-move">
                                              <a href="functions.php?m=delete_proc_survey&id=<?php echo $pe_id; ?>&sess_id=<?php echo $s; ?>&indx=<?php echo $i; ?>&ms=all" class="float-right"><i class="fi-trash sort-icon float-right"></i></a>
                                              <?php echo $survey_ids[$i]." - ".$arr_survey_info['c_description']; ?>
                                      </span>
                                 </li>
                          <?php  }
                           } ?>
                          <a href="procedures.php?m=addsurveys&id=<?php echo $pe_id; ?>&sess_id=<?php echo $s; ?>&ms=all"><li class="add"><button class="button expanded text-center add-survey" type="button"><i class="eido-icon-plus-circle"></i></button></li></a>
                         <!--<img src="../img/icons/add_white.png" alt="add icon" />  removed and replaced with svg icon in above line-->
                     </ul>
                  </div>
            <?php } // ********* end loop over sessions ?>
                  <div class="small-12 medium-12 large-12 cell text-center">
                  <input type="submit" class="button large" name="updateproc" value="Update Procedure">
            </div>
          </div>
          </div>
   </form>
<!-- END MSALL SECTION -->
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
     <?php include "../includes/footer.php"; ?>
  <!-- End Footer -->
</div>
    <!--<script src="../js/vendor/jquery.js"></script>-->
    <script src="../js/vendor/what-input.js"></script>
    <script src="../js/vendor/foundation.js"></script>
    <script src="../js/app.js"></script>
    <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

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

          // MANAGESURVEYS - This button will increment the value  PLUS
          $('[data-quantity="plus"]').click(function(e){
          // Stop acting like a button
          e.preventDefault();
          // Get the field name
          fieldName = $(this).attr('data-field');
          // Get its current value
          var currentVal = parseInt($('input[name='+fieldName+']').val());
          // If is not undefined
          if (!isNaN(currentVal) && currentVal < <?php echo $MAX_SESSIONS; ?>){
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1).change();
          } else {
            // Otherwise put MAX_SESSIONS there
            $('input[name='+fieldName+']').val(<?php echo $MAX_SESSIONS; ?>).change();
         }
         });
         
         // MANAGESURVEYS - This button will decrement the value till 0
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

      // MANAGESURVEYS - changed # of sessions - save
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

// ******* same 3 functions for MS ALL list *********

          // MSALL - This button will increment the value  PLUS
          $('[data-quantity="plus_all"]').click(function(e){
          // Stop acting like a button
          e.preventDefault();
          // Get the field name
          fieldName = $(this).attr('data-field');
          // Get its current value
          var currentVal = parseInt($('input[name='+fieldName+']').val());
          // If is not undefined
          if (!isNaN(currentVal) && currentVal < <?php echo $MAX_SESSIONS; ?>){
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1).change();
          } else {
            // Otherwise put MAX_SESSIONS there
            $('input[name='+fieldName+']').val(<?php echo $MAX_SESSIONS; ?>).change();
         }
         });

         // MSALL - This button will decrement the value till 0 MINUS
         $('[data-quantity="minus_all"]').click(function(e) {
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

      // MSALL - changed # of sessions - save
        $('input[name=quantity_all]').change(function(){
             var currentVal = $(this).val();
             $.ajax({
                url: "./ajax/set_session_data.php",
                method: "POST",
                data: {type: "numberofsession_save",noofsession: currentVal, pe_id: '<?php echo $pe_id;?>'},
                dataType: "text",
             }).done(function(response){
               // once ajax is completed
               console.log(response);
               window.location.reload(true); 
             });
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

         // set session value based on select session drop-down
         $('select[name=session_number]').change(function(){
            currentVal = $(this).val();
            sessionNumber_prev = <?php echo $_SESSION['session_number']; ?>;
            sessionName= $('input[name=sessionName]').val();
            sessionDelay= $('input[name=session_delay]').val();   
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
              data: {type: "procedures_save",sess_id: currentVal, num_sess: quantVal, pe_id: '<?php echo $pe_id;?>',
                     sess_id_prev: sessionNumber_prev, sess_name: sessionName, sess_delay: sessionDelay }, 
              dataType: "text",
            }).done(function(response){
               // once ajax is completed
	       console.log(response);
               window.location.reload(true);
               //window.location.assign("procedures.php?m=managesurveys&id=<?php echo $pe_id; ?>&r="+Math.rando());
            });
         });

//         $('input[name=sessionName]').keyup(function(){
//            currentVal = $('select[name=session_number]').val();
//            currentName = $(this).val();
//            $.ajax({
//              url: "./ajax/set_session_data.php",
//              method: "POST",
//              data: {  type: "sessionname_save",
//                       sess_id: currentVal, 
//                       sess_name: currentName, 
//                       pe_id: '<?php echo $pe_id;?>'
//                    },
//              dataType: "text",
//            });
//         });
        
// **** save the session name after entering it onblur() ******


          $(".sessionName").blur(function() {
            var pe_id = "<?php echo $pe_id ;?>";
            var sessionName = this.value;
            var fieldName = $(this).attr('name');
              if (fieldName == 'c_session1Name') {
                  var sess_id = 1;
              } else if (fieldName == 'c_session2Name') {
                  var sess_id = 2;
              } else if (fieldName == 'c_session3Name') {
                  var sess_id = 3;
              } else if (fieldName == 'c_session4Name') {
                  var sess_id = 4;
              } else if (fieldName == 'c_session5Name') {
                  var sess_id = 5;
              } else if (fieldName == 'c_session6Name') {
                  var sess_id = 6;
              }
              $.ajax({
                url: "./ajax/set_session_data.php",
                method: "POST",
                data: {  type: "sessionname_save",
                         sess_id: sess_id,
                         sess_name: sessionName,
                         pe_id: pe_id 
                      },
                dataType: "text",
              });
          });
// **** save the session name after entering it onblur() ******


            $(".sessionDelay").blur(function() {
                var pe_id = "<?php echo $pe_id ;?>";
                var sessionDelay = this.value;
                var fieldName = $(this).attr('name');
                if (fieldName == 'c_session1Delay') {
                    var sess_id = 1;
                } else if (fieldName == 'c_session2Delay') {
                    var sess_id = 2;
                } else if (fieldName == 'c_session3Delay') {
                    var sess_id = 3;
                } else if (fieldName == 'c_session4Delay') {
                    var sess_id = 4;
                } else if (fieldName == 'c_session5Delay') {
                    var sess_id = 5;
                } else if (fieldName == 'c_session6Delay') {
                    var sess_id = 6;
                }
                $.ajax({
                    url: "./ajax/set_session_data.php",
                    method: "POST",
                    data: {  type: "sessiondelay_save",
                        sess_id: sess_id,
                        sess_delay: sessionDelay,
                        pe_id: pe_id
                    },
                    dataType: "text",
                });
            });
 //         function saveNameOne(pe_id, sess_id) {
 //             window.alert("HEY");
 //             sessionName = $('input[name=sessionName]').val();
 //             $.ajax({
 //               url: "./ajax/set_session_data.php",
 //               method: "POST",
 //               data: {  type: "sessionname_save",
 //                        sess_id: sess_id,
 //                        sess_name: sessionName,
 //                        pe_id: pe_id
 //                     },
 //               dataType: "text",
 //             });
 //         }
            $('input[name=sessionName]').blur(function() {

               // function saveNameOne(, ) {
                    //  function saveNameOne(pe_id, sess_id) {
                    //window.alert("HEY");
                    var pe_id = "<?php echo $pe_id; ?>";
                    var sess_id = "<?php echo $sess_id; ?>";
                    var sessionName = $('input[name=sessionName]').val();
                    $.ajax({
                        url: "./ajax/set_session_data.php",
                        method: "POST",
                        data: {
                            type: "sessionname_save",
                            sess_id: sess_id,
                            sess_name: sessionName,
                            pe_id: pe_id
                        },
                        dataType: "text",
                    });

            });

// **** save the session delay after entering it onblur() ******

          $('input[name=session_delay]').blur(function() {
              var pe_id = "<?php echo $pe_id; ?>";
              var sess_id = "<?php echo $sess_id; ?>";
              var sessionDelay = $('input[name=session_delay]').val();
              $.ajax({
                url: "./ajax/set_session_data.php",
                method: "POST",
                data: {  type: "sessiondelay_save",
                         sess_id: sess_id,
                         sess_delay: sessionDelay,
                         pe_id: pe_id
                      },
                dataType: "text",
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
		           $.post("./ajax/set_session_data.php", { type: 'sort_surveys', pe_id: '<?php echo $pe_id; ?>', sess_id: currentSession, surveys: order, ms: 'one' });
	           }
           });
           $("#sortable").disableSelection();

        // add sortable feature  *******  MSALL  *******
           $("#sortable_all").sortable({
               axis:"y",
               cancel: "span",
	           update: function(e, ui) {
		           //update our id based on our id
		           var order = [],
			           currentSession = $li.attr('data-session');
		           $(this).find("li").each(function() {
		           	    var $li = $(this);
		           	    order.push($li.attr('data-survey'));
		           });
		           $.post("./ajax/set_session_data.php", { type: 'sort_surveys', pe_id: '<?php echo $pe_id; ?>', sess_id: currentSession, surveys: order, ms: 'all' });
	           }
           });
           $("#sortable_all").disableSelection();

           $("input[name=sessionName]").on("change", function(e) {
           	    var $this = $(this),
	                currentSession = $('select[name=session_number]').val();
           	    $.post("./ajax/set_session_data.php", { 
                            type: 'update_session_name', 
                            pe_id: '<?php echo $pe_id; ?>', 
                            sess_id: currentSession, 
                            session_name: $this.val()
                    });
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
