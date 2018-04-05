<?php

/*******************************************************
* is_html_util.php
* Copyright 2001, IdeaStar Inc.
* HTML Utilities
*
* PWH derived from inc.utilities.php
* Re-arranged 04/06/01 - MH
********************************************************/

/*******************************************************
* Outputs an HTML combobox to stdout
*
* Arguments: table is the Database Table to retrieve the
*            values from
*            edit_mode if view the simply echo the value
*                      if new then produce combo with nothing selected
*                      otherwise produce combo with current
*                      value selected
* Returns: nothing

* JO 2006-05-15: Updated for use with dbi_query

********************************************************/
function selectOptions($table,$edit_mode,$current)
{
  $currentArray = split(",",$current);
  if ($edit_mode == "View")
  {
    for ($i=0; $i<sizeof($currentArray); $i++)
    {
      //$optionQuery = db_query("SELECT label FROM ".$table." WHERE value='".$currentArray[$i]."'");
      $optionQuery = dbi_query("SELECT label FROM ".$table." WHERE value='".$currentArray[$i]."'");
      //$optionResult = db_fetch($optionQuery);
      $optionResult = $optionQuery->fetch_assoc();
      if ($i != 0) echo "<br>";
        echo $optionResult[label];
    }
  }
  else
  {
    $optionQuery = dbi_query("SELECT * FROM ".$table);
    if ($edit_mode == "New")
    {
      //while ($optionResult = db_fetch($optionQuery))
      while ($optionResult = $optionQuery->fetch_assoc())
        echo "\n<option value=\"".$optionResult[value]."\">".$optionResult[label]."</option>";
    }
    else
    {
      while ($optionResult = $optionQuery->fetch_assoc())
      {
        echo "\n<option value=\"".$optionResult[value]."\"";
        for ($i=0; $i<sizeof($currentArray); $i++)
        {
          if ($optionResult[value] == $currentArray[$i]) echo " SELECTED";
          echo ">".$optionResult[label]."</option>";
        }
      }
    }

    return;
  }
}

/*******************************************************
* Make drop down box for list of values
* MH - 05/06/01
* Arguments: table_name,
*            value,
*            label
*            default value
* Retruns: print out combo box
********************************************************/
/**
 * Make a drop-down list from a database table
 *
 * @param string $table_name
 * @param string $value	
 * @param string $label
 * @param string $default
 * @param string $where
 * @param string $order
 */
function make_combo($table_name, $value, $label, $default, $where="", $order="", $default_label="")
{
   if ($order == "")
      $order = " ORDER BY $value";

   $sql = "SELECT * FROM $table_name $where $order;";
// echo $sql;
   $optionQuery = dbi_query($sql);
   if (mysql_errno()) echo "<option value=\"\" selected>".mysql_error()."</option>";

   // WEL - 4/1/14 - added this so there is a blank at the top
   // lets you clear a drop down if you picked a value you didn't want
   echo "<option value=\"\"></option>";
   if ($default == "")
      echo "<option value=\"\" selected>$default_label</option>";
   else
      $selected = "";
   while ($optionResult = $optionQuery->fetch_assoc())
   {
      if ($optionResult[$value] == $default)
         $selected = "selected";
      else
         $selected = "";

      echo "<option value=\"$optionResult[$value]\" $selected>$optionResult[$label]</option>";
   } //end-loop

   return;
}

/*******************************************************
* Make check boxes for list of values
* MH - 05/08/01
* Arguments: table_name,
*            value,
*            label
*            default value in array
*            num of columns to display list of checkboxes
* Retruns: print checkboxes with field names checkbox1 to checkboxn
********************************************************/
function make_checkbox($table_name, $value, $label, $default, $column, $where="", $order="", $class="", $input_name="", $distinct=false)
{
   $checked = "";
   if ($order == "")
      $order = " ORDER BY $value";

   if ($distinct)
  	 $sql = "SELECT distinct $value, $label FROM $table_name $where $order;";
   else
  	 $sql = "SELECT * FROM $table_name $where $order;";
   $optionQuery = dbi_query($sql);

   $count = $optionQuery->num_rows;
   echo "<table class=\"$class\">";
   $j = 1;
   $i = $column + 1;
   while ($optionResult = $optionQuery->fetch_assoc())
   {
      if ($default != "")
      {
         $checked = "";
         for ($ii=0; $ii<count($default); $ii++)
         {
            if ($default[$ii] == $optionResult[$value])
               $checked = "checked";
         }
      }

      if ($i <= $column)
      {
         if ($input_name)
           echo "<td><input type=\"checkbox\" name=\"$input_name$j\" value=\"".$optionResult[$value]."\" $checked>&nbsp;".$optionResult[$label]."&nbsp;&nbsp;</td>";
         else
           echo "<td><input type=\"checkbox\" name=\"checkbox".$j."\" value=\"".$optionResult[$value]."\" $checked>&nbsp;".$optionResult[$label]."&nbsp;&nbsp;</td>";
      }
      else
      {
         $i = 1;
         if ($j > 1) echo "</tr>";
         echo "<tr>";
         if ($input_name)
           echo "<td><input type=\"checkbox\" name=\"$input_name$j\" value=\"".$optionResult[$value]."\" $checked>&nbsp;".$optionResult[$label]."&nbsp;&nbsp;</td>";
         else
           echo "<td><input type=\"checkbox\" name=\"checkbox".$j."\" value=\"".$optionResult[$value]."\" $checked>&nbsp;".$optionResult[$label]."&nbsp;&nbsp;</td>";
      }
      $i++;
      $j++;
   }
   $checkbox_total = $j - 1;
   echo "<input type=\"hidden\" name=\"checkbox_total\" value=\"$checkbox_total\">";
   echo "</table>";
}

?>
