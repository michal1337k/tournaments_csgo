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
        $id  = $_GET['id'];
        $isstarted = mysqli_query($db_conn, "SELECT is_started FROM tournament WHERE id = $id;");
        echo "<div class='tournamentsarea'>";  
        include('../parts/tournament_menu.php');
        if($iss = mysqli_fetch_array($isstarted))
        {
            if($iss['is_started'] == 1)
            {
                /* ----------------- LADDER SHOW----------------- */
                $pary = mysqli_query($db_conn, "SELECT id, team1id, team2id FROM pary WHERE tournament_id = $id;");
                while($para = mysqli_fetch_array($pary)){
                    $team1 = $para['team1id'];
                    $team2 = $para['team2id'];
                    $team1name = mysqli_query($db_conn, "SELECT nazwa FROM team WHERE id = $team1");
                    $team2name = mysqli_query($db_conn, "SELECT nazwa FROM team WHERE id = $team2");
                    $t1 = mysqli_fetch_array($team1name);
                    $t2 = mysqli_fetch_array($team2name);
                    echo "<table><th><a href=match.php?id=".$para['id'].">Strona meczu</a></th><tr><td>".$t1['nazwa']."</td></tr><tr><td>VS</td></tr><tr><td>".$t2['nazwa']."</td></tr></table>";
                }
                /* ----------------- ----------------  ----------------- */
            }
            else {
                echo "Turniej jeszcze nie wystartował";
            }
        } 
        else {
            echo "Turniej nie istnieje";
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
