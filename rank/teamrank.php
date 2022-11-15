<html>
<head>
<link rel="stylesheet" href="../css/style.css" /> 
<script class="cssdeck" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="../js/search.js"></script>
</head>
<body>
<?php
require "../db/dbconn.php";
require '../steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {
    header("Location: index.php");
}  else {
    include ('../steamauth/userInfo.php');
    include ('../parts/menu.php');
    $limit = 20; 
    $sql = mysqli_query($db_conn, "SELECT team.id, team.img, team.nazwa, team.id, stats_team.played, stats_team.won, stats_team.punkty FROM team, stats_team WHERE stats_team.id_team = team.id ORDER BY punkty DESC;");
    $total_rows = mysqli_num_rows($sql);
    $sqlpoint = mysqli_query($db_conn, "SELECT last_clear_points FROM clearpoints_log ORDER BY last_clear_points DESC LIMIT 1;");
    $lastresetpoints = mysqli_fetch_array($sqlpoint);
    $datalast = new DateTime($lastresetpoints['last_clear_points']);
    $data = date_format($datalast, 'd.m.Y, H:i');
    echo "<div class='rankingarea'><p style='text-align:center;padding-bottom:2%;'>Punkty rankingowe resetowane są co <b>2 miesiące</b>. Ostatni reset: <b>$data</b></p><p class='filtr'><a href='main.php'>zawodnicy</a><a href='teamrank.php'>drużyny</a></p><p style='text-align:center;padding:1%;'><input type='text' class='searchrank' id='search' placeholder='Wpisz frazę' /></p><table class='ranking'><tr class='naglowek'><th>Miejsce</th><th>logo</th><th style='width:25%'>nazwa</th><th>ilość członków</th><th>Rozegrane turnieje</th><th>Wygrane turnieje</th><th>WinRatio</th><th>punkty</th></tr>";
    if(isset($_GET['page']))
    {
        if($_GET['page'] == 1 || $_GET['page'] == 0) { 
            $i = 1;
        } else {
            $i = ($_GET['page'] - 1) * $limit +1;
        }
    } else {
        $i = 1;
    }
     
    if($total_rows > 0)
    {
        $total_pages = ceil ($total_rows / $limit); 
        if (!isset ($_GET['page']) ) {  
            $page_number = 1;  
        } else {  
            $page_number = $_GET['page'];  
        }  
        $initial_page = ($page_number-1) * $limit;   
        $sql = mysqli_query($db_conn, "SELECT team.img, team.nazwa, team.id, stats_team.played, stats_team.won, stats_team.punkty FROM team, stats_team WHERE stats_team.id_team = team.id ORDER BY punkty DESC LIMIT $initial_page, $limit;");  
        echo "<div class='topx'>";
        echo "<div class='pagination'>";
        for($page_number = 1; $page_number<= $total_pages; $page_number++) {  

            echo '<a href = "teamrank.php?page='.$page_number .'">'.$page_number.'</a>';  
    
        } 
        echo "</div>";
        while($row = mysqli_fetch_array($sql))
        {   
            // WIN RATIO
            if ($row['played'] == 0)
            {
                $winratio = 0;
            } else
            {
                $winratio = substr(($row['won'] / $row['played'])*100, 0, 4);
            }
            // MEDALE
            echo "<tr><td class='tdbor'>";
            if($i == 1)
            {
                echo"🥇";
            } 
            else if($i == 2){
                echo "🥈";
            }
            else if($i == 3)
            {
                echo "🥉";
            }
            else {
                echo $i;
            }
            //TEAM LOGO
            if(!$row['img'])
            {
                $img = "<img src='../img/pyt.png' class='teamimg' style='width:100%' />";
            }
            else {
                $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' class='teamimg' style='width:100%' />";
            }
            $teamid = $row['id'];
            $sql2 = mysqli_query($db_conn,"SELECT COUNT(*) as ilosc_czlonkow FROM users WHERE team_id = $teamid;");
            $row2 = mysqli_fetch_array($sql2);
            echo "</td><td class='tdbor' style='width:10%' >$img</td><td class='tdbor'><a href='../team.php?id=".$row['id']."'>".$row['nazwa']."</a></td><td class='tdbor'>".$row2['ilosc_czlonkow']."</td><td class='tdbor'>".$row['played']."</td><td class='tdbor'>".$row['won']."</td><td class='tdbor'>$winratio%</td><td>".$row['punkty']."</td></tr>";
            $i++;
        }
    echo "</table></div></div>";
    }
}
?>
</body>
</html>

