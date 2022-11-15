<html lag="pl">
<head>
<meta charset="UTF-8">
    <?php 
        require "db/dbconn.php";
        require 'steamauth/steamauth.php';
    ?>
    <script>
        //anty ref, żeby formularz nie wysyłał się 100 razy
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<link rel="stylesheet" href="css/style.css" />   
</head>
<body>
<?php
    if(!isset($_SESSION['steamid'])) {

        header("Location: index.php");

    }  else {
        include ('steamauth/userInfo.php'); //To access the $steamprofile array
        $steamid = $steamprofile['steamid'];
        $username = $steamprofile['personaname'];
        $avatar = $steamprofile['avatarfull'];
        $lastlogin = $steamprofile['lastlogoff'];
        $online = $steamprofile['personastate'];
        
        include ('parts/menu.php');

        /* ----------------- TEAM CREATE CHECK ----------------- */
        if(isset($_POST['nazwadruzyny']))
        {
            $tm = $_POST['nazwadruzyny'];
            $isteam = mysqli_query($db_conn, "SELECT nazwa FROM team WHERE nazwa = '$tm';");
        
            if($row = mysqli_fetch_assoc($isteam))
            {
                echo "<div id='alert' class='alert'>
                <span class='closebtn'>&times;</span>  
                <span class='textwarning'>Nie udało się utworzyć drużyny. Podana nazwa jest już zajęta.</span>
                </div>";
                unset($_POST['nazwadruzyny']);
            }
            else {
                if(!empty($_FILES['logo']['name'])) { 
                    $fileName = basename($_FILES['logo']['name']); 
                    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
                    $allowTypes = array('jpg','png','jpeg','gif'); 
                    if(in_array($fileType, $allowTypes)){ 
                        $image = $_FILES['logo']['tmp_name']; 
                        $imgContent = addslashes(file_get_contents($image)); 
                        $createteam = mysqli_query($db_conn, "INSERT INTO team SET nazwa = '$tm', img = '$imgContent';");
                        $last_id = mysqli_insert_id($db_conn);
                        $setcaptain = mysqli_query($db_conn, "UPDATE users SET team_id = $last_id, is_captain = 1 WHERE steamid = $steamid;");
                        $sql = mysqli_query($db_conn, "INSERT INTO stats_team SET id_team = $last_id;");
                        echo "<div id='alert' class='alert success'>
                        <span class='closebtn'>&times;</span>  
                        <span class='textwarning'>Drużyna o nazwie <i>".$_POST['nazwadruzyny']."</i> została utworzona!</span>
                        </div>";
                        unset($_POST['nazwadruzyny']);
                    }
                }
            }
        }
        //profil
        echo "<div class='profilearea'>";
        echo "<img class='avatarprofile' src='$avatar'/><div class='userdata'><b class='daneteamu'>Dane użytkownika</b><hr class='hr1'> <p>$username</p> <p>SteamID: <b class='steamidhash'>$steamid</b></p><br><p><a href='".$steamprofile['profileurl']."edit/info' target='_blank' class='edytujprofil'>Edytuj profil</a></p></div>";

        /* ----------------- IS TEAM EXISTS CHECK ----------------- */
        $team = mysqli_query($db_conn, "SELECT team.nazwa, team.id, team.img, users.is_captain FROM team, users WHERE team.id = users.team_id AND users.steamid = $steamid;");
        if($row = mysqli_fetch_assoc($team))
        {
            if(!$row['img'])
            {
                $img = "<img src='../img/pyt.png' class='teamimg' /><br>";
            }
            else {
                $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' class='teamimg' /><br>";
            }
            echo "<div class='teamdata'><b class='daneteamu'>Dane drużyny<hr class='hr1'></b><h3><a class='tnazwa' href=team.php?id=".$row['id'].">$img".$row['nazwa']."</a></h3>";
            if($row['is_captain'] == 1)
            {
                echo "<p><a class='edytujdruzyne' href='teamedit.php'>Edytuj drużynę</a></p>";
            }
            echo "</div>";
        }
        else
        {
            echo "<div class='teamdata'><b class='daneteamu'>Dane drużyny</b><hr class='hr1'>
            <h4>Aktualnie nie jesteś w żadnej drużynie!</h4>
            <h3>Stwórz własną drużyne!</h3>
            <form action='userprofile.php' method='post' enctype='multipart/form-data'>
            <table class='teamform'>
                <tr><td>Nazwa drużyny</td><td><input type='text' name='nazwadruzyny' required/></td></tr>
                <tr><td>Logo drużyny: </td><td><input type='file' name='logo' required/></td></tr>
                <tr><td></td><td><button type='submit'>Stwórz drużyne</button></td></tr>
            </table>
            </form>
            </div>";
           
        }

        /* ----------------- STATYSTYKI ----------------- */
        echo "<div class='stats'>Statystyki<br><hr class='hr1'>";
        $sql = mysqli_query($db_conn, "SELECT stats_user.id, stats_user.played, stats_user.won, stats_user.punkty FROM stats_user, users WHERE stats_user.id_user = users.id AND users.steamid = $steamid;");
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
        $sql = mysqli_query($db_conn, "SELECT id FROM users WHERE steamid = $steamid;");
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
                    echo "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."' alt='".$row['opis']."' title='".$row['opis']."' class='odzn' style='max-width:4.5%'/>";
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
            echo "</div>";
        }
    } 
?>
<script type="text/javascript" src="js/alert.js"></script>
</body>
</html>