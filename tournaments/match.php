<html>
<head>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
require "../db/dbconn.php";
require '../steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {

    header("Location: ../index.php");
}
else{
    include ('../steamauth/userInfo.php'); //To access the $steamprofile array
    $steamid = $steamprofile['steamid'];
    include ('../parts/menu.php');
    if (isset($_GET['id'])) {
        $matchid  = $_GET['id'];
        $rnds1 = null;
        $rnds2 = null;
        $winner = 0;
        /* ----------------- SET TOURNAMENT_ID TO MENU ----------------- */
        $sql = mysqli_query($db_conn, "SELECT tournament_id FROM pary WHERE id = $matchid;");
        $row = mysqli_fetch_array($sql);
        $id = $row['tournament_id'];
        /* ----------------- -------------- ----------------- */
        echo "<div class='tournamentsarea'>";  
        include('../parts/tournament_menu.php');
        /* ----------------- ROUNDS ----------------- */
        if(isset($_POST['pkt1'])){
            $rounds = $_POST['pkt1'];
            $addrounds = mysqli_query($db_conn, "UPDATE pary SET team1_rounds = $rounds WHERE id = $matchid;");
            $selrounds = mysqli_query($db_conn, "SELECT team1_rounds FROM pary WHERE id = $matchid ;");
            $selectrounds = mysqli_fetch_array($selrounds);
            $rnds1 = $selectrounds['team1_rounds'];
        }
        if(isset($_POST['pkt2'])){
            $rounds = $_POST['pkt2'];
            $addrounds = mysqli_query($db_conn, "UPDATE pary SET team2_rounds = $rounds WHERE id = $matchid;");
            $selrounds = mysqli_query($db_conn, "SELECT team2_rounds FROM pary WHERE id = $matchid;");
            $selectrounds = mysqli_fetch_array($selrounds);
            $rnds2 = $selectrounds['team2_rounds'];
        }
        /* ----------------- -------------- ----------------- */
        $pary = mysqli_query($db_conn, "SELECT id, team1id, team2id FROM pary WHERE id = $matchid;");
        $para = mysqli_fetch_array($pary);
        $team1 = $para['team1id'];
        $team2 = $para['team2id'];
        $team1name = mysqli_query($db_conn, "SELECT id, nazwa FROM team WHERE id = $team1");
        $team2name = mysqli_query($db_conn, "SELECT id, nazwa FROM team WHERE id = $team2");
        $t1 = mysqli_fetch_array($team1name);
        $t2 = mysqli_fetch_array($team2name);
        echo "<table><tr><td>".$t1['nazwa']."</td></tr><tr><td>VS</td></tr><tr><td>".$t2['nazwa']."</td></tr></table>";
        $iscpt = mysqli_query($db_conn, "SELECT is_captain, team_id FROm users WHERE steamid = $steamid;");
        $iscpt1 = mysqli_fetch_array($iscpt);
        /* ----------------- IF MATCH FINISHED ----------------- */
        $checkroundsxd = mysqli_query($db_conn, "SELECT team1_rounds, team2_rounds FROM pary WHERE id = $matchid;");
        $chck = mysqli_fetch_array($checkroundsxd);
        if(!is_null($chck['team1_rounds']) && !is_null($chck['team2_rounds']))
        {
            echo "<table><th>Wynik</th><tr><td>".$t1['nazwa']."</td><td>".$chck['team1_rounds']."</td></tr><tr><td>".$t2['nazwa']."</td><td>".$chck['team2_rounds']."</td></tr></table>";
            if($chck['team1_rounds'] > $chck['team2_rounds'])
            {
                $winteam = mysqli_query($db_conn, "UPDATE pary SET winner_team_id = $team1 WHERE id = $matchid;");
                $award = mysqli_query($db_conn, "UPDATE stats_user INNER JOIN users ON stats_user.id_user= users.id SET stats_user.punkty = stats_user.punkty + 15 WHERE users.team_id = $team1;");
                $award = mysqli_query($db_conn, "UPDATE stats_user INNER JOIN users ON stats_user.id_user= users.id SET stats_user.punkty = stats_user.punkty + 5 WHERE users.team_id = $team2;");
                $winner = 1;
            } 
            else if($chck['team2_rounds'] > $chck['team2_rounds']) //else if bo dogrywki
            {
                $winteam = mysqli_query($db_conn, "UPDATE pary SET winner_team_id = $team2 WHERE id = $matchid;");
                $award = mysqli_query($db_conn, "UPDATE stats_user INNER JOIN users ON stats_user.id_user= users.id SET stats_user.punkty = stats_user.punkty + 15 WHERE users.team_id = $team2;");
                $award = mysqli_query($db_conn, "UPDATE stats_user INNER JOIN users ON stats_user.id_user= users.id SET stats_user.punkty = stats_user.punkty + 5 WHERE users.team_id = $team1;");
                $winner = 1;
            }
        }

        /* ----------------- ------------- ----------------- */
        if($iscpt1['is_captain'] == 1)
        {
            if($iscpt1['team_id'] == $t1['id'])
            {
                $t11 = $t1['id'];
                if($winner != 1)
                {
                    echo "<form action='match.php?id=$matchid' method='post'><input type='number' min='0' max='50' name='pkt1' value='$rnds1' placeholder='Rundy drużyny' /><button type='submit'>Zapisz</button></form>";
                }
            }
            else if($iscpt1['team_id'] == $t2['id'])
            {
                $t22 = $t2['id'];
                if($winner != 1)
                {
                    echo "<form action='match.php?id=$matchid' method='post'><input type='number' min='0' max='50' name='pkt2' value='$rnds2' placeholder='Rundy drużyny' /><button type='submit'>Zapisz</button></form>";
                }
            }
        }
        echo "</div>";
    } 
    else{
        header("Location: main.php");
    }
}
?>
</body>
</html>
