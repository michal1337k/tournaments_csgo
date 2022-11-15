<html lag="pl">
<head>
<meta charset="UTF-8">
<?php 
    require 'steamauth/steamauth.php';
?>
</head>
<body>
    <?php
    $_SERVER['HTTPS']='on';
    if(!isset($_SESSION['steamid'])) {
        loginbutton(); //login button
    }  else {
        include ('steamauth/userInfo.php'); //To access the $steamprofile array
        header("Location: main.php");
    }     
    ?>
</body>
</html>