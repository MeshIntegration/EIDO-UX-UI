<?php
/*
 * lib/funcions.php 
 * Copyright 2018, Mesh integration, LLC
 * WEL/SD - 1/7/18
 */

/**
 * generate pagination html
 * @param int $page current page
 * @param int $totalRecord list of records in the list
 * @return string html content for pagination
 */
function get_pagination($page, $totalRecord) {
   global $row, $page, $pageLimit, $pageWindow;

   $totalPage = ceil($totalRecord / $row);
   $pagination = "<div class='small-12 large-12 cell'><ul class='pagination pagination-centered' role='menubar' aria-label='pagination'>";

   if ($page == 1) {
      $pagination .= "<li class='unavailable'><a>Prev</a></li>";
   } else {
      $link = "?page=" . ($page - 1);
      $pagination .= "<li class='arrow'><a href='" . $link . "'>Prev</a></li>";
   }
   if ($totalPage > $pageLimit) {
      $paginationStack = array();
      for ($i = 1; $i <= $totalPage; $i++) {
         if ($page == $i) {
            $paginationStack[] = "<li class='current'><a>" . $i . "</a></li>";
         } else {
            $paginationStack[] = "<li><a href='?page=$i'>" . $i . "</a></li>";
         }
      }
      $currentPageWindow = $pageWindow;
      $newPaginationStack = array();
      for ($i = 0; $i < $currentPageWindow; $i++) {
         $index = ($page - (($pageWindow / 2) - $i));
         if (!empty($paginationStack[$index])) {
            $newPaginationStack[] = $paginationStack[$index];
            $nextPage = $i + 1;
         }
      }
      if (($index + 1) < $totalPage) {
         $newPaginationStack[] = "<li><a href='?page=$nextPage'>...</a></li>";
         $newPaginationStack[] = "<li><a href='?page=$totalPage'>" . $totalPage . "</a></li>";
      } else {
         array_unshift($newPaginationStack, "<li><a href='?page=$i'>...</a></li>");
         array_unshift($newPaginationStack, "<li><a href='?page=1'>1</a></li>");
      }
      $pagination .= implode("", $newPaginationStack);
   } else {
      for ($i = 1; $i <= $totalPage; $i++) {
         if ($page == $i) {
            $pagination .= "<li class='current'><a>" . $i . "</a></li>";
         } else {
            $pagination .= "<li><a href='?page=$i'>" . $i . "</a></li>";
         }
      }
   }
   if ($page == $totalPage) {
      $pagination .= "<li class='unavailable'><a>Next</a></li>";
   } else {
      $link = "?page=" . ($page + 1);
      $pagination .= "<li class='arrow'><a href='" . $link . "'>Next</a></li>";
   }
   $pagination .= "</ul></div>";
   return $pagination;
}

/*************************************************************
 * get API response
 * @param string $URL
 * @param array $requestParam
 * @param boolean $is_url_custom set to true (1) if using a URL other 
                  than the BASE_URL as the path to the URL_method
 * @param string $requestType - can be "GET" or "POST"
 * @param string $auth_type - DEFAULT, BASIC_AUTH, NONE
 * @param string $responseType - can be "OBJECT" or "JSON" 
 */
function getCurlResponse($URL_method, $requestParam, $is_url_custom = 0, $requestType = "GET", $auth_type = "DEFAULT", $responseType = "OBJECT", $loginas="") {
   global $USERNAME, $HASH, $BASE_URL, $PASSWORD;
   $logfile="wel.log";

   logMsg("Funcions: getCurlResponse: loginas: $loginas", $logfile);
   if ($loginas=="")
   {
      $auth = array(
        "j_username" => $USERNAME,
        "hash" => $HASH
      );
   }
   else
   {
      $auth = array(
        "j_username" => $USERNAME,
        "hash" => $HASH,
        "loginAs" => $loginas
      );
   }

   // Get cURL resource
   $curl = curl_init();
   if ($auth_type == "BASIC_AUTH") {
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($curl, CURLOPT_USERPWD, "$USERNAME:$PASSWORD");
      $param = $requestParam;
   } else if($auth_type == "NONE") {
      $param = $requestParam;
   } else {
      $param = array_merge($auth, $requestParam);
   }

   if ($requestType == "GET") {
      $param = array_map(function($key, $value) {
         return $key . "=" . $value;
      }, array_keys($param), array_values($param));
      if ($is_url_custom) {
         $URL = $URL_method . "?" . implode("&", $param);
      } else {
         $URL = $BASE_URL . $URL_method . "?" . implode("&", $param);
      }
   } elseif ($requestType == "POST") {
      // need to maintaint CURL post call
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
      if ($is_url_custom) {
         $URL = $URL_method;
      } else {
         $URL = $BASE_URL . $URL_method;
      }
   }
   logMsg("functions.php: GetCurlResponse: URL: $URL", $logfile);
   // echo "<br / >$URL<br />";
   // Set some options - we are passing in a useragent too here
   curl_setopt_array($curl, array(
     CURLOPT_RETURNTRANSFER => 1,
     CURLOPT_URL => $URL,
     CURLOPT_USERAGENT => 'EIDO Helper library',
   ));
   // Send the request & save response to $resp
   $resp = curl_exec($curl);

   if (curl_error($curl)) {
      $error = (object) (array('is_error' => 1, 'error' => curl_error($curl)));
      return $error;
   }
   if ($responseType == "OBJECT") {
      return $resp = json_decode($resp);
   } else {
      return $resp;
   }

   // Close request to clear up some resources
   curl_close($curl);
}
?>
