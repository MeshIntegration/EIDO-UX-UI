<?php 
for ($i=2018; $i>=1900; $i--)
   echo "<option value=\"$i\" <?php if (\$dob_year==\"$i\") echo \"selected\" ?>>$i</option>";

?>
