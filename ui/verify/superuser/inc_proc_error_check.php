<?php 
        // error check
        $error_flag=false;
        $type_flag="pre";
        for ($s=1; $s<=$number_of_sessions; $s++)  {
          $varname_name = "c_session".$s."Name";
          $varname_delay = "c_session".$s."Delay";
          if ($s==1) $delay_str = ""; else $delay_str = ", $varname_delay";
          $varname_type = "c_prePost".$s;
          $sql = "SELECT $varname_name, $varname_type
                         $delay_str
                  FROM $TBLPROCEPISODES
                  WHERE id='$pe_id'";
logMsg($sql,"wel.log");
          $GetQuery = dbi_query($sql);
          $qryResult = $GetQuery->fetch_assoc();
          $session_name = $qryResult[$varname_name];
          $session_delay = $qryResult[$varname_delay];
          $session_type= $qryResult[$varname_type];
logMsg("$s - $varname_name $session_name - $varname_delay $session_delay - $varname_type $session_type - F: $type_flag", "wel.log");
          if ($session_name=="") {
             $_SESSION['session_name_error'][$s] = true;
logMsg("$s - name error","wel.log");
             $error_flag=true;
          }
          else
             $_SESSION['session_name_error'][$s] = false;
          if ($session_delay=="" && $s>1) {
             $_SESSION['session_delay_error'][$s] = true;
logMsg("$s - delay error","wel.log");
             $error_flag=true;
          }
          else
             $_SESSION['session_delay_error'][$s] = false;
          if ($session_type=="") {
             $_SESSION['session_type_error'][$s] = true;
logMsg("$s - type error","wel.log");
             $error_flag=true;
          }
          else
             $_SESSION['session_type_error'][$s] = false;
          if ($session_type=="Post" && $s==1) {
             $_SESSION['session_type_first_error'][$s] = true;
logMsg("$s - type first error","wel.log");
             $error_flag=true;
          }
          else
             $_SESSION['session_type_first_error'][$s] = false;
          if ($session_type=="Pre" && $type_flag=="post") {
             $_SESSION['session_type_order_error'][$s] = true;
logMsg("$s - type order error","wel.log");
             $error_flag=true;
          }
          else
             $_SESSION['session_type_order_error'][$s] = false;

          $survey_ids = array();
          $survey_ids = get_surveys_by_proc($pe_id,$s); 
          if (count($survey_ids)==0) {
             $_SESSION['session_survey_error'][$s] = true;
logMsg("$s - survey error","wel.log");
             $error_flag=true;
          }
          else
             $_SESSION['session_survey_error'][$s] = false;

          if ($session_type=="Post") $type_flag="post";
        }
        // check they have at least one POST session
logMsg("$s - TYPE FLAG = $type_flag ","wel.log");
        if ($type_flag=="pre") {
             $_SESSION['session_type_nopost_error'][$number_of_sessions] = true;
             $error_flag=true;
logMsg("$s - NO Post error ","wel.log");
        }
        else
             $_SESSION['session_type_nopost_error'][$number_of_sessions] = false;

        // clear out any errors for sessions not used
        for ($s=$number_of_sessions+1; $s<=$MAX_SURVEYS; $s++) {
           $_SESSION['session_survey_error'][$s] = false;
           $_SESSION['session_type_order_error'][$s] = false;
           $_SESSION['session_type_first_error'][$s] = false;
           $_SESSION['session_type_nopost_error'][$s] = false;
           $_SESSION['session_type_error'][$s] = false;
           $_SESSION['session_delay_error'][$s] = false;
           $_SESSION['session_name_error'][$s] = false;
        }
?>
