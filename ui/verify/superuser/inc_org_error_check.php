<?php
// used for add and update org 
   //  check for required fields and formats here
   if ($name=="")
      $_SESSION['add_orgname_error']=true; else $_SESSION['add_orgname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$name))
      $_SESSION['add_orgname_format_error']=true; else $_SESSION['add_orgname_format_error']=false;
   // account owner
   if ($fname=="")
      $_SESSION['add_fname_error']=true; else $_SESSION['add_fname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$fname))
      $_SESSION['add_fname_format_error']=true; else $_SESSION['add_fname_format_error']=false;
   if ($lname=="")
      $_SESSION['add_lname_error']=true; else $_SESSION['add_lname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$lname))
      $_SESSION['add_lname_format_error']=true; else $_SESSION['add_lname_format_error']=false;
   if ($email=="")
      $_SESSION['add_email_error']=true; else $_SESSION['add_email_error']=false;
   if ($email<>"" && !filter_var($email, FILTER_VALIDATE_EMAIL))
      $_SESSION['add_bad_email_error']=true; else $_SESSION['add_bad_email_error']=false;

   // first admin
   if ($admin_fname=="")
      $_SESSION['add_admin_fname_error']=true; else $_SESSION['add_admin_fname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$admin_fname))
      $_SESSION['add_admin_fname_format_error']=true; else $_SESSION['add_admin_fname_format_error']=false;
   if ($admin_lname=="")
      $_SESSION['add_admin_lname_error']=true; else $_SESSION['add_admin_lname_error']=false;
   if (!preg_match("/^[a-zA-Z' -]*$/",$admin_lname))
      $_SESSION['add_admin_lname_format_error']=true; else $_SESSION['add_admin_lname_format_error']=false;
   if ($admin_email=="")
      $_SESSION['add_admin_email_error']=true; else $_SESSION['add_admin_email_error']=false;
   if ($admin_email<>"" && !filter_var($admin_email, FILTER_VALIDATE_EMAIL))
      $_SESSION['add_bad_admin_email_error']=true; else $_SESSION['add_bad_admin_email_error']=false;

   if ($type=="")
      $_SESSION['add_type_error']=true; else $_SESSION['add_type_error']=false;
   // check logo file
   if ($_FILES['header_logo']['name']<>"")
   {
      logMsg($_FILES['header_logo']['name'], $logfile);
      if ($_FILES['header_logo']['error'] > 0)
      {
         $_SESSION['add_logo_error']=true;
         logMsg("File Upload Error: ".$_FILES['header_logo']['error'], $logfile);
      }
      else
      {
         $_SESSION['add_logo_error']=false;
         $loc = strrpos($_FILES['header_logo']['name'], ".");
         $ext = strtolower(substr($_FILES['header_logo']['name'], $loc+1));
         if (!in_array($ext, $arr_ext))
            $_SESSION['add_logo_type_error']=true; else $_SESSION['add_logo_type_error']=false;
      }
   }
?>
