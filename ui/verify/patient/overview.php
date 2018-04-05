    <?php if ($mode=='overview')
              {  $sql_o  = "SELECT *
                            FROM app_fd_ver_patientEpisodes
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
                 if ($c_procedureId<>"")
                    $procedure = $c_procedureId." - ".$c_description;
                 else
                    $procedure = ""; // none selected yet
                 $pt_status = get_pt_status($id);

                 if ($pt_status == "Inactive")
                    $pt_status_class = "ps_grey";
                 else if (Spt_status == "Alert")
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
                 $sql_tl= "SELECT *
                           FROM app_fd_ver_patientTimelines
                           WHERE c_patientEpisodeId='$pe_id'
                           ORDER BY dateCreated DESC";
                 logMsg($sql_tl, $logfile);
                 $GetQuery_tl = dbi_query($sql_tl);
                 logMsg("Timeline NumRows: ".$GetQuery_tl->num_rows, $logfile);
                 $tl=0;
                 while ($qryResult_tl = $GetQuery_tl->fetch_assoc())
                 {
                    $device_type = $qryResult_tl['c_deviceType'];
                    if (strpos(strtolower($device_type), "datasift"))
                       logMsg("BAD DEVICE: $device_type", $logfile);
                    else
                    {
                       $arr_tl[$tl]=$qryResult_tl;
                       $tl++;
                    }
                 }
                 if ($GetQuery_tl->num_rows==0)
                 {
                    $timeline = false;
                 }
                 else
                 {
                    $timeline = true;
                 }
          }
        ?>

