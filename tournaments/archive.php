<html lang='pl'>
<head>

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
        /* ----------------- TOURNAMENT INFO ----------------- */
        if (isset($_GET['id'])) {
            $id  = $_GET['id'];
            $tournaments = mysqli_query($db_conn, "SELECT real_id, slots, nagroda, is_full, start_data FROM tournament_archiwum WHERE real_id = $id;");
            $row = mysqli_fetch_array($tournaments);
            $miejsca = $row['slots'];
            $datastartu = new DateTime($row['start_data']);
            $data = date_format($datastartu, 'l (d.m.Y), H:i');
            $zapisane = mysqli_query($db_conn, "SELECT team.nazwa, team.id FROM team, tournament_team_archiwum WHERE team.id = tournament_team_archiwum.id_team AND tournament_team_archiwum.id_tournament = $id;");
            $m1 = mysqli_query($db_conn, "SELECT team.nazwa FROM team, winners WHERE winners.id_team = team.id AND winners.id_tournament = $id AND miejsce = 1;");
            $miejsce1 = mysqli_fetch_array($m1);
            $m2 = mysqli_query($db_conn, "SELECT team.nazwa FROM team, winners WHERE winners.id_team = team.id AND winners.id_tournament = $id AND miejsce = 2;");
            $miejsce2 = mysqli_fetch_array($m2);
            $m3 = mysqli_query($db_conn, "SELECT team.nazwa FROM team, winners WHERE winners.id_team = team.id AND winners.id_tournament = $id AND miejsce = 3;");
            $miejsce3 = mysqli_fetch_array($m3);
            if(!$miejsce1) $miejsce1['nazwa'] = "Brak danych";
            if(!$miejsce2) $miejsce2['nazwa'] = "Brak danych";
            if(!$miejsce3) $miejsce3['nazwa'] = "Brak danych";
            echo "<table><th>Turniej 5v5 #".$row['real_id']."</th><tr><td>Ilość miejsc:</td><td>".$row['slots']."</td></tr><tr><td>Pula nagród:</td><td>".$row['nagroda']."</td></tr><tr><td colspan='2'>$data</td></tr><tr><td>1 miejsce:</td><td>".$miejsce1['nazwa']."</td></tr><tr><td>2 miejsce:</td><td>".$miejsce2['nazwa']."</td></tr><tr><td>3 miejsce:</td><td>".$miejsce3['nazwa']."</td></tr></table>";
            while($rowteamy = mysqli_fetch_array($zapisane)){
                echo "<a href=../team.php?id=".$rowteamy['id'].">".$rowteamy['nazwa']."</a><br>";
            }
            /* ----------------- -------------- ----------------- */

        }


        


    }
    ?>
</body>
</html>