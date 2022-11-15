<?php
require "../db/dbconn.php";
require '../steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {

    header("Location: ../index.php");
}
else{
    include ('../steamauth/userInfo.php'); //To access the $steamprofile array
    $steamid = $steamprofile['steamid'];
    if (isset($_GET['id'])) {
        $id  = $_GET['id'];
        $idteamm = mysqli_query($db_conn, "SELECT team_id, is_captain FROM users WHERE steamid = $steamid;");
        if($idteam = mysqli_fetch_array($idteamm))
        {
            if($idteam['is_captain'] == 1){
                $idt = $idteam['team_id'];
                $sql = mysqli_query($db_conn, "INSERT INTO tournament_team SET id_team = '$idt', id_tournament = '$id';");
                $_SESSION['dodanodoturnieju'] = 1;
                header("Location: tournament.php?id=$id");
            } else{
                header("Location: tournament.php?id=$id");
            }
        }
    }
    else{
        header("Location: ../main.php");
    }
}
?>
