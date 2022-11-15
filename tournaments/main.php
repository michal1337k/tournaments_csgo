<html lang="pl-PL">
<head>
<meta charset="UTF-8">
<script src="http://code.jquery.com/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../js/timer.js"></script>
<!-- <script type="text/javascript" src="../js/countdown.js"></script> -->
<meta http-equiv="refresh" content="n" />
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

    echo "<div class='tournamentsarea'>";
    /* ----------------- LIST OF STARTED TOURNAMENTS ----------------- */
    $already = mysqli_query($db_conn, "SELECT id, slots, nagroda, start_data, is_started FROM tournament WHERE is_started = 1;");
    if($already)
    {   
        echo "<b class='naglowekturniej'>Aktualnie trwające turnieje</b><hr class='hr1'>";
        $i = 1;
        while($row = mysqli_fetch_array($already)){  
            $datastartu = new DateTime($row['start_data']);
            $data = date_format($datastartu, 'd.m.Y, H:i');
            if($i >= 5)
            {
                $i = 1;
            }
            echo "<div style='background:url(../img/bg-imgs/$i.jpg); background-size:100% 100%;' class='tournamentblock'><table><th colspan='2'>Turniej 5v5 #".$row['id']."</th><tr><td>Ilość miejsc:</td><td style='text-align:left;'>".$row['slots']."</td></tr><tr><td>Nagroda główna:</td><td style='text-align:left;'>".$row['nagroda']."$</td></tr><tr><td colspan='2'>$data</td></tr><tr><td colspan='2' style='text-align:center'><a href=tournament.php?id=".$row['id']."><button>Więcej</button></a></td></tr></table></div>";
            $i++;
        }
    }

    /* ----------------- --------- ----------------- */
    /* ----------------- LIST OF TOURNAMENTS ----------------- */
    $tournaments = mysqli_query($db_conn, "SELECT id, slots, nagroda, is_full, start_data FROM tournament WHERE is_started = 0;");
    if($tournaments){
        echo "<b class='naglowekturniej'>nadchodzące turnieje</b><hr class='hr1'>";
        $i = 1;
        while($row = mysqli_fetch_array($tournaments)){
            if($row['is_full'] == 1)
            {
                $row['slots'] = "Brak wolnych miejsc";
            }   
            $datastartu1 = new DateTime($row['start_data']);
            $data1 = date_format($datastartu1, 'd.m.Y, H:i');
            if($i >= 5)
            {
                $i = 1;
            }
            echo "<div style='background:url(../img/bg-imgs/$i.jpg); background-size:100% 100%;' class='tournamentblock'><table><tr><th colspan='2'>Turniej 5v5 #".$row['id']."</th></tr><tr><td>Ilość miejsc:</td><td style='text-align:left;'>".$row['slots']."</td></tr><tr><td>Nagroda główna:</td><td style='text-align:left;'>".$row['nagroda']."$</td></tr><tr><td colspan='2'>$data1</td></tr><tr></tr><tr><td colspan='2' style='text-align:center'><a href=tournament.php?id=".$row['id']."><button>Dołącz</button></a></td></tr></table></div>";
            $i++;
        }
    }
    /* ----------------- --------- ----------------- */

    
    /* ----------------- ARCHIWUM  ----------------- */
    $tournamentsarchiwum = mysqli_query($db_conn, "SELECT real_id, slots, nagroda, start_data FROM tournament_archiwum;");
    if($tournamentsarchiwum){
        echo "<b class='naglowekturniej' style='padding-top:2%'>archiwum turniejów</b><hr class='hr1'>";
        $i = 1;
        while($row = mysqli_fetch_array($tournamentsarchiwum)){ 
            $datastartu2 = new DateTime($row['start_data']);
            $data2 = date_format($datastartu2, 'd.m.Y, H:i');
            if($i >= 5)
            {
                $i = 1;
            }
            echo "<div style='background:url(../img/bg-imgs/$i.jpg); background-size:100% 100%;' class='tournamentblock'><table><th colspan='2'>Turniej 5v5 #".$row['real_id']."</th><tr><td>Ilość miejsc:</td><td style='text-align:left;'>".$row['slots']."</td></tr><tr><td>Nagroda główna:</td><td style='text-align:left;'>".$row['nagroda']."$</td></tr><tr><td colspan='2'>$data2</td></tr><tr><td colspan='2' style='text-align:center'><a href=archive.php?id=".$row['real_id']."><button>Więcej</button></a></td></tr></table></div>";
            $i++;
        }
    }
    /* ----------------- --------- ----------------- */
    
    echo "</div>";










}
?>
<div id="timer"><?php include "time.php"?></div>
</body>
</html>