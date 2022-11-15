<?php
    require "../db/dbconn.php";
    $date = date('Y-m-d H:i:s');
    $tournaments = mysqli_query($db_conn, "SELECT id, slots, start_data, is_full FROM tournament;");
    while($trnm = mysqli_fetch_array($tournaments)){
        $date2 = substr($date, 0, 16);
        $trnmdate = substr($trnm['start_data'], 0, 16);
        if($date2 == $trnmdate)
        {
            $idtournament = $trnm['id'];
            if($trnm['is_full'] == 1)
            {
                $rounds = $trnm['slots']/2;
                /* ----------------- LOSOWANIE PIERWSZEJ DRABINKI ----------------- */
                $teams = mysqli_query($db_conn, "SELECT team.nazwa, team.id FROM tournament_team, team WHERE tournament_team.id_team = team.id AND tournament_team.id_tournament = $idtournament;");
                $count = mysqli_query($db_conn, "SELECT COUNT(team.id) AS ilosc FROM tournament_team, team WHERE tournament_team.id_team = team.id AND tournament_team.id_tournament = $idtournament;");
                $xcount = mysqli_fetch_array($count); 
                $x = $xcount['ilosc'];
                while($teamss = mysqli_fetch_array($teams)){
                    $teamid = $teamss['id'];
                    if($x>0){
                        if ($x%2==0){
                            $addpar = mysqli_query($db_conn, "INSERT INTO pary SET tournament_id = $idtournament, team1id = $teamid, runda = $rounds;");
                            $x--;
                        }
                        else {
                            $addpar = mysqli_query($db_conn, "UPDATE pary SET team2id = $teamid WHERE team2id = 0 AND tournament_id = $idtournament AND runda = $rounds LIMIT 1;");
                            $x--;
                        }
                    }

                }
                $started = mysqli_query($db_conn, "UPDATE tournament SET is_started = 1 WHERE id = $idtournament;");
                header("Location: tournament.php?id=$idtournament");
                /* ----------------- ------------ ----------------- */
            }
            else {
                $delete = mysqli_query($db_conn, "DELETE FROM tournament WHERE id = $idtournament;");
                $delete = mysqli_query($db_conn, "DELETE FROM tournament_team WHERE id_tournament = $idtournament;");
                header("Location: main.php");
            }
        } 

    }
    
?>
