<!doctype html>
<?php
// ***************************************
// patient/patients.php
// 2018 Copyright, Mesh Integration LLC
// 1/4/18 - WEL
// ***************************************

require_once '../utilities.php';
require_once "../alert_intruders.php";
if ($user_role<>"USER")
{
   header("Location: /ui/login.php");
   exit();
}

require_once 'functions.php';
session_start();
$logfile = "wel.log";

$mode = get_query_string('m');
logMsg("Patients: mode: ".$mode, $logfile);
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
// get patient list
$sql = "SELECT *
        FROM app_fd_pro_patientEpisodes
	ORDER BY c_surname";
$GetQuery = dbi_query($sql);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Lookup</title>
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/user.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/eido.css">
  <link rel="stylesheet" href="../css/app.css">  
  <link rel="stylesheet" href="../css/jquery.datepick.css">
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
  <link rel="icon" type="image/png" href="../favicon.png">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script>
    $(function() {
          $('#popupDatepicker').datepick();
          $('#inlineDatepicker').datepick({onSelect: showDate});
    });

    function showDate(date) {
          alert('The date chosen is ' + date);
    }
  </script>
</head>
<body>
<div class="grid-container">
  <!-- Start Header -->
  <?php include '../includes/patient_header.php';?>
  <!-- End Header -->
  <!-- Start Title Bar & Navigation -->  
  <form>
    <div class="grid-x grid-padding-x grey_header">
	  <div class="small-12 medium-2 cell gh_form">
        <label>I'm looking for a:
          <select>
    	    <option value="Patient">Patient</option>
  		  </select>
        </label>
      </div>
	  <div class="small-12 medium-5 cell gh_form basic">
        <label>Search by:
          <input type="text" placeholder="Jones">
        </label>
      </div>
	  <div class="small-12 medium-3 cell gh_form">
        <label>Procedure Date:
          <input type="text" placeholder=".medium-6.cell">
        </label>
      </div>
	  <div class="small-12 medium-2 cell gh_form no-label">
        <button type="submit" name="" class="button">Search</button>
      </div>
    </div>
  </form>		
  <!-- End Title Bar & Navigation --> 
  <!-- Start Content -->
  <div class="grid-x grid-margin-x su">
    <!-- Start Content-Left -->
    <div class="small-12 medium-6 large-6 cell content-left">
      <table width="90%" border="0"  class="su-table">
  	    <tbody>
          <tr>  
			<td colspan="3">
			  <span class="float-left"><a href="#"><i class="icon fi-plus fade"></i>&nbsp;<span>BULK ACTIONS</span></a></span>
			  <span class="float-right"><a href="#"><span>SORT BY</span>&nbsp;<i class="icon fi-plus fade-right"></i></a></span>
		    </td>
          </tr>
                  <!-- Start Filters Panel -->
<tr><td colspan="3">
                  <div class="grid-x">
                     <div class="cell">
                        <div class="accordion" data-accordion data-allow-all-closed="true">
                           <div class="accordion-item" data-accordion-item>
                              <!-- Accordion tab title -->
                              <a href="#" class="accordion-title">SORT BY</a>
                              <div class="accordion-content" data-tab-content>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">TIME ADDED</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small selected">NEWEST FIRST</a>
                                          <a class="button small off">OLDEST FIRST</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">NAME</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small off">A-Z</a>
                                          <a class="button small selected">Z-A</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">ACTIVITY</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small selected">MOST ACTIVE</a>
                                          <a class="button small off">LEAST ACTIVE</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">GENDER</div>
                                    <div class="cell">
                                       <div class="stacked-for-small button-group">
                                          <a class="button small selected">ANY</a>
                                          <a class="button small off">MALE</a>
                                          <a class="button small off">FEMALE</a>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">PROCEDURE DATE</div>
                                    <div class="cell">
                                       <div class="input-group">
                                          <input class="input-group-field searchbox" placeholder="DD-MM-YYYY" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                                          <div class="input-group-button"><input type="submit" class="button gold" value="GO" name="submit"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="grid-x align-middle">
                                    <div class="cell filtername">SEARCH WITHIN RESULTS</div>
                                    <div class="cell">
                                       <div class="input-group">
                                          <input class="input-group-field searchbox" placeholder="Hobbs" type="text" name="query" value="<?php if (!empty($_POST['query'])) echo $_POST['query']; ?>">
                                          <div class="input-group-button"><input type="submit" class="button gold" value="GO" name="submit"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
</td></tr>
                  <!-- End Filters Panel -->
          <tr>
            <td width="10%"><input type="checkbox"></td>
            <td width="80%">&nbsp;</td>
			<td width="10%">&nbsp;</td>
          </tr>
               <?php while ($qryResult=$GetQuery->fetch_assoc())
                     {
                        $id=$qryResult['id'];
                        $c_surname=$qryResult['c_surname'];
                        $c_firstName=$qryResult['c_firstName'];
			$c_referenceNumberHospitalId=$qryResult['c_referenceNumberHospitalId'];
			$c_procedureId=$qryResult['c_procedureId'];
			$c_description=$qryResult['c_description'];
			$pt_name =$c_surname.", ".$c_firstName;
                        $procedure = $c_procedureId." - ".$c_description;
                        $pt_status = get_pt_status($id);
                        if ($pt_status == "Inactive")
                           $pt_status_class = "pending_status";
                        else if (Spt_status == "Email Bounced")
                           $pt_status_class = "off_status";
                        else if ($pt_status == "Active")
                           $pt_status_class = "on_status";
               ?>
		  <tr>
                        <td width="10%"><input type="checkbox"></td>
			<td width="80%"><p class="name <?php echo $pt_status_class; ?>"><?php echo $pt_name; ?><br />
                              <span class="small">HospNo: <?php echo $c_referenceNumberHospitalId; ?><br />
                              <?php echo $procedure; ?> </span></p>
                        </td>
			<td width="10%"><a href="patients.php?m=overview&id=<?php echo $id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right" /></a></td>
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
        <!-- Start Content-Right  MAIN SECTION-->
        <?php
           $active_ct = get_stat_counts('active');
           $inactive_ct = get_stat_counts('inactive');
           $alert_ct = get_stat_counts('alert');

           // get the recent notifications 
           ;
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $main_hide; ?>">
          <h2>Add Patient</h2>
      <p>Start a Verify session with a new patient</p>
          <div class="grid-x">
            <div class="hide-for-small-only medium-2">&nbsp;</div>
        <div class="small-12 medium-8"><a href="patients.php?m=add"><button class="button large expanded">Get Started</button></a></div>
                <div class="hide-for-small-only medium-2">&nbsp;</div>
          </div>
          <hr class="gap" />
          <h2>Recent Notifications</h2>
          <table class="notifications">
            <tbody>
                  <tr>
                    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
                    <td class="upper">EMAIL BOUNCED<br />
                          PATIENT <strong>JAMES HIGSON</strong>
                        </td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
                    <td class="upper">EMAIL BOUNCED<br />
                          PATIENT <strong>JAMES HIGSON</strong>
                        </td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
                    <td class="upper">EMAIL BOUNCED<br />
                          PATIENT <strong>JAMES HIGSON</strong>
                        </td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="icon_frame text-center"><i class="fi-mail align-middle"></td>
                    <td class="upper">EMAIL BOUNCED<br />
                          PATIENT <strong>JAMES HIGSON</strong>
                        </td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td>&nbsp;</td>
                  </tr>
            </tbody>
          </table>
          <div class="grid-x">
            <div class="hide-for-small-only medium-2">&nbsp;</div>
        <div class="small-12 medium-8"><button type="button" name="" value="" class="button large expanded">View All</button></div>
                <div class="hide-for-small-only medium-2">&nbsp;</div>
          </div>
          <hr class="gap" />
          <h2>Stats</h2>
      <div class="grid-x">
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
                <input type="text" name="email" placeholder="NG12 5HP">
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
        <?php if ($mode=='overview')
              {  $sql_o  = "SELECT *
                            FROM app_fd_pro_patientEpisodes
                            WHERE id = '$pe_id'";
                 $GetQuery_o = dbi_query($sql_o);
                 $qryResult_o = $GetQuery_o->fetch_assoc();
                 $id=$qryResult_o['id'];
                 $c_surname=$qryResult_o['c_surname'];
                 $c_firstName=$qryResult_o['c_firstName'];
                 $c_nhsNumber=$qryResult_o['c_nhsNumber'];
                 $c_referenceNumberHospitalId=$qryResult_o['c_referenceNumberHospitalId'];
                 $c_dateOfBirth=$qryResult_o['c_dateOfBirth'];
                 $c_gender=$qryResult_o['c_gender'];
                 $c_postalCode=$qryResult_o['c_postalCode'];
                 $c_emailAddress=$qryResult_o['c_emailAddress'];
                 $c_mobileNumber=$qryResult_o['c_mobileNumber'];
                 $c_procedureId=$qryResult_o['c_procedureId'];
                 $c_description=$qryResult_o['c_description'];
                 $c_plannedProcedureDate=$qryResult_o['c_plannedProcedureDate'];
                 $procedure = $c_procedureId." - ".$c_description;
                 $pt_status = get_pt_status($id);
                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Email Bounced")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
                 $c_tags = $qryResult_o['c_tags'];
                 $arr_tags = array();
                 $arr_tags = explode(",", $c_tags);
                 $current_session = $qryResult_o['c_currentSessionNumber'];
                 
                 $email_sent = false;
                 $url_click_thru = false;
                 $session_started = false;
                 $session_incomplete = false;
                 $reminder_email = false;
                 $session_resumed = false;
                 $session_complete = false;
                 $procedure_complete = false;
                 $sql_tl= "SELECT c_emailSent".$current_session." AS email_sent, 
                                  c_urlClickThrough".$current_session." AS url_click_thru, 
                                  c_patientStartedSurveySession".$current_session." AS session_started, 
                                  c_surveySessionIncomplete".$current_session." AS session_incomplete, 
                                  c_reminderEmailSent".$current_session." AS reminder_email, 
                                  c_patientResumesSession".$current_session." AS session_resumed, 
                                  c_sessionComplete".$current_session." AS session_complete, 
                                  c_procedureMarkedComplete AS procedure_complete 
                           FROM app_fd_pro_patientTimelines
                           WHERE c_patientEpisodeId='$pe_id'";               
logMsg($sql_tl, $logfile);
                 $GetQuery_tl = dbi_query($sql_tl);
                 $qryResult_tl = $GetQuery_tl->fetch_assoc();
logMsg("NumRows: ".$GetQuery_tl->num_rows, $logfile);
                 if ($GetQuery_tl->num_rows==0)
                 {
                    $timeline = false;
                 }
                 else
                 {
                    $timeline = true; 
                    if ($qryResult_tl['email_sent']<>"")
                    {
                       $email_sent = true;
                       $email_sent_date = format_tl_date($qryResult_tl['email_sent']);
                    }
                    if ($qryResult_tl['url_click_thru']<>"")
                    {
                       $url_click_thru = true;
                       $url_click_thru_date = format_tl_date($qryResult_tl['url_click_thru']);
                    }
                    if ($qryResult_tl['session_started']<>"")
                    {
                       $session_started = true;
                       $session_started_date = format_tl_date($qryResult_tl['session_started']);
                    }
                    if ($qryResult_tl['session_incomplete']<>"")
                    {
                       $session_incomplete = true;
                       $session_incomplete_date = format_tl_date($qryResult_tl['session_incomplete']);
                    }
                    if ($qryResult_tl['reminder_email']<>"")
                    {
                       $reminder_email = true;
                       $reminder_email_date = format_tl_date($qryResult_tl['reminder_email']);
                    }
                    if ($qryResult_tl['session_resumed']<>"")
                    {
                       $session_resumed = true;
                       $session_resumed_date = format_tl_date($qryResult_tl['session_resumed']);
                    }
                    if ($qryResult_tl['session_complete']<>"")
                    {
                       $session_complete = true;
                       $session_complete_date = format_tl_date($qryResult_tl['session_complete']);
                    }
                    if ($qryResult_tl['procedure_complete']<>"")
                    {
                       $procedure_complete = true;
                       $procedure_complete_date = format_tl_date($qryResult_tl['procedure_complete']);
                    }
                 }
              }
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $overview_hide; ?>">
          <div class="back"><a href="patients.php?m=add"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h5>Patient Overview<br /><span class="small">See a patient's progress through Verify</span></h5>
          <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
          <table class="overview">
            <tr>
                  <td class="no_left_pad">
                    HospNo: <?php echo $c_referenceNumberHospitalId; ?><br />
                        NHS No: <?php echo $c_nhsNumber; ?><br />
                        DOB:  <?php echo $c_dateOfBirth; ?> 
                  </td>
                  <td><a href="patients.php?m=detail&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="align-center-middle" /></a></td>
                </tr>
                <tr>
                  <td class="no_left_pad">
                    <p>
                          Procedure<br />
                          <?php echo $procedure; ?> 
                        </p>
                    <p>
                          Procedure Date<br />
                          <?php echo $c_plannedProcedureDate; ?> 
                        </p>
                  </td>
                  <td><a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="align-center-middle" /></a></td>
                </tr>
                <tr>
                  <td colspan="2" class="no_left_pad">
                    <p>Status<br />
                      <img src="../img/icons/exclaimation_red.png" alt="attention needed" class="left_icon" />Missed Activity<br />
            </p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="no_left_pad">
                    <p>Tags<br />
                    <?php for ($i=0; $i<count($arr_tags); $i++) { ?>
                        <span class="label tag"><?php echo $arr_tags[$i]; ?>&nbsp;&nbsp;&nbsp;</span>
                    <?php } ?>
                </p>
                  </td>
                </tr>
          </table>
          <p>
            <table>
                  <tr>
                    <td colspan="5" class="no_left_pad">Patient Timeline</td>
                  </tr>
                <?php if (!$timeline) { ?>
                   <tr>
                    <td colspan="5" class="no_left_pad">No Survey Activity</td>
                  </tr>
                <?php } ?> 
                <?php if ($email_sent) { ?>
                  <tr>
                    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td><img src="../img/icons/envelope.png" alt="Envelope icon"></td>
                    <td class="upper">Email Sent</td>
                    <td><?php echo $email_sent_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($url_click_thru) { ?>
                  <tr>
                    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td><img src="../img/icons/pointer.png" alt="Cursor icon"></td>
                    <td class="upper">URL Invite Clicked</td>
                    <td><?php echo $url_click_thru_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($session_started) { ?>
                  <tr>
                    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td><img src="../img/icons/page_small.png" alt="Small page icon"></td>
                          <td class="upper">Session Started<br />
                          </td>
                    <td><?php echo $session_started_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($session_incomplete) { ?>
                  <tr>
                    <td class="status action_needed"><img src="../img/icons/caution.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td class="action_needed"><img src="../img/icons/page_small.png" alt="Cursor icon"></td>
                    <td class="upper action_needed">Session Incomplete</td>
                        <td class="action_needed"><?php echo $session_incomplete_date; ?></td>
                    <td class="action_needed"><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($reminder_email) { ?>
                  <tr>
                    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td><img src="../img/icons/envelope.png" alt="Envelope icon"></td>
                    <td>Reminder Email Sent</td>
                    <td><?php echo $reminder_email_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($session_resumed) { ?>
                  <tr>
                    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td><img src="../img/icons/page_small.png" alt="Envelope icon"></td>
                    <td>Session Resumed</td>
                    <td><?php echo $session_resumed_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($session_complete) { ?>
                  <tr>
                    <td class="status"><img src="../img/icons/check.png" alt="Circular checkmark icon" class="align-middle"></td>
                    <td><img src="../img/icons/page_small.png" alt="page icon"></td>
                    <td>Session Complete</td>
                    <td><?php echo $session_complete_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
                  <tr class="space">
                    <td colspan="5">&nbsp;</td>
                  </tr>
                <?php } ?>
                <?php if ($procedure_complete) { ?>
                  <tr>
                    <td class="action_needed"><img src="../img/icons/arrow.png" alt="Arrow icon" class="align-middle"></td>
                    <td><img src="../img/icons/procedure.png" alt="Procedure icon"></td>
                          <td class="upper"><?php echo $procedure; ?></td>
                    <td><?php echo $procedure_complete_date; ?></td>
                    <td><img src="../img/icons/greater.png" alt=""></td>
                  </tr>
               <tr>
                    <td class="action_needed">&nbsp;</td>
                    <td colspan="3" class="action_needed"><button type="button" name="" value="PROCEDURE COMPLETE" class="button expanded">PROCEDURE COMPLETE</button></td>
                    <td class="action_needed">&nbsp;</td>
                </tr>
                <?php } ?>
            </table>
          </p>
        </div>
        <!-- END OVERVIEW SECTION -->
        <!-- DETAIL SECTION -->
        <?php if ($mode=='detail')
              {  $sql_d  = "SELECT *
                            FROM app_fd_pro_patientEpisodes
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
                 else if (Spt_status == "Email Bounced")
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
                 else if (Spt_status == "Email Bounced")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
              }
logMsg("mode: $mode  detail_hide: $detail_hide editaddress_hide: $editaddress_hide",$logfile);
logMsg("c_surname: $c_surname c_address: $c_address Session_surname ".$_SESSION['add_surname']." session_address: ".$_SESSION['add_address'], $logfile);
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
                <?php if ($mode=="detail") { $m="edit"; $btn_class="inactive"; $btn_text="Edit Patient"; }
                 else if ($mode=="addreview") { $m="addconfirm"; $btn_class="active"; $btn_text="Confirm"; }
                 else if ($mode=="editreview") { $m="editconfirm"; $btn_class="active"; $btn_text="Confirm"; }
                ?>
                <a href="patients.php?m=<?php echo $m; ?>&id=<?php echo $id; ?>"><button type="button" name="" value="edit patient" class="button large expanded <?php echo $btn_class; ?>" /><?php echo $btn_text; ?></button></a>
             </div>
             <div class="hide-for-small-only medium-3 large-3 cell"></div>
          </div>
        </div>
        <!-- END DETAIL SECTION -->
        <!-- EDIT SECTION -->
        <?php if ($mode=='edit')
              {  $sql_e  = "SELECT *
                            FROM app_fd_pro_patientEpisodes
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
                            FROM app_fd_pro_patientEpisodes
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
                 else if (Spt_status == "Email Bounced")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
              }
              else if ($mode=="addaddress")
              {
                 $c_postalCode=$_SESSION['add_postalcode'];
              }
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
                <input type="text" name="address" value="<?php echo $c_address; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Address 2
                <input type="text" name="address2" value="<?php echo $c_address2; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Town
                <input type="text" name="city" value="<?php echo $c_city; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>County
                <input type="text" name="county" value="<?php echo $c_county; ?>">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>Postcode
                <input type="text" name="postalcode" value="<?php echo $c_postalCode; ?>">
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
        <!-- PROCDETAIL SECTION -->
        <?php if ($mode=="procdetail" || $mode=="proccomplete" || $mode=="procdate")
              {  $sql_pd  = "SELECT *
                            FROM app_fd_pro_patientEpisodes
                            WHERE id = '$pe_id'";
                 $GetQuery_pd = dbi_query($sql_pd);
                 $qryResult_pd = $GetQuery_pd->fetch_assoc();
                 $id=$qryResult_pd['id'];
                 $c_surname=$qryResult_pd['c_surname'];
                 $c_firstName=$qryResult_pd['c_firstName'];
                 $c_procedureId=$qryResult_pd['c_procedureId'];
                 $c_description=$qryResult_pd['c_description'];
                 $c_plannedProcedureDate=$qryResult_pd['c_plannedProcedureDate'];
                 $c_userName=$qryResult_pd['c_userName'];
logMsg("Patients: Proccomplete: c_userName: $c_userName", $logfile);
                 $procedure = $c_procedureId." - ".$c_description;
                 $pt_status = get_pt_status($id);
                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Email Bounced")
                    $pt_status_class = "ps_red";
                 else if ($pt_status == "Active")
                    $pt_status_class = "ps_green";
              }
        ?>
        <div class="small-12 medium-6 large-6 cell content-right <?php echo $procdetail_hide; ?>">
          <div class="back"><a href="patients.php?m=overview&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
          <h5>Procedure Details<br /><span class="small">The patient's procedure.</span></h5>
          <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
          <h6>Procedure</h6>
      <p><?php echo $procedure; ?></p>
      <h6>Procedure Date</h6>
      <p><?php echo $c_plannedProcedureDate; ?></p>
          <div class="small-12 medium-12 large-12 cell text-center">
            <a href="#" class="no-u"><p class="directive">Change Procedure Date<a href="patients.php?m=procdate&id=<?php echo $pe_id; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle" /></a></p></a>
      </div>
          <h6>Mark Procedure Complete<br /><span class="small">This will trigger the post-op surveys</span></h6>      
          <div class="grid-x">
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
            <div class="small-12 medium-8 large-8 cell text-center">
              <p>&nbsp;</p>
              <a href="patients.php?m=proccomplete&id=<?php echo $pe_id; ?>"><button type="button" name="" value="Procedure Complete" class="button large expanded" />Procedure Complete</button></a>
            </div>
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
          </div>
          <div class="grid-x">
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
            <div class="small-12 medium-8 large-8 cell text-center">
              <p>&nbsp;</p>
              <button type="button" name="" value="" class="button large expanded red" />Cancel Procedure</button>
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
          <h6>Procedure</h6>
          <p><?php echo $procedure; ?></p>
          <h6>Procedure Date</h6>
          <p><?php echo $c_plannedProcedureDate; ?></p>
	  <hr />
          <p><strong>Search for the name of the surgeon who will perform this procedure</strong></p>
          <form action="patients_a.php?m=proccomplete&id=<?php echo $pe_id; ?>&loginas=<?php echo urlencode($c_userName); ?>" method="post">
                <div class="grid-container">
          <div class="grid-x grid-padding-x">
                <div class="small-12 medium-12 large-12 cell">
                  <label>Surgeon Name
                <input type="text" name="" placeholder="Search by name">
              </label>
            </div>
                        <div class="small-12 medium-12 large-12 cell">
                  <label>GMC Number
                <input type="text" name="" placeholder="">
              </label>
            </div>
                  </div>
                </div>
          <div class="grid-x">
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
            <div class="small-12 medium-8 large-8 cell text-center">
              <p>&nbsp;</p>
              <button type="submit" name="" value="confirm complete" class="button large expanded" />confirm complete</button>
      </form>
            </div>
                <div class="hide-for-small-only medium-2 large-2 cell"></div>
          </div>
        </div>
        <!-- END PROCCOMPLETE SECTION -->
        <!-- PROCDATE SECTION -->
       <div class="small-12 medium-6 large-6 cell content-right <?php echo $procdate_hide; ?>">
          <div class="back"><a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div>
             <h5>Procedure Details<br /><span class="small">The patient's procedure.</span></h5>
             <h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
             <h6>Procedure</h6>
             <p><?php echo $procedure; ?></p>
             <h6>Procedure Date</h6>
             <p><?php echo $c_plannedProcedureDate; ?></p>
             <hr />
             <p>Select the new procedure date.</p>
             <form action="patients_a.php?m=procdate&id=<?php echo $pe_id; ?>" method="post">`
             <div id="inlineDatepicker"></div>
             <div class="grid-x">
                   <div class="hide-for-small-only medium-3 large-3 cell"></div>
                   <div class="small-12 medium-6 large-6 cell text-center">
                      <p>&nbsp;</p>
                      <button type="button" name="" value="save" class="button large expanded" />save</button>
                   </div>
             <div class="hide-for-small-only medium-3 large-3 cell"></div>
             </form>
          </div>
        </div>
        <!-- END PROCDATE SECTION -->
  </div>
  <!-- End Content -->
     <!-- END Left Content -->
  </div>
  <!-- End Content --> 
  <!-- Start Footer -->
  <div class="grid-x footer align-middle">
    <div class="small-6 medium-6 large-6 cell">
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
      <script src="../js/jquery.plugin.min.js"></script>
      <script src="../js/jquery.datepick.js"></script>
      <script src="../js/app.js"></script>
      <script>
         $(document).ready(function () {

         });
      </script>  
  </body>
</html>
