<?php 
include "/var/www/html/ui/verify/utilities.php";
$sql="ALTER TABLE dir_user DROP COLUMN c_organizationId";
dbi_query($sql);
$sql="ALTER TABLE dir_organization DROP COLUMN parentId";
dbi_query($sql);
$sql="ALTER TABLE app_fd_ver_patientEpisodes DROP COLUMN c_hospitalId";
dbi_query($sql);
$sql="ALTER TABLE app_fd_ver_organizations DROP COLUMN c_type,
                                           DROP COLUMN c_levela,
                                           DROP COLUMN c_firstUser";
dbi_query($sql);
$sql="ALTER TABLE app_fd_ver_surgeons DROP COLUMN c_organizationId,
                                      DROP COLUMN c_user_id";
dbi_query($sql);
$sql="ALTER TABLE app_fd_ver_surveys  DROP COLUMN c_id
                                      DROP COLUMN c_prePost";
dbi_query($sql);
exit();
?>
