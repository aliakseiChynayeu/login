<?php
include("header.php");
include("logged_in_check.php");

$validImageTypes = array("image/png", "image/gif", "image/jpg");
 

if(isset($_POST['submit']))
{
	$imageType = $_FILES["image"]["type"];
	$imageName = $_FILES["image"]["name"];
	$imageSize = $_FILES["image"]["size"];
	#check image type
	if (!in_array($imageType, $validImageTypes)) 
	{
  		$err['image'] = $msg['error.image.wrong.type'];
	}
	#check image size
	if($imageSize > 1024*3*1024) 
	{
		$err['image'] = $msg['error.image.wrong.size'];
	}
    if(strlen($imageName) > 30) 
    {
        $err['image'] = $msg['error.image.wrong.name'];
    }
    # check login
    $err['login'] = checkTextField($_POST['login'], 'login', $msg);
    #check name
    $err['name'] = checkTextField($_POST['name'], 'name', $msg);
    #check surname
    $err['surname'] = checkTextField($_POST['surname'], 'surname', $msg);
    #check password
    $err['password'] = checkTextField($_POST['password'], 'password', $msg);

	$err = array_filter($err);    
    if(empty($err)) 
    {
	    # check is already exist
    	$query_str = sprintf("SELECT COUNT(user_id) FROM users WHERE user_login='%s'", mysql_real_escape_string($_POST['login']));

    	$query = mysql_query($query_str);
    	if(mysql_result($query, 0) > 0)
    		{
        		$err['login'] = $msg['error.user.exist'];
    		}
    }
    $err = array_filter($err);
    # No errors
    if(empty($err))
    {   
        
        $login = $_POST['login'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $password = md5(md5(trim($_POST['password'])));   
        // Chech is file uploaded
        if(is_uploaded_file($_FILES["image"]["tmp_name"]))
        {
            #Move file from temp dir to folder
            $file_name = date('Y-h-m-s')."_".$login."_".$_FILES["image"]["name"];
            $file_path = $storage_path.mb_convert_encoding($file_name, 'Windows-1251', 'UTF-8');
            move_uploaded_file($_FILES["image"]["tmp_name"], $file_path);
        } 
        else 
        {
            log_error_message("unable to upload file to ".$file_path." for user ".$login);
        }

        mysql_query('SET NAMES utf8');
        $query_insert = sprintf("INSERT INTO users SET user_login='%s', user_password='%s', user_name='%s', user_surname='%s', user_image='%s'", $login, $password, $name, $surname, mysql_real_escape_string($file_name));

        $result = mysql_query($query_insert);

        if ($result) 
        {
        	log_debug_message(' user with login ='.$login." successfully added");
        	#select user back to populate fields with hash and 
        	$query_str = sprintf("SELECT user_id, user_password, user_name, user_surname FROM users WHERE user_login='%s' LIMIT 1", mysql_real_escape_string($_POST['login']));
        	$query = mysql_query($query_str);
	        $data = mysql_fetch_assoc($query);

	        $hash = md5(generateCode(10));     
            # Update user with hash code
            $result = mysql_query("UPDATE users SET user_hash='".$hash."' WHERE user_id='".$data['user_id']."'");
            # Save session parameters
            $_SESSION['user_name'] = $data['user_name'].' '.$data['user_surname'];
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['logged_in'] = true;
            $_SESSION['hash'] = $hash;
        	$_SESSION["user_name"] = $name.' '.$surname;
            $_SESSION["image_name"] = $file_name;

        	header("Location: welcome.php"); 
        	exit();
        }
        else 
        {
        	log_error_message(mysql_error());
        	$err['general'] = $msg['error.registrate.db'];
            unlink($file_path);
        }
    }
    else
    {
        $err['general'] = $msg['error.registrate.general'];
    }
}
?>
 
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$msg['registration.title'];?></title>
<link rel="stylesheet" type="text/css" href="includes/css/style.css" />
<script type="text/javascript" src="includes/js/login.js" ></script>
<script type="text/javascript">
    var messages = <?php echo json_encode($msg);?>;
    var errors = <?php echo json_encode($err);?>;
</script>
</head>
<body onLoad="onBodyLoad();">
<div class="container">
    <section id="content">
    	<?php include("language.php"); ?>
        <form method="post" onsubmit="return validateRegistrationForm();" enctype="multipart/form-data">
            <h1><?=$msg['registration.header'];?></h1>
            <div>
            	<span class="head_error" id="general_error"></span>
            </div>	
            <div>
            	<span id="login_error" class="error">&nbsp;</span>
                <input type="text" placeholder="<?=$msg['registration.login']; ?>" id="login" name="login" />
            </div>
            <div>
            	<span id="password_error" class="error">&nbsp;</span>
                <input type="password" placeholder="<?=$msg['registration.password']?>" id="password" name="password" />
            </div>
             <div>
             	<span id="name_error" class="error">&nbsp;</span>
                <input type="text" placeholder="<?=$msg['registration.name']?>" id="name" name="name"/>
            </div>
             <div>
             	<span id="surname_error" class="error">&nbsp;</span>
                <input type="text" placeholder="<?=$msg['registration.surname']?>" id="surname" name="surname" />
            </div>
            <div class="button">
            	<span id="image_error" class="error">&nbsp;</span>
            	<input type="FILE" name="image" id='image'>
			</div>
            <div>
                <input type="submit" name="submit" value="<?=$msg['registration.register']?>" />
                 <a href="index.php"><?=$msg['registration.already.registered'];?></a>
            </div>
        </form><!-- form -->
    </section><!-- content -->
</div><!-- container -->
</body>
</html>