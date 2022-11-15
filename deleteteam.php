<?php
require "db/dbconn.php";
require 'steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {
    header("Location: index.php");
}  
else {
    include ('steamauth/userInfo.php');
    $steamid = $steamprofile['steamid'];
    if (isset($_GET['id'])) {
        $id  = $_GET['id'];
        $sql = mysqli_query($db_conn, "SELECT team_id, is_captain FROM users WHERE steamid = $steamid;");
        $row = mysqli_fetch_array($sql);
        if(($row['team_id'] == $id) && ($row['is_captain'] == 1))
        {
            $sql = mysqli_query($db_conn, "DELETE FROM team WHERE id = $id;");
            $sql = mysqli_query($db_conn, "UPDATE users SET is_captain = 0, team_id = 0 WHERE team_id = $id;");
            $sql = mysqli_query($db_conn, "DELETE FROM stats_team WHERE id_team = $id;");
            header("Location: userprofile.php");
        } 
        else {
            header("Location: userprofile.php");
        }
    }
    else {
        header("Location: userprofile.php");
    }
}
?>


