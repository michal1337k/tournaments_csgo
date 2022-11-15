<html>
<head>
<link rel="stylesheet" href="css/style.css" />   
</head>
<body>
<?php
require "db/dbconn.php";
require 'steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {
    header("Location: index.php");
}  else {
if (isset($_GET['id'])) {
    include ('steamauth/userInfo.php');
    include ('parts/menu.php');
    $id  = $_GET['id'];
    $res = mysqli_query($db_conn, "SELECT users.id, users.is_captain, users.nickname, team.nazwa, team.img FROM users, team WHERE users.team_id = team.id AND team.id = '$id';");
    $row = mysqli_fetch_array($res);
    if(isset($row['nazwa'])){
        echo "<div class='profilearea'>";
        if(!$row['img'])
        {
            $img = "<img src='../img/pyt.png' class='teamimg' /><br>";
        }
        else {
            $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' class='teamimg' /><br>";
        }
        echo "<div class='teamdata'><b class='daneteamu'>".$row['nazwa']."<hr class='hr1'></b><h3>$img</h3></div>";
        $kpt = mysqli_query($db_conn, "SELECT users.nickname FROM users, team WHERE users.team_id = team.id AND team.id = '$id' AND users.is_captain = 1;");
        $cptrow = mysqli_fetch_array($kpt);
        echo "<div class='teamdata'><b class='daneteamu'>Członkowie<hr class='hr1'></b></div>";
        echo "<p class='kapitan'><a href=profile.php?id=".$row['id'].">".$cptrow['nickname']."</a></p>";
        while($row = mysqli_fetch_array($res)){
            echo "<div class='member'><a href=profile.php?id=".$row['id'].">".$row['nickname']."</a></div>";
        }
    echo "</div>";
    }
   else{
    header("Location: index.php");
   }
}
}
?>
</body>
</html>

