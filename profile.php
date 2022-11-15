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
    $id  = $_GET['id'];
    include ('steamauth/userInfo.php');
    include ('parts/menu.php');
    /* ----------------- USER INFO ----------------- */
    $res = mysqli_query($db_conn, "SELECT users.steamid, users.nickname FROM users WHERE users.id = '$id'  LIMIT 1;");
    while($row = mysqli_fetch_array($res)){
        $usersteamid = $row['steamid'];
        echo "<div class='profilearea'><div class='userdata'><b class='daneteamu'>Dane użytkownika</b><hr class='hr1'> <p>".$row['nickname']."</p> <p>SteamID: <b class='steamidhash'>".$row['steamid']."</b></p></div>";
    }
    /* ----------------- TEAM INFO ----------------- */
    $team = mysqli_query($db_conn, "SELECT team.nazwa, team.id, team.img, users.is_captain FROM team, users WHERE team.id = users.team_id AND users.steamid = $usersteamid;");
    if($row = mysqli_fetch_assoc($team))
    {
        if(!$row['img'])
        {
            $img = "<img src='../img/pyt.png' class='teamimg' /><br>";
        }
        else {
            $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' class='teamimg' /><br>";
        }
        echo "<div class='teamdata'><b class='daneteamu'>Dane drużyny<hr class='hr1'></b><h3><a class='tnazwa' href=team.php?id=".$row['id'].">$img".$row['nazwa']."</a></h3></div>";
    }
    else
    {
        echo "<div class='teamdata'><b class='daneteamu'>Dane drużyny<hr class='hr1'></b><h3><p>Ten użytkownik nie posiada żadnej drużyny.</p></h3></div>";
    }
    /* ----------------- STATS ----------------- */
    echo "<div class='stats'>Statystyki<br><hr class='hr1'>";
    $sql = mysqli_query($db_conn, "SELECT stats_user.id, stats_user.played, stats_user.won, stats_user.punkty FROM stats_user, users WHERE stats_user.id_user = users.id AND users.steamid = $usersteamid;");
    $row = mysqli_fetch_array($sql);
    if(isset($row['id'])){
        if ($row['played'] == 0)
        {
            $winratio = 0;
        } else
        {
            $winratio = substr(($row['won'] / $row['played'])*100, 0, 2);
        }
        echo "<table class='statstable'><tr><th>Rozegrane turnieje</th><th>Wygrane turnieje</th><th>winratio</th><th>Punkty</th></tr><tr><td>".$row['played']."</td><td>".$row['won']."</td><td>$winratio%</td><td>".$row['punkty']."</td></tr></table>";
    }
    echo "</div>";
    /* ----------------- GABLOTA ----------------- */
    $sql = mysqli_query($db_conn, "SELECT id FROM users WHERE steamid = $usersteamid;");
    $row = mysqli_fetch_array($sql);
    if(isset($row['id'])){
        echo "<div class='gablota'>Gablota z odznakami<br><hr class='hr1'>";
        $userid = $row['id'];
        $sql = mysqli_query($db_conn, "SELECT puchary.nazwa, puchary.img, puchary.opis, puchary_users.id_turnieju FROM puchary, puchary_users, users WHERE puchary_users.id_puchar = puchary.id AND puchary_users.id_user = users.id AND puchary_users.id_user = $userid;");
        while($row = mysqli_fetch_array($sql))
        {
            if(!$row['img'])
            {
                echo "<img src='img/pyt.png' alt='".$row['nazwa']."' title='".$row['opis']."' class='odzn'/>";
            }
            else {
                $row['opis'] .= $row['id_turnieju'];
                echo "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' alt='".$row['opis']."' title='".$row['opis']."' class='odzn'/>";
            }
        }
        $sql = mysqli_query($db_conn, "SELECT products.nazwa, products.img FROM products, product_user WHERE product_user.id_product = products.id AND product_user.id_user = $userid;");
        while($row = mysqli_fetch_array($sql))
        {
            if(!$row['img'])
            {
                echo "<img src='img/pyt.png' alt='".$row['nazwa']."' title='".$row['nazwa']."' class='odzn'/>";
            }
            else {
                echo "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' alt='".$row['nazwa']."' title='".$row['nazwa']."' class='odzn'/>";
            }
        }
        if(!mysqli_num_rows($sql)){
            echo "<p>Użytkownik nie posiada żadnych odznak.</p>";
        }
        echo "</div>";
    }
}
}
?>
</body>
</html>

