<?php
  include("header.php");
  include("logged_in_check.php");

if(isset($_POST['submit']))

{
       # check login
    $err['login'] = checkTextField($_POST['login'], 'login', $msg);
    #check password
    $err['password'] = checkTextField($_POST['password'], 'password', $msg);
    $err = array_filter($err);
    if (empty($err))
    {
        # Get user from database with equals user_login field
        mysql_query("SET NAMES utf8"); 
        $query_str = sprintf("SELECT user_id, user_password, user_name, user_surname, user_image FROM users WHERE user_login='%s' LIMIT 1", mysql_real_escape_string($_POST['login']));
        $query = mysql_query($query_str);

        $data = mysql_fetch_assoc($query);
        # Compare passwords
        if($data['user_password'] === md5(md5($_POST['password'])))
        {
            # Generate random hash
            $hash = md5(generateCode(10));     
            # Update user with hash code
            $result = mysql_query("UPDATE users SET user_hash='".$hash."' WHERE user_id='".$data['user_id']."'");
            # Save session parameters
            $_SESSION['user_name'] = $data['user_name'].' '.$data['user_surname'];
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['logged_in'] = true;
            $_SESSION['hash'] = $hash;
            $_SESSION["image_name"] = $data['user_image'];
        
            # Redirect to welcome page
            if ($result) {
                header("Location: welcome.php"); exit();
            }
            else {
                log_error_message(mysql_error());
            }
        }
        else
        {
            $err['general'] = $msg['error.wrong.logn'];
        }
    }
    else 
    {
        $err['general'] = $msg['error.login.general'];
    }
}
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$msg['index.title'];?></title>
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
        <form method="POST" onsubmit="return validateLoginForm();" name="login_form">
            <h1><?=$msg['index.header'];?></h1>
            <span class="head_error" id="general_error"></span>
            <div>
                <span id="login_error" class="error"></span>
                <input type="text" placeholder="<?=$msg['index.login'];?>" id="login" name="login" />
            </div>
            <div>
                <span id="password_error" class="error"></span>
                <input type="password" placeholder="<?=$msg['index.password'];?>" id="password" name="password" />
            </div>
            <div>
                <input type="submit" name="submit" value="<?=$msg['index.submit'];?>" />
                <a href="registration.php"><?=$msg['index.register.link'];?></a>
            </div>
        </form><!-- form -->
    </section><!-- content -->
</div><!-- container -->
</body>
</html>