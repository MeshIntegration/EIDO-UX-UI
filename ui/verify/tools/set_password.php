<?php
// set_password.php
// set a password for a user

include "../utilities.php";

// hard code these and run from the command line:  "php set_password.php"
$username = "wayne@mindstreams.com";
$password = "password";

$hash = password_hash($password, PASSWORD_BCRYPT);
if (!password_verify($password, $hash)) {
   echo "\nInvalid hash generation\n\n";
   exit;
}

$sql = "UPDATE dir_user
        SET password='$hash',
            uipassword='$hash'
        WHERE username='$username'
        AND active=1";
dbi_query($sql);
$ct=dbi_affected_rows();
echo "\n$ct users updated\n\n";

?>
