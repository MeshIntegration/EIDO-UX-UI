<?php

/*******************************************************
* is_util.php
* Copyright 2001, IdeaStar Inc.
* General Purpose Utilities
*
* PWH derived from inc.utilities.php
* Re-arranged 04/06/01 - MH
********************************************************/

/********************************************************
* Escape any quote characters 
* Carriage returns are NOT removed
* MH - 05/04/01
*
* Arguments: string as received from the web browser
* Returns: escaped string enclosed in single quotes
*********************************************************/
function replaceQuote($input)
{

   $input = preg_replace("/<script/i", "", $input);
   $input = preg_replace("/script>/i", "", $input);
   $input = ereg_replace("<\?", "", $input);
   $input = ereg_replace("\?>", "", $input);
   $input = ereg_replace("'", "\'", $input);

   $result = "'".$input."'";
   return $result;
}

/********************************************************
* escapeAchar escape a given character in an input string
*
* Arguments: string to be searched and escaped, 
*            character to escape, escape character to use
* Returns: escaped string all nicely escaped
*********************************************************/
function escapeAchar($input, $escapedChar, $escapeChar="\\")
{
  $string = stripslashes(trim($input));
  $result = "";
  for ($i=0;$i<strlen($string);$i++)
  {
    $char = substr($string,$i,1);
    if ($char == $escapedChar) $result .= $escapeChar.$char;
    else $result .= $char;
  }

  return $result;
}

/********************************************************
* escapeQuote escape any single quote characters in order
* to insert the string into a database.  Carriage returns are
* also removed from the string.
*
* Arguments: string as received from the web browser
* Returns: escaped string enclosed in single quotes
*********************************************************/
function escapeQuote($input, $escapeChar="\\", $quoteChar="'")
{
  $input = ereg_replace("<\?", "", $input);
  $input = ereg_replace("\?>", "", $input);
  $input = preg_replace("/<script/i", "", $input);
  $input = preg_replace("/script>/i", "", $input);
  $string = stripslashes(trim($input));
  $result = "";
  for ($i=0;$i<strlen($string);$i++)
  {
    $char = substr($string,$i,1);
    if ($char == "'") $result .= $escapeChar."'";
    elseif ($char == "\r") $result .= "";
    elseif ($char == "\n") $result .= " ";  //MH - 03/30/01
    else $result .= $char;
  }

  return $quoteChar.$result.$quoteChar;
}

/********************************************************
* Removes any non-numeric characters from a string
*  in order to prepare it to be inserted into a numeric database field.
*
* Arguments: string are received from the web browser
* Returns: string containing only numeric characters
*          if the original string has no nummeric characters
*          this returns "0"
*********************************************************/
function escapeValue($value) 
{
  $result = "";
  for ($i=0;$i<strlen($value);$i++) 
  {
    $char = substr($value,$i,1);
    if ($char == "'") 
      $result .= "";
    elseif ($char == ".") 
      $result .= ".";
    elseif ($char == "\r") 
      $result .= "";
    elseif ($char == "-") 
      $result .= "-";
    elseif (($char >= "0") && ($char <= "9")) 
      $result .= $char;
  }
  if ($result == "") 
    $result = "0";

  return $result;
}

/********************************************************
* get today's date in mysql ready format
*
* Arguments: none
* Returns: string holding current date "YYYY-MM-DD"
*********************************************************/
function get_today()
{
  $today = getdate();

  return("$today[year]-$today[mon]-$today[mday]");
}

/********************************************************
* Determine Age from DOB compared to CURRENT date
*
* Arguments: month day year
* Returns: "Bad Date" if the date is wrong
*          the age in years otherwise
*********************************************************/
function make_age($AppMonth, $AppDay, $AppYear)
{
  $age = "Bad Date";	
  if (checkdate($AppMonth, $AppDay, $AppYear))
  {
    $today = getdate();
    if ($AppMonth < $today['mon'])
      $hadBD = 0;
    elseif (($AppMonth == $today['mon']) && ($AppDay <= $today['mday']))
      $hadBD = 0;
    else
      $hadBD = 1;
    $age = $today['year'] - $AppYear - $hadBD;		
  }

  return ($age);
}

/********************************************************
* Determine Age Nearest Birthday
*
* Arguments: month day year
* Returns: "Bad Date" if the date is wrong
*********************************************************/
function make_age_nearest($dob_mo, $dob_day, $dob_yr)
{
   $today = getdate();

   $current_dob_format = mktime(0,0,0,$dob_mo,$dob_day,$today[year]);
   $current_dob = getdate($current_dob_format);

   $diff = $current_dob[mon] - $today[mon];
   $age = $today[year] - $dob_yr;

   if ($diff <= 6) $factor = 0;
   else $factor = -1;

   $age = $age + $factor;

   return ($age);
}

/********************************************************
* Debugging functions
*********************************************************/
function ss_array_as_string (&$array, $column = 0) {
    $str = "Array(<BR>\n";
    while(list($var, $val) = each($array)){
        for ($i = 0; $i < $column+1; $i++){
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        $str .= $var.' ==> ';
        $str .= ss_as_string($val, $column+1)."<BR>\n";
    }

    for ($i = 0; $i < $column; $i++){
        $str .= "&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    return $str.')';

}

/********************************************************
*********************************************************/
function ss_object_as_string (&$object, $column = 0) {

    if (empty($object->classname)) {
        return "$object";

    }
    else {
        $str = $object->classname."(<BR>\n";
        while (list(,$var) = each($object->persistent_slots)) {
            for ($i = 0; $i < $column; $i++){
                $str .= "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            global $$var;
            $str .= $var.' ==> ';
            $str .= ss_as_string($$var, column+1)."<BR>\n";
        }
        for ($i = 0; $i < $column; $i++){
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        return $str.')';
    }
}

/********************************************************
*********************************************************/
function ss_as_string (&$thing, $column = 0) {
    if (is_object($thing)) {
        return ss_object_as_string($thing, $column);
    }
    elseif (is_array($thing)) {
        return ss_array_as_string($thing, $column);
    }
    elseif (is_double($thing)) {
        return "Double(".$thing.")";
    }
    elseif (is_long($thing)) {
        return "Long(".$thing.")";
    }
    elseif (is_string($thing)) {
        return "String(".$thing.")";
    }
    else {
        return "Unknown(".$thing.")";
    }
}

/********************************************************
* Computes total space used by all files in the given 
* directory
*
* Arguments: directory path
* Returns: -1 if failed, or the total in Mg
*********************************************************/
function dir_size($dirpath)
{
  // recurse through all subdirectories, sizing each
  //
  $total = 0;

  // we don't quite know how to do this yet...

  return($total);
}

/*******************************************************
* Format from mm/dd/yyyy to yyyy-mm-dd
* MH - 08/17/10
*******************************************************/
function format_mysql_date($orig_date, $sep="/")
{
   $arr_dates = explode($sep, $orig_date);
   $mysql_date = $arr_dates[2]."-".$arr_dates[0]."-".$arr_dates[1];

   return $mysql_date;
}

/*******************************************************
* To split MySQL date format (YYYY-MM-DD)
* MH - 05/19/01
*******************************************************/
function split_mysql_date($mysql_date, &$date_yr, &$date_mo, &$date_day)
{
   $date_array = explode("-",$mysql_date);
   $date_yr = $date_array[0];
   $date_mo = $date_array[1];
   $date_day = $date_array[2];
}

/*******************************************************
* To split MySQL military time format (HR:MIN:SEC)
* MH - 05/19/01
* RP - 04/03/09 Updated Noon-hour AM/PM issue
*******************************************************/
function split_mysql_time($mysql_time, &$hr, &$min, &$sec, &$ampm)
{
   /*****************************************
   $time_array = split(":",$mysql_time);
   $hr = $time_array[0];
   $min = $time_array[1];
   $sec = $time_array[2];

   if ($hr > 12)
   {
      $hr = $hr - 12;
      $ampm = "PM";
   }
   else
   {
      $ampm = "AM";
   }
   *****************************************/
   $time_array = explode(":",$mysql_time);
   $hr = $time_array[0];
   $min = $time_array[1];
   $sec = $time_array[2];

   if ($hr >= 12)
   {
      $hr = ($hr==12) ? $hr : $hr - 12;
      $ampm = "PM";
   }
   else
   {
      $hr = ($hr==0) ? 12 : $hr;
      $ampm = "AM";
   }   

   if (strlen($hr) == 1)
      $hr = "0".$hr;
   if (strlen($min) == 1)
      $min = "0".$min;
}

/*************************************************************
* Format military time into regular format
**************************************************************/
function format_ampm($mil_time)
{
   $arr_hr_min = explode(":", $mil_time);
   $hr = $arr_hr_min[0];
   $min = $arr_hr_min[1];

   if ($hr > 12)
   {
      $hr = $hr - 12;
      $ampm_time = $hr.":".$min." PM";
   }
   else
      $ampm_time = $hr.":".$min." AM";

   return $ampm_time;
}

/*******************************************************
* Log message 
* MH - 05/31/01
*
* Arguments: msg, logfile
* Retruns:
* changed $date_time to work better with PHP5
********************************************************/
function logMsg($msg, $logfile)
{
   global $logdir;

   $logfile = $logdir."/".$logfile;

   //Get current date time
   //$today = getdate();
   //$date_time = $today[mon]."/".$today[mday]."/".$today[year]." ".$today[hours].":".$today[minutes];
   $date_time = date("m/d/Y H:i:s"); 
   
   //Get IP address
   $ip = getenv ("REMOTE_ADDR");

   //Build message
   $msg = $date_time." - ".$ip.": ".$msg."\n";

   //Write the log
   $fp = fopen($logfile, "a");
   fwrite($fp, $msg);
   fclose($fp);
}

function logMsg2($msg, $logfile, $ldir=false)
{
   //logdir should be defined in the sitevar file
   global $logdir;

   if (!$ldir) $logdir = $ldir;

   $logfile = $logdir."/".$logfile;

   //Get current date time
   //$today = getdate();
   //$date_time = $today[mon]."/".$today[mday]."/".$today[year]." ".$today[hours].":".$today[minutes];
   $date_time = date("m/d/Y H:i:s");

   //Get IP address
   $ip = getenv ("REMOTE_ADDR");

   //Build message
   $msg = $date_time." - ".$ip.": ".$msg."\n";

   //Write the log
   $fp = fopen($logfile, "a");
   fwrite($fp, $msg);
   fclose($fp);
}

/*******************************************************
* Encrypt the data and returns the encrypted data 
* MH - 03/04/03
*
* Arguments: key, text string
* Retruns: encrypted data
********************************************************/
function is_encrypt($key, $str_text)
{
   //return($str_text);

   //Open module, and create initialization vector
   $td = mcrypt_module_open ('des', '', 'ecb', '');
   $key = substr ($key, 0, mcrypt_enc_get_key_size ($td));
   $iv_size = mcrypt_enc_get_iv_size ($td);
   $iv = mcrypt_create_iv ($iv_size, MCRYPT_RAND);

   //Initialize encryption handle
   if (mcrypt_generic_init ($td, $key, $iv) != -1) {

      //Encrypt data
      $c_t = mcrypt_generic ($td, $str_text);
      mcrypt_generic_deinit ($td);

      //Clean up
      mcrypt_module_close ($td);
  }

  return(trim($c_t));
}

/*******************************************************
* Decrypt the data and returns the decrypted data 
* MH - 03/04/03
*
* Arguments: $key that you encrypted with $c_t
* Retruns: Decrypted data
********************************************************/
function is_decrypt($key, $c_t)
{
   //return($c_t);

   //Open module, and create initialization vector
   $td = mcrypt_module_open ('des', '', 'ecb', '');
   $key = substr ($key, 0, mcrypt_enc_get_key_size ($td));
   $iv_size = mcrypt_enc_get_iv_size ($td);
   $iv = mcrypt_create_iv ($iv_size, MCRYPT_RAND);

   //Initialize encryption handle
   if (mcrypt_generic_init ($td, $key, $iv) != -1) {
      //Reinitialize buffers for decryption
      mcrypt_generic_init ($td, $key, $iv);
      $p_t = mdecrypt_generic ($td, $c_t);

      //Clean up
      mcrypt_generic_deinit ($td);
      mcrypt_module_close ($td);
   }

   return (trim($p_t));
}

/*******************************************************
* Encrypt the data and returns the encrypted data 
********************************************************/
function ms_encrypt($str_text)
{
   $str_encrypt = base64_encode($str_text);
   return $str_encrypt;
}

/*******************************************************
* Decrypt the data and returns the decrypted data 
********************************************************/
function ms_decrypt($str_text)
{
   $str_decrypt = base64_decode($str_text);
   return $str_decrypt;
}

/***************************************************************
* Make full name with a proper spacing depending on middle name
* MH - 05/28/03
*
* Arguments: $fname, middle, lname
* Retruns: Full Name
****************************************************************/
function format_fullname($fname, $middle, $lname, $suffix="")
{
   $full_name = ucwords(trim($fname)." ".trim(trim($middle)." ".trim($lname)));
   if ($suffix != "")
      $full_name = $full_name.", ".$suffix;
   return($full_name);
}

/*******************************************************
* To split full name into first, middle, last
* This function is not bullet proof with foreign names
* MH - 06/04/09
*******************************************************/
function split_fullname($fullname, &$fname, &$middle, &$lname)
{
   $arr_names = split(" ",$fullname);
   $fname = $arr_names[0];
   if (count($arr_names) == 2)
   {
      //i.e., no middle
      $middle = "";
      $lname = trim($arr_names[1]);
   }
   else
   {
      //Concatinate any words after middle to lname
      $middle = trim($arr_names[1]);
      for ($i=2; $i<count($arr_names); $i++)
      {
         $lname .= trim($arr_names[$i]);
      }
   }
}

/***************************************************************
* Make address with a proper format depending on the address2
* MH - 05/28/03
*
* Arguments: address1, address2, city, state, zip, sep(<br> or \n)
* Retruns: Formatted address string
****************************************************************/
function format_address($address1, $address2, $city, $state, $zip, $sep="<br>")
{
   $address = "";
   if (trim($address1) != "")
   {
      $address .= $address1.$sep;
      if (trim($address2) != "")
         $address .= $address2.$sep;
   }
   if(trim($city) != "")
      $address .= "$city, ";
   if(trim($state) != "" || trim($zip) != "")
      $address .= "$state $zip";
      
   return($address);
}

/***************************************************************
* Format the 10-digit phone/fax number to (xxx)xxx-xxxx
* MH - 05/28/03
*
* Arguments: phone (w/o spaces)
* Retruns: Formatted address string
****************************************************************/
function format_phone_number($phone)
{
   //Take the dashes out first
   if ($phone != "")
   {
      $phone = ereg_replace("-", "", $phone);
      $phone1 = substr($phone, 0, 3);
      $phone2 = substr($phone, 3, 3);
      $phone3 = substr($phone, 6, 4);
      $phone = "($phone1) $phone2-$phone3";
   }
   return($phone);
}

/***************************************************************
* Format the SSN from xxxxxxxxx to xxx-xx-xxxx
* MH - 08/01/03
*
* Arguments: SSN (w/o spaces and dashes)
* Retruns: Formatted SSN string
****************************************************************/
function format_ssn($ssn)
{
   $ssn = substr($ssn, 0, 3)."-".substr($ssn, 3, 2)."-".substr($ssn, 5, 4);
   return($ssn);
}

/***************************************************************
* Set a cookie
* MH - 06/17/03
*
* Arguments: cookie name, cookie value, expired time in sec, path on the server, 
*            cookie domain, flag to set only in secured connection
* Retruns: Just set the cookie
****************************************************************/
function is_setcookie ($name, $value, $expire=0, $path="/", $domain, $secure=0)
{
   setcookie($name, $value, $expire, $path, $domain, $secure);
}
/******************
* Formats given date in given format
* GK - 10/10/03
*
* Arguments: date string (almost any format)
*            return format (from php date() function syntax)
* Returns formated date string
*******************/

function format_date($dt)
{
	return(substr($dt,5,2) . "/" . substr($dt,-2) ."/" .  substr($dt, 0, 4));
}

/***************************************************************
* Difference between two given dates
* MH - 05/07/04
*
* Arguments: date1, date2 in (YYYY-MM-DD format) 
* Retruns: number of days
****************************************************************/
function date_diff2 ($date1, $date2)
{
   $sql = "SELECT (TO_DAYS('$date1')-TO_DAYS('$date2')) AS diff;";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $diff = $qryResult[diff];

   return ($diff);
}

/***************************************************************
* Difference between two given times
* MH - 05/07/04
*
* Arguments: time1, time2 in (YYYY-MM-DD HH:MM:SS format) 
* Retruns: time
****************************************************************/
function time_diff ($time1, $time2)
{
   $sql = "SELECT TIMEDIFF('$time1', '$time2') AS diff;";
   $GetQuery = dbi_query($sql);
   $qryResult = $GetQuery->fetch_assoc();
   $diff = $qryResult[diff];

   return ($diff);
}

/***************************************************************
* Get query string value and strips the suspected characters
****************************************************************/
function get_query_string($var)
{
    if(is_array($_GET[$var])) {
        return $_GET[$var];
    }
   $value = $_GET[$var];
   $value = ereg_replace("<", "", $value);
   $value = ereg_replace(">", "", $value);

   return ($value);
}

?>
