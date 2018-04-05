<!doctype html>
<?php
// ***************************************
// patient/patients.php
// 2018 Copyright, Mesh Integration LLC
// 1/4/18 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
require_once "inc_tl_data.php";

if ($user_role<>"USER" && $user_role<>"ADMIN")
{
   header("Location: /ui/verify/login.php");
   exit();
}

require_once 'functions.php';
session_start();
$logfile = "patient.log";

////////////////////////////////////////////////
// sorting and filtering session setting 
////////////////////////////////////////////////

if (isset($_GET['filter']) && $_GET['filter']==1){
// set the filter detail in session

   if(isset($_GET['operation']) && $_GET['operation']==1){
      $_SESSION['filter']['operation'] = 1;
   }else if(isset($_GET['operation']) && $_GET['operation']==2){
      $_SESSION['filter']['operation'] = 2;
   } 
 
   if(isset($_GET['time_added']) && $_GET['time_added']==1){
      $_SESSION['filter']['time_added'] = 1; 
   }else if(isset($_GET['time_added']) && $_GET['time_added']==2){
      $_SESSION['filter']['time_added'] = 2;
   }

   if(isset($_GET['name']) && $_GET['name']==1){
      $_SESSION['filter']['name'] = 1;
   }else if(isset($_GET['name']) && $_GET['name']==2){
      $_SESSION['filter']['name'] = 2;
   }

   if(isset($_GET['activity']) && $_GET['activity']==1){
      $_SESSION['filter']['activity'] = 1;
   }else if(isset($_GET['activity']) && $_GET['activity']==2){
      $_SESSION['filter']['activity'] = 2;
   }

   if(isset($_GET['gender']) && $_GET['gender']==1){
      $_SESSION['filter']['gender'] = 1;
   }else if(isset($_GET['gender']) && $_GET['gender']==2){
      $_SESSION['filter']['gender'] = 2;
   }else if(isset($_GET['gender']) && $_GET['gender']==3){
      $_SESSION['filter']['gender'] = 3;
   }
   // top search query
   if(isset($_REQUEST['top_search_submit']) && !empty($_REQUEST['top_search_submit'])){
      $_SESSION['filter']['top_search_query'] = $_REQUEST['top_search_query'];
      $_SESSION['filter']['looking_for'] = $_REQUEST['looking_for'];
      $_SESSION['filter']['procedure_date'] = $_REQUEST['procedure_date'];
   }

   header("Location:patients.php");
   exit;
}

$mode = get_query_string('m');
if ($mode=="") { logMsg("mode value was blank", $logfile); $mode="main"; }
logMsg("Patients(TOP)> mode: ".$mode, $logfile);
$id = get_query_string('id');

// turn everything off
$main_hide = "hide";
$add_hide = "hide";
$overview_hide = "hide";
$detail_hide = "hide";
$edit_hide = "hide";
$editaddress_hide = "hide";
$addaddress_hide = "hide";
$procdetail_hide = "hide";
$proccomplete_hide = "hide";
$procdate_hide = "hide";
$procselect_hide = "hide";
$procdate_hide = "hide";
$procsurgeon_hide = "hide";
$procsummary_hide = "hide";
$procconfirm_hide = "hide";

if ($mode=="" || $mode=="main")
{
   $main_hide = "";
}
else if ($mode=="add")
{
   $add_hide = "";
   //$pe_id=$id;
}
else if ($mode=="overview")
{
   $overview_hide = "";
   $pe_id=$id;
}
else if ($mode=="detail" || $mode=="addreview" || $mode=="editreview")
{
   $detail_hide = "";
   $pe_id=$id;
}
else if ($mode=="edit")
{
   $edit_hide = "";
   $pe_id=$id;
}
else if ($mode=="editaddress" || $mode=="addaddress")
{
   $editaddress_hide = "";
   $pe_id=$id;
}
else if ($mode=="procdetail")
{
logMsg(">>>> procdetail IF",$logfile);
   $procdetail_hide = "";
   $pe_id=$id;
}
else if ($mode=="proccomplete")
{
   $proccomplete_hide = "";
   $pe_id=$id;
}
else if ($mode=="procdate")
{
   $procdate_hide = "";
   $pe_id=$id;
}
else if ($mode=="procselect")
{
   $procselect_hide = "";
   $pe_id=$id;
}
else if ($mode=="procdate")
{
   $procdate_hide = "";
   $pe_id=$id;
}
else if ($mode=="procsurgeon")
{
   $procsurgeon_hide = "";
   $pe_id=$id;
}
else if ($mode=="procsummary")
{
   $procsummary_hide = "";
   $pe_id=$id;
}
else if ($mode=="procconfirm")
{
   $procconfirm_hide = "";
   $pe_id=$id;
}

// need to change according to session
if (isset($_GET['page']) && !empty($_GET['page'])) {
   $page = $_GET['page'];
   $start = ($page - 1) * $row;
}

// get patient list
$sql = "SELECT *,
           (SELECT count(*)
            FROM $TBLTIMELINES as timeline
            WHERE timeline.c_patientEpisodeId=episodes.id AND c_timelineEntryDetail in ('".implode("','",$timeline_entry_detail)."')) `total_activity`
        FROM $TBLPTEPISODES as episodes
	WHERE #WHERE#
        ORDER BY #ORDER# LIMIT #LIMIT#";

$pagination_sql = "SELECT *,
                      (SELECT count(*)
                       FROM $TBLTIMELINES as timeline
                       WHERE timeline.c_patientEpisodeId=episodes.id AND c_timelineEntryDetail in ('".implode("','",$timeline_entry_detail)."')) `total_activity`
                  FROM $TBLPTEPISODES as episodes
                  WHERE #WHERE#
                  ORDER BY #ORDER# 
                  ";
/////////////////////////////////////////////////// 
// Add filter to query : START
//////////////////////////////////////////////////

if(isset($_SESSION['filter']) && sizeof($_SESSION['filter'])>0){
   $limit = "$start,$row";

   // Quick Filter
   if (isset($_SESSION['filter']['operation'])){
      if($_SESSION['filter']['operation']==1){
         $where[] = "c_procedureStatus = 'POST'"; 
      }else if($_SESSION['filter']['operation']==2){
         $where[] = "c_procedureStatus = 'PRE'";
      }
   }

   if (isset($_SESSION['filter']['name'])){
      if($_SESSION['filter']['name']==1){
         $order[] = "c_firstName ASC" ;
         $order[] = "c_surname ASC" ;
      }else if($_SESSION['filter']['name']==2){
         $order[] = "c_firstName DESC" ;
         $order[] = "c_surname DESC" ;
      }
   }

   if (isset($_SESSION['filter']['time_added'])){
      if($_SESSION['filter']['time_added']==1){
         $order[] = "dateModified DESC" ;
      }else if($_SESSION['filter']['time_added']==2){
         $order[] = "dateModified ASC" ;
      }
   }

   if (isset($_SESSION['filter']['activity'])){
      if($_SESSION['filter']['activity']==1){
         $where[] = "c_status = 'Episode Complete'" ;
         $order[] = "total_activity DESC" ;
      }else if($_SESSION['filter']['activity']==2){
         $where[] = "c_status = 'Episode Complete'" ;
         $order[] = "total_activity ASC" ;
      }
   }

   if (isset($_SESSION['filter']['gender'])){
      if($_SESSION['filter']['gender']==1){
         // we need all records do nothing
      }else if($_SESSION['filter']['gender']==2){
         // we need only Male records
         $where[] = "c_gender='Male'";
      }else if($_SESSION['filter']['gender']==3){
         // we need only Female records
         $where[] = "c_gender='Female'";
      }
   }

   // add top search query

   if (isset($_SESSION['filter']['top_search_query'])){
      if(!empty($_SESSION['filter']['top_search_query'])){
          // add the search query to filter sql
         if($_SESSION['filter']['looking_for']=='patient'){
            $where[] = "c_surname LIKE '%".$_SESSION['filter']['top_search_query']."%' OR c_firstName LIKE '%".$_SESSION['filter']['top_search_query']."%'";
         }else if($_SESSION['filter']['looking_for']=='procedure'){
            $where[] = "c_description LIKE '%".$_SESSION['filter']['top_search_query']."%'";
         }else if($_SESSION['filter']['looking_for']=="surgeon"){
            $where[] = "c_surgeonName LIKE '%".$_SESSION['filter']['top_search_query']."%'";
         }
      }

      if(!empty($_SESSION['filter']['procedure_date'])){
         $procedure_date = new DateTime($_SESSION['procedure_date']);
         $where[] = "DATE(dateCreated)='".$procedure_date->format('Y-m-d')."'";
      }
   }

}else{
// no filter found so add default
   $limit = "$start, $row";
}

if(empty($where)){
   $where[] = 1;
}

if(empty($order)){
   $order[] = "c_surname ASC";
}

$sql = str_replace(
          array("#WHERE#","#ORDER#","#LIMIT#"),
          array(
             implode(" AND ",$where),
             implode(", ",$order),
             $limit
          ),
          $sql         
       );

logMsg("SQL for filter > ".$sql , $logfile);

$pagination_sql = str_replace(
                     array("#WHERE#","#ORDER#","#LIMIT#"),
                     array(
                        implode(", ",$where),
                        implode(", ",$order),
                     ),
                     $pagination_sql
                  );

///////////////////////////////////////////////////
// Add Filter to Query : END 
/////////////////////////////////////////////////// 

$GetQuery = dbi_query($sql);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Lookup</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/app.css">  
  <link rel="stylesheet" href="../css/foundation-datepicker.min.css">
  <link rel="stylesheet" href="../css/timeline.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <link rel="icon" type="image/png" href="../favicon.png">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
  <?php include '../includes/patient_header.php';?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <form action="patients.php?filter=1" method="POST">
    <div class="grid-x grid-padding-x grey_header">
      <div class="small-12 medium-2 cell gh_form">
        <label>I'm looking for a:
          <select name="looking_for" value="">
    	    <option value="patient">Patient</option>
            <option value="procedure">Procedure</option>
            <option value="surgeon">Surgeon</option>
  		  </select>
        </label>
      </div>
	  <div class="small-12 medium-5 cell gh_form basic">
        <label>Search by:
          <input name="top_search_query" value="<?php echo isset($_SESSION['filter']['top_search_query'])?$_SESSION['filter']['top_search_query']:'';?>"  type="text" placeholder="Jones">
        </label>
      </div>
	  <div class="small-12 medium-3 cell gh_form">
        <label>Procedure Date:
          <input id="popupDatepicker"  name="procedure_date" class="date_element" value="<?php if(isset($_SESSION['filter']['procedure_date'])){echo $_SESSION['filter']['procedure_date'];}?>" type="text">
        </label>
      </div>
	  <div class="small-12 medium-2 cell gh_form no-label">
        <button type="submit" name="top_search_submit" class="button" value="search">Search</button>
        <a href="clear_search.php?m=<?php echo $mode; ?>" class="float-right align-center-middle"><img src="../img/close-icon.png" alt="" style="margin:7px;"/></a></strong>
      </div>
    </div>
  </form>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x su">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="100%" border="0"  class="su-table stack">
  	    <tbody>
          <!-- **************************************************************
                         Start Filters Panel 
               **************************************************************  -->
          <tr class="no_hover">
		    <td colspan="3" class="sort">
              <div class="grid-x">
                <div class="small-12 cell">
                  <div class="accordion" data-accordion data-allow-all-closed="true">
                    <div class="accordion-item" data-accordion-item>
					  <a href="#" class="accordion-title sort"></a>
                      <!-- Accordion tab title -->
                      <div class='grid-x'>
                          <div class="small-6 medium-8 cell text-left">
                             <a href='patients.php?filter=1&operation=1' class="button <?php echo (isset($_SESSION['filter']['operation']) && $_SESSION['filter']['operation']==1)?"selected":"inactive";?>">Post Op</a>&nbsp;&nbsp;<a href='patients.php?filter=1&operation=2' class="button <?php echo (isset($_SESSION['filter']['operation']) && $_SESSION['filter']['operation']==2)?"selected":"inactive";?>">Pre Op</a></div></div>
                             <div class="small-6 medium-4 cell"></div>
                      <!-- // initial sort by tab		  		 
                      <a href="#" class="accordion-title sort">
					    <div class="grid-x">
					      <div class="small-12 medium-8 cell text-left"><button class="button fc">Post Op</button>&nbsp;&nbsp;<button class="button fc">Pre Op</button></div>
						  <div class="small-12 medium-4 cell"></div>
				        </div>	
					  </a>
                      -->
                      <div class="accordion-content sort" data-tab-content>
					    <div class="grid-x rule">
                          <div class="small-12 medium-4 cell">
                            <label for="middle-label" class="middle">Time Added:</label>
                          </div>
                          <div class="small-12 medium-8 cell">
                            <a href="patients.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added']==1)?"selected":"inactive";?>">Newest First</a>&nbsp;<a href="patients.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added']==2)?"selected":"inactive";?>">Oldest First</a>
                          </div>
                        </div>
						<div class="grid-x rule">
                          <div class="small-12 medium-4 cell">
                            <label for="middle-label" class="middle">Name:</label>
                          </div>
                          <div class="small-12 medium-8 cell">
                            <a href="patients.php?filter=1&name=1" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name']==1)?"selected":"inactive";?>">A-Z</a>&nbsp;<a href="patients.php?filter=1&name=2" class="button <?php echo (isset($_SESSION['filter']['name']) && $_SESSION['filter']['name']==2)?"selected":"inactive";?>">Z-A</a>
                          </div>
                        </div>
						<div class="grid-x rule">
                          <div class="small-12 medium-4 cell">
                            <label for="middle-label" class="middle">Activity:</label>
                          </div>
                          <div class="small-12 medium-8 cell">
                            <a href="patients.php?filter=1&activity=1" class="button <?php echo (isset($_SESSION['filter']['activity']) && $_SESSION['filter']['activity']==1)?"selected":"inactive";?>">Most Active</a>&nbsp;<a href="patients.php?filter=1&activity=2" class="button <?php echo (isset($_SESSION['filter']['activity']) && $_SESSION['filter']['activity']==2)?"selected":"inactive";?>">Least Active</a>
                          </div>
                        </div>
						<div class="grid-x rule">
                          <div class="small-12 medium-4 cell">
                            <label for="middle-label" class="middle">Gender:</label>
                          </div>
                          <div class="small-12 medium-8 cell">
                            <a href="patients.php?filter=1&gender=1" class="button <?php echo (isset($_SESSION['filter']['gender']) && $_SESSION['filter']['gender']==1)?"selected":"inactive";?>">Any</a>&nbsp;<a href="patients.php?filter=1&gender=2" class="button <?php echo (isset($_SESSION['filter']['gender']) && $_SESSION['filter']['gender']==2)?"selected":"inactive";?>">Male</a>&nbsp;<a href="patients.php?filter=1&gender=3" class="button <?php echo (isset($_SESSION['filter']['gender']) && $_SESSION['filter']['gender']==3)?"selected":"inactive";?>">Female</a>
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
                  </div>
                </div>
              </div>
            </td>
	  </tr>
 <!-- **********************************************
            End Filters Panel 
********************************************** -->
          <tr>
            <td><input type="checkbox"></td>
            <td>&nbsp;</td>
			<td>&nbsp;</td>
          </tr>
               <?php while ($qryResult=$GetQuery->fetch_assoc())
                     {
                        $id=$qryResult['id'];
                        $c_surname=$qryResult['c_surname'];
                        $c_firstName=$qryResult['c_firstName'];
			$c_referenceNumberHospitalId=$qryResult['c_referenceNumberHospitalId'];
			$c_procedureId=$qryResult['c_procedureId'];
			$c_description=$qryResult['c_description'];
			$c_displayName=$qryResult['c_displayName'];
			$pt_name =$c_surname.", ".$c_firstName;
                        if ($c_procedureId<>"")
                           $procedure = $c_procedureId." - ".$c_description;
                        else
                           $procedure = "";
                        $pt_status = get_pt_status($id);
                        if ($pt_status == "Inactive") $pt_status_class = "pending_status";
                        else if ($pt_status=="Alert") $pt_status_class = "off_status";
                        else if ($pt_status == "Active") $pt_status_class = "on_status";
               ?>
		  <tr>
            <td><input type="checkbox"></td>
			<td class='clickable-row su_data' data-href='patients.php?m=overview&id=<?php echo $id; ?>'>
			  <p class="<?php echo $pt_status_class; ?>">
			    <span class="uc"><?php echo $pt_name; ?></span><br />
                HospNo: <?php echo $c_referenceNumberHospitalId; ?><br />
                <?php echo $procedure; ?>
		      </p>
            </td>
			<td width="10%"><a href="patients.php?m=overview&id=<?php echo $id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></a></td>
		  </tr>
              <?php } ?>
              <?php 
                 $GetQuery = dbi_query($pagination_sql);
                 $totalRecord = $GetQuery->num_rows;
                 $pagination = get_pagination($page, $totalRecord);
              ?>
		  <tr>
  	    </tbody>
      </table>
	  <div class="grid grid-x text-center">
	    <div class="small-12 pagination-btm"><?php echo $pagination; ?></div>
	  </div>
    </div>
<!-- End Content-Left -->
<!-- Start Content-Right  MAIN SECTION-->
        <?php if ($mode=="main")
        {
           $active_ct = get_stat_counts('active');
           $inactive_ct = get_stat_counts('inactive');
           $alert_ct = get_stat_counts('alert');

           // get the recent notifications - type - how many - patientId
           $arr_notifications = get_notifications('Alert', '3', "");
        }
        ?>
   <div class="small-12 medium-6 large-6 cell content-right <?php echo $main_hide; ?>">
	  <h3>Add Patient</h3>
	  <div class="small-12 cell field">
             <p>Start a Verify session with a new patient</p>
             <div class="grid-x">
                 <div class="hide-for-small-only medium-2">&nbsp;</div>
                 <div class="small-12 medium-8">
                    <a href="patients.php?m=add"><button class="button large expanded">Get Started</button></a>
                 </div>
                 <div class="hide-for-small-only medium-2">&nbsp;</div>
             </div>
	  </div>
          <hr class="gap" />
          <h3>Recent Notifications</h3>
          <table class="notifications">
            <tbody>
              <?php for ($n=0; $n<count($arr_notifications); $n++)
                      { $n_name = $arr_notifications[$n]['c_timelineEntryDetail'];
                        $n_date = $arr_notifications[$n]['dateCreated'];
                        $n_date = substr($n_date,0,strpos($n_date," "));
                        $n_date = format_uk_date($n_date);
                        $n_type = $arr_notifications[$n]['c_timelineEntryType']; // Alert or Event
                        $n_id = $arr_notifications[$n]['id'];
                        $n_patient_name = $arr_notifications[$n]['c_firstName']." ".$arr_notifications[$n]['c_surname'];
                        $n_patientEpisodeId = $arr_notifications[$n]['c_patientEpisodeId'];
                        $n_imgfile = $arr_tl_data[$n_desc];
                        $n_class = "status action_needed";
                        $n_icon="caution.png";
               ?>
                  <tr>
                    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
                    <td class="upper clickable-row su_data" data-href="patients.php?m=overview&id=<?php echo $n_patientEpisodeId; ?>"><?php echo $n_name; ?><br />
                          Patient <strong><?php echo $n_patient_name; ?></strong>
                    </td>
                    <td><a href="patients.php?m=overview&id=<?php echo $n_patientEpisodeId; ?>"><img src="../img/icons/greater.png" alt="" class="align-right"></a></td>
                  </tr>
                  <tr class="space">
                    <td colspan="3">&nbsp;</td>
                  </tr>
               <?php } ?>
            </tbody>
          </table>
          <div class="grid-x">
              <div class="hide-for-small-only medium-2">&nbsp;</div>
              <div class="small-12 medium-8"><button type="button" name="" value="" class="button large expanded">View All</button></div>
              <div class="hide-for-small-only medium-2">&nbsp;</div>
          </div>
          <hr class="gap" />
          <h3>Stats</h3>
	  <br />
      	  <div class="grid-x field">		
              <div class="small-12 auto cell text-center grey_bdr">
                <h6>ACTIVE<br /> PATIENTS</h6>
                <h2 class="yes"><?php echo $active_ct; ?></h2>
                <p class="smaller">TOTAL</p>
              </div>
              <div class="small-12 auto cell text-center grey_bdr">
                <h6>INACTIVE<br /> PATIENTS</h6>
                <h2 class="no"><?php echo $inactive_ct; ?></h2>
                <p class="smaller">TOTAL</p>
              </div>
              <div class="small-12 auto cell text-center">
                <h6>UNRESOLVED<br />ALERTS</h6>                  
		      <h2 class="no"><?php echo $alert_ct; ?></h2>
                <p class="smaller">TOTAL</p>
              </div>
          </div>
          <p>&nbsp;</p>
          <div class="grid-x">
              <div class="hide-for-small-only medium-2">&nbsp;</div>
              <div class="small-12 medium-8"><button type="button" name="" value="" class="button large expanded">View Stats</button></div>
              <div class="hide-for-small-only medium-2">&nbsp;</div>
          </div>
   </div>
<!-- END MAIN SECTION -->
<!-- ADD SECTION -->
  <div class="small-12 medium-6 large-6 cell content-right  <?php echo $add_hide; ?>">
       <div class="back"><a href="patients.php?m=main"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
       <h5>Add Patient<br /><span class="small">Start a Verify session with a new patient</span></h5>
       <form action="patients_a.php?m=add" method="post">
       <div class="grid-container">
          <div class="grid-x grid-padding-x">
              <div class="small-12 medium-12 large-12 cell">
                  <label>First Name
                  <input type="text" name="fname" placeholder="">
                  </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
                  <label>Surname
                  <input type="text" name="lname" placeholder="">
                  </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
                &nbsp; 
              </div>
              <div class="small-12 medium-12 large-12 cell">
                  <label>NHS Number
                  <input type="text" name="nhsnumber" placeholder="">
                  </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
                  <label>Hospital Number
                  <input type="text" name="hospitalnumber" placeholder="">
                  </label>
              </div>
              <div class="small-12 medium-12 large-12 cell">
                &nbsp; 
              </div>
              <div class="small-12 medium-12 large-12 cell">
                  <label>Gender
                                <select id="gender" name="gender">
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                                  <option value="Unspecified">Unspecified</option>
                                </select>
                  </label>
                </div>
                <div class="small-12 medium-12 large-12 cell">
                    <label>Date of Birth
                    <input type="text" name="dob" placeholder="">
                    </label>
                </div>
                <div class="small-12 medium-12 large-12 cell">
                   &nbsp; 
                </div>
                <div class="small-12 medium-12 large-12 cell">
                    <label>Postcode
                    <input type="text" name="postalcode" placeholder="NG12 5HP">
                    </label>
                </div>
                <div class="small-12 medium-12 large-12 cell">
                   &nbsp; 
                </div>
                <div class="small-12 medium-12 large-12 cell">
                   <span class="small">Please enter at least one contact method for the patient</span>
                </div>
                <div class="small-12 medium-12 large-12 cell">
                    <label>Email Address
                    <input type="text" name="email" >
                    </label>
                </div>
                <div class="small-12 medium-12 large-12 cell">
                    <label>Mobile Number
                    <input type="text" name="mobilenumber" placeholder="">
                    </label>
                </div>
          </div>
       </div>
       <div class="grid-x">
          <div class="hide-for-small-only medium-3 large-3 cell"></div>
          <div class="small-12 medium-6 large-6 cell text-center">
              <p>&nbsp;</p>
              <button type="submit" name="add_patient" value="add patient" class="button large expanded" />ADD PATIENT</button>
          </div>
          <div class="hide-for-small-only medium-3 large-3 cell"></div>
          </form>
       </div>
   </div>
 <!-- END ADD SECTION -->
<!-- OVERVIEW SECTION -->
<?php // code to read and manipupate data for this section
      include "overview.php"; ?>
<div class="small-12 medium-6 large-6 cell content-right <?php echo $overview_hide; ?>">
       <!--<div class="back"><a href="patients.php?m=add"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>-->
       <h3>Patient Overview<br /><span class="small">See a patient's progress through Verify</span></h3>
       <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
       <table class="su-table stack">
            <tr>
              <td class='clickable-row su_data' data-href='patients.php?m=detail&id=<?php echo $pe_id; ?>' colspan="2">
                <strong>HospNo:</strong> <?php echo $c_referenceNumberHospitalId; ?><br />
                <strong>NHS No:</strong> <?php echo $c_nhsNumber; ?><br />
                <strong>DOB:</strong>  <?php echo $c_dateOfBirth; ?>
              </td>
              <td><a href="patients.php?m=detail&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="align-right" /></a></td>
            </tr>
            <tr>
              <td>
                <p><strong>Procedure:</strong></p>
                <p><strong>Procedure Date:</strong></p>
              </td>
              <td class='clickable-row su_data' data-href='patients.php?m=procdetail&id=<?php echo $pe_id; ?>'>
                <p><?php echo $procedure; ?></p>
                <p><?php echo $c_plannedProcedureDate; ?></p>
              </td>
              <?php if ($procedure=="") { ?>
                   <td><a href="patients.php?m=procselect&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="align-right" /></a></td>
              <?php } else { ?>
                   <td><a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="align-right" /></a></td>
              <?php } ?>
            </tr>
        <?php if (count($arr_tags)>0) { ?>
            <tr>
              <td>
                <p><strong>Tags:</strong></p>
              </td>
              <td colspan="2">
                 <?php for ($i=0; $i<count($arr_tags); $i++) { ?>
                      <span class="label tag"><?php echo $arr_tags[$i]; ?>&nbsp;&nbsp;&nbsp;</span>
                 <?php } ?>
              </td>
            </tr>
        <?php } ?>
        </table>
</div>
<!-- END OVERVIEW SECTION -->
        <!-- DETAIL SECTION -->
        <?php if ($mode=='detail')
              {  $sql_d  = "SELECT *
                            FROM app_fd_ver_patientEpisodes
                            WHERE id = '$pe_id'";
                 $GetQuery_d = dbi_query($sql_d);
                 $qryResult_d = $GetQuery_d->fetch_assoc();
                 $id=$qryResult_d['id'];
                 $c_surname=$qryResult_d['c_surname'];
                 $c_firstName=$qryResult_d['c_firstName'];
                 $c_address=$qryResult_d['c_address'];
                 $c_address2=$qryResult_d['c_address2'];
                 $c_city=$qryResult_d['c_city'];
                 $c_county=$qryResult_d['c_county'];
                 $c_postalCode=$qryResult_d['c_postalCode'];
                 $c_nhsNumber=$qryResult_d['c_nhsNumber'];
                 $c_referenceNumberHospitalId=$qryResult_d['c_referenceNumberHospitalId'];
                 $c_dateOfBirth=$qryResult_d['c_dateOfBirth'];
                 $c_gender=$qryResult_d['c_gender'];
                 $c_emailAddress=$qryResult_d['c_emailAddress'];
                 $c_mobileNumber=$qryResult_d['c_mobileNumber'];
                 $c_procedureId=$qryResult_d['c_procedureId'];
                 $c_description=$qryResult_d['c_description'];
                 $procedure = $c_procedureId." - ".$c_description;
                 $pt_status = get_pt_status($id);
                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Alert")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
              } 
              else if ($mode=="addreview")
              {
                 $c_surname=$_SESSION['add_lname'];
                 $c_firstName=$_SESSION['add_fname'];
                 $c_address=$_SESSION['add_address'];
                 $c_address2=$_SESSION['add_address2'];
                 $c_city=$_SESSION['add_city'];
                 $c_county=$_SESSION['add_county'];
                 $c_postalCode=$_SESSION['add_postalcode'];
                 $c_nhsNumber=$_SESSION['add_nhsnumber'];
                 $c_referenceNumberHospitalId=$_SESSION['add_hospitalnumber'];
                 $c_dateOfBirth=$_SESSION['add_dob'];
                 $c_gender=$_SESSION['add_gender'];
                 $c_emailAddress=$_SESSION['add_email'];
                 $c_mobileNumber=$_SESSION['add_mobilenumber'];
                 $pt_status_class = "ps_grey";
              }
              else if ($mode=="editreview")
              {
                 $c_surname=$_SESSION['edit_lname'];
                 $c_firstName=$_SESSION['edit_fname'];
                 $c_address=$_SESSION['edit_address'];
                 $c_address2=$_SESSION['edit_address2'];
                 $c_city=$_SESSION['edit_city'];
                 $c_county=$_SESSION['edit_county'];
                 $c_postalCode=$_SESSION['edit_postalcode'];
                 $c_nhsNumber=$_SESSION['edit_nhsnumber'];
                 $c_referenceNumberHospitalId=$_SESSION['edit_hospitalnumber'];
                 $c_dateOfBirth=$_SESSION['edit_dob'];
                 $c_gender=$_SESSION['edit_gender'];
                 $c_emailAddress=$_SESSION['edit_email'];
                 $c_mobileNumber=$_SESSION['edit_mobilenumber'];
                 $pt_status = get_pt_status($id);
                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Alert")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
              }
// logMsg("c_surname: $c_surname c_address: $c_address Session_surname ".$_SESSION['add_surname']." session_address: ".$_SESSION['add_address'], $logfile);
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $detail_hide; ?>">
          <div class="back"><a href="patients.php?m=overview&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
             <h5>Confirm<br /><span class="small">Check and confirm the information entered</span></h5>
             <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small">"Pending"</span></h5>
               <h6>First Name</h6>
               <p><?php echo $c_firstName; ?></p>
               <h6>Surname</h6>
               <p><?php echo $c_surname; ?></p>
          <hr />
                <h6>NHS Number</h6>
               <p><?php echo $c_nhsNumber; ?></p>
               <h6>Hospital Number</h6>
               <p><?php echo $c_referenceNumberHospitalId; ?></p>
          <hr />
               <h6>Date of Birth</h6>
               <p><?php echo $c_dateOfBirth; ?></p>
               <h6>Address</h6>
               <p><?php if ($c_address<>"") echo $c_address."<br />"; ?>
               <?php if ($c_city<>"") echo $c_city."<br />"; ?>
               <?php if ($c_county<>"") echo $c_county."<br />"; ?>
               <?php if ($c_postalCode<>"") echo $c_postalCode."<br />"; ?></p>
               <h6>Gender</h6>
               <p><?php echo $c_gender; ?></p>
          <hr />
               <h6>Email Address</h6>
                   <p><a href="mailto:<?php echo $c_emailAddress; ?>"><?php echo $c_emailAddress; ?></a></p>
                   <h6>Mobile Number</h6>
               <p><?php echo $c_mobileNumber; ?></p>
          <hr />
          <div class="grid-x">
             <div class="hide-for-small-only medium-3 large-3 cell"></div>
             <div class="small-12 medium-6 large-6 cell text-center">
                <p>&nbsp;</p>
                <?php if ($mode=="detail") { $goto="patients.php"; $m="edit"; $btn_class="inactive"; $btn_text="Edit Patient"; }
                 else if ($mode=="addreview") { $goto="patients_a.php"; $m="addconfirm"; $btn_class="active"; $btn_text="Confirm"; }
                 else if ($mode=="editreview") { $goto="patients_a.php"; $m="editconfirm"; $btn_class="active"; $btn_text="Confirm"; }
                ?>
                <a href="<?php echo $goto; ?>?m=<?php echo $m; ?>&id=<?php echo $pe_id; ?>"><button type="button" name="" value="edit patient" class="button large expanded <?php echo $btn_class; ?>" /><?php echo $btn_text; ?></button></a>
             </div>
             <div class="hide-for-small-only medium-3 large-3 cell"></div>
          </div>
        </div>
        <!-- END DETAIL SECTION -->
        <!-- EDIT SECTION -->
        <?php if ($mode=='edit')
              {  $sql_e  = "SELECT *
                            FROM app_fd_ver_patientEpisodes
                            WHERE id = '$pe_id'";
                 $GetQuery_e = dbi_query($sql_e);
                 $qryResult_e = $GetQuery_e->fetch_assoc();
                 $id=$qryResult_e['id'];
                 $c_surname=$qryResult_e['c_surname'];
                 $c_firstName=$qryResult_e['c_firstName'];
                 $c_nhsNumber=$qryResult_e['c_nhsNumber'];
                 $c_referenceNumberHospitalId=$qryResult_e['c_referenceNumberHospitalId'];
                 $c_dateOfBirth=$qryResult_e['c_dateOfBirth'];
                 $c_gender=$qryResult_e['c_gender'];
                 $c_postalCode=$qryResult_e['c_postalCode'];
                 $c_emailAddress=$qryResult_e['c_emailAddress'];
                 $c_mobileNumber=$qryResult_e['c_mobileNumber'];
                 $c_gender=$qryResult_e['c_gender'];
                 $c_address=$qryResult_e['c_address'];
                 $c_county=$qryResult_e['c_county'];
                 $c_city=$qryResult_e['c_city'];
              } 
          ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $edit_hide; ?>">
          <div class="back"><a href="patients.php?m=detail&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h5>Edit Patient<br /><span class="small">View or edit the patient</span></h5>
          <form action="patients_a.php?m=edit&id=<?php echo $pe_id; ?>" method="post">
          <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell">
                  <label>First Name
                  <input type="text" name="fname" value="<?php echo $c_firstName; ?>">
                  </label>
                </div>
                <div class="small-12 medium-12 large-12 cell">
                  <label>Surname
                <input type="text" name="lname" value="<?php echo $c_surname; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                &nbsp;
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>NHS Number
                <input type="text" name="nhsnumber" value="<?php echo $c_nhsNumber; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Hospital Number
                <input type="text" name="hospitalnumber" value="<?php echo $c_referenceNumberHospitalId; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                &nbsp;
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Gender
                                <select name="gender" id="gender">
                                  <option <?php if ($c_gender=="Male") echo "selected"; ?>>Male</option>
                                  <option <?php if ($c_gender=="Female") echo "selected"; ?>>Female</option>
                                  <option <?php if ($c_gender=="Unspecified") echo "selected"; ?>>Unspecified</option>
                                </select>
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Date of Birth
                <input type="text" name="dob" value="<?php echo $c_dateOfBirth; ?>">
              </label>
            </div>
            <div class="small-12 medium-12 large-12 cell">
                &nbsp;
            </div>
            <div class="small-12 medium-12 large-12 cell">
                 <p>Address</p> 
                 <p><?php echo $c_address; ?><br />
                    <?php echo $c_city; ?><br />
                    <?php echo $c_county; ?><br />
                    <?php echo $c_postalCode; ?></p>
            </div>
            <div class="small-12 medium-6 large-6 cell text-center">
              <center><a href="patients.php?m=editaddress&id=<?php echo $pe_id; ?>" class="button large expanded inactive">EDIT ADDRESS</a></center>
            </div>
            <div class="small-12 medium-12 large-12 cell">
                &nbsp;
            </div>
            <div class="small-12 medium-12 large-12 cell">
                 <span class="small">Please enter at least one contact method for the patient</span>
            </div>
            <div class="small-12 medium-12 large-12 cell">
                  <label>Email Address
                <input type="text" name="email" value="<?php echo $c_emailAddress; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Mobile Number
                <input type="text" name="mobilenumber" value="<?php echo $c_mobileNumber; ?>">
              </label>
            </div>
                  </div>
                </div>
          <div class="grid-x">
                <div class="hide-for-small-only medium-3 large-3 cell"></div>
            <div class="small-12 medium-6 large-6 cell text-center">
              <p>&nbsp;</p>
              <center><button type="submit" name="" class="button large expanded">SAVE</button></center>
            </div>
                <div class="hide-for-small-only medium-3 large-3 cell"></div>
      </form>
          </div>
        </div>
        <!-- END EDIT SECTION -->
        <!-- EDITADDRESS SECTION -->
        <?php if ($mode=='editaddress')
              {  $sql_ea  = "SELECT *
                            FROM app_fd_ver_patientEpisodes
                            WHERE id = '$pe_id'";
                 $GetQuery_ea = dbi_query($sql_ea);
                 $qryResult_ea = $GetQuery_ea->fetch_assoc();
                 $id=$qryResult_ea['id'];
                 $c_surname=$qryResult_ea['c_surname'];
                 $c_firstName=$qryResult_ea['c_firstName'];
                 $c_postalCode=$qryResult_ea['c_postalCode'];
                 $c_address=$qryResult_ea['c_address'];
                 $c_address2=$qryResult_ea['c_address2'];
                 $c_county=$qryResult_ea['c_county'];
                 $c_city=$qryResult_ea['c_city'];
                 $pt_status = get_pt_status($id);
                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Alert")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
              }
              else if ($mode=="addaddress")
              {
                 $c_postalCode=$_SESSION['add_postalcode'];
              }
logMsg(">>>  editaddress_hide = $editaddress_hide", $logfile);
          ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $editaddress_hide; ?>">
          <?php if ($mode=="editaddress") { ?>
               <div class="back"><a href="patients.php?m=edit&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
               <h5>Edit Address<br /><span class="small">Update the address for the patient.</span></h5>
               <form action="patients_a.php?m=editaddress&id=<?php echo $pe_id; ?>" method="post">
               <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
          <?php } else if ($mode=="addaddress") { ?>
               <div class="back"><a href="patients.php?m=add"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
               <h5>Confirm Address<br /><span class="small">Select the patient's address.</span></h5>
               <form action="patients_a.php?m=addaddress" method="post">
          <?php } ?>
          <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell">
                  <label>Postcode
                <input type="text" name="postalcode" id="postalcode_ea" value="<?php echo $c_postalCode; ?>">
              </label>
            </div>
               <div class="small-12 medium-12 large-12 cell">
                  <label>Select the patients address below:
                     <select id="address" name="found_address" size="10">
                         <?php echo get_address_by_postcode($c_postalCode); ?>
                     </select>
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Address 1
                <input id="address_1_ea"  type="text" name="address" value="<?php echo $c_address; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Address 2
                <input id="address_2_ea"  type="text" name="address2" value="<?php echo $c_address2; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Town
                <input id="city_ea"  type="text" name="city" value="<?php echo $c_city; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>County
                <input id="country_ea"  type="text" name="county" value="<?php echo $c_county; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Postcode
                <input id="postalcode_2_ea"  type="text" name="postalcode" value="<?php echo $c_postalCode; ?>">
              </label>
            </div>
                  </div>
                </div>
          <div class="grid-x">
                <div class="hide-for-small-only medium-3 cell"></div>
            <div class="small-12 medium-6 cell text-center">
              <p>&nbsp;</p>
              <button type="submit" name="editaddress" value="Save" class="button large expanded" />Save</button>
            </div>
                <div class="hide-for-small-only medium-3 cell"></div>
      </form>
          </div>
        </div>
        <!-- END EDITADDRESS SECTION -->

          <?php 
            // ********************************************************************************
            // *
            // *  this code pulls data from procepisodes that is used by multiple sections
            // *
            // ********************************************************************************
              if ($mode=="procdetail" || $mode=="proccomplete" || $mode=="procdate" || 
                                         $mode=="procsummary" || $mode=="procsurgeon")
              {  $sql_pd  = "SELECT *
                            FROM app_fd_ver_patientEpisodes
                            WHERE id = '$pe_id'";
logMsg(">>>> procEpisode SQL: $sql_pd",$logfile);
                 $GetQuery_pd = dbi_query($sql_pd);
                 $qryResult_pd = $GetQuery_pd->fetch_assoc();
                 $id=$qryResult_pd['id'];
                 $c_surname=$qryResult_pd['c_surname'];
                 $c_firstName=$qryResult_pd['c_firstName'];
                 $c_procedureId=$qryResult_pd['c_procedureId'];
                 $c_description=$qryResult_pd['c_description'];
                 $c_plannedProcedureDate=$qryResult_pd['c_plannedProcedureDate'];
                 $c_nhsNumber=$qryResult_pd['c_nhsNumber'];
                 $c_referenceNumberHospitalId=$qryResult_pd['c_referenceNumberHospitalId'];
                 $c_dateOfBirth=$qryResult_pd['c_dateOfBirth'];
                 $c_gender=$qryResult_pd['c_gender'];
                 $c_postalCode=$qryResult_pd['c_postalCode'];
                 $c_surgeonName=$qryResult_pd['c_surgeonName'];
                 $c_gmcNumber=$qryResult_pd['c_gmcNumber'];
                 $c_emailAddress=$qryResult_pd['c_emailAddress'];
                 $c_userName=$qryResult_pd['c_userName'];
                 $c_displayName=$qryResult_pd['c_displayName'];
                 $procedure = $c_procedureId." - ".$c_displayName;
                 $pt_status = get_pt_status($id);
                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Alert")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
logMsg(">>>> pt_status = $pt_status", $logfile);
              }
        ?>
        <!-- PROCSELECT SECTION -->
<div class="small-12 medium-6 large-6 cell content-right <?php echo $procselect_hide; ?>">
  <h3>Select Procedure<br><span class="small">Which procedure will the patient have?</span></h3>
  <form action="patients_a.php?m=procselect&id=<?php echo $pe_id; ?>" method="post">
	<div class="grid-container">
    	  <div class="grid-x">
      	    <div class="small-12 cell field">
        	<label>Procedure
                <select name="proc_id">
                    <?php make_combo($TBLPROCEPISODES, "c_procedureId", "c_description", $procedure, "", " ORDER BY c_procedureId "); ?>
                </select>
                </label>
            </div>
      		<div class="small-12 cell field">
                   <h5>Procedure Summary</h5>
                   <p>Insert Procedure code here</p>
                </div>
		<div class="small-12 cell field">
        	   <h5>Overview</h5>
                   <p>Insert Timeline Code</p>
                </div>
		<div class="small-12 cell text-center">
		   <button type="submit" id="add" class="button large" value="Select">Select</button>
                </div>
         </div>
     </div>
 </form>
</div>
        <!-- END PROCSELECT SECTION -->
        <!-- PROCDATE SECTION -->
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $procdate_hide; ?>">
	  <h3>Select Procedure Date<br><span class="small">What date is the procedure planned for?</span></h3>
	  <form action="patients_a.php?m=procdate&id=<?php echo $pe_id; ?>" method="post">
  	  <div class="grid-container">
    	     <div class="grid-x">
      	       <div class="small-12 cell field">
        	   <h5>Procedure</h5>
                <?php echo $procedure; ?> 
                </label>
            </div>
               <div class="small-12 field">
                  <div class="input-group">
                     <span class="input-group-label"><i class="fi-calendar"></i></span>
                     <input class="input-group-field date_element" type="text" name="proc_date" value="">
                     <div class="input-group-button"> 
                        <button type="submit" id="add" class="button" value="Next">Next</button>
                     </div>
                  </div>
               </div><div class="small-12 cell field hide">
               <div class="grid-x grid-padding-x">
                  <label><i class="fi-calendar"></i>
                   <div class="input-group">
                <select class="input-group-field" name="proc_day" placeholder="Day">
                   <?php include "../includes/select_day.html"; ?>
                </select>&nbsp;&nbsp;
                <select class="input-group-field" name="proc_month" placeholder="Month">
                   <?php include "../includes/select_month.html"; ?>
                </select>&nbsp;&nbsp;
                <select class="input-group-field" name="proc_year" placeholder="Year">
                   <option value="">Year</option>
                   <option value="2019">2019</option>
                   <option value="2019">2018</option>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="input-group-button">
                    <button type="submit" id="add" class="button" value="Next">Next</button>
                </div>
              </div>
        </label>
              </div>
              <div class="small-12 cell text-center">
              </div>
            </div>
    	  </div>
  	</div>
      </form>
    </div>
    <!-- END PROCDATE  SECTION -->
    <!-- PROCSURGEON SECTION -->
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $procsurgeon_hide; ?>">
          <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
          <h3>Select Surgeon<br /><span class="small">Which surgeon will perform the procedure?</span></h3>
          <table class="su-table stack">
            <tr>
                  <td><strong>Procedure</strong></td>
                  <td><?php echo $procedure; ?></td>
                </tr>
                <tr>
                  <td><strong>Procedure Date</strong></td>
                  <td><?php echo $c_plannedProcedureDate; ?></td>
                </tr>
          </table>
          <h5>Search for the name of the surgeon who will perform this procedure.</h5>
          <div class="field">
            <form action="patients_a.php?m=procsurgeon&id=<?php echo $pe_id; ?>" method="post">
                  <div class="grid-container">
            <div class="grid-x grid-padding-x">
                  <div class="small-12 medium-12 large-12 cell">
                    <label>Surgeon Name
                  <select name="proc_surgeon">
                    <?php make_combo("app_fd_ver_surgeons","c_surgeonName", "c_surgeonName", $proc_surgeon, "", " ORDER BY c_surgeonName "); ?>
                  </select>
                </label>
              </div>
                          <div class="small-12 medium-12 large-12 cell">
                    <label>GMC Number
                  <input type="text" name="proc_gmcnumber" placeholder="">
                </label>
              </div>
                    </div>
                  </div>
            <div class="grid-x">
                  <div class="hide-for-small-only medium-3 large-3 cell"></div>
              <div class="small-12 medium-6 large-6 cell text-center">
                    <p>&nbsp;</p>
                    <button type="submit" name="" value="Next" class="button large expanded" />Next</button>
              </div>
                  <div class="hide-for-small-only medium-3 large-3 cell"></div>
            </div>
        </form>
       </div>
    </div>
    <!-- END PROCSURGEON SECTION -->
    <!-- PROCSUMMARY SECTION -->
      <div class="small-12 medium-6 large-6 cell content-right <?php echo $procsummary_hide; ?>">
          <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
          <h5>Summary<br /><span class="small">Check and confirm</span></h5>
          <h6 class="border">Patient</h6>
      <h6>Name</h6>
      <p><?php echo $c_surname.",".$c_firstName; ?></p>
          <h6>NHS Number</h6>
      <p><?php echo $c_nhsNumber; ?></p>
      <h6>Hospital Number</h6>
      <p><?php echo $c_referenceNumberHospitalId; ?></p>
      <h6>Date of Birth</h6>
      <p><?php echo $c_dateOfBirth; ?></p>
          <h6 class="border">Procedure</h6>
          <p><?php echo $procedure; ?></p>
          <h6 class="border">Procedure Date</h6>
          <p><?php echo $c_plannedProcedureDate; ?></p>
          <h6 class="border">Surgeon</h6>
          <p><?php echo $c_surgeonName; ?><br />GMC: <?php echo $c_gmcNumber; ?></p>
          <div class="grid-x">
                <div class="hide-for-small-only medium-3 large-3 cell"></div>
            <div class="small-12 medium-6 large-6 cell text-center">
              <p>&nbsp;</p>
              <a href="patients_a.php?m=procconfirm&id=<?php echo $pe_id; ?>" class="button large expanded" />Confirm</a>
            </div>
                <div class="hide-for-small-only medium-3 large-3 cell"></div>
          </div>
        </div>
    <!-- END PROCSUMMARY -->
    <!-- PROCCONFIRM SECTION -->
        <div class="small-12 medium-6 large-6 cell content-right  <?php echo $procconfirm_hide; ?>">
          <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div>
          <h5>Confirmation<br /><span class="small"></span></h5>
          <p>&nbsp;</p>
          <div class="grid-x text-center">
            <div class="hide-for-small-only medium-3 large-3 cell"></div>
                <div class="small-12 medium-6 large-6 cell">
                  <p>&nbsp;</p>
              <h5 class="notification">SUCCESS</h5>
                  <div class="grid-x grid-padding-x">
                    <div class="small-12 medium-12 large-12 cell text-center">
                      <p>The patient has been added successfully.</p>
                    </div>
                    <div class="small-12 medium-12 large-12 cell text-center">
                      <p>&nbsp;</p>
                          <a href="patients.php?m=add" class="button large expanded">ADD ANOTHER</a>
                          <a href="patients.php?m=main" class="button large expanded">HOME</a>
                    </div>
                   </div>
                </div>
            <div class="hide-for-small-only medium-3 large-3 cell"></div>
          </div>
        </div>
    <!-- PROCCONFIRM END -->
    <!-- PROCDETAIL SECTION -->
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $procdetail_hide; ?>">
<?php logMsg(">>>> in procdetail section", $logfile); ?>
<?php logMsg(">>>> procdetail_hide = $procdetail_hide", $logfile); ?>
          <div class="back"><a href="patients.php?m=overview&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h3>Procedure Details<br /><span class="small">The patient's procedure.</span></h3>
		  <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
          <table class="su-table stack">
		    <tr>
			  <td><strong>Procedure</strong></td>
			  <td><?php echo $procedure; ?></td>
			</tr>
			<tr>
			  <td><strong>Procedure Date</strong></td>
			  <td><?php echo $c_plannedProcedureDate; ?></td>
			</tr>
		  </table>
          <div class="small-12 medium-12 large-12 cell text-center">
            <a href="#" class="no-u"><p class="directive">Change Procedure Date<a href="patients.php?m=procdate&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></p></a></a>
          </div>
          <h3>Mark Procedure Complete<br /><span class="small">This will trigger the post-op surveys</span></h3>      
          <div class="grid-x">
            <div class="hide-for-small-only medium-2 large-2 cell"></div>
            <div class="small-12 medium-8 large-8 cell text-center">
              <p>&nbsp;</p>
              <a href="patients.php?m=proccomplete&id=<?php echo $pe_id; ?>" name="" value="Procedure Complete" class="button large expanded">Procedure Complete</a>
            </div>
            <div class="hide-for-small-only medium-2 large-2 cell"></div>
          </div>
          <div class="grid-x">
            <div class="hide-for-small-only medium-2 large-2 cell"></div>
            <div class="small-12 medium-8 large-8 cell text-center">
              <p><a href="patients.php?m=proccancel&id=<?php echo $pe_id; ?>" name="" value="" class="button large expanded red">Cancel Procedure</a>
            </div>
            <div class="hide-for-small-only medium-2 large-2 cell"></div>
          </div>
        </div>
        <!-- END PROCDETAIL -->
        <!-- PROCCOMPLETE SECTION -->
       <div class="small-12 medium-6 large-6 cell content-right <?php echo $proccomplete_hide; ?>">
          <div class="back"><a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h5>Procedure Details<br /><span class="small">The patient's procedure.</span></h5>
          <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
		  <table class="su-table stack">
		    <tr>
			  <td><strong>Procedure</strong></td>
			  <td><?php echo $procedure; ?></td>
			</tr>
			<tr>
			  <td><strong>Procedure Date</strong></td>
			  <td><?php echo $c_plannedProcedureDate; ?></td>
			</tr>
		  </table>
          <p><strong>Search for the name of the surgeon who will perform this procedure</strong></p>
          <form action="patients_a.php?m=proccomplete&id=<?php echo $pe_id; ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell">
                  <label>Surgeon Name
                <select name="proc_surgeon" >
                   <?php make_combo("app_fd_ver_surgeons","c_surgeonName", "c_surgeonName", $proc_surgeon, "", " ORDER BY c_surgeonName "); ?>
                </select>
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>GMC Number
                <input type="text" name="proc_gmcnumber" placeholder="">
              </label>
            </div>
                  </div>
                </div>
          <div class="grid-x">
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
            <div class="small-12 medium-8 large-8 cell text-center">
              <p>&nbsp;</p>
              <p><br /><br />&nbsp;</p>
              <button type="submit" name="" value="confirm complete" class="button large expanded" />confirm complete</button>
      </form>
            </div>
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
          </div>
        </div>
        <!-- END PROCCOMPLETE SECTION -->
     <!-- END Left Content -->
  </div>
  <!-- Start Footer -->
      <?php include "../includes/footer.php"; ?>
      <?php logMsg("Patients(BOT)> mode: $mode",$logfile); ?>
  <!-- End Footer -->
</div>
      <script src="../js/vendor/jquery.js"></script>
      <script src="../js/vendor/what-input.js"></script>
      <script src="../js/vendor/foundation.js"></script>
      <script src="../js/jquery.plugin.min.js"></script>
      <script src="../js/foundation-datepicker.min.js"></script>
      <script src="../js/app.js"></script>
      <script>
         $(document).ready(function () {

    	    $('.date_element').fdatepicker({
               format:'dd/mm/yyyy',
	    });

            $(".clickable-row").click(function() {
              window.location = $(this).data("href");
            });
            $("#postalcode_ea").keyup(function(){
               var postalcode_val = $(this).val();
               var postalcode  = postalcode_val.replace(/ /g,'');
               if (postalcode.length == 6){
                  console.log("Let's get the address");
                  $.ajax({ 
                     url: "./ajax/get_address_by_postcode.php",
                     method: "POST",
                     data: {postcode: postalcode_val},
                     dataType: "HTML",
                  }).done(function(response){
                     // once ajax is completed
                     if(response.length > 0){
                        $("#address").html(response);
                        $("#address_1_ea").val("") ;
                        $("#address_2_ea").val("") ;
                        $("#city_ea").val("");
                        $("#country_ea").val("");
                        $("#postalcode_2_ea").val("");
                     }else{
                        $("#address").html("");
                     }
                  });
               }                
            });

            $("#address").change(function(){
               var address = $("#address option:selected").val(); 
               var address_array = address.split("~");
               $("#address_1_ea").val(address_array[0]) ;               
               $("#address_2_ea").val(address_array[1]) ;
               $("#city_ea").val(address_array[2]);
               $("#country_ea").val(address_array[3]);
               $("#postalcode_2_ea").val(address_array[4]);
            });
         });
      </script>  
  </body>
</html>
