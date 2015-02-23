<?php
include("db_connection.php");
     #Login check
if(isset($_SESSION['logged_in'])) {
    # Check hash 
    $query_str = sprintf("SELECT user_hash FROM users WHERE user_id='%s' LIMIT 1", mysql_real_escape_string($_SESSION['user_id']));
    $query = mysql_query($query_str);

    $data = mysql_fetch_assoc($query);

    $session_hash = $_SESSION['hash'];
    $db_hash = $data['user_hash'];

    if($session_hash == $db_hash) {
        header("Location: welcome.php"); exit();
    }
    else {
        session_unset();
    }
}
?>