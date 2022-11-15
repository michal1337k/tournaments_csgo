<html lang='pl'>
<head>
<link rel="stylesheet" href="css/style.css" />   
    <script type="text/javascript" src="js/script.js"></script>
    <script>
        //anty ref, żeby formularz nie wysyłał się 100 razy
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    function ConfirmDelete(id)
      {
            if (confirm("Czy na pewno chcesz usunąć tego członka z drużyny?"))
                location.href='removefromteam.php?id='+id;
      }
    
    function ConfirmDestroy(id) 
    {
        if (confirm("Czy na pewno chcesz usunąć drużyne? TA OPERACJA JEST BEZPOWROTNA"))
                location.href='deleteteam.php?id='+id;
    }
</script>
</head>
<body>
<?php
require "db/dbconn.php";
require 'steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {

    header("Location: index.php");

}  else {
   
    include ('steamauth/userInfo.php');
    $steamid = $steamprofile['steamid'];
    include ('parts/menu.php');
    /* ----------------- IS IT CAPTAIN CHECK ----------------- */
    $sql = mysqli_query($db_conn, "SELECT is_captain, team_id FROm users WHERE steamid = $steamid;");
    $row1 = mysqli_fetch_array($sql);
    $teamidfromuser = $row1['team_id'];
    if($row1['is_captain'] == 1)
    {
        /* ----------------- TEAM EDIT CHECK ----------------- */
        if(isset($_POST['nazwadruzyny']))
        {
            $tm = $_POST['nazwadruzyny'];
            $isteam = mysqli_query($db_conn, "SELECT nazwa FROM team WHERE nazwa = '$tm';");
            if($row = mysqli_fetch_assoc($isteam))
            {
                echo "<div id='alert' class='alert'>
                <span class='closebtn'>&times;</span>  
                <span class='textwarning'>Istnieje już drużyna o takiej nazwie.</span>
                </div>";
                unset($_POST['nazwadruzyny']);
            }
            else {
                $updateteam = mysqli_query($db_conn, "UPDATE team SET nazwa = '$tm' WHERE id = $teamidfromuser;");
                echo "<div id='alert' class='alert success'>
                <span class='closebtn'>&times;</span>  
                <span class='textwarning'>Zmiany zostały zapisane.</span>
                </div>";
                unset($_POST['nazwadruzyny']);
            }
        }
        /* ----------------- ------ ----------------- */
        /* ----------------- LOGO UPDATE ----------------- */
        if(!empty($_FILES['logo']['name'])) { 
            $fileName = basename($_FILES['logo']['name']); 
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
            $allowTypes = array('jpg','png','jpeg','gif'); 
            if(in_array($fileType, $allowTypes)){ 
                $image = $_FILES['logo']['tmp_name']; 
                $imgContent = addslashes(file_get_contents($image)); 
                $updatelogo = mysqli_query($db_conn, "UPDATE team SET img = '$imgContent' WHERE id = $teamidfromuser;");
                unset($_FILES['logo']['name']);
            }
        }
        /* ----------------- ------ ----------------- */
        /* ----------------- ADD MEMBER CHECK ----------------- */
        if(isset($_POST['steamidzawodnika']))
        {
            $memsteam = $_POST['steamidzawodnika'];
            $isinteam = mysqli_query($db_conn, "SELECT users.team_id, users.nickname, users.steamid FROM users WHERE users.steamid = $memsteam;");
            $dane = mysqli_fetch_assoc($isinteam);
            if(isset($dane['steamid']))
            {
                if($dane['steamid'] == $memsteam)
            {
                if($dane['team_id'] == 0)
                {  
                    
                    $myteam = mysqli_query($db_conn, "SELECT team.id FROM team, users WHERE team.id = users.team_id AND users.steamid = $steamid;");
                    $myteam1 = mysqli_fetch_assoc($myteam);
                    $myteamxx = $myteam1['id'];
                    $maxmemberssql = mysqli_query($db_conn, "SELECT COUNT(steamid) as members FROM users WHERE team_id = $myteamxx;");
                    $maxmembers1 = mysqli_fetch_assoc($maxmemberssql);
                    /* ----------------- TEAM FULL CHECK ----------------- */
                    if($maxmembers1['members'] >= 5)
                    {
                        $setfull = mysqli_query($db_conn, "UPDATE team SET is_full = 1 WHERE id = $myteamxx;");
                        echo "<div id='alert' class='alert'>
                        <span class='closebtn'>&times;</span>  
                        <span class='textwarning'>Drużyna jest pełna (maksymalnie 5 członków).</span>
                        </div>";
                    }
                    else{ 
                        $memberrequest = mysqli_query($db_conn, "INSERT INTO team_users_request SET id_users = $memsteam, id_team = $myteamxx;");
                        echo "<div id='alert' class='alert success'>
                        <span class='closebtn'>&times;</span>  
                        <span class='textwarning'>Wysłano zaproszenie do drużyny.</span>
                        </div>";
                    }
                    /* ----------------- --------------- ----------------- */
                }
                else{
                    echo "<div id='alert' class='alert'>
                    <span class='closebtn'>&times;</span>  
                    <span class='textwarning'>Zawodnik o nicku ".$dane['nickname']." należy już do innej drużyny.</span>
                    </div>";
                }

            }
            else{
                echo "<div id='alert' class='alert'>
                <span class='closebtn'>&times;</span>  
                <span class='textwarning'>Podany użytkownik nie istnieje, sprawdź podane SteamID.</span>
                </div>";
            }
            unset($_POST['steamidzawodnika']);
            
            }
            else {
                echo "<div id='alert' class='alert'>
                <span class='closebtn'>&times;</span>  
                <span class='textwarning'>Podany użytkownik nie istnieje, sprawdź podane SteamID.</span>
                </div>";
            }
            
        }
        /* ----------------- ------ ----------------- */

        echo "<div class='profilearea'>";
        $team = mysqli_query($db_conn, "SELECT team.nazwa, team.id, team.img FROM team, users WHERE team.id = users.team_id AND users.steamid = $steamid;");
        if($row = mysqli_fetch_assoc($team))
        {
            if(!$row['img'])
            {
                $img = "<img src='../img/pyt.png' class='teamimg' /><br>";
            }
            else {
                $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' class='teamimg' /><br>";
            }
            echo "<div class='teamdata'><b class='daneteamu'>".$row['nazwa']."<hr class='hr1'></b><h3>$img</h3><form action='teamedit.php' method='post' enctype='multipart/form-data'><p><input type='file' name='logo' required class='filebtn' /></p><button type='submit' class='confirmbutton'>Aktualizuj logo</button></form></div><form action='teamedit.php' method='post'><table class='teamedit'>
                <tr><td>Nazwa</td><td>Edytuj</td></tr>
                <tr><td><input type='text' name='nazwadruzyny' id='edit1' value='".$row['nazwa']."' disabled class='teaminput' /></td><td><input type='checkbox' id='check1' onclick='scr1()'/></td></tr>
                <tr><td colspan='2'><button type='submit' class='confirmbutton' style='padding:3%;'>Zapisz zmiany</button></td></tr></table></form>";

            
            echo "<div class='teamdata'><b class='daneteamu'>Członkowie<hr class='hr1'></b></div>";
            echo "<p style='text-align:center;'>Dodaj zawodnika</p> <form action='teamedit.php' method='post' class='addform'><input type='text' name='steamidzawodnika' required class='teaminput' style='width:40%' placeholder='SteamID' /><br><button type='submit' class='confirmbutton'>Wyślij zaproszenie</button></form>";
            echo "<table class='teameditmembers'> <col style='width: 50%;' /> <col style='width: 50%;' />";
            $teamid = $row['id'];
            $membrs = mysqli_query($db_conn, "SELECT users.id, users.nickname FROM team, users WHERE team.id = users.team_id AND team.id = $teamid;");
            while($memb = mysqli_fetch_assoc($membrs)){
                echo "<tr><td class='tdbor'><a href=profile.php?id=".$memb['id'].">".$memb['nickname']."</a></td><td><input type='button' onclick='ConfirmDelete(".$memb['id'].")' value='Usuń z drużyny' class ='btndelete' /></td></tr>";
            }
            echo "</table>";
            echo "<hr class='hr1'><p style='text-align:center;'><input type='button' onclick='ConfirmDestroy($teamid)' value='USUŃ DRUŻYNE' class='btndelete' style='padding: 1.5%;' /></p></div>";
        }
        else
        {
            header("Location: main.php");  
        }
    }
    else {
        //nie jesteś kapitanem
        header("Location: main.php");
    }
}
?>
<script type="text/javascript" src="js/alert.js"></script>
</body>