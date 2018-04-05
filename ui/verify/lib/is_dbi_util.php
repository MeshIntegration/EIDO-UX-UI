<?php

/*******************************************************
* is_dbi_util.php
* Copyright 2006, IdeaStar Inc.
* Database Access Utilities
*
* A MySQL Improved version of is_db_util.php
* created 2006/05/04 JO
********************************************************/

// note: this should be moved to the php utilities folder

/*
class is_db_util{
    static $db_obj;
    
    
    //static function provides a database object using a singleton pattern
    // * will create a new object of type
    
    static function get_db_obj(){
        if (is_object(is_db_util::$db_obj)) {
        	return is_db_util::$db_obj;
        }
    }
}
*/

// Create a new mysqli object, this will have to be used as a global (yuck!)
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
$mysqli->select_db(DB_NAME);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/**
 * Preforms an MySQLi query and returns a result object
 *
 * @param String $sql
 * @return MySQLi result object
 */
function dbi_query($sql){
    global $mysqli; // an OO database object
    global $arr_mysql_error; // tells us what to do if we encounter an error
    $callback = $arr_mysql_error['callback'];

    if ($callback && function_exists($callback)){
        if ($qry = $mysqli->query($sql)){
            return($qry);
        }else{
            $callback($sql, $mysqli->errno, $mysqli->error);
        }
    }else{
        return $mysqli->query($sql);
    }

    //printf("Host information: %s\n", $mysqli->host_info);
    if(!$result = $mysqli->query($sql)){
        printf("Error: %s\n", $mysqli->error);
    }
    return $result;
}

/**
 * Returns an insert id for the last query
 *
 * @return int
 */
function dbi_insert_id(){
    global $mysqli;
    return $mysqli->insert_id;
}

/**
 * Returns an number of affected rows for the last query
 *
 * @return int
 */
function dbi_affected_rows(){
    global $mysqli;
    return $mysqli->affected_rows;
}

/********************************************************
* db_insert inserts a blank record into the specified
* table and returns the new id of that record.
*
* Arguments: database table name
* Returns: id value of the inserted record
*********************************************************/
function dbi_insert($table, $id_field)
{
  $sql = "INSERT INTO ".$table." (".$id_field.") VALUES (0);";
  dbi_query($sql);
  return dbi_insert_id();
}

/**
 * wrapper for the mysqli_real_escape_string() method
 * escapes unwanted characters from $string
 *
 * @param string $string
 * @return string
 * @author JO 2006-08-28
 */
function dbi_escape($string){
	global $mysqli;
    return $mysqli->real_escape_string($string);
}

?>
