<?php
// test_post.php
// WEL 1/13/18
require_once '../utilities.php';
require_once 'functions.php';

get_proc_info('b3bce54b-23b1f8f7-6edadd0e-b5a6ba0a');
exit();

$p=get_query_string('p');
get_address_by_postcode($p);

?>
