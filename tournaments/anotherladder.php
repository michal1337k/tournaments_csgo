<?php
require "../db/dbconn.php";
$isstared = mysqli_query($db_conn, "SELECT id FROM tournament WHERE id = $id AND is_started = 1;");
if($yes = mysqli_fetch_array($isstared))
{
    $check1 = mysqli_query($db_conn, "SELECT COUNT(id) AS trwajace FROM pary WHERE tournament_id = $id;");
    $chck1 = mysqli_fetch_array($check1);
    $check2 = mysqli_query($db_conn, "SELECT COUNT(id) AS zakonczone FROM pary WHERE tournament_id = $id AND winner_team_id IS NOT NULL;");
    $chck2 = mysqli_fetch_array($check2);
    if($chck1['trwajace'] == $chck2['zakonczone'])
    {
        /* ----------------- GENERATE ANOTHER ROUND ----------------- */
        $nowround = mysqli_query($db_conn, "SELECT MIN(runda) AS mini FROM pary WHERE tournament_id = $id;");
        $nowroundd = mysqli_fetch_array($nowround);
        $rnd = $nowroundd['mini'];
            /* ----------------- WINNER ----------------- */
        if($rnd == 1)
        {
            $winner = mysqli_query($db_conn, "SELECT winner_team_id, team1id, team2id FROM pary WHERE tournament_id = $id AND runda = 1;");
            $win = mysqli_fetch_array($winner);
            if($win['winner_team_id'] == $win['team1id']) { $firstplace = $win['team1id']; $secondplace = $win['team2id']; }
            if($win['winner_team_id'] == $win['team2id']) { $firstplace = $win['team2id']; $secondplace = $win['team1id']; }
            $nagroda = mysqli_query($db_conn, "SELECT nagroda FROM tournament WHERE id = $id;");
            $nagrodaa = mysqli_fetch_array($nagroda);
            /* ----------------- 100% for 1st and 0.6% for 2nd ----------------- */
            $pierwsze = $nagrodaa['nagroda'];
            $drugie = $nagrodaa['nagroda'] * 0.6;
            $award = mysqli_query($db_conn, "UPDATE users SET stan_konta = stan_konta + $pierwsze WHERE team_id = $firstplace;");
            $award = mysqli_query($db_conn, "UPDATE users SET stan_konta = stan_konta + $drugie WHERE team_id = $secondplace;");
            /* ----------------- ------------ ----------------- */
            /* ----------------- POINTS AWARD  (50 for 1st and 30 for 2nd) ----------------- */
            $award = mysqli_query($db_conn, "UPDATE stats_user INNER JOIN users ON stats_user.id_user= users.id SET stats_user.played = stats_user.played + 1, stats_user.won = stats_user.won + 1, stats_user.punkty = stats_user.punkty + 50 WHERE users.team_id = $firstplace;");
            $award = mysqli_query($db_conn, "UPDATE stats_user INNER JOIN users ON stats_user.id_user= users.id SET stats_user.played = stats_user.played + 1, stats_user.punkty = stats_user.punkty + 30 WHERE users.team_id = $secondplace;");
            $award = mysqli_query($db_conn, "UPDATE stats_team SET played = played + 1, won = won + 1, punkty = punkty + 50 WHERE id_team = $firstplace;");
            $awars = mysqli_query($db_conn, "UPDATE stats_team SET played = played + 1, punkty = punkty + 30 WHERE id_team = $secondplace;");
            /* ----------------- ------------ ----------------- */
            $finished = mysqli_query($db_conn, "UPDATE tournament SET is_finished = 1 WHERE id = $id;");
            $toarchiwum = mysqli_query($db_conn, "INSERT INTO tournament_archiwum (real_id, slots, nagroda, is_full, start_data, is_finished) SELECT id, slots, nagroda, is_full, start_data, is_finished FROM tournament WHERE id = $id;");
            $deletefromtournament = mysqli_query($db_conn, "DELETE FROM tournament WHERE id = $id;");
        }
            /* ----------------- ------------ ----------------- */
        else{
            /* ----------------- POINTS AWARD  5 FOR PLAYED, 15 FOR WINNER MATCH ----------------- */
            $sql = mysqli_query($db_conn, "SELECT team1id, team2id, winner_team_id FROM pary WHERE tournament_id = $id AND runda = $rnd;");
            $row = mysqli_fetch_array($sql);
            $winnerid = $row['winner_team_id'];
            if($row['team1id'] == $row['winner_team_id'])
            {
                $secondid = $row['team2id'];
            }
            else {
                $secondid = $row['team1id'];
            }
            $sql = mysqli_query($db_conn,"UPDATE stats_team SET punkty = punkty + 15 WHERE id_team = $winnerid;");
            $sql = mysqli_query($db_conn,"UPDATE stats_team SET punkty = punkty + 5 WHERE id_team = $secondid;");
            $sql = mysqli_query($db_conn,"UPDATE stats_user INNER JOIN users ON stats_user.id_user = user.id SET stats_user.punkty = stats_user.punkty + 15 WHERE users.team_id = $winnerid;");
            $sql = mysqli_query($db_conn,"UPDATE stats_user INNER JOIN users ON stats_user.id_user = user.id SET stats_user.punkty = stats_user.punkty + 5 WHERE users.team_id = $secondid;");
            /* ----------------- ------------ ----------------- */
            $count = mysqli_fetch_array($db_conn, "SELECT COUNT(winner_team_id) AS ilosc FROM pary WHERE tournament_id = $id AND runda = $rnd;");
            $xcount = mysqli_fetch_array($count); 
            $x = $xcount['ilosc'];
            $teams = mysqli_query($db_conn, "SELECT pary.winner_team_id, team.nazwa FROM pary, team WHERE pary.winner_team_id = team.id AND pary.tournament_id = $id AND pary.runda = $rnd;");
            $rnd--;
            while($teamss = mysqli_fetch_array($teams)){
                $teamid = $teamss['winner_team_id'];
                if($x>0){
                    if ($x%2==0){
                        $addpar = mysqli_query($db_conn, "INSERT INTO pary SET tournament_id = $id, team1id = $teamid, runda = $rnd;");
                        $x--;
                    }
                    else {
                        $addpar = mysqli_query($db_conn, "UPDATE pary SET team2id = $teamid WHERE team2id = 0 AND tournament_id = $id AND runda = $rnd LIMIT 1;");
                        $x--;
                    }
                }
            }
        }
    /* ----------------- ------------ ----------------- */
    }
}
?>
