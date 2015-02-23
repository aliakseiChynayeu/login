<?php
include("header.php");
if (empty($_SESSION['user_name']))
{
	$user = $msg['user.unnamed'];
}
else 
{
	$user = $_SESSION['user_name'];
}

$user_id = $_SESSION['user_id'];
$logged_in = $_SESSION['logged_in'];
$hash = $_SESSION['hash'];
$image_path = "images/".$_SESSION["image_name"];
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?=$msg['welcome.title'];?></title>
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
            <h1><?=$msg['welcome.header'];?></h1>
            <div>
                <?= $user;?>
            </div>
            <div>
                <br>
                <br>
                <br>
            </div>
             <div>
                <?=$msg['welcome.user.id'];?> : <?= $user_id;?>
                <br>
            </div>
             <div>
                <?=$msg['welcome.hash'];?> : <?= $hash;?>
                <br>
            </div>
            <br><br>
            <img src="<?= $image_path;?>" style="height:auto; width:auto; max-width:150px; max-height:150px;"></img>
            <br>  <br>  
            <div>
                <a href="?logout=true"><?=$msg['welcome.back'];?></a>
            </div>
             <div>
                <br>
                <br>
                <br>
            </div>
    </section><!-- content -->
</div><!-- container -->
</body>
</html>