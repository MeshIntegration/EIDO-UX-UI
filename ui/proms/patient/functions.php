<?php
// ***************************************
// patient/functions.php
// 2018 Copyright, Mesh Integration LLC
// 1/13/17 - WEL
// ***************************************

session_start();
require_once '../utilities.php';
$logfile = "wel.log";
$debug=true;  // turn on for extra logging

function format_tl_date($tl_datetime)
{
   $month_name['01']="Jan";
   $month_name['02']="Feb";
   $month_name['03']="Mar";
   $month_name['04']="Apr";
   $month_name['05']="May";
   $month_name['06']="Jun";
   $month_name['07']="Jul";
   $month_name['08']="Aug";
   $month_name['09']="Sep";
   $month_name['10']="Oct";
   $month_name['11']="Nov";
   $month_name['12']="Dec";

   list($dt, $tm) = explode(" ", $tl_datetime);
   list($y, $m ,$d) = explode("-", $dt);
   $mname = $month_name[$m];
   $fdt = "$d $mname $y";
   return $fdt;
}

function get_stat_counts($type)
{
   if ($type=='active')
   {
      $sql = "SELECT COUNT(*) AS ct 
              FROM app_fd_pro_patientEpisodes
              WHERE c_status<>'Episode Complete'
              AND c_proceedOrCancel<>'Cancel'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct; 
   }
   else if ($type=='inactive')
   {
      $sql = "SELECT COUNT(*) AS ct 
              FROM app_fd_pro_patientEpisodes
              WHERE c_status='Episode Complete'
              OR c_proceedOrCancel='Cancel'";
      $GetQuery = dbi_query($sql);
      $qryResult=$GetQuery->fetch_assoc();
      $ct = $qryResult['ct'];
      return $ct; 
   }
   else if ($type=='alert')
   {
      return 999;
   }
}

function get_address_by_postcode($postcode)
{
   // param: UK post code
   // output: HTML select code with address list
   //         address data is tilde ~ seperated in value
   // uses API for Ideal Postcodes - ideal-postcodes.co.uk

   // $postcode = "LN22PD";  // for testing
   // $postcode = "ZE29XT";  // for testing
   // $postcode = "ID1 1QD";  // Ideal's test postcode for testing

   $debug = false;

   $api_key  = "ak_j3jxqn23XM9ku2bMrbMQdEGtgr1Ah";  // EIDO Systems Account
   //$api_key  = "iddqd";  // Ideal's API Key for testing  

   if ($debug)
   {
      // check the API key is good or not
      $base_url = "https://api.ideal-postcodes.co.uk/v1/keys/";
      $url = $base_url.$api_key;
      echo $url."<BR /><BR />";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = json_decode(curl_exec($ch), true);
      curl_close($ch);
      echo "<PRE>";
      print_r($response);
      echo "</PRE><BR /><BR />";
      //exit();
   }

   $base_url = "https://api.ideal-postcodes.co.uk/v1/postcodes/";
   $url = $base_url . rawurlencode($postcode) . "?api_key=" . $api_key;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $response = json_decode(curl_exec($ch), true);
   curl_close($ch);
   $addresses = $response["result"];
   if (!isset($addresses))
   {
      echo "<PRE>";
      print_r($response);
      echo "</PRE><BR /><BR />";
      exit();
   }

   $select="";
   for ($i=0; $i<count($addresses); $i++)
   {
      $address1 = $addresses[$i]['line_1'];
      $address2 = $addresses[$i]['line_2'];
      $city = $addresses[$i]['post_town'];
      $county = $addresses[$i]['county'];
      $select .= "<option value='$address1~$address2~$city~$county~$postcode'>$address1 $city, $county</option>";
   }

echo "Postcode: $postcode<BR /><BR />";
echo $url."<BR /><BR />";
echo "<select name='test' size='5'>";
echo $select;
echo "</select>";
   //return $select;

   echo "<PRE>";
   print_r($addresses);
   echo "</PRE>";
}
?>
