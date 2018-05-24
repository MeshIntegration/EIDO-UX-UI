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

if($user_role <> "USER" && $user_role <> "ADMIN") {
	header("Location: /ui/verify/login.php");
	exit();
}

require_once 'functions.php';
session_start();
$return_to = "pt";
$home = "patient.php";
$logfile = "patient.log";

// need to change according to session
$script_name = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);

if((isset($_GET['page']) && !empty($_GET['page']))) {
	$page = $_GET['page'];
	$start = ($page - 1) * $row;
} else if(isset($_SESSION['page'][$script_name]['no']) && !empty($_SESSION['page'][$script_name]['no'])) {
	$page = $_SESSION['page'][$script_name]['no'];
	$start = ($page - 1) * $row;
}
$_SESSION['page'][$script_name]['no'] = $page;

////////////////////////////////////////////////
// sorting and filtering session setting
////////////////////////////////////////////////

logMsg("Filter name: ".$_SESSION['filter']['name'], $logfile);

if(isset($_GET['filter']) && $_GET['filter'] == 1) {
	// set the filter detail in session
	$is_default_filter = false;
	// reset the pagination values when any filter / search start
	$_SESSION['page'][$script_name]['no'] = 0;

	// if operation was already set and they clicked it again - turn it off (TOGGLE)
	if(isset($_GET['operation']) && $_GET['operation'] == 1 && ($_SESSION['filter']['operation'] == 0 || $_SESSION['filter']['operation'] == 2)) {
		$_SESSION['filter']['operation'] = 1;
	} else if(isset($_GET['operation']) && $_GET['operation'] == 1 && $_SESSION['filter']['operation'] == 1) {
		unset($_SESSION['filter']['operation']);
	} else if(isset($_GET['operation']) && $_GET['operation'] == 2 && ($_SESSION['filter']['operation'] == 0 || $_SESSION['filter']['operation'] == 1)) {
		$_SESSION['filter']['operation'] = 2;
	} else if(isset($_GET['operation']) && $_GET['operation'] == 2 && $_SESSION['filter']['operation'] == 2) {
		unset($_SESSION['filter']['operation']);
	}
	if(isset($_GET['time_added']) && $_GET['time_added'] == 1) {
		$_SESSION['filter']['time_added'] = 1;
	} else if(isset($_GET['time_added']) && $_GET['time_added'] == 2) {
		$_SESSION['filter']['time_added'] = 2;
	}

	if(isset($_GET['name']) && $_GET['name'] == 1) {
		$_SESSION['filter']['name'] = 1;
	} else if(isset($_GET['name']) && $_GET['name'] == 2) {
		$_SESSION['filter']['name'] = 2;
	}

	if(isset($_GET['activity']) && $_GET['activity'] == 1) {
		$_SESSION['filter']['activity'] = 1;
                unset($_SESSION['filter']['time_added']); // this can't be left on for this one
	} else if(isset($_GET['activity']) && $_GET['activity'] == 2) {
		$_SESSION['filter']['activity'] = 2;
                unset($_SESSION['filter']['time_added']);
	}

	if(isset($_GET['status']) && $_GET['status'] == 1) {
		$_SESSION['filter']['status'] = 1;
	} else if(isset($_GET['status']) && $_GET['status'] == 2) {
		$_SESSION['filter']['status'] = 2;
	} else if(isset($_GET['status']) && $_GET['status'] == 3) {
		$_SESSION['filter']['status'] = 3;
	} else if(isset($_GET['status']) && $_GET['status'] == 4) {
		$_SESSION['filter']['status'] = 4;
	} else if(isset($_GET['status']) && $_GET['status'] == 5) {
		$_SESSION['filter']['status'] = 5;
	} else if(isset($_GET['status']) && $_GET['status'] == 6) {
		$_SESSION['filter']['status'] = 6;
	}

	if(isset($_GET['gender']) && $_GET['gender'] == 1) {
		$_SESSION['filter']['gender'] = 1;
	} else if(isset($_GET['gender']) && $_GET['gender'] == 2) {
		$_SESSION['filter']['gender'] = 2;
	} else if(isset($_GET['gender']) && $_GET['gender'] == 3) {
		$_SESSION['filter']['gender'] = 3;
	}

	// search within results
	if(isset($_POST['search_within_submit']) && !empty($_POST['search_within_submit'])) {
		$_SESSION['filter']['search_within_query'] = $_REQUEST['search_within_query'];
	}

	if(isset($_GET['tags']) && !empty($_GET['tags']) && ($_GET['tags'] != $_SESSION['filter']['tags'])) {
		$_SESSION['filter']['tags'] = $_GET['tags'];
	} else {
		$_SESSION['filter']['tags'] = "";
	}

	// top search query
	if(isset($_REQUEST['top_search_submit']) && !empty($_REQUEST['top_search_submit'])) {
                // if there was a search term and they deleted it - clear all results
                if ($_SESSION['filter']['top_search_query']<>"" && $_REQUEST['top_search_query']=="") {
                      header("Location: clear_search.php?m=main");
                      exit();
                } else {
		      $_SESSION['filter']['top_search_query'] = $_REQUEST['top_search_query'];
		      $_SESSION['filter']['looking_for'] = $_REQUEST['looking_for'];
		      $_SESSION['filter']['procedure_date'] = $_REQUEST['procedure_date'];
		      //logMsg(">>> Procedure date search value: " . $_SESSION['filter']['procedure_date'], $logfile);
      
		      // 4/9/18 - SD
		      // Pre-Op & Post-Op filters are reset(cleared)
		      unset($_SESSION['filter']['operation']);

		      // status, gender, Search within and tag search will reset
		      unset($_SESSION['filter']['status']);
		      unset($_SESSION['filter']['gender']);
                }
	}

	if(get_query_string('m') <> 'stats') {
		header("Location:patients.php");
		exit;
	}
} else {
	if(!isset($_SESSION['filter'])) {     // OR count($_SESSION['filter']) <= 2) {
		// default filter state
		$is_default_filter = true;

		// time added
		$_SESSION['filter']['time_added'] = 1;

		// Name
		// $_SESSION['filter']['name'] = 1;
	}
}

$mode = get_query_string('m');
if($mode == "") {
	logMsg("mode value was blank", $logfile);
	$mode = "main";
}
logMsg("Patients(TOP)> mode: " . $mode, $logfile);
$id = get_query_string('id');
if($id <> $_SESSION['curr_pe_id']) {
	$_SESSION['tl_view_all'] = false;
	$_SESSION['curr_pe_id'] = $id;
}

// turn everything off
$main_hide = "hide";
$add_hide = "hide";
$overview_hide = "hide";
$stats_hide = "hide";
$detail_hide = "hide";
$edit_hide = "hide";
$editaddress_hide = "hide";
$editconfirm_hide = "hide";
$addaddress_hide = "hide";
$procproceed_hide = "hide";
$procdetail_hide = "hide";
$proccomplete_hide = "hide";
$proccancel_hide = "hide";
$procdate_hide = "hide";
$procselect_hide = "hide";
$procdate_hide = "hide";
$procsurgeon_hide = "hide";
$procsummary_hide = "hide";
$procconfirm_hide = "hide";
$review_hide = "hide";
$reviewconfirm_hide = "hide";

if($mode == "" || $mode == "main") {
	$main_hide = "";
} else if($mode == "add") {
	$add_hide = "";
	//$pe_id=$id;
} else if($mode == "overview") {
	$overview_hide = "";
	$pe_id = $id;
        $timeline_mode=get_query_string('tlm');
} else if($mode == "stats") {
	$stats_hide = "";
} else if($mode == "detail" || $mode == "addreview" || $mode == "editreview") {
	$detail_hide = "";
	$pe_id = $id;
} else if($mode == "edit") {
	$edit_hide = "";
	$pe_id = $id;
} else if($mode == "editaddress" || $mode == "addaddress") {
	$editaddress_hide = "";
	$pe_id = $id;
} else if($mode == "editconfirm") {
	$editconfirm_hide = "";
	$pe_id = $id;
} else if($mode == "procproceed") {
	$procproceed_hide = "";
	$pe_id = $id;
} else if($mode == "procdetail") {
	$procdetail_hide = "";
	$rtn = get_query_string('rtn');
	$procdetail_rtn_str = "&rtn=$rtn";
	$pe_id = $id;
} else if($mode == "proccomplete") {
	$proccomplete_hide = "";
	$pe_id = $id;
} else if($mode == "proccancel") {
	$proccancel_hide = "";
	$pe_id = $id;
} else if($mode == "procdate") {
	$procdate_hide = "";
	$rtn = get_query_string('rtn');
	if($rtn == "ov")
		$procdate_rtn_mode = "overview"; else $procdate_rtn_mode = "procselect";
	$pe_id = $id;
} else if($mode == "procselect") {
	$procselect_hide = "";
	$rtn = get_query_string('rtn');
	if($rtn == "ov")
		$procselect_rtn_mode = "overview"; else $procselect_rtn_mode = "detail";
        if ($_SESSION['workflow']=="ADD") 
                $procselect_rtn_mode = "procproceed";
	$pe_id = $id;
	$proc_id_selected = get_query_string('proc_id');
} else if($mode == "procsurgeon") {
	$procsurgeon_hide = "";
	$pe_id = $id;
} else if($mode == "procsummary") {
	$procsummary_hide = "";
	$pe_id = $id;
} else if($mode == "procconfirm") {
	$procconfirm_hide = "";
	$pe_id = $id;
} else if($mode == "review") {
	$review_hide = "";
	$pe_id = $id;
	$timeline_id = get_query_string('tid');
} else if($mode == "reviewconfirm") {
	$reviewconfirm_hide = "";
	$pe_id = $id;
}

// get patient list
$sql = "SELECT *,  
           (SELECT count(*)
            FROM $TBLTIMELINES as timeline
            WHERE timeline.c_patientEpisodeId=episodes.id AND c_timelineEntryDetail in ('" . implode("','", $timeline_entry_detail) . "')) AS total_activity
        FROM $TBLPTEPISODES AS episodes
        WHERE #WHERE#
        ORDER BY #ORDER# 
        LIMIT #LIMIT#";

$pagination_sql = "SELECT *,
                      (SELECT count(*)
                       FROM $TBLTIMELINES as timeline
                       WHERE timeline.c_patientEpisodeId=episodes.id AND c_timelineEntryDetail in ('" . implode("','", $timeline_entry_detail) . "')) `total_activity`
                  FROM $TBLPTEPISODES as episodes
                  WHERE #WHERE#
                  ORDER BY #ORDER# 
                  ";
///////////////////////////////////////////////////
// Add filter to query : START
//////////////////////////////////////////////////

// Filter session will be destroyed if the requested page is visited from some other page
$dirname = dirname($_SERVER['HTTP_REFERER']) . "/";
$req_dirname = dirname($_SERVER['PHP_SELF']) . "/";
// logMsg("$dirname - $req_dirname", $logfile);
if(basename($dirname) != basename($req_dirname)) {
	// only unset the search query items
	// logMsg(">>>> Unset the search query items", $logfile);
	unset($_SESSION['filter']['top_search_query']);
	unset($_SESSION['filter']['looking_for']);
	unset($_SESSION['filter']['procedure_date']);
	unset($_SESSION['filter']['search_within_query']);
}

if(isset($_SESSION['filter']) && sizeof($_SESSION['filter']) > 0) {
	$limit = "$start,$row";

	// Quick Filter
	if(isset($_SESSION['filter']['operation'])) {
		if($_SESSION['filter']['operation'] == 1) {
			$where[] = "c_procedureStatus = 'POST'";
		} else if($_SESSION['filter']['operation'] == 2) {
			$where[] = "c_procedureStatus = 'PRE'";
		}
	}

	if(isset($_SESSION['filter']['name'])) {
		if($_SESSION['filter']['name'] == 1) {
			$order[] = "c_surname ASC";
			$order[] = "c_firstName ASC";
		} else if($_SESSION['filter']['name'] == 2) {
			$order[] = "c_surname DESC";
			$order[] = "c_firstName DESC";
		}
	}

	if(isset($_SESSION['filter']['time_added'])) {
		if($_SESSION['filter']['time_added'] == 1) {
			$order[] = "dateCreated DESC";
		} else if($_SESSION['filter']['time_added'] == 2) {
			$order[] = "dateCreated ASC";
		}
	}

	if(isset($_SESSION['filter']['activity'])) {
		if($_SESSION['filter']['activity'] == 1) {
			//$where[] = "c_status = 'Episode Complete'";
			$order[] = "total_activity DESC";
		} else if($_SESSION['filter']['activity'] == 2) {
			//$where[] = "c_status = 'Episode Complete'";
			$order[] = "total_activity ASC";
		}
	}

	// logMsg(">>>Mode: $mode -  Filter-Status = " . $_SESSION['filter']['status'], $logfile);
	if(isset($_SESSION['filter']['status'])) {
		if($_SESSION['filter']['status'] == 1) {
			// need to get status filter - red ALERTS
			$where[] = "c_hasAlert='Y'";
			$order[] = "c_surname ASC";
		} else if($_SESSION['filter']['status'] == 2) {
			// need to get status filter - green ACTIVE
			$where[] = "c_status<>'PENDING' AND c_procedureStatus<>'CANCEL' 
                                AND c_status<>'Episode Complete' AND c_hasAlert<>'Y'";
			$order[] = "c_surname ASC";
		} else if($_SESSION['filter']['status'] == 3) {
			// need to get status filter - gray INACTIVE
			$where[] = "c_status='PENDING' OR c_procedureStatus='CANCEL' OR c_status='Episode Complete'";
			$order[] = "c_surname ASC";
		} else if($_SESSION['filter']['status'] == 4) {
			// need to get status filter - ALL/TOTAL
			//$where[] = "" ;
			$order[] = "c_surname ASC";
		} else if($_SESSION['filter']['status'] == 5) {
			// need to get status filter - COMPLETED SURVEYS/TODAY 
                        $today=date("Y-m-d");
			$where[] = "c_status='Session Complete' AND DATE(dateModified)='$today'" ;
			$order[] = "c_surname ASC";
		} else if($_SESSION['filter']['status'] == 6) {
			// need to get status filter - INCOMPLETE SURVEYS/TOTAL 
			$where[] = "c_status LIKE 'Invited to Session%'" ;
			$order[] = "c_surname ASC";
		}
	}

	if(isset($_SESSION['filter']['gender'])) {
		if($_SESSION['filter']['gender'] == 1) {
			// we need all records do nothing
		} else if($_SESSION['filter']['gender'] == 2) {
			// we need only Male records
			$where[] = "c_gender='Male'";
		} else if($_SESSION['filter']['gender'] == 3) {
			// we need only Female records
			$where[] = "c_gender='Female'";
		}
	}

	if(isset($_SESSION['filter']['tags'])) {
		if(!empty($_SESSION['filter']['tags'])) {
			$where[] = "c_tags='" . $_SESSION['filter']['tags'] . "'";
		}
	}

	// add top search query

	if(isset($_SESSION['filter']['top_search_query'])) {
		if(!empty($_SESSION['filter']['top_search_query'])) {
			// add the search query to filter sql
			$top_search_query = explode(" ", strtolower($_SESSION['filter']['top_search_query']));
			if($_SESSION['filter']['looking_for'] == 'patient') {
				foreach($top_search_query as $temp)
					$where[] = "(c_surname LIKE '%" . $temp . "%' OR c_firstName LIKE '%" . $temp . "%' OR id LIKE '%" . $temp . "%')";
			} else if($_SESSION['filter']['looking_for'] == 'procedure') {
				foreach($top_search_query as $temp)
					$where[] = "(c_description LIKE '%" . $temp . "%' OR c_procedureId LIKE '%" . $temp . "%')";
			} else if($_SESSION['filter']['looking_for'] == "surgeon") {
				foreach($top_search_query as $temp)
					$where[] = "(c_surgeonName LIKE '%" . $temp . "%' OR c_gmcNumber LIKE '%" . $temp . "%')";
			}
		}

		if(!empty($_SESSION['filter']['procedure_date'])) {
			//$procedure_date = new DateTime($_SESSION['procedure_date']);
			//$where[] = "c_plannedProcedureDate='".$procedure_date->format('d/m/Y')."'";
			$where[] = "c_plannedProcedureDate='" . $_SESSION['filter']['procedure_date'] . "'";
		}
	}

} else {
	// no filter found so add default
	$limit = "$start, $row";
}

if(empty($where)) {
	$where[] = 1;
}

if(empty($order)) {
	$order[] = "c_surname ASC";
}

$sql = str_replace(array("#WHERE#", "#ORDER#", "#LIMIT#"),
	array(implode(" AND ", $where),
		implode(", ", $order),
		$limit),
	$sql);

logMsg("SQL for Patient List >>> " . $sql, $logfile);

$pagination_sql = str_replace(array("#WHERE#", "#ORDER#", "#LIMIT#"),
	array(implode(" AND ", $where),
		implode(", ", $order),),
	$pagination_sql);

// Filter to add within search
if(isset($_SESSION['filter']) && sizeof($_SESSION['filter']) > 0) {
	if(isset($_SESSION['filter']['search_within_query']) && !empty($_SESSION['filter']['search_within_query'])) {

		// get patient list
		$sub_sql = "SELECT *,
           (SELECT count(*)
            FROM $TBLTIMELINES as timeline
            WHERE timeline.c_patientEpisodeId=episodes.id AND c_timelineEntryDetail in ('" . implode("','", $timeline_entry_detail) . "')) `total_activity`
        FROM $TBLPTEPISODES as episodes
        WHERE #WHERE#
        ORDER BY #ORDER#";

		$sub_pagination_sql = "SELECT *,
                      (SELECT count(*)
                       FROM $TBLTIMELINES as timeline
                       WHERE timeline.c_patientEpisodeId=episodes.id AND c_timelineEntryDetail in ('" . implode("','", $timeline_entry_detail) . "')) `total_activity`
                  FROM $TBLPTEPISODES as episodes
                  WHERE #WHERE#
                  ORDER BY #ORDER#
                  ";
		$sub_sql = str_replace(array("#WHERE#", "#ORDER#"),
			array(implode(" AND ", $where),
				implode(", ", $order),),
			$sub_sql);

		$sub_pagination_sql = str_replace(array("#WHERE#", "#ORDER#"),
			array(implode(", ", $where),
				implode(", ", $order),),
			$sub_pagination_sql);

		$sub_where_field = array("t.c_postalCode",
			"t.c_description",
			"t.c_surname",
			"t.c_procedure",
			"t.c_userName",
			"t.c_tags",
			"t.c_firstName",
			"t.c_status",
			"t.c_hospitalName",
			"t.c_surgeonName",
			"t.c_gmcNumber",
			"t.c_displayName");

		$sub_where_value = $_SESSION['filter']['search_within_query'];

		$sub_where = array_map(function ($sub) use ($sub_where_value) {
			return $sub . " LIKE '%" . $sub_where_value . "%'";
		},
			$sub_where_field);

		$sub_where_str = implode(" OR ", $sub_where);
		$sql = "SELECT t.* 
              FROM ($sub_sql) as t 
              WHERE " . $sub_where_str . " LIMIT " . $limit;

		$pagination_sql = "SELECT t.*
              FROM ($sub_pagination_sql) as t
              WHERE " . $sub_where_str;
	}
}

// fetch tags value
$tags_sql = "SELECT DISTINCT(c_tags) FROM $TBLPTEPISODES  WHERE c_tags is not null";

$tags_GetQuery = dbi_query($tags_sql);

///////////////////////////////////////////////////
// Add Filter to Query : END
///////////////////////////////////////////////////

$GetQuery = dbi_query($sql);
$GetQuery_all = dbi_query($pagination_sql);
// logMsg(">>>>>> PAGINATION SQL: $pagination_sql",$logfile);
$results_count = $GetQuery_all->num_rows;
// logMsg(">>> results_count: $results_count",$logfile);

?>
<html class="no-js" lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Patient Lookup</title>
	<link rel="stylesheet" href="../css/foundation.css">
	<link rel="stylesheet" href="../css/dashboard.css">
	<link rel="stylesheet" href="../css/app.css">
	<link rel="stylesheet" href="../css/foundation-datepicker.min.css">
	<link rel="stylesheet" href="../css/timeline.css">
	<link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">


	<link rel="stylesheet" href="/ui/verify/css/icons/eido-icons.css" type="text/css" />
	<link rel="icon" type="image/png" href="../favicon.png">
    <!--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"/>-->

	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="../css/eido.css">
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>-->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>
<div class="grid-container">
	<!-- Start Header -->
	<?php include '../includes/patient_header.php'; ?>
	<!-- End Header -->
	<!-- Start Title Bar & Navigation -->
	<form action="patients.php?filter=1" method="POST">
		<div class="grid-x grid-padding-x grey_header search_bar">
			<div class="small-12 medium-2 cell gh_form">
				<label>I'm looking for a:
					<?php $looking_for = (isset($_SESSION['filter']['looking_for'])) ? $_SESSION['filter']['looking_for'] : ""; ?>
					<select id="looking_for" name="looking_for" value="">
						<option value="patient" <?php if($looking_for == "patient")
							echo "selected"; ?>>Patient
						</option>
						<option value="procedure" <?php if($looking_for == "procedure")
							echo "selected"; ?>>Procedure
						</option>
						<option value="surgeon" <?php if($looking_for == "surgeon")
							echo "selected"; ?>>Surgeon
						</option>
					</select>
				</label>
			</div>
			<div class="small-12 medium-4 cell gh_form basic">
				<label>Search by:
					<div class="input-group tb-search">
						<div class="input-group-button left-append">
							<i class="fi-magnifying-glass"></i>
							<!--<a href="clear_search.php?m=<?php echo $mode; ?>" class="float-right align-center-middle"><img src="../img/close-icon.png" alt="" style="margin:7px;"/></a>-->
						</div>
						<input type="text" id="top_search_query" name="top_search_query" value="<?php echo isset($_SESSION['filter']['top_search_query']) ? $_SESSION['filter']['top_search_query'] : ''; ?>"
								class="input-group-field">
						<div class="input-group-button right-append">
							<a href="clear_search.php?m=main" class="clear-icon">
								<i class="eido-icon-remove"></i>
							</a>
							<!--<a href="clear_search.php?m=<?php echo $mode; ?>" class="float-right align-center-middle"><img src="../img/close-icon.png" alt="" style="margin:7px;"/></a>-->
						</div>
					</div>
				</label>
			</div>
			<div class="small-12 medium-4 cell gh_form">
				<label>Procedure Date:
					<div class="input-group tb-search">
						<div class="input-group-button left-append">
							<i class="fi-calendar"></i>
						</div>
						<input id="popupDatepicker" name="procedure_date" class="date_element" value="<?php if(isset($_SESSION['filter']['procedure_date'])) {
							echo $_SESSION['filter']['procedure_date'];
						} ?>" type="text"
								class="input-group-field">
						<div class="input-group-button right-append">
							<a href="clear_procdate_search.php?m=main" class="clear-icon">
								<i class="eido-icon-remove"></i>
							</a>
						</div>
					</div>
				</label>
			</div>
			<div class="small-12 medium-2 cell gh_form no-label text-right">
				<button type="submit" name="top_search_submit" class="button" value="search">Search</button>
			</div>
		</div>
	</form>
	<!-- End Title Bar & Navigation -->
	<!-- Start Content -->
	<div class="grid-x su">
		<!-- Start Content-Left -->
		<div class="small-12 medium-6 large-6 cell content-left">
			<div border="0" class="su-table stack large-12">
				<!-- **************************************************************
                         Start Filters Panel
               **************************************************************  -->

		<div class="grid-x">
			<div class="small-12 cell">
			<div class="accordion" data-accordion data-allow-all-closed="true">
					<div class="accordion-item" data-accordion-item>
						<a href="#" class="accordion-title sort"></a>
						<!-- Accordion tab title -->
						<div class='grid-x row'>
							<div class="small-6 medium-8 cell text-left padding-10">
								<a href='patients.php?filter=1&operation=1' class="button <?php echo (isset($_SESSION['filter']['operation']) && $_SESSION['filter']['operation'] == 1) ? "selected" : "inactive"; ?>">Post op</a>&nbsp;&nbsp;<a href='patients.php?filter=1&operation=2' class="button <?php echo (isset($_SESSION['filter']['operation']) && $_SESSION['filter']['operation'] == 2) ? "selected" : "inactive"; ?>">Pre op</a>
							</div>
						</div>
						<div class="small-12 medium-12 cell"></div>
						<div class='grid-x row'>
							<div class="small-6 medium-6 cell text-left padding-10">
								Results: <?php echo $results_count; ?>
                                                        </div>
							<div class="small-6 medium-6 cell  padding-10">
                                              <?php if (!isset($_SESSION['filter']['name']) && !isset($_SESSION['filter']['status']) && !isset($_SESSION['filter']['activity']) && !isset($_SESSION['filter']['gender']) && !isset($_SESSION['filter']['search_within_query'])) { ?>
                                                   <span class="float-right">Filters Disabled</span>
                                              <?php } else { ?>
                                                   <span class="float-right">Filters Active | <a href="clear_filter.php" class="float-right ">&nbsp; Reset</a></span>
                                              <?php } ?>
							</div>
						</div>
						<!-- // initial sort by tab
                  <a href="#" class="accordion-title sort">
	    <div class="grid-x">
	      <div class="small-12 medium-8 cell text-left"><button class="button fc">Post Op</button>&nbsp;&nbsp;<button class="button fc">Pre Op</button></div>
		  <div class="small-12 medium-4 cell"></div>
        </div>
	  </a>
                  -->
						<div class="accordion-content sort" data-tab-content>
							<?php
							// Reset Filter button is not shown if filter is default
							if(!$is_default_filter) {
								?>
								<div class="grid-x rule">
									<div class="small-12 cell">
										<!--<a href="clear_filter.php" class="float-right align-center-middle"><img src="../img/close-icon.png" alt="" style="margin:7px;"></a>
										<a href="clear_filter.php" class="float-right align-center-middle">Reset Filters</a>-->
									</div>
								</div>
							<?php } ?>
							<div class="grid-x rule" style="margin-top: 0.875rem;">
								<div class="small-12 medium-4 cell">
									<label for="middle-label" class="middle">Time Added</label>
								</div>
								<div class="small-12 medium-8 cell">
			<a href="patients.php?filter=1&time_added=1" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 1) ? 'selected' : 'inactive'; ?>">Newest First</a>&nbsp;<a href="patients.php?filter=1&time_added=2" class="button <?php echo (isset($_SESSION['filter']['time_added']) && $_SESSION['filter']['time_added'] == 2) ? 'selected' : 'inactive'; ?>">Oldest First</a>
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
							<div class="grid-x rule">
								<div class="small-12 medium-12 cell">
									<label for="middle-label" class="ml_label">Recent tags</label>
								</div>
								<div class="small-12 medium-12 cell">
									<?php
									while($tags_qryResult = $tags_GetQuery->fetch_assoc()) {
										?>
										<a href="patients.php?filter=1&tags=<?php echo $tags_qryResult['c_tags']; ?>"><span class="label <?php echo (isset($_SESSION['filter']['tags']) && $_SESSION['filter']['tags'] == $tags_qryResult['c_tags']) ? "success" : "secondary"; ?> "><?php echo strtoupper($tags_qryResult['c_tags']); ?></span></a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<!-- **********************************************
            End Filters Panel
********************************************** -->
				<!-- *******************
          <tr>
            <td><input type="checkbox"></td>
            <td>&nbsp;</td>
			<td>&nbsp;</td>
          </tr>
      ****************    -->
				<div class="row">

					<ul class="patient-list">

					<?php while($qryResult = $GetQuery->fetch_assoc()) {
						$id = $qryResult['id'];
						$c_surname = strtoupper($qryResult['c_surname']);
						$c_firstName = ucfirst($qryResult['c_firstName']);
						$c_referenceNumberHospitalId = $qryResult['c_referenceNumberHospitalId'];
						$c_procedureId = $qryResult['c_procedureId'];
						$c_description = $qryResult['c_description'];
						$c_displayName = $qryResult['c_displayName'];
						$pt_name = $c_surname . ", " . $c_firstName;
						if($c_procedureId <> "")
							$procedure = $c_procedureId . " - " . $c_description; else
							$procedure = "";
						$pt_status = get_pt_status($id);
						if($pt_status == "Inactive" || $pt_status == "Pending")
							$pt_status_class = "pending_status"; 
                                                else if($pt_status == "Alert")
							$pt_status_class = "off_status"; 
                                                else if($pt_status == "Active")
							$pt_status_class = "on_status";

						$isSelected = '';
						if($pe_id == $id) {
							$isSelected = ' class="selected"';
						}
						?>

						<li<?php echo $isSelected; ?>>
							<a href="patients.php?m=overview&tlm=key&id=<?php echo $id; ?>">
								<span class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
								<p class="<?php echo $pt_status_class; ?>">
									<span class="uc"><?php echo $pt_name; ?></span><br/>
									HospNo: <?php echo $c_referenceNumberHospitalId; ?><br/>
									<?php echo $procedure; ?>
								</p>
							</a>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<?php
			$GetQuery = dbi_query($pagination_sql);
			$totalRecord = $GetQuery->num_rows;
			$pagination = get_pagination($page, $totalRecord);
			?>
			<div class="row text-center">
				<div class="small-12 pagination-btm"><?php echo $pagination; ?></div>
			</div>
		</div>
		<!-- End Content-Left -->
		<!-- Start Content-Right  MAIN SECTION-->
		<?php if($mode == "main") {
			$active_ct = get_stat_counts('active');
			$inactive_ct = get_stat_counts('inactive');
			$alert_ct = get_stat_counts('alert');

			// get the recent notifications - type - how many - patientId
			$arr_notifications = get_notifications('Alert', '3', "");
			$has_notifications = false;
			if(count($arr_notifications) > 0) {
				$has_notifications = true;
			}
		}
		?>
		<div class="small-12 medium-6 large-6 cell content-right <?php echo $main_hide; ?>">

			<h3>Add Patient</h3>
			<div style="margin-left: 20px; margin-right: 20px; padding-left: 0px" class="small-12 cell field">
				<p>Start a Verify session with a new patient</p>
				<div class="grid-x">
					<div class="hide-for-small-only medium-2">&nbsp;</div>
					<div class="small-12 medium-7">
						<a href="patients_a.php?m=gotoaddpt">
							<button class="button large expanded">Get Started</button>
						</a>
					</div>
					<div class="hide-for-small-only medium-2">&nbsp;</div>
				</div>
			</div>
			<hr class="gap"/>
			<h3>Recent Notifications</h3>
            <div class="standard-padding <?php echo($has_notifications == true ? " hide" : "") ?>">
	            <div class='notification-bell text-center'>
		            <span class="icon display-block"><i class="icon eido-icon-bell-o"></i></span>
		            <p><strong>No notifications right now.</strong><br>Everything must be working.</p>
	            </div>
            </div>
			<div class="standard-padding <?php echo($has_notifications == false ? "hide" : "") ?>" style="margin-bottom:30px;">
				<ul class="" id="Notifications">
					<?php for($n = 0; $n < count($arr_notifications); $n++) {
					$n_name = $arr_notifications[$n]['c_timelineEntryDetail'];
					$n_date = $arr_notifications[$n]['dateCreated'];
					$n_date = substr($n_date, 0, strpos($n_date, " "));
					$n_date = format_uk_date($n_date);
					$n_type = $arr_notifications[$n]['c_timelineEntryType']; // Alert or Event
					$n_id = $arr_notifications[$n]['id'];
					$n_patient_name = $arr_notifications[$n]['c_firstName'] . " " . $arr_notifications[$n]['c_surname'];
					$n_patientEpisodeId = $arr_notifications[$n]['c_patientEpisodeId'];
					$n_imgfile = $arr_tl_data[$n_desc];
					$n_class = "status action_needed";

					$n_icon = "envelope-open-o22";
					if($n_type == "Whatever signifies a bounced email") {
						$n_icon = "envelope-open-o22";
					} elseif($n_type == "Whatever signifies a rejected text message") {
						$n_icon = "comment";
					}
					?>
					<li>
						<a class="link-full-block" href="patients.php?m=overview&tlm=key&id=<?php echo $n_patientEpisodeId; ?>">
							<span class="right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
							<div class="n-icon">
								<i class="icon eido-icon-<?php echo $n_icon; ?>"></i>
							</div>
							<p><?php echo $n_name; ?><br/>Patient <strong><?php echo $n_patient_name; ?></strong></p>

						</a>
					</li>
					<?php } ?>
				</ul>
			</div>

			<div class="grid-x<?php echo($has_notifications == false ? " hide" : "") ?>">
				<div class="hide-for-small-only medium-2">&nbsp;</div>
				<div class="small-12 medium-8">
					<a href="patients.php?m=main&filter=1&status=1&page=1" class="button large expanded">View all</a>
				</div>
				<div class="hide-for-small-only medium-2">&nbsp;</div>
			</div>
			<hr style="margin-right: 20px; margin-left: 20px;" class=""/>
			<h3>Stats</h3>
			<br/>
			<div class="grid-x field">
				<div class="small-12 auto cell text-center grey_bdr">
					<h6>ACTIVE<br/> PATIENTS</h6>
					<h2 class="yes"><?php echo $active_ct; ?></h2>
					<p class="smaller">TOTAL</p>
				</div>
				<div class="small-12 auto cell text-center grey_bdr">
					<h6>INACTIVE<br/> PATIENTS</h6>
					<h2 class="no"><?php echo $inactive_ct; ?></h2>
					<p class="smaller">TOTAL</p>
				</div>
				<div class="small-12 auto cell text-center">
					<h6>UNRESOLVED<br/>ALERTS</h6>
					<h2 class="no"><?php echo $alert_ct; ?></h2>
					<p class="smaller">TOTAL</p>
				</div>
			</div>
			<p>&nbsp;</p>
			<div class="grid-x">
				<div class="hide-for-small-only medium-2">&nbsp;</div>
				<div class="small-12 medium-8"><a href="patients.php?m=stats" class="button large expanded active">View stats</a></div>
				<div class="hide-for-small-only medium-2">&nbsp;</div>
			</div>
            </div>

		<!-- END MAIN SECTION -->
		<!-- STATS SECTION -->
		<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right  <?php echo $stats_hide; ?>">
			<div class="back clickable-row btn-back" data-href="patients.php?m=main">
				<a href="patients.php?m=main">
                    <span><i class="icon eido-icon-chevron-left"></i>
                    Back</span>
                </a>
			</div>
			<h3>Stats<br/><span class="small"></span></h3>
			<p>User statistics for Verify</p>
			<div class="grid-x text-center">
				<div class="small-12 medium-12 large-12 cell">
					<table width="100%" border="0" class="su-table stack">
						<tr>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=2&page=1" width="60%">
								<a href="patients.php?m=stats&filter=1&status=2&page=1" class="no-u">
									<h4 class="">ACTIVE PATIENTS<a href="patients.php?m=stats"></h4></a>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=2&page=1" width="20%">
								<?php echo  "<strong>".get_stat_counts('active')."</strong><br />TOTAL"; ?>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=2&page=1" width="20%">
								<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle"/>
							</td>
						</tr>
						<tr>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=3&page=1" width="60%">
								<a href="patients.php?m=stats&filter=1&status=3&page=1" class="no-u">
									<h4 class="">INACTIVE PATIENTS<a href="patients.php?m=stats&filter=1&status=3&page=1"></h4></a>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=3&page=1" width="20%">
								<?php echo  "<strong>".get_stat_counts('inactive')."</strong><br />TOTAL"; ?>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=3&page=1" width="20%">
								<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle"/>
							</td>
						</tr>
						<tr>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=4&page=1" width="60%">
								<a href="patients.php?m=stats&filter=1&status=4&page=1" class="no-u">
									<h4 class="">TOTAL PATIENTS<a href="patients.php?m=stats"></h4></a>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=4&page=1" width="20%">
								<?php echo  "<strong>".get_stat_counts('total')."</strong><br />TOTAL"; ?>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=4&page=1" width="20%">
								<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle"/>
							</td>
						</tr>
						<tr>
							<td class="su-data" colspan="3">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=5&page=1" width="60%">
								<a href="patients.php?m=stats&filter=1&status=5&page=1" class="no-u"><h4 class="">COMPLETED SURVEYS<a href="patients.php?m=stats&filter=1&status=5&page=1"></h4></a>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=5&page=1" width="20%">
								<?php echo "<strong>".get_stat_counts('surveycomplete')."</strong><br />TODAY"; ?>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=5&page=1" width="20%">
								<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle"/>
							</td>
						</tr>
						<tr>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=6&page=1" width="60%">
								<a href="patients.php?m=stats&filter=1&status=6&page=1" class="no-u"><h4 class="">INCOMPLETE SURVEYS<a href="patients.php?m=stats&filter=1&status=6&page=1"></h4></a>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=6&page=1" width="20%">
								<?php echo "<strong>".get_stat_counts('surveyincomplete')."</strong><br />TOTAL"; ?>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=6&page=1" width="20%">
								<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle"/>
							</td>
						</tr>
						<tr>
							<td class="su-data" colspan="3">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=1&page=1" width="60%">
								<a href="patients.php?m=stats&filter=1&status=1&page=1" class="no-u"><h4 class="">UNRESOLVED ALERTS<a href="patients.php?m=stats"></h4></a>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=1&page=1" width="20%">
								<?php echo  "<strong>".get_stat_counts('alert')."</strong><br />TOTAL"; ?>
							</td>
							<td class="su-data clickable-row" data-href="patients.php?m=stats&filter=1&status=1&page=1" width="20%">
								<img src="../img/icons/greater.png" alt="greater than icon" class="float-right align-middle"/>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!-- STATS END -->
		<!-- ADD SECTION -->
		<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right  <?php echo $add_hide; ?>">
			<div class="back clickable-row btn-back" data-href="patients.php?m=main">
				<a href="patients.php?m=main">
                <span><i class="icon eido-icon-chevron-left"></i>
                Back</span>
                </a>
			</div>
			<h3>Add Patient<span class="small sub-text">Start a Verify session with a new patient</span></h3>
			<form action="patients_a.php?m=add" method="post" class="" style="margin-left: 20px; margin-right: 20px; padding-left: 0;padding-right: 0;">
				<div class="grid-container">
					<div class="grid-x grid-padding-x">
						<div class="small-12 cell">
							<?php if($_SESSION['add_fname_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your First Name</strong> - this is required</div>"; else if($_SESSION['add_fname_format_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your first name</strong> - no special characters are allowed</div>"; ?>
							<label>First Name
								<input type="text" name="fname" placeholder="" value="<?php echo $_SESSION['add_fname']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_lname_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your Surname</strong> - this is required</div>"; else if($_SESSION['add_lname_format_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your last name</strong> - no special characters are allowed</div>"; ?>
							<label>Surname
								<input type="text" name="lname" placeholder="" value="<?php echo $_SESSION['add_lname']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_nhsnumber_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your NHS Numbr</strong> - this is required</div>"; else if($_SESSION['add_nhsnumber_format_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your NHS number</strong> - no letters or special characters are allowed</div>"; else if($_SESSION['add_nhsnumber_length_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your NHS number</strong> - it should be nine (9) digits</div>"; ?>
							<label>NHS Number
								<input type="text" name="nhsnumber" placeholder="" value="<?php echo $_SESSION['add_nhsnumber']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_hospitalnumber_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your Hospital Number</strong> - this is required</div>"; else if($_SESSION['add_hospitalnumber_format_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your Hospital Number</strong> - no special characters are allowed</div>"; ?>
							<label>Hospital Number
								<input type="text" name="hospitalnumber" placeholder="" value="<?php echo $_SESSION['add_hospitalnumber']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_gender_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your Gender</strong> - this is required</div>"; ?>
							<label>Gender
								<select id="gender" name="gender">
									<option value=""></option>
									<option value="Male" <?php if($_SESSION['add_gender'] == "Male")
										echo "selected"; ?>>Male
									</option>
									<option value="Female" <?php if($_SESSION['add_gender'] == "Female")
										echo "selected"; ?>>Female
									</option>
									<option value="Unspecified" <?php if($_SESSION['add_gender'] == "Unspecified")
										echo "selected"; ?>>Unspecified
									</option>
								</select>
							</label>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_dob_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your Date of Birth</strong> - this is required</div>"; else if($_SESSION['add_dob_format_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your Date of Birth</strong> - special characters are not allowed</div>"; else if($_SESSION['add_dob_invalid_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your Date of Birth</strong> - you enterd an invalid date</div>"; ?>
							<label>Date of Birth
								<input type="text" name="dob" placeholder="DD/MM/YYYY" value="<?php echo $_SESSION['add_dob']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_postalcode_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter your Post Code</strong> - this is required</div>"; else if($_SESSION['add_postalcode_format_error'])
								echo "<div class='error_message fi-alert'><strong>Please correct your Post Code</strong> - no special charaters are allowed</div>"; ?>
							<label>Postcode
								<input type="text" name="postalcode" placeholder="" value="<?php echo $_SESSION['add_postalcode']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<hr style="margin-left:0;margin-right: 0;" class=""/>
						</div>
						<div class="small-12 cell" style="margin-top: -2px">
							<?php if($_SESSION['add_no_contact_error'])
								echo "<div class='error_message fi-alert'><strong>Please enter an Email Address OR Mobile Number</strong> - one is required</div>"; ?>
							<span class="sub-text">Please enter at least one contact method for the patient</span>
						</div>
						<div class="small-12 cell">
							<?php if($_SESSION['add_bad_email_error'])
								echo "<div class='error_message fi-alert'><strong>This is not a valid email address</strong> - please check</div>"; ?>
							<label>Email Address
								<input type="text" name="email" value="<?php echo $_SESSION['add_email']; ?>">
							</label>
						</div>
						<div class="small-12 cell">
							<label>Mobile Number
								<input type="text" name="mobilenumber" placeholder="" value="<?php echo $_SESSION['add_mobilenumber']; ?>">
							</label>
						</div>
					</div>
				</div>
				<div class="grid-x">
					<div class="hide-for-small-only medium-3 large-3 cell"></div>
					<div class="small-12 medium-6 large-6 cell text-center">
						<p>&nbsp;</p>
						<button type="submit" name="add_patient" value="add patient" class="button large expanded btn-bold"/>
						Add patient</button>
					</div>
					<div class="hide-for-small-only medium-3 large-3 cell"></div>
			</form>
		</div>
	</div>
	<!-- END ADD SECTION -->
	<!-- OVERVIEW SECTION -->
	<?php if($mode == 'overview') {
		$sql_o = "SELECT *
                            FROM $TBLPTEPISODES
                            WHERE id = '$pe_id'";
		$GetQuery_o = dbi_query($sql_o);
		$qryResult_o = $GetQuery_o->fetch_assoc();
		$id = $qryResult_o['id'];
		$c_surname = $qryResult_o['c_surname'];
		$c_surname_uc = strtoupper($qryResult_o['c_surname']);
		$c_firstName = $qryResult_o['c_firstName'];
		$c_nhsNumber = $qryResult_o['c_nhsNumber'];
		$c_referenceNumberHospitalId = $qryResult_o['c_referenceNumberHospitalId'];
		$c_dateOfBirth = $qryResult_o['c_dateOfBirth'];
		$c_gender = $qryResult_o['c_gender'];
		$c_postalCode = $qryResult_o['c_postalCode'];
		$c_emailAddress = $qryResult_o['c_emailAddress'];
		$c_mobileNumber = $qryResult_o['c_mobileNumber'];
		$c_procedureId = $qryResult_o['c_procedureId'];
		$c_procedure = $qryResult_o['c_procedure'];  // long id key
		$c_description = $qryResult_o['c_description'];
		$c_surgeonName = $qryResult_o['c_surgeonName'];
                $c_gmcNumber = $qryResult_o['c_gmcNumber'];
		$c_status = $qryResult_o['c_status'];
		$c_plannedProcedureDate = $qryResult_o['c_plannedProcedureDate'];
		if($c_procedureId <> "") {
			$procedure = $c_procedureId . " - " . $c_description;
			$ov_proc_link = "patients.php?m=procdetail&rtn=ov&id=$pe_id";
		} else {
			$procedure = ""; // none selected yet
			$ov_proc_link = "patients.php?m=procselect&rtn=ov&id=$pe_id";
		}
		$pt_status = get_pt_status($pe_id);

		if ($pt_status == "Inactive" || $pt_status == "Pending")
			$pt_status_class = "ps_grey"; 
                  else if ($pt_status == "Active")
			$pt_status_class = "ps_green";
                  else //  if (Spt_status == "Alert") 
			$pt_status_class = "ps_red"; 

		$c_tags = $qryResult_o['c_tags'];
		//$arr_tags = array();
		if($c_tags <> "")
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
		//if(isset($_SESSION['tl_view_all']) && !$_SESSION['tl_view_all'])
			//$tl_view_all_str = "AND tl.c_sessionNumber='$current_session'";
		$sql_tl = "SELECT *
                           FROM $TBLTIMELINES tl
                           WHERE tl.c_patientEpisodeId='$pe_id'
                           AND (tl.c_timelineEntryType='Event' 
                                OR (tl.c_timelineEntryType='Alert' AND tl.c_timelineAlertStatus='Open'))
                           ORDER BY dateCreated";
		$GetQuery_tl = dbi_query($sql_tl);
		// logMsg("Timeline NumRows: " . $GetQuery_tl->num_rows, $logfile);
		$arr_tl = array();
		$tl = 0;
		while($qryResult_tl = $GetQuery_tl->fetch_assoc()) {
			$device_type = $qryResult_tl['c_deviceType'];
			if(strpos(strtolower($device_type), "datasift"))
				 logMsg("TL BAD DEVICE: $device_type", $logfile); 
                        else if(strtolower($qryResult_tl['c_timelineEntryDetail']) == "survey complete")
				;  //  skip
			else if ($timeline_mode=='all' || 
                                 $qryResult_tl['c_timelineEntryDetail']=="Report Generated" || 
                                 $qryResult_tl['c_timelineEntryDetail']=="Request review" || 
                                 $qryResult_tl['c_timelineEntryDetail']=="PROCEDURE COMPLETE" || 
                                 strpos($qryResult_tl['c_timelineEntryDetail'],"hard fail") || 
                                 $qryResult_tl['c_timelineEntryType']=="Alert") {
			      $arr_tl[$tl] = $qryResult_tl;
			      $tl++;
			}
		}
                // get scheduled surveys and procedure if not already comeplete
		$arr_tl = get_future_tl($pe_id, $arr_tl);
		if(count($arr_tl) == 0) {
			$timeline = false;
		} else {
			$timeline = true;
		}
	}
	?>
	<div class="small-12 medium-6 large-6 cell content-right <?php echo $overview_hide; ?>">
		<!-- <div class="back clickable-row" data-href="patients.php?m=main"><a href="patients.php?m=main"><img src="../img/icons/back.png" alt="less than icon" class="float-left" /></a>Back</div> -->
		<h3>Patient Overview<br/><span class="small sub-text">See a patient's progress through Verify</span></h3>
		<h5 class="<?php echo $pt_status_class; ?>"><?php echo " $c_surname_uc , $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
		<div class="patient-data">
			<a href="patients.php?m=detail&id=<?php echo $pe_id; ?>" class="link-full-block standard-padding standard-border-bottom">
				<span class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
				<div class="grid-x">
					<div class="small-4">
						<p><strong>HospNo:</strong></p>
						<p><strong>NHS No:</strong></p>
						<p><strong>DOB:</strong></p>
					</div>
					<div class="small-8">
						<p><?php echo $c_referenceNumberHospitalId; ?></p>
						<p><?php echo $c_nhsNumber; ?></p>
						<p><?php echo $c_dateOfBirth; ?></p>
					</div>
				</div>
			</a>
		</div>
		<?php if($c_procedureId == ""): ?>
			<div class="patient-data">
				<div>
					<span class="text-center display-block standard-border-bottom"><a href="<?php echo $ov_proc_link; ?>" class="button">Add Procedure</a></span>
				</div>

			</div>

		<?php else: ?>

			<div class="patient-data">
				<a href="patients.php?m=detail&id=<?php echo $pe_id; ?>" class="link-full-block standard-padding standard-border-bottom">
					<span class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
					<!-- have to have this one in their row for wrapping to work correctly-->
					<div class="grid-x">
						<div class="small-4">
							<p><strong>Procedure:</strong></p>
						</div>
						<div class="small-8">
							<p><?php echo $procedure; ?></p>
						</div>
					</div>
					<div class="grid-x">
						<div class="small-4">
							<p><strong>Procedure Date:</strong></p>
							<p><strong>Surgeon:</strong></p>
						</div>
						<div class="small-8">
							<p><?php if($c_plannedProcedureDate == "")
									echo "<span style='color:red;'>No date selected.</span>"; else echo $c_plannedProcedureDate; ?></p>
							<p><?php if($c_surgeonName == "")
									echo "<span style='color:red;'>No surgeon selected.</span>"; else echo $c_surgeonName; ?></p>
						</div>
					</div>
				</a>
			</div>
		<?php endif; ?>

		<div class="patient-data standard-padding standard-border-bottom">
			<div class="grid-x">
				<div class="small-4">
					<p><strong>Status:</strong></p>
				</div>
				<div class="small-8">
					<?php echo $c_status; ?>
				</div>
			</div>
		</div>

		<?php if(count($arr_tags) > 0): ?>
		<div class="patient-data standard-padding standard-border-bottom">
			<div class="grid-x">
				<div class="small-4">
					<p><strong>Tags:</strong></p>
				</div>
				<div class="small-8">
					<?php for($i = 0; $i < count($arr_tags); $i++): ?>
						<span class="label tag"><?php echo $arr_tags[$i]; ?>&nbsp;&nbsp;&nbsp;</span>
					<?php endfor; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="patient-data">
			<div class="standard-padding">
				<?php
				if ($timeline) echo "<span class='float-right'>SHOW: <a href='patients.php?m=overview&id=$pe_id&tlm=key' class='link-standard-color ".($timeline_mode!='all' ? "link-active" : '')."' />Key events</a> | <a href='patients.php?m=overview&id=$pe_id&tlm=all' class='link-standard-color ".($timeline_mode=='all' ? "link-active" : '')."' />All events</a></span>";
				else echo " No Survey Activity"; ?>
				<h5 class="timeline-show-events standard-weight">Patient Timeline:</h5>
			</div>
		</div>


		<div class="<?php if($timeline)
                        echo "timeline timeline-right timeline-show-events"; ?>">
			<table class="su-table">
				<?php for($tl = 0; $tl < count($arr_tl); $tl++) {
					$tl_desc = trim($arr_tl[$tl]['c_timelineEntryDetail']);
					$tl_date = $arr_tl[$tl]['dateCreated'];
					//$tl_date = substr($tl_date,0,strpos($tl_date," "));
					//$tl_date = format_uk_date($tl_date);
					$tl_date = format_tl_date($tl_date);
					$tl_type = $arr_tl[$tl]['c_timelineEntryType']; // Alert or Event
					$tl_id = $arr_tl[$tl]['id'];
					$tl_imgfile = $arr_tl_data[$tl_desc];
					$tl_session = $arr_tl[$tl]['c_sessionNumber'];
                                        $tl_data = $arr_tl[$tl]['c_data'];
                                        $varname = "c_session".$tl_session."Name";
                                        $arr_proc_info=get_proc_info($c_procedure);
                                        $tl_session_name=$arr_proc_info[$tl_session][$varname];
//echo "$tl_session - $varname - $tl_session_name<br />";
//echo "<PRE>";
//print_r($arr_proc_info);
//echo "</PRE>";
//exit();
					if($tl_type == "Alert" || strpos($tl_desc, "validation error")) {
						$tl_class = "timeline-icon-cs";
						$tl_iconfile = "exclaimation_red.png";  // "caution.png";
					} else if($tl_type == "Event") {
						$tl_class = "timeline-icon-green";
						$tl_iconfile = "white_circle.png";
					} else if($tl_type == "Future Event") {
						$tl_class = "timeline-icon-cs";
						$tl_iconfile = "white_circle.png";   //arrow.png";
					}

					$tl_link = "";
					$tl_btn_str = "";
					unset($tl_link2);
					if($tl_desc == "Procedure Completed") {
						//$tl_class = "timeline-icon-cs";
						//$tl_iconfile = "white_circle.png";
						$tl_desc = "PROCEDURE ";
						$tl_link = "patients.php?m=proccomplete&id=$pe_id";
						$tl_btn_str = "<a href='$tl_link'><button class='button active'>Procedure Completed</button></a>";
						// get procedure date into mysql format then pass to UK format func
						list($d, $m, $y) = explode("/", $c_plannedProcedureDate);
						$c_plannedProcedureDate2 = "$y-$m-$d";
						$tl_date = format_tl_date($c_plannedProcedureDate2);
					} else if($tl_desc == "PROCEDURE COMPLETE") {
						//$tl_class = "timeline-icon-cs";
						//$tl_iconfile = "arrow.png";
						$tl_link = "none"; //  "patients.php?m=procdetail&id=$pe_id";
                        $tl_iconfile = "procedure.png"; 
						// get procedure date into mysql format then pass to UK format func
						list($d, $m, $y) = explode("/", $c_plannedProcedureDate);
						$c_plannedProcedureDate2 = "$y-$m-$d";
						$tl_date = format_tl_date($c_plannedProcedureDate2);
						// logMsg("$c_plannedProcedureDate - $c_plannedProcedureDate2 - $tl_date", $logfile);
					} else if(strpos($tl_desc, "Scheduled Survey")) {
                                                $tl_link="none";
					} else if(in_array($tl_desc, $arr_tl_sess)) {
						$tl_desc = str_replace("Session", "Survey", $tl_desc); 
                                                $tl_desc = $tl_desc."<br />".$tl_session_name;
                                                $tl_link="none";
                                        }  else if($tl_desc == "Report Generated") {
                                                // 4/9/18 WEL - moved the PDF link from episodes to timeline table
						//$fieldname = "c_survey" . $tl_session . "PdfLink";
						//$sql_link = "SELECT $fieldname 
                                                             //FROM $TBLPTEPISODES 
                                                             //WHERE id='$pe_id'";
						//$GetQuery_link = dbi_query($sql_link);
						//$qryResult_link = $GetQuery_link->fetch_assoc();
                                                //  $qryResult_link[$fieldname];
						$tl_link = $tl_data;
                                                $tl_link2 = "<a href='$tl_data' />$tl_session_name</a>";  
                                                $tl_desc=$tl_desc."<br />".$tl_link2;
						// $tl_link2 = substr($tl_link, 11);
						// $loc = strpos($tl_link2, ">");
						// $tl_link2 = substr($tl_link2, 0, $loc - 1);
						// logMsg("Report Link: $sql_link - $tl_link", $logfile);
						// logMsg("Link2: $tl_link2", $logfile);
					} else if($tl_desc == "Request review") {
                                                $tl_desc="Sign In Review"; 
						$tl_link = "patients.php?m=review&id=$pe_id&tid=$tl_id";
						$tl_btn_str = "<a href='$tl_link'><button class='button active align-right'>Check Patient Details</button></a>";
					// } else if(strpos($tl_desc, "validation error")) {
					//      $tl_link = "patients_a.php?m=clearalert&id=$pe_id&tid=$tl_id";
                                        //      $tl_btn_str = "&nbsp;&nbsp;&nbsp;<a href='$tl_link'><button class='button active align-right'>Check Patient Details</button></a>";
					} else if($tl_desc == "Survey Email Sent") {
                                                $tl_desc="Invite Email Sent"."<br />".$tl_session_name; 
						$tl_link = "none";
					} else if($tl_desc == "SMS Email Sent") {
                                                $tl_desc="Invite SMS Sent"."<br />".$tl_session_name; 
						$tl_link = "none";
					} else if($tl_desc == "Survey Email Clicked") {
                                                $tl_desc="URL Invite Clicked"."<br />".$tl_session_name; 
						$tl_link = "none";
					} else if($tl_desc == "Email Bounce") {
                                                $tl_desc="Email Bounced"."<br />".$tl_session_name; 
						$tl_link = "patients_a.php?m=clearalert&id=$pe_id&tid=$tl_id";
						$tl_btn_str = "<a href='$tl_link'><button class='button active align-right'>Update Patient Details</button></a>";
					} else if($tl_desc == "SMS Bounce") {
                                                $tl_desc="SMS Rejected"; 
						$tl_link = "patients_a.php?m=clearalert&id=$pe_id&tid=$tl_id";
						$tl_btn_str = "<a href='$tl_link'><button class='button active align-right'>Check Patient Details</button></a>";
					} else {
                                                $tl_desc = $tl_desc."<br />".$tl_session_name;
                                                $tl_link = "none";
                                        }
					// logMsg("Desc: $tl_desc Date: $tl_date Type: $tl_type Image: $tl_imgfile Icon: $tl_iconfile Class: $tl_class", $logfile);
					?>
			<tr <?php echo $tl_link != 'none' ? 'class="clickable-row" data-href="'.$tl_link.'"' : ''; ?>>
				<td width="20%">
					<span class="date"><?php echo $tl_date; ?></span>
				</td>
				<td width="12%" class="timeline-item">
					<span class="timeline-wrapper">
					     <span class="<?php echo $tl_class; ?>">
					         <img src="../img/icons/<?php echo $tl_iconfile; ?>" width="20">
                                                           </span>
					</span>
				</td>
				<td <?php if ($tl_btn_str<>"") echo "colspan='2'"; else echo "width='58%'"; ?> class="timeline-item tlbdr <?php if ($tl_link<>"none") echo "clickable-row"; ?>" <?php if ($tl_link<>"none") echo "data-href='$tl_link'"; ?>>
                          <span class="timeline-content">
                               <?php echo "<p><strong>".$tl_desc."</strong></p>";
                                     echo $tl_btn_str; 
	                                 // if($tl_desc == "Report Generated")
		                         // echo "<br />$tl_link"; else echo 
                                ?>
                          </span>
			</td>
			<?php if($tl_btn_str == "") { ?>
				<td width="10%" class="tlbdr">
					<?php if ($tl_link=="none") echo "&nbsp;"; else { ?>
					<a href="<?php echo $tl_link; ?>"><img src="../img/icons/greater.png" alt="greater than icon" class="align-right"/></a>
                                          <?php } ?>
				</td>
			<?php } ?>
			</tr>
			<?php } ?>
		</table>
		</div>
		<table class="su-table">
			<?php if($timeline && false) {
                               // TURNED OFF FOR NOW
				if($_SESSION['tl_view_all'])
					$view_all_str = "Current Survey Activity"; else $view_all_str = "View All"; ?>
				<tr>
					<td width="20%">&nbsp;</td>
					<td colspan="3">
						<center><a href="patients_a.php?m=tl_view_all&id=<?php echo $pe_id; ?>">
								<button class="large button inactive"><?php echo $view_all_str; ?></button>
							</a>
				</tr>
			<?php } ?>
		</table><!-- end timeline -->
		<?php ; // ***********************  <<<<<<  these two CLOSING DEVS ?>
    </div>
	<!-- END OVERVIEW SECTION -->
	<!-- DETAIL SECTION -->
	<?php if($mode == 'detail') {
		$sql_d = "SELECT *
                            FROM $TBLPTEPISODES
                            WHERE id = '$pe_id'";
		$GetQuery_d = dbi_query($sql_d);
		$qryResult_d = $GetQuery_d->fetch_assoc();
		$id = $qryResult_d['id'];
		$c_surname = $qryResult_d['c_surname'];
        $c_surname_uc = strtoupper($qryResult_d['c_surname']);
		$c_firstName = $qryResult_d['c_firstName'];
		$c_address = $qryResult_d['c_address'];
		$c_address2 = $qryResult_d['c_address2'];
		$c_city = $qryResult_d['c_city'];
		$c_county = $qryResult_d['c_county'];
		$c_postalCode = $qryResult_d['c_postalCode'];
		$c_nhsNumber = $qryResult_d['c_nhsNumber'];
		$c_referenceNumberHospitalId = $qryResult_d['c_referenceNumberHospitalId'];
		$c_dateOfBirth = $qryResult_d['c_dateOfBirth'];
		$c_gender = $qryResult_d['c_gender'];
		$c_emailAddress = $qryResult_d['c_emailAddress'];
		$c_mobileNumber = $qryResult_d['c_mobileNumber'];
		$c_procedureId = $qryResult_d['c_procedureId'];
		$c_description = $qryResult_d['c_description'];
		$procedure = $c_procedureId . " - " . $c_description;
		$pt_status = get_pt_status($pe_id);
		if($pt_status == "Inactive" || $pt_status == "Pending")
			$pt_status_class = "ps_grey"; 
                else if(Spt_status == "Alert")
			$pt_status_class = "ps_red"; 
                else if($pt_status == "Active")
			$pt_status_class = "ps_green";
	} else if($mode == "addreview") {
		$c_surname = $_SESSION['add_lname'];
        $c_surname_uc = strtoupper($_SESSION['add_lname']);
		$c_firstName = $_SESSION['add_fname'];
		$c_address = $_SESSION['add_address'];
		$c_address2 = $_SESSION['add_address2'];
		$c_city = $_SESSION['add_city'];
		$c_county = $_SESSION['add_county'];
		$c_postalCode = $_SESSION['add_postalcode'];
		$c_nhsNumber = $_SESSION['add_nhsnumber'];
		$c_referenceNumberHospitalId = $_SESSION['add_hospitalnumber'];
		$c_dateOfBirth = $_SESSION['add_dob'];
		$c_gender = $_SESSION['add_gender'];
		$c_emailAddress = $_SESSION['add_email'];
		$c_mobileNumber = $_SESSION['add_mobilenumber'];
		$pt_status_class = "ps_grey";
	} else if($mode == "editreview") {
		$c_surname = $_SESSION['edit_lname'];
        $c_surname_uc = strtoupper($_SESSION['edit_lname']);
		$c_firstName = $_SESSION['edit_fname'];
		$c_address = $_SESSION['edit_address'];
		$c_address2 = $_SESSION['edit_address2'];
		$c_city = $_SESSION['edit_city'];
		$c_county = $_SESSION['edit_county'];
		$c_postalCode = $_SESSION['edit_postalcode'];
		$c_nhsNumber = $_SESSION['edit_nhsnumber'];
		$c_referenceNumberHospitalId = $_SESSION['edit_hospitalnumber'];
		$c_dateOfBirth = $_SESSION['edit_dob'];
		$c_gender = $_SESSION['edit_gender'];
		$c_emailAddress = $_SESSION['edit_email'];
		$c_mobileNumber = $_SESSION['edit_mobilenumber'];
		$pt_status = get_pt_status($pe_id);
		if($pt_status == "Inactive")
			$pt_status_class = "ps_grey"; else if(Spt_status == "Alert")
			$pt_status_class = "ps_red"; else if($pt_status == "Active")
			$pt_status_class = "ps_green";
	}
	// logMsg("c_surname: $c_surname c_address: $c_address Session_surname ".$_SESSION['add_surname']." session_address: ".$_SESSION['add_address'], $logfile);
	?>
	<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right patientcontent <?php echo $detail_hide; ?>">
		<?php if($_SESSION['workflow'] == "ADD")
			$rtn_back = "addaddress"; else $rtn_back = "overview&id=$pe_id"; ?>
		<div class="back clickable-row btn-back" data-href="patients.php?m=<?php echo $rtn_back; ?>">
			<a href="patients.php?m=<?php echo $rtn_back; ?>">
                <span><i class="icon eido-icon-chevron-left"></i>
                Back</span>
                </a>
		</div>
                <?php if ($mode=="detail") { ?>
		   <h3>Patient Details
			<span class="small sub-text">View or edit the patient</span>
		   </h3>
                <?php } else { ?>
		   <h3>Confirm
			<span class="small sub-text">Check and confirm the information entered</span>
		   </h3>
                <?php } ?>
		<h5 style="margin-left: 20px; margin-right: 20px;" class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname_uc, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
		<form action="" method="post" class="" style="margin-right: 20px;margin-left: 20px;padding-left: 0;">
			<div class="grid-container">
				<div class="grid-x grid-padding-x">
					<div class="small-12 medium-12 large-12 cell">
						<label>First Name</label>
						<h5><?php echo $c_firstName; ?></h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Surname</label>
						<h5><?php echo $c_surname_uc; ?></h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<hr style="margin-left: 0px;" class="full"/>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>NHS Number</label>
						<h5><?php echo $c_nhsNumber; ?></h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Hospital Number</label>
						<h5><?php echo $c_referenceNumberHospitalId; ?></h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<hr style="margin-left: 0px;" class="full"/>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Date of Birth</label>
						<h5><?php echo $c_dateOfBirth; ?></h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Address</label>
						<h5><?php if($c_address <> "")
								echo $c_address . "<br />"; ?>
							<?php if($c_city <> "")
								echo $c_city . "<br />"; ?>
							<?php if($c_county <> "")
								echo $c_county . "<br />"; ?>
							<?php if($c_postalCode <> "")
								echo $c_postalCode . "<br />"; ?>
						</h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Gender</label>
						<h5><?php echo $c_gender; ?></h5>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<hr style="margin-left: 0px;" class="full"/>
					</div>
                    <?php if ($c_emailAddress<>"") { ?>
					<div class="small-12 medium-12 large-12 cell">
						<label>Email Address</label>
						<h5><a href="mailto:<?php echo $c_emailAddress; ?>"><?php echo $c_emailAddress; ?></a></h5>
					</div>
                    <?php } ?>
                    <?php if ($c_mobileNumber<>"") { ?>
					<div class="small-12 medium-12 large-12 cell">
						<label>Mobile Number</label>
						<h5><?php echo $c_mobileNumber; ?></h5>
					</div>
                    <?php } ?>
					<div class="small-12 medium-12 large-12 cell">
						<hr style="margin-left: 0px;" class="full"/>
					</div>
				</div>
			</div>
		</form>
		<div class="grid-x">
			<div class="hide-for-small-only medium-3 large-3 cell"></div>
			<div class="small-12 medium-6 large-6 cell text-center">
				<p>&nbsp;</p>
				<?php if($mode == "detail") {
					$goto = "patients.php";
					$m = "edit";
					$btn_class = "inactive";
					$btn_text = "Edit Patient";
				} else if($mode == "addreview") {
					$goto = "patients_a.php";
					$m = "addconfirm";
					$btn_class = "active";
					$btn_text = "Confirm";
				} else if($mode == "editreview") {
					$goto = "patients_a.php";
					$m = "editconfirm";
					$btn_class = "active";
					$btn_text = "Confirm";
				}
				?>
				<a href="<?php echo $goto; ?>?m=<?php echo $m; ?>&id=<?php echo $pe_id; ?>">
					<button type="button" name="" value="edit patient" class="button large expanded <?php echo $btn_class; ?>"/>
					<?php echo $btn_text; ?></button></a>
			</div>
			<div class="hide-for-small-only medium-3 large-3 cell"></div>
		</div>
	</div>
	<!-- END DETAIL SECTION -->
	<!-- EDIT SECTION -->
	<?php if($mode == 'edit') {
		$sql_e = "SELECT *
                            FROM $TBLPTEPISODES
                            WHERE id = '$pe_id'";
		$GetQuery_e = dbi_query($sql_e);
		$qryResult_e = $GetQuery_e->fetch_assoc();
		$id = $qryResult_e['id'];
		$c_surname = $qryResult_e['c_surname'];
        $c_surname_uc = strtoupper($qryResult_e['c_surname']);
		$c_firstName = $qryResult_e['c_firstName'];
		$c_nhsNumber = $qryResult_e['c_nhsNumber'];
		$c_referenceNumberHospitalId = $qryResult_e['c_referenceNumberHospitalId'];
		$c_dateOfBirth = $qryResult_e['c_dateOfBirth'];
		$c_gender = $qryResult_e['c_gender'];
		$c_postalCode = $qryResult_e['c_postalCode'];
		$c_emailAddress = $qryResult_e['c_emailAddress'];
		$c_mobileNumber = $qryResult_e['c_mobileNumber'];
		$c_gender = $qryResult_e['c_gender'];
		$c_address = $qryResult_e['c_address'];
		$c_county = $qryResult_e['c_county'];
		$c_city = $qryResult_e['c_city'];
	}
	?>
	<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $edit_hide; ?>">
		<div class="back clickable-row btn-back" data-href="patients.php?m=detail&id=<?php echo $pe_id; ?>">
			<a href="patients.php?m=detail&id=<?php echo $pe_id; ?>">
                <span><i class="icon eido-icon-chevron-left"></i>
                Back</span>
            </a>
		</div>
		<h3>Edit Patient<br/><span class="small">View or edit the patient</span></h3>
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
								<option <?php if($c_gender == "Male")
									echo "selected"; ?>>Male
								</option>
								<option <?php if($c_gender == "Female")
									echo "selected"; ?>>Female
								</option>
								<option <?php if($c_gender == "Unspecified")
									echo "selected"; ?>>Unspecified
								</option>
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
						<p><?php echo $c_address; ?><br/>
							<?php echo $c_city; ?><br/>
							<?php echo $c_county; ?><br/>
							<?php echo $c_postalCode; ?></p>
					</div>
					<div class="small-12 medium-6 large-6 cell text-center">
						<center><a href="patients.php?m=editaddress&id=<?php echo $pe_id; ?>" class="button large expanded inactive">Edit address</a>
						</center>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						&nbsp;
					</div>
					<div class="small-12 medium-12 large-12 cell" style="margin-top: -2px">
						<span class="sub-text" >Please enter at least one contact method for the patient</span>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Email Address
							<input type="text" name="email" value="<?php echo $c_emailAddress; ?>">
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell" >
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
					<center>
						<button type="submit" name="" class="button large expanded">Save</button>
					</center>
				</div>
				<div class="hide-for-small-only medium-3 large-3 cell"></div>
		</form>
	</div>
</div>
<!-- END EDIT SECTION -->
<!-- EDITADDRESS SECTION -->
<?php if($mode == 'editaddress') {
	$sql_ea = "SELECT *
                            FROM $TBLPTEPISODES
                            WHERE id = '$pe_id'";
	$GetQuery_ea = dbi_query($sql_ea);
	$qryResult_ea = $GetQuery_ea->fetch_assoc();
	$id = $qryResult_ea['id'];
	$c_surname = $qryResult_ea['c_surname'];
    $c_surname_uc = strtoupper($qryResult_ea['c_surname']);
	$c_firstName = $qryResult_ea['c_firstName'];
	$c_postalCode = $qryResult_ea['c_postalCode'];
	$c_address = $qryResult_ea['c_address'];
	$c_address2 = $qryResult_ea['c_address2'];
	$c_county = $qryResult_ea['c_county'];
	$c_city = $qryResult_ea['c_city'];
	$pt_status = get_pt_status($id);
	if($pt_status == "Inactive")
		$pt_status_class = "ps_grey"; 
        else if(Spt_status == "Alert")
		$pt_status_class = "ps_red"; 
        else if($pt_status == "Active")
		$pt_status_class = "ps_green";
} else if($mode == "addaddress") {
	$c_address = $_SESSION['add_address'];
	$c_address2 = $_SESSION['add_address2'];
	$c_city = $_SESSION['add_city'];
	$c_county = $_SESSION['add_county'];
	$c_postalCode = $_SESSION['add_postalcode'];
	// logMsg("c_postalCode=$c_postalCode", $logfile);
}
?>
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $editaddress_hide; ?>">
	<?php if($mode == "editaddress") { ?>
	<div class="back clickable-row btn-back" data-href="patients.php?m=add&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=add&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
	<h3>Edit Address<br/><span class="small">Update the address for the patient.</span></h3>
	<form action="patients_a.php?m=editaddress&id=<?php echo $pe_id; ?>" method="post" class="rs-adj">
		<h5 style="margin-right: 0;margin-left: 0;" class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname_uc, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
		<?php } else if($mode == "addaddress") { ?>

		<div class="back clickable-row btn-back" data-href="patients.php?m=add&id=<?php echo $pe_id; ?>">
			<a href="patients.php?m=add&id=<?php echo $pe_id; ?>">
                <span><i class="icon eido-icon-chevron-left"></i>
                Back</span>
            </a>
		</div>
		<h3>Confirm Address
			<span class="small sub-text">Select the patient's address.</span>
		</h3>
		<form action="patients_a.php?m=addaddress" method="post" class="rs-adj">
			<?php } ?>
			<div class="grid-container">
				<div class="grid-x grid-padding-x">
					<div class="small-12 medium-12 large-12 cell">
						<?php if($_SESSION['add_address_error'])
							echo "<div class='error_message fi-alert'><strong>Please select an address for this post code</strong> - this is required</div>"; ?>
						<label>Postcode
							<input type="text" name="postalcode" id="postalcode_ea" value="<?php echo $c_postalCode; ?>">
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<?php if($_SESSION['add_address_error'])
							echo "<strong>Select an address</strong> - this is required."; ?>
						<label>
							<select id="address" class="select_address" name="found_address" size="10">
								<?php echo get_address_by_postcode($c_postalCode); ?>
							</select>
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Address 1
							<input id="address_1_ea" type="text" name="address" value="<?php echo $c_address; ?>">
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Address 2
							<input id="address_2_ea" type="text" name="address2" value="<?php echo $c_address2; ?>">
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Town
							<input id="city_ea" type="text" name="city" value="<?php echo $c_city; ?>">
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>County
							<input id="country_ea" type="text" name="county" value="<?php echo $c_county; ?>">
						</label>
					</div>
					<div class="small-12 medium-12 large-12 cell">
						<label>Postcode
							<input id="postalcode_2_ea" type="text" name="postalcode2" value="<?php echo $c_postalCode; ?>">
						</label>
					</div>
				</div>
			</div>
			<div class="grid-x">
				<div class="hide-for-small-only medium-3 cell"></div>
				<div class="small-12 medium-6 cell text-center">
					<p>&nbsp;</p>
					<button type="submit" name="editaddress" value="Save" class="button large expanded"/>
					Save</button>
				</div>
				<div class="hide-for-small-only medium-3 cell"></div>
		</form>
</div>
</div>
<!-- END EDITADDRESS SECTION -->
<!-- EDITCONFIRM SECTION -->
<?php if($mode == 'editconfirm')
	$arr_pt_info = get_pt_info($pe_id); ?>
<div class="small-12 medium-6 large-6 cell content-right  <?php echo $editconfirm_hide; ?>">
	<!-- <div class="back"><img src="../img/icons/back.png" alt="less than icon" class="float-left" />Back</div> -->
	<h3>Confirmation</h3>
	<div class="grid-x text-center">
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
		<div class="small-12 medium-6 large-6 cell">
			<h5 class="">You have updated the patient's information</h5>
			<?php if($arr_pt_info['c_status'] <> "PENDING") { ?>
				<div class="grid-x grid-padding-x">
					<div class="small-12 medium-12 large-12 cell text-center">
						<p>Would you like to resend the most recent survey invitation?</p>
					</div>
					<div class="small-12 medium-12 large-12 cell text-center">
						<p>&nbsp;</p>
						<a href="resend_invite.php?id=<?php echo $pe_id; ?>" class="button large expanded">SEND</a>
						<a href="patients.php?m=main" class="button large expanded inactive">NO THANKS</a>
					</div>
				</div>
			<?php } else { ?>
				<div class="grid-x grid-padding-x">
					<div class="small-12 medium-12 large-12 cell text-center">
						<p>&nbsp;</p>
						<a href="patients.php?main=m&id=<?php echo $pe_id; ?>" class="button large expanded">HOME</a>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
	</div>
</div>
<!-- EDITCONFIRM END -->
<!-- PROCPROCEED SECTION -->
<div class="small-12 medium-6 large-6 cell content-right  <?php echo $procproceed_hide; ?>">

	<h3>Add Patient</h3>
	<div class="grid-x text-center">
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
		<div class="small-12 medium-6 large-6 cell">
			<p>
			<center><img src="../img/success-guy.png" width="110"></center>
			</p>
			<h5 class="">Add Procedure?</h5>
			<div class="grid-x grid-padding-x">
				<div class="small-12 medium-12 large-12 cell text-center">
					<p>Would you like to add a procedure to this patient now, or enter details for a new patient?</p>
				</div>
				<div class="small-12 medium-12 large-12 cell text-center">
					<p>&nbsp;</p>
					<a href="patients.php?m=procselect&id=<?php echo $pe_id; ?>" class="button large expanded">Add procedure</a>
					<a href="patients_a.php?m=gotoaddpt" class="button large expanded">New patient</a>
					<a href="patients.php?m=main" class="button large expanded inactive">Home</a>
				</div>
			</div>
		</div>
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
	</div>
</div>
<!-- PROCPROCEED END -->
<!--REVIEW SECTION -->
<?php
if($mode == "review") {
	$sql_rev = "SELECT *
                            FROM $TBLPTEPISODES
                            WHERE id = '$pe_id'";
	$GetQuery_rev = dbi_query($sql_rev);
	$qryResult_rev = $GetQuery_rev->fetch_assoc();
	$id = $qryResult_pd['id'];
	$c_surname = $qryResult_rev['c_surname'];
    $c_surname_uc = strtoupper($qryResult_rev['c_surname']);
	$c_nhsNumber = $qryResult_rev['c_nhsNumber'];
	$c_dateOfBirth = $qryResult_rev['c_dateOfBirth'];
	$c_postalCode = $qryResult_rev['c_postalCode'];
	$c_surnameEntered = $qryResult_rev['c_surnameEntered'];
	$c_nhsNumberEntered = $qryResult_rev['c_nhsNumberEntered'];
	$c_dateOfBirthEntered = $qryResult_rev['c_dateOfBirthEntered'];
	$c_postalCodeEntered = $qryResult_rev['c_postalCodeEntered'];
	$c_surname = $qryResult_rev['c_surname'];
	$c_firstName = $qryResult_rev['c_firstName'];
    $c_surname_uc = strtoupper($qryResult_rev['c_surname']);
}
?>

<div class="small-12 medium-6 large-6 cell content-right <?php echo $review_hide; ?>">
	<h3>Review Patient Access<br><span class="small">Update patient data after a failed login.</span></h3>
	<h5 class="ps_red"><?php echo $c_surname_uc . ", " . $c_firstName; ?><span class="small">Sign In Review</span></h5>
	<div class="standard-padding">
		<p>The patient has attempted to sign in, but cannot match their data with the system.<br/>
			The most likely cause is a data entry error in Verify.</p>
		<p>Check the data the patient entered vs the data in your PAS system to see if there is a mis-match. Use this screen to update any errors.</p>
	</div>

	<form action="patients_a.php?m=review&id=<?php echo $pe_id; ?>&tid=<?php echo $timeline_id; ?>" method="post">
		<?php if(strtoupper($c_surname) <> strtoupper($c_surnameEntered)) : ?>
			<h5>Surname</h5>
			<label class="eido-block-radio active">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="surname_radio" value="P" id="" checked>
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Patient Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_surnameEntered; ?></span>
					</div>
				</div>
			</label>
			<label class="eido-block-radio">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="surname_radio" value="V" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Verify Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_surname; ?></span>
					</div>
				</div>
			</label>
			<p class="text-right small" style="margin-bottom:5px">Select which line is correct, or add a new data point below.</p>
			<label class="eido-block-radio dim">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="surname_radio" value="N" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">New</span>
					</div>
					<div class="small-8">
						<span class="separator"><input type="text" class="new_input" name="new_surname"></span>
					</div>
				</div>
			</label>

			<hr/>
		<?php endif; ?>
		<?php if(strtoupper($c_dateOfBirth) <> strtoupper($c_dateOfBirthEntered)): ?>
			<h5>Date of Birth</h5>
			<label class="eido-block-radio active">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="dob_radio" value="P" id="" checked>
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Patient Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_dateOfBirthEntered; ?></span>
					</div>
				</div>
			</label>
			<label class="eido-block-radio">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="dob_radio" value="V" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Verify Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_dateOfBirth; ?></span>
					</div>
				</div>
			</label>
			<p class="text-right small" style="margin-bottom:5px">Select which line is correct, or add a new data point below.</p>
			<label class="eido-block-radio dim">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="dob_radio" value="N" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">New</span>
					</div>
					<div class="small-8">
						<span class="separator"><input type="text" class="new_input" name="new_dob"></span>
					</div>
				</div>
			</label>

			<hr/>

		<?php endif; ?>
		<?php if(strtoupper($c_postalCode) != strtoupper($c_postalCodeEntered)): ?>
			<h5>Postcode</h5>
			<label class="eido-block-radio active">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="postalcode_radio" value="P" id="" checked>
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Patient Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_postalCodeEntered; ?></span>
					</div>
				</div>
			</label>
			<label class="eido-block-radio">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="postalcode_radio" value="V" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Verify Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_postalCode; ?></span>
					</div>
				</div>
			</label>
			<p class="text-right small" style="margin-bottom:5px">Select which line is correct, or add a new data point below.</p>
			<label class="eido-block-radio dim">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="postalcode_radio" value="N" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">New</span>
					</div>
					<div class="small-8">
						<span class="separator"><input type="text" class="new_input" name="new_postalcode"></span>
					</div>
				</div>
			</label>
			<hr/>

		<?php endif; ?>
		<?php if(strtoupper($c_nhsNumber) != strtoupper($c_nhsNumberEntered)): ?>
			<h5>NHS Number</h5>
			<label class="eido-block-radio active">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="nhsnumber_radio" value="P" id="" checked>
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Patient Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_nhsNumberEntered; ?></span>
					</div>
				</div>
			</label>
			<label class="eido-block-radio">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="nhsnumber_radio" value="V" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">Verify Entry</span>
					</div>
					<div class="small-8">
						<span class="separator"><?php echo $c_nhsNumber; ?></span>
					</div>
				</div>
			</label>
			<p class="text-right small" style="margin-bottom:5px">Select which line is correct, or add a new data point below.</p>
			<label class="eido-block-radio dim">
				<span class="float-right">
					<div class="eido-simple-radio">
						<input type="radio" name="nhsnumber_radio" value="N" id="">
						<span class="checkmark"></span>
					</div>
				</span>
				<div class="grid-x">
					<div class="small-4">
						<span class="entry">New</span>
					</div>
					<div class="small-8">
						<span class="separator"><input type="text" class="new_input" name="new_nhsnumber"></span>
					</div>
				</div>
			</label>
			<hr/>

		<?php endif; ?>
		<div class="grid-x">
			<div class="hide-for-small-only medium-3 large-3 cell"></div>
			<div class="small-12 medium-6 large-6 cell text-center">
				<p>&nbsp;</p>
				<button type="submit" name="" value="Update Patient" tabindex="1" data-position="top" data-alignment="center" class="button large inactive expanded"/>
				Update Patient</button>
			</div>
	</form>
	<div class="hide-for-small-only medium-3 large-3 cell"></div>
</div>
</div>
<!-- END REVIEW SECTION -->
<!-- REVIEWCONFIRM SECTION -->
<div class="small-12 medium-6 large-6 cell content-right  <?php echo $reviewconfirm_hide; ?>">
	<h3>Confirmation<br/><span class="small"></span></h3>
	<p>&nbsp;</p>
	<div class="grid-x text-center">
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
		<div class="small-12 medium-6 large-6 cell">
			<p>&nbsp;</p>
			<h5 class="notification">You have updated the patient's information.</h5>
			<div class="grid-x grid-padding-x">
				<div class="small-12 medium-12 large-12 cell text-center">
					<p>An email will be sent to inform the patient.</p>
				</div>
				<div class="small-12 medium-12 large-12 cell text-center">
					<p>&nbsp;</p>
					<a href="patients.php?m=main" class="button large expanded">Ok</a>
				</div>
			</div>
		</div>
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
	</div>
</div>
<!-- REVIEWCONFIRM END -->

<?php
// ********************************************************************************
// *
// *  this code pulls data from patient episodes that is used by multiple sections
// *
// ********************************************************************************
if($mode == "procdetail" || $mode == "proccomplete" || $mode == "procdate" || "procselect" || $mode == "procsummary" || $mode == "procsurgeon") {
	$sql_pd = "SELECT *
                            FROM $TBLPTEPISODES
                            WHERE id = '$pe_id'";
	$GetQuery_pd = dbi_query($sql_pd);
	$qryResult_pd = $GetQuery_pd->fetch_assoc();
	$id = $qryResult_pd['id'];
	$c_surname = $qryResult_pd['c_surname'];
    $c_surname_uc = strtoupper($qryResult_pd['c_surname']);
	$c_firstName = $qryResult_pd['c_firstName'];
	$c_plannedProcedureDate = $qryResult_pd['c_plannedProcedureDate'];
	$c_nhsNumber = $qryResult_pd['c_nhsNumber'];
	$c_referenceNumberHospitalId = $qryResult_pd['c_referenceNumberHospitalId'];
	$c_dateOfBirth = $qryResult_pd['c_dateOfBirth'];
	$c_gender = $qryResult_pd['c_gender'];
	$c_postalCode = $qryResult_pd['c_postalCode'];
	$c_surgeonName = $qryResult_pd['c_surgeonName'];
	$c_gmcNumber = $qryResult_pd['c_gmcNumber'];
	$c_emailAddress = $qryResult_pd['c_emailAddress'];
	$c_userName = $qryResult_pd['c_userName'];
	$c_procedureId = $qryResult_pd['c_procedureId']; // the short code e.g. OS3
	$c_description = $qryResult_pd['c_description'];
	$c_displayName = $qryResult_pd['c_displayName'];
	$c_procedure = $qryResult_pd['c_procedure'];  // the long random Id
        $c_status = $qryResult_pd['c_status'];
        $c_procedureStatus = $qryResult_pd['c_procedureStatus'];
	$procedure = $c_procedureId . " - " . $c_displayName;
	$pt_status = get_pt_status($id);
	if($pt_status == "Inactive")
		$pt_status_class = "ps_grey"; else if(Spt_status == "Alert")
		$pt_status_class = "ps_red"; else if($pt_status == "Active")
		$pt_status_class = "ps_green";

    $sql_sd = "SELECT id
FROM $TBLSURGEONS
WHERE c_gmcNumber = '$c_gmcNumber'";
$GetQuery_sd = dbi_query($sql_sd);
$qryResult_sd = $GetQuery_sd->fetch_assoc();
$c_surgeonId =$qryResult_sd['id'];


} ?>

<!-- PROCSELECT SECTION -->
<?php if($mode == "procselect") {
	if($c_procedureId == "") {
		// logMsg("ProcSelect: NO procedure in PTEPISODES - use session: " . $_SESSION['proctl_proc_id'], $logfile);
		$arr_proc_info = get_proc_info($proc_id_selected);
		//$proc_id_selected = $_SESSION['proctl_proc_id'];
	} else {
		// logMsg("ProcSelect: Procedure in PTEPISODES - c_procedure: " . $c_procedure, $logfile);
		$arr_proc_info = get_proc_info($c_procedure);
		$proc_id_selected = $c_procedure;
	}
	// for testing - $arr_proc_info=get_proc_info('b3bce54b-23b1f8f7-6edadd0e-b5a6ba0a');
	// logMsg("ProcSelect: ProcIid_selected = $proc_id_selected", $logfile);
} ?>

<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $procselect_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=<?php echo $procselect_rtn_mode; ?>&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=<?php echo $procselect_rtn_mode; ?>&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
	<h3>Select Procedure<br><span class="small">Which procedure will the patient have?</span></h3>
	<form action="patients_a.php?m=procselect&id=<?php echo $pe_id; ?>" method="post" class="rs-adj">
		<div class="grid-container">
			<div class="grid-x">
				<div class="small-12 cell">
					<?php if($_SESSION['proc_select_error'])
						echo "<div class='error_message fi-alert'><strong>Please select a procedure</strong> - this is required</div>";
					$_SESSION['proc_select_error'] = false; ?>
					<label>Procedure
						<select name="proc_id">
							<?php make_combo($TBLPROCEPISODES, "id", "c_description", $proc_id_selected, "", " ORDER BY c_procedureId "); ?>
						</select>
					</label>
				</div>
				<div class="small-12 cell">
					<h6>Procedure Summary</h6>
					<?php if($proc_id_selected == "") {
						; ?>
						<p>No procedure selected</p>
					<?php } else { ?>
						<?php echo "<p>" . $arr_proc_info[0]['c_procedureId'] . " - " . $arr_proc_info[0]['c_description'] . "</p>"; ?>
						<label>Overview</label>
						<div class="timeline timeline-right">
							<table class="su-table">
								<?php $proctl_num_sessions = $arr_proc_info[0]['c_numberOfSessions'];
								//logMsg("PS: num_sess: $proctl_num_sessions", $logfile);
								$proctl_prepost_prev = "PRE"; // always starts with PRE
								for($pi = 1; $pi <= $proctl_num_sessions; $pi++) {
									$varname1 = "c_prePost" . $pi;
									//logMsg("PS: pi=$pi - prePost_prev=$proctl_prepost_prev - prePost=".$arr_proc_info[$pi][$varname1], $logfile);
									if(strtoupper($arr_proc_info[$pi][$varname1]) <> strtoupper($proctl_prepost_prev)) {
										// changed from pre to post - insert Procedure
										$proctl_class = "timeline-icon-cs";
										$proctl_iconfile = "arrow.png";
										$proctl_desc = $arr_proc_info[0]['c_description'];
										$proctl_prepost_prev = $arr_proc_info[$pi][$varname1];
										// bit of a hack - drop counter back one so we get the session info next pass
										$pi--;
									} else {
										// Survey (session)
										$proctl_class = "timeline-icon-cs";
										$proctl_iconfile = "caution.png";
										$varname2 = "c_session" . $pi . "Name";
										$proctl_desc = $arr_proc_info[$pi][$varname2];
										$proctl_prepost_prev = $arr_proc_info[$pi][$varname1];
									}
									// PROCTL
									?>
									<tr>
										<td width="20%">
											&nbsp;
										</td>
										<td width="12%" class="timeline-item">
											<span class="timeline-wrapper">
												<span class="<?php echo $proctl_class; ?>">
                                                    <img src="../img/icons/<?php echo $proctl_iconfile; ?>" width="20">
                                                </span>
											</span>
										</td>
										<td width="58%" class="timeline-item tlbdr">
                                <span class="timeline-content">
                                <p><?php echo $proctl_desc; ?></p>
                                </span>
										</td>
										<td width="10%" class="tlbdr">
											&nbsp;
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					<?php } ?>
				</div>
				<div class="small-12 cell text-center">
					<button type="submit" id="add" class="button large" value="Select">Select</button>
				</div>
	</form>
	<?php if($c_procedureId == "") { ?>
		<p class="text-center">
			<strong><a href="patients.php?m=procproceed&id=<?php echo $pe_id; ?>">SKIP this step and add a procedure later.</a></strong><br/>
			Note that nothing will be sent to the pateint until a procuedure is selected.</p>
	<?php } ?>
</div>
</div>
</div>
<!-- END PROCSELECT SECTION -->
<!-- PROCDATE SECTION -->
       <?php if ($_SESSION['workflow']=="PROCDATE") {
                $procdate_value=$c_plannedProcedureDate;
                $procdate_rtn_mode="procdetail";
             } else { 
                $procdate_value = $_SESSION['proc_date_entered'];    
                $procdate_rtn_mode="procselect";
             }
             // logMsg("Session Workflow: ".$_SESSION['workflow'],$logfile);
        ?>
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $procdate_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=<?php echo $procdate_rtn_mode; ?>&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=<?php echo $procdate_rtn_mode; ?>&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
	<h3 style="padding-bottom: 25px;" >Select Procedure Date<br><span  class="small">What date is the procedure planned for?</span></h3>
	<?php ; // &rtn=pd   ???  ?>
	<form action="patients_a.php?m=procdate&id=<?php echo $pe_id; ?>" method="post">
             <input type="hidden" name="rtn" value="<?php echo $rtn; ?>">
		<div style="padding-left: 0" class="grid-container">
			<div class="grid-x">
				<div style="padding-left: 0" class="small-12 cell field">
					<h5>Procedure</h5>
                    <label style="padding-bottom: 15px;">
					<?php echo $procedure; ?>
					</label>
				</div>
				<div style="padding-left: 0; margin-bottom: 0px; padding-bottom: 0px" class="small-12 field">
                                   <?php if($_SESSION['proc_date_error']) echo "<div class='error_message fi-alert'><strong>Please correct the procedure date</strong> - the date cannot be in the past.<br /></div>"; ?>
					<div style="padding-left: 0; margin-bottom: 0" class="input-group">
						<span style="padding-left: 0" class="input-group-label"><i class="fi-calendar"></i></span>
						<input class="input-group-field date_element" type="text" id="proc_date" name="proc_date" value="<?php echo $procdate_value; ?>">
						<!--<div class="input-group-button">
							<button type="submit" id="add" class="button" value="Next">Next</button>
						</div>-->
					</div>
				</div>
				<div class="small-12 cell field hide">
					<div class="grid-x grid-padding-x">
						<label><i class="fi-calendar"></i>
							<div class="input-group">
								<select style="padding-left: 0" class="input-group-field" name="proc_day" placeholder="Day">
									<?php include "../includes/select_day.html"; ?>
								</select>&nbsp;&nbsp;
								<select class="input-group-field" name="proc_month" placeholder="Month">
									<?php include "../includes/select_month.html"; ?>
								</select>&nbsp;&nbsp;
								<select class="input-group-field" name="proc_year" placeholder="Year">
									<option value="">Year</option>
									<option value="2018">2018</option>
									<option value="2019">2019</option>
								</select>&nbsp;&nbsp;&nbsp;&nbsp;
								<!--<div class="input-group-button">
									<button type="submit" id="add" class="button" value="Next">Next</button>
								</div>-->
							</div>
						</label>
					</div>
					<div class="small-12 cell text-center">
					</div>
				</div>
			</div>
		</div>
        <div class="grid-x">
        <div class="hide-for-small-only medium-3 large-3 cell"></div>
        <div class="small-12 medium-6 large-6 cell text-center">
            <p>&nbsp;</p>
            <button type="submit" name="" value="Next" class="button large expanded"/>
            Next</button>
        </div>
        <div class="hide-for-small-only medium-3 large-3 cell"></div>
    </div>
	</form>

</div>

<!-- END PROCDATE  SECTION -->
<!-- PROCSURGEON SECTION -->

<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $procsurgeon_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=procdate&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=procdate&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
	<h3>Select Surgeon<br/><span class="small">Which surgeon will perform the procedure?</span></h3>
    <div style="margin-left: 20px; margin-right: 20px;">
	<table class="su-table stack">
		<tr>
			<td style="padding-left: 0"><strong>Procedure</strong></td>
			<td><?php echo $procedure; ?></td>
		</tr>
		<tr>
			<td style="padding-left: 0"><strong>Procedure Date</strong></td>
			<td><?php echo $c_plannedProcedureDate; ?></td>
		</tr>
	</table>
    </div>
	<form action="patients_a.php?m=procsurgeon&id=<?php echo $pe_id; ?>" method="post" class="rs-adj">
		<div class="grid-container">
			<div class="grid-x">
				<div class="small-12 cell">
					<h5>Search for the name of the surgeon who will perform this procedure.</h5>
				</div>
				<div class="small-12 cell">
					<label>Surgeon Name
						<select id="proc_surgeonname" class="select2 proc_surgeonname">
							<?php make_combo("app_fd_ver_surgeons", "id", "c_surgeonName", "$c_surgeonId", "", " ORDER BY c_surgeonName "); ?>
						</select>
					</label>
					<input id="proc_surgeon" type="hidden" name="proc_surgeon" class="proc_surgeon" value="<?php echo $c_surgeonName; ?>"/>
				</div>
				<div class="small-12 cell">
					<select id="proc_surgeonname_temp" class="hide proc_surgeonname_temp">
						<?php make_combo($TBLSURGEONS, "id", "c_gmcNumber", "$c_surgeonId", "", " ORDER BY c_surgeonName "); ?>
					</select>
					<label>GMC Number
						<input id="proc_gmcnumber_temp" disabled type="text" class=proc_gmcnumber_temp" value="<?php echo $c_gmcNumber; ?>">
					</label>
					<input id="proc_gmcnumber" type="hidden" name="proc_gmcnumber" value="<?php echo $c_gmcNumber; ?>" class="proc_gmcnumber">
				</div>
			</div>
		</div>
		<div class="grid-x">
			<div class="hide-for-small-only medium-3 large-3 cell"></div>
			<div class="small-12 medium-6 large-6 cell text-center">
				<p>&nbsp;</p>
				<button type="submit" name="" value="Next" class="button large expanded"/>
				Next</button>
			</div>
	</form>
	<div class="hide-for-small-only medium-3 large-3 cell"></div>
</div>
</div>
<!-- </div> WEL -->
<!-- END PROCSURGEON SECTION -->
<!-- PROCSUMMARY SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $procsummary_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=procsurgeon&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=procsurgeon&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>

	<h3 style=" border-bottom-style: none"">Summary<br/><span class="small">Check and confirm</span></h3>
    <div style="margin-left: 20px; margin-right: 20px;">
	<h6 class="border"></h6>
	<h6>Patient Name</h6>
	<p><?php echo $c_surname_uc . "," . $c_firstName; ?></p>
	<h6>NHS Number</h6>
	<p><?php echo $c_nhsNumber; ?></p>
	<h6>Hospital Number</h6>
	<p><?php echo $c_referenceNumberHospitalId; ?></p>
	<h6>Date of Birth</h6>
	<p><?php echo $c_dateOfBirth; ?></p>
	<h6 class="border"></h6>
    <h6>Procedure</h6>
	<p><?php echo $procedure; ?></p>
	<h6>Procedure Date</h6>
	<p><?php echo $c_plannedProcedureDate; ?></p>
    <h6 class="border"></h6>
	<h6>Surgeon</h6>
	<p><?php echo $c_surgeonName; ?><br/>GMC: <?php echo $c_gmcNumber; ?></p>
    </div>
	<div class="grid-x">
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
		<div class="small-12 medium-6 large-6 cell text-center">
			<p>&nbsp;</p>
                      <?php if ($c_status=="PENDING") { ?>
			  <a href="patients_a.php?m=procconfirm&id=<?php echo $pe_id; ?>" class="button large expanded"/><strong>Confirm</strong></a>
                      <?php } else { ?>
			  <a href="patients.php?m=overview&id=<?php echo $pe_id; ?>" class="button large expanded"/><strong>Confirm</strong></a>
                      <?php } ?>
		</div>
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
	</div>
</div>
<!-- END PROCSUMMARY -->
<!-- PROCCONFIRM SECTION -->
<div class="small-12 medium-6 large-6 cell content-right  <?php echo $procconfirm_hide; ?>">
	<!--
	<div class="back clickable-row" data-href="patients.php?m=procsurgeon&id=<?php echo $pe_id; ?>">
		<img src="../img/icons/back.png" alt="less than icon" class="float-left"/>Back
	</div>-->
	<h3>Confirmation<br/><span class="small"></span></h3>
	<div class="grid-x text-center">
		<div class="hide-for-small-only medium-2 large-2 large-offset-1 cell"></div>
		<div class="small-12 medium-6 large-6 cell">
			<p class="text-center" style="padding-top: 60px;"><img src="../img/success-guy.png" width="110"></p>
			<h5 class="uc">Success</h5>
			<div class="grid-x grid-padding-x">
				<div class="small-12 medium-12 large-12 cell text-center">
					<p>The patient has been added successfully.</p>
				</div>
				<div class="small-12 medium-12 large-12 cell text-center">
					<p>&nbsp;</p>
					<a href="patients.php?m=add" class="button large expanded">Add Another</a>
					<a href="patients.php?m=main" class="button large expanded inactive">Home</a>
				</div>
			</div>
		</div>
		<div class="hide-for-small-only medium-2 large-2 cell"></div>
	</div>
</div>
<!-- PROCCONFIRM END -->
<!-- PROCDETAIL SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $procdetail_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=overview&tlm=key&id=<?php echo $pe_id; ?>" style="">
        <a href="patients.php?m=overview&tlm=key&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
    <div style="margin-left: 20px; margin-right: 20px; line-height: 1.2">
	<span style="font-size:medium;font-weight:700;">Procedure Details</span><br />
        <span class="small" style="font-size:small;">The patient's procedure</span>
    </div>
	<h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname_uc, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
        
	<table class="" style="margin-bottom: 0px !important">
		<tr>
			<td style="padding-left: 20px; width: 30%"><strong>Procedure:</strong></td>
			<td><?php echo $procedure; ?></td>
		</tr>
		<tr>
			<td style="padding-left: 20px; padding-top: 0px;"><strong>Procedure Date:</strong></td>
			<td style="padding-top: 0px;"><?php echo $c_plannedProcedureDate; ?></td>
		</tr>
		<tr>
			<td style="padding-left: 20px; padding-top: 0px;"><strong>Surgeon:</strong></td>
			<td style="padding-top: 0px;"><?php if($c_surgeonName == "") echo "<span style='color:red;'>No surgeon selected.</span>"; else echo $c_surgeonName; ?></td>
		</tr>
		<tr>
			<td style="padding-left: 20px; padding-top: 0px; padding-bottom: 25px;"><strong>GMC Number:</strong></td>
			<td style="padding-top: 0px; padding-bottom: 25px;"><?php if($c_gmcNumber == "") echo "<span style='color:red;'>GMS Number not available.</span>"; else echo $c_gmcNumber; ?></td>
		</tr>
	</table>
    <ul class="patient-list">
        <li style="margin-left: 20px; margin-right: 20px; border-bottom: 1px solid #D3D1D1 !important; font-family: 'Lato-Bold', 'Lato Bold', 'Lato-Light', 'Lato Light', 'Lato', 'sans-serif'; text-decoration-color: #0d2240;">
 <?php if ($c_procedureStatus=="PRE") { ?>
    <div class="small-12 medium-12 large-12 cell text-left clickable-row" data-href="patients_a.php?m=gotoprocdate&id=<?php echo $pe_id; ?>" style="border-top: 1px solid #D3D1D1 !important;">
        <a href="patients_a.php?m=gotoprocdate&id=<?php echo $pe_id; ?>" class="no-u" style="padding-left: 10px; padding-top: 20px; padding-bottom: 20px;">
            <span style="padding-top: 5px:" class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
            <p style="width:80%; color: #0d2240; font-size: small;"><span style="font-size:medium;font-weight:700;">Change procedure date or surgeon</span>
                <br />Select a new date or change surgeon
            </p>
        </a>
    </div>
	<div class="small-12 medium-12 large-12 cell text-left clickable-row" data-href="patients.php?m=proccomplete&id=<?php echo $pe_id; ?>" style="border-top: 1px solid #D3D1D1 !important;">
		<a href="patients.php?m=proccomplete&id=<?php echo $pe_id; ?>" class="no-u" style="padding-left: 10px; padding-top: 20px; padding-bottom: 20px;">
            <span style="padding-top: 5px:" class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
			<p style="width:80%; color: #0d2240; font-size: small;"><span style="font-size:medium;font-weight:700;">Mark procedure as complete</span>
                <br />This will trigger the post-op surveys
			</p>
        </a>
	</div>
 <?php } ?>
            <?php if ($c_status<>"EpisodeComplete") { ?>
	<div class="small-12 medium-12 large-12 cell text-left clickable-row" data-href="patients.php?m=proccancel&id=<?php echo $pe_id; ?>" style="border-top: 1px solid #D3D1D1 !important;">
		<a href="patients.php?m=proccancel&id=<?php echo $pe_id; ?>" class="no-u" style="padding-left: 10px; padding-top: 20px; padding-bottom: 20px;">
            <span style="padding-top: 5px:" class="float-right right-arrow"><i class="icon eido-icon-chevron-right"></i></span>
			<p style="width:80%; color: #0d2240; font-size: small;"><span style="font-size:medium;font-weight:700; color: #0d2240">Stop sending surveys</span>
                <br />This will retain all existing patient data in the system, but will not send any more surveys
			</p>
        </a>
	</div>
            <?php } ?>
    </li>
    </ul>
</div>
<!-- END PROCDETAIL -->

<!-- PROCCANCEL SECTION -->
<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $proccancel_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
	<div class="grid-x text-center">
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
		<div class="small-12 medium-6 large-6 cell">
			<center><h3>Are you susre you want to cancel this proceudre?</h3></center>
			<p><br/><br/>No more surveys will be sent to the patient and all their previous respnses will be voided.</p>
			<p>&nbsp;</p>
			<a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>">
				<button name="" value="confirm complete" class="button large expanded inactive"/>
				No</button></a>
			<a href="patients_a.php?m=proccancel&id=<?php echo $pe_id; ?>">
				<button name="" value="confirm complete" class="button large expanded alert"/>
				Confirm Cancel</button></a>
		</div>
		<div class="hide-for-small-only medium-3 large-3 cell"></div>
	</div>
</div>
<!-- END PROCCANCEL SECTION -->
<!-- PROCCOMPLETE SECTION -->
<?php
    $sql_sd = "SELECT id
FROM $TBLSURGEONS
WHERE c_gmcNumber = '$c_gmcNumber'";
    $GetQuery_sd = dbi_query($sql_sd);
    $qryResult_sd = $GetQuery_sd->fetch_assoc();
    $c_surgeonId = $qryResult_sd['id'];
?>

<div style="padding-top: 0px !important;" class="small-12 medium-6 large-6 cell content-right <?php echo $proccomplete_hide; ?>">
	<div class="back clickable-row btn-back" data-href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>">
		<a href="patients.php?m=procdetail&id=<?php echo $pe_id; ?>">
            <span><i class="icon eido-icon-chevron-left"></i>
            Back</span>
        </a>
	</div>
	<h3>Mark Procedure Complete<br/><span class="small">Comfirm that the procedure has been completed and by who</span></h3>
	<h5 class="<?php echo $pt_status_class; ?>"><?php echo "$c_surname_uc, $c_firstName"; ?><span class="small"><?php echo $pt_status; ?></span></h5>
	<div style="margin-left: 20px; margin-right: 20px;">
    <table class="su-table stack">
		<tr style="border-bottom: none;">
			<td style="padding-left: 0px; border-bottom: none;"><strong>Procedure</strong></td>
			<td style="border-bottom: none;" ><?php echo $procedure; ?></td>
		</tr>
		<tr>
			<td style="padding-left: 0px;"><strong>Procedure Date</strong></td>
			<td><?php echo $c_plannedProcedureDate; ?></td>
		</tr>
	</table>
    </div>
        <div class="grid-container">
                <div class="grid-x">
                                <div class="small-12 medium-12 large-12 cell">

                                        <p style="padding-left: 15px; padding-right: 15px;text-align: center;">Search for the name of the surgeon who performed this procedure</p>

                                        <form action="patients_a.php?m=proccomplete&id=<?php echo $pe_id; ?>" method="post">
                                            <div class="small-12 cell">
                                        <label>Surgeon Name
                                                <select id="proc_surgeonname_complete" class="select2 proc_surgeonname">
                                                        <?php make_combo("app_fd_ver_surgeons", "id", "c_surgeonName", "$c_surgeonId", "", " ORDER BY c_surgeonName "); ?>
                                                </select>
                                        </label>
                                        <input id="proc_surgeon_complete" type="hidden" name="proc_surgeon" class="proc_surgeon" value="<?php echo $c_surgeonName; ?>"/>
                                        </div>
                                <div class="small-12 cell">
                                        <select id="proc_surgeonname_temp_complete" class="hide proc_surgeonname_temp">
                                                <?php make_combo($TBLSURGEONS, "id", "c_gmcNumber", "$c_gmcNumber", "", " ORDER BY c_surgeonName "); ?>
                                        </select>
                                        <label>GMC Number
                                                <input id="proc_gmcnumber_temp_complete" disabled type="text" class=proc_gmcnumber_temp" value="<?php echo $c_gmcNumber; ?>">
                                        </label>
                                        <input id="proc_gmcnumber_complete" type="hidden" name="proc_gmcnumber" value="<?php echo $c_gmcNumber; ?>" class="proc_gmcnumber">
                                </div>
                                </div>
                </div>
        </div>
	<div class="grid-x">
		<div class="hide-for-small-only medium-2 large-2 cell"></div>
		<div class="small-12 medium-8 large-8 cell text-center">
            <h5 style="padding-top: 55px; padding-bottom: 15px; text-align: center;">Mark the procedure as complete.</h5>
			<button type="submit" name="" value="confirm complete" class="button large expanded"/>
			Confirm Complete</button>
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
<?php ; // logMsg("Patients(BOT)> mode: $mode",$logfile); ?>
<!-- End Footer -->
</div>
<!--<script src="../js/vendor/jquery.js"></script>-->
<script src="../js/vendor/what-input.js"></script>
<script src="../js/vendor/foundation.js"></script>
<script src="../js/jquery.plugin.min.js"></script>



<script src="../js/foundation-datepicker.min.js"></script>
<script src="../js/app.js"></script>
<script>

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
 //   $(document).ready(function() {
 //      $("#proc_surgeonname").select2();
 //   });
  //$(function() {
   //   var placeholder = "&#xf002 Select a place";
   //   $(".select2").select2({
   //       placeholder: placeholder,
   //       width: null,
   //       escapeMarkup: function(m) {
   //           return m;
   //       }
   //   });
 // });

                $(document).ready(function() {

                    $('.date_element').fdatepicker({
                        format: 'dd/mm/yyyy',
                    });

                    <?php if($procdate_hide == "")
                    echo "$('#proc_date').fdatepicker('show');" ?>


                    $("#postalcode_ea").keyup(function() {
                        $("#address").html("");
                        var postalcode_val = $(this).val();
                        var postalcode = postalcode_val.replace(/ /g, '');
                        if(postalcode.length == 6 || postalcode.length == 7) {
                            console.log("Let's get the address");
                            $.ajax({
                                url: "./ajax/get_address_by_postcode.php", method: "POST", data: {postcode: postalcode_val}, dataType: "HTML",
                            }).done(function(response) {
                                // once ajax is completed
                                if(response.length > 0) {
                                    $("#address").html(response);
                                    $("#address_1_ea").val("");
                                    $("#address_2_ea").val("");
                                    $("#city_ea").val("");
                                    $("#country_ea").val("");
                                    $("#postalcode_2_ea").val("");
                                }
                            });
                        }
                    });

                    $("#address").change(function() {
                        var address = $("#address option:selected").val();
                        var address_array = address.split("~");
                        $("#address_1_ea").val(address_array[0]);
                        $("#address_2_ea").val(address_array[1]);
                        $("#city_ea").val(address_array[2]);
                        $("#country_ea").val(address_array[3]);
                        $("#postalcode_2_ea").val(address_array[4]);
                    });

                    // WEL set session value based on select session
                    $('select[name=proc_id]').change(function() {
                        currentVal = $(this).val();
                        window.location.assign("patients.php?m=procselect&proc_id=" + currentVal + "&id=<?php echo $pe_id; ?>");
                    });

                    //////////////////////////////////
                    // Top Bar Search : START
                    /////////////////////////////////

                    $("#top_search_query").autocomplete({
                        source: function(request, response) {
                            var looking_for = $("#looking_for").find(":selected").val();

                            // disable the autocomplete feature for patient name
                            if(looking_for == "patient") {
                                response("");
                                return;
                            }
                            var search_query = $("#top_search_query").val();
                            $.ajax({
                                method: "POST", url: "./ajax/get_search_suggestion.php", dataType: "json", data: {
                                    looking_for: looking_for, search_query: search_query
                                }, success: function(data) {
                                    response(data);
                                }
                            });
                        }, minLength: 1, select: function(event, ui) {
                            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
                        }
                    });

                    // Change Placeholder of Search bar

                    $("#looking_for").change(function() {
                        var looking_for = $(this).val();
                        switch(looking_for) {
                            case "patient":
                                $("#top_search_query").attr("placeholder", "Name or ID Number");
                                break;
                            case "procedure":
                                $("#top_search_query").attr("placeholder", "Procedure Name or Code");
                                break;
                            case "surgeon":
                                $("#top_search_query").attr("placeholder", "Surgeon Name or GMC Number");
                                break;
                        }
                    }).change();

                    ///////////////////////////////////
                    // Top Bar Search : END
                    ///////////////////////////////////


                    // change the GCM value - Select Surgeon



                    $("#proc_surgeonname").change(function() {
                        var proc_surgeon_id = $(this).val();
                        var proc_surgeon_name = $.trim($("#proc_surgeonname :selected").text());
                        $("#proc_surgeon").val(proc_surgeon_name);

                        $("#proc_surgeonname_temp").val(proc_surgeon_id);
                        var proc_gmcnumber = $("#proc_surgeonname_temp :selected").text();

                        $("#proc_gmcnumber").val(proc_gmcnumber);
                        $("#proc_gmcnumber_temp").val(proc_gmcnumber);
                    });
                    //duplicate for Procedute Comfirm section

                    $("#proc_surgeonname_complete").change(function() {
                        var proc_surgeon_id = $(this).val();
                        var proc_surgeon_name = $.trim($("#proc_surgeonname_complete :selected").text());
                        $("#proc_surgeon_complete").val(proc_surgeon_name);

                        $("#proc_surgeonname_temp_complete").val(proc_surgeon_id);
                        var proc_gmcnumber = $("#proc_surgeonname_temp_complete :selected").text();

                        $("#proc_gmcnumber_complete").val(proc_gmcnumber);
                        $("#proc_gmcnumber_temp_complete").val(proc_gmcnumber);
                    });
                    $(document).ready(function() {
                        $("#addpt").on("click", function() {
                            $("form div").removeClass("error_message");
                            $("form label").removeClass("error_message");
                            $("form div").removeClass("fi-alert");
                            $("form label").removeClass("fi-alert");
                            $("form")[0].reset();
                            $.get("clearsession.php");
                        });
                    });

                    //////////////////////////////////
                    // Review Section
                    //////////////////////////////////

                    // autocheck radio when user try to enter new value
                    $(".new_input").keyup(function() {
                        var new_value = $(this).val();
                        if(new_value.length > 0) {
                            $(this).parents("tr").find("input[type=radio]").prop("checked", true);
                        } else {
                            $(this).parents("tr").find("input[type=radio]").prop("checked", false);
                        }
                    });


                });
            
</script>
</body>
</html>
