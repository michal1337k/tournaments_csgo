<?php
if(!isset($_SESSION['steamid'])) {

    header("Location: ../index.php");
}
else{
    echo "<link rel='stylesheet' type='text/css' href='http://localhost/TURNIEJE/css/menu.css' />"; 
    $steamid = $steamprofile['steamid'];
    $username = $steamprofile['personaname'];
    /* ----------------- MONEY AND TEAM ID CHECK ----------------- */
    $money = mysqli_query($db_conn, "SELECT stan_konta, team_id FROM users WHERE steamid = '$steamid';");
    $moneyrow = mysqli_fetch_assoc($money);
    /* ----------------- --------- ----------------- */

    echo "
    <div class='bg'>
        <ul class='navMenu'>
            <a href='http://localhost/TURNIEJE/main.php'><img class='imghead' src='http://localhost/TURNIEJE/img/logo.png' /></a>
            <a href='http://localhost/TURNIEJE/main.php'><li>aktualności</li></a>
            <a href='http://localhost/TURNIEJE/tournaments/main.php'><li>turnieje</li></a>
            <a href='http://localhost/TURNIEJE/rank/main.php'><li>ranking</li></a>
            <a href='http://localhost/TURNIEJE/shop/index.php'><li>sklep</li></a>
            <div class='profil'>
            <ul>
                
                <li><b style='color:green'>$".$moneyrow['stan_konta']."</b> <img src=".$steamprofile['avatar']." title='' alt='' /> $username
                <ul>
                    <a href='http://localhost/TURNIEJE/userprofile.php'><li  class='bgg'>profil</li></a>
                    <a href='http://localhost/TURNIEJE/team.php?id=".$moneyrow['team_id']."'><li>drużyna</li></a>
                    <a href='?logout'><li>Wyloguj</li></a>
                </ul>
                </li>
            </ul>
            </div>
        </ul>
    </div>    
  ";
}
?>
