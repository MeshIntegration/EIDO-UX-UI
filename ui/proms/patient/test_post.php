<?php
// test_post.php
// WEL 1/13/18
require_once '../utilities.php';

$p=get_query_string('p');
require_once 'functions.php';
get_address_by_postcode($p);

?>
