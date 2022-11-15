<html lang='pl'>
<head>
<script src="http://code.jquery.com/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../js/timer.js"></script>
<link rel="stylesheet" href="../css/style.css" />
<meta http-equiv="refresh" content="n" />
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
            $tournaments = mysqli_query($db_conn, "SELECT id, slots, nagroda, is_full, start_data FROM tournament WHERE id = $id;");
            $row = mysqli_fetch_array($tournaments);
            $miejsca = $row['slots'];
            $datastartu = new DateTime($row['start_data']);
            $data = date_format($datastartu, 'd.m.Y, H:i');
            /* ----------------- IF TOURNAMENT IS FULL SIGNED ----------------- */
            if($row['is_full'] == 1)
            {
                $row['slots'] = "0";
                $zapiszsie = null;
                $wypiszsie = null;
            }
            else{
                $sql = mysqli_query($db_conn, "SELECT is_captain, team_id FROM users WHERE steamid = $steamid;");
                $row1 = mysqli_fetch_array($sql);
                /* ----------------- IF IS CAPTAIN ----------------- */
                if($row1['is_captain'] == 1 && $row1['team_id'] != 0)
                {
                    $tt = $row1['team_id'];
                    $teamalready = mysqli_query($db_conn, "SELECT tournament_team.id_team FROM tournament_team, team WHERE tournament_team.id_team = team.id AND tournament_team.id_team =$tt AND tournament_team.id_tournament = $id;");
                    /* ----------------- IF TEAM ALREADY SIGNED ----------------- */
                    if($tma = mysqli_fetch_array($teamalready)){
                        $zapiszsie = null;
                        $wypiszsie = "<a href='remove.php?id=$id'><button class='wypisz'>Wypisz się</button></a>";
                    }
                    else { 
                        $zapiszsie =  "<a href='join.php?id=$id'><button class='zapisz'>Zapisz się</button></a>";
                        $wypiszsie = null;
                    }
                    /* ----------------- ----------------- ----------------- */
                }
                else {
                    $zapiszsie = null;
                    $wypiszsie = null;
                }
                /* ----------------- ----------------- ----------------- */
            }
            /* ----------------- ----------------- ----------------- */
            echo "<div class='tournamentsarea'>";
            include('../parts/tournament_menu.php');
            echo "<div style='text-align:center;display:flex;justify-content:center;align-items:center;margin-bottom:5%;margin-top:-3.5%;margin-left:-10%;width:120%;height:280px;background:url(../img/bg-imgs/5v5.png); background-size:100% 100%;'><h2 class='titletour'>Turniej 5v5 #".$row['id']."</h2></div>";
            echo "<p class='buttonst'>$zapiszsie $wypiszsie</p>";
            /* ----------------- DATA TIMER ----------------- */
            echo "<p id='demo' class='starttimer'>Start za</p>";
            $dataD = date_format($datastartu, 'd');
            $dataM = date_format($datastartu, 'm');
            $dataY = date_format($datastartu, 'Y');
            $dataH = date_format($datastartu, 'H');
            $dataMI = date_format($datastartu, 'i');
            $dataS = date_format($datastartu, 's');
            echo "<input type='hidden' id='year' value='$dataY'/><input type='hidden' id='month' value='$dataM'/><input type='hidden' id='day' value='$dataD'/><input type='hidden' id='hour' value='$dataH'/><input type='hidden' id='minute' value='$dataMI'/><input type='hidden' id='second' value='$dataS'/>";
            /* ----------------- ----------------- ----------------- */
            /* ----------------- NAGRODA ----------------- */
            // 100% - 1 miejsce, 60% - 2 miejsce, 15% - 3-X miejsc
            /* ----------------- ----------------- ----------------- */
            echo "<div class='left-tour-area'>";
            $zapisane = mysqli_query($db_conn, "SELECT team.img, team.nazwa, team.id FROM team, tournament_team WHERE team.id = tournament_team.id_team AND tournament_team.id_tournament = $id;");
            echo "<div class='leftbox'>
                    <div class='nagloweknagrody'>informacje</div>
                    <div class='t-rowl'>Ilość miejsc:</div><div class='t-rowr'>".$row['slots']."</div>
                    <div class='t-rowl'>Nagroda główna:</div><div class='t-rowr'><b style='color:green;'>".$row['nagroda']."$</b></div>
                    <div class='t-rowl'>Typ rozgrywki:</div><div class='t-rowr'>5v5</div>
                    <div class='t-rowl'>Data startu:</div><div class='t-rowr'>$data</div></div>
                </div><div class='right-tour-area'>
                    <div class='nagloweknagrody'>nagrody</div>
                    <div class='r-row'>🥇</div><div class='r-rowr'><b style='color:green;'>".$row['nagroda']."$</b> + 50pkt + pamiątkowy puchar</div>
                    <div class='r-row'>🥈</div><div class='r-rowr'><b style='color:green;'>".substr($row['nagroda']*0.6, 0, 4)."$</b> + 30pkt + pamiątkowy puchar</div>
                    <div class='r-row'>3 - $miejsca</div><div class='r-rowr'><b style='color:green;'>".substr($row['nagroda']*0.15, 0, 3)."$</b></div>
                    <br><br><br><p>Dodatkowo:<br>- 5pkt za każdy rozegrany mecz<br>- 15pkt za każdy wygrany mecz</p>
                </div>";
            $sloty = 0;
            echo "<div class='teamlist'><b class='daneteamu'>zapisane drużyny</b><hr class='hr1' style='clear:both;margin-bottom: 5%;'>";
            while($rowteamy = mysqli_fetch_array($zapisane)){
                if(!$rowteamy['img'])
                {
                    $img = "<img src='../img/pyt.png' class='teamimg' title='".$rowteamy['nazwa']."' />";
                }
                else {
                    $img = "<img src='data:image/jpeg;base64,".base64_encode($rowteamy['img'])."' class='teamimg' title='".$rowteamy['nazwa']."' />";
                }
                echo "<a href=../team.php?id=".$rowteamy['id'].">$img</a>";
                $sloty++;
            }
            if($sloty >= $miejsca)
            {
                $update = mysqli_query($db_conn, "UPDATE tournament SET is_full = 1 WHERE id = $id;");
            } else{
                $update = mysqli_query($db_conn, "UPDATE tournament SET is_full = 0 WHERE id = $id;");
            }
            echo "</div></div>";
            /* ----------------- -------------- ----------------- */

            /* ----------------- WHEN REMOVED ----------------- */
            if(isset($_SESSION['usunietozturnieju'])){
                if($_SESSION['usunietozturnieju'] == 1)
                {
                    echo "<div id='alert' class='alert info'>
                    <span class='closebtn'>&times;</span>  
                    <span class='textwarning'>Wypisałeś się z turnieju, możesz się ponownie zapisać.</span>
                    </div>";
                    unset($_SESSION['usunietozturnieju']);
                }
            }
            /* ----------------- -------------- ----------------- */
            /* ----------------- WHEN SIGNED ----------------- */
            if(isset($_SESSION['dodanodoturnieju'])){
                if($_SESSION['dodanodoturnieju'] == 1)
                {
                    echo "<div id='alert' class='alert info'>
                    <span class='closebtn'>&times;</span>  
                    <span class='textwarning'>Twoja drużyna została zapisana do turnieju.</span>
                    </div>";
                    unset($_SESSION['dodanodoturnieju']);
                }
            }
            /* ----------------- -------------- ----------------- */
            /* ----------------- WHEN STARTED ----------------- */
            
            /* ----------------- -------------- ----------------- */

        }
    }
    ?>
    <div id="timer"><?php require_once "time.php";?></div>
    <div id="secondtimer"><?php include "anotherladder.php";?></div>
    <script type="text/javascript" src="../js/alert.js"></script>
    <script type="text/javascript" src="../js/countdown.js"></script>
</body>
</html>