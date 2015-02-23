<?php
    session_start();
    $storage_path = getcwd()."\\images\\";
    $langs = array("en", "ru");
    # Setup langauge and get appropriate messages storage
  	if (isset($_GET['lang'])) 
    {
      $lang = trim($_GET['lang']);  
    }
    else if (isset($_COOKIE['lang'])) 
    {
      $lang = trim($_COOKIE['lang']);
    }
    if (in_array($lang, $langs)) 
    {
      #specific language 
      require 'includes/locale/'.$lang.'/messages.php';
      setcookie('lang', $lang);
    }
    else 
    {
      #default locaion file
      require 'includes/locale/en/messages.php';   
      setcookie('lang', 'en');
    }

    if(isset($_GET['logout'])) 
    {
      session_unset();
      header("Location: index.php"); 
      exit();
    }

    # function to check text field on empty, number of letters and length
    # $field  - field to check
    # $field_name - name of checked field
    # $messages - message storage
    function checkTextField($field, $field_name, $messages) 
    {
      if (!isset($field))
      {
        return $messages['error.'.$field_name.'.empty'];
      }
      if(!preg_match("/^[a-zA-Z0-9]+$/", $field))
      {
        return $messages['error.'.$field_name.'.letters'];
      }
      if(strlen($field) < 3 or strlen($field) > 30)
      {  
        return $messages['error.'.$field_name.'.length'];
      }
      return null;
    }

    #function to generate code
    function generateCode($length=6) 
    {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

      $code = "";
      $clen = strlen($chars) - 1;  
      while (strlen($code) < $length) 
      {
        $code .= $chars[mt_rand(0,$clen)];  
      }
      return $code;
    }


    #general log if exist
    function log_message($data, $file)
    { 
      $fh = fopen($file, 'a') or die("can't open file");
      fwrite($fh,$data."\n");
      fclose($fh);
    };

    #error log
    function log_error_message($data) 
    {
      $file = "logs/errors.log";
      log_message($data, $file);
    };

     #debug log
    function log_debug_message($data) 
    {
      $file = "logs/debug.log";
      $data = date('Y-m-d  h:m:s').$data;
      log_message($data, $file);
    };
	?>