<?php
# Database connection
# mysql_connect("localhost", "login_admin", "VVBMrsxaDXPTTuDe");
include("header.php");
 mysql_connect("localhost", "admin", "");
mysql_select_db("login");



if(isset($_POST['submit']))

{
    $err = array();
    # check login
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))

    {

        $err[] = $msg['error.login.letters'];

    }

     if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)

    {

        $err[] = $msg['error.login.length'];

    }
    # check is already exist

    $query = mysql_query("SELECT COUNT(user_id) FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."'");

    if(mysql_result($query, 0) > 0)

    {
        $err[] = $msg['error.user.exist'];
    }

    # No errors

    if(count($err) == 0)

    {    
        $login = $_POST['login'];
        $password = md5(md5(trim($_POST['password'])));   

        mysql_query("INSERT INTO users SET user_login='".$login."', user_password='".$password."'");

        header("Location: login.php"); exit();

    }

    else
    {

        print $msg['error.login.general'];

        foreach($err AS $error)

        {

            print $error."<br>";

        }

    }

}

?>
<form method="POST">

Логин <input name="login" type="text"><br>

Пароль <input name="password" type="password"><br>

<input name="submit" type="submit" value="Зарегистрироваться">

</form>