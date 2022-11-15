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
        $sql = mysqli_query($db_conn, "SELECT team_id FROM users WHERE id = $id;");
        $row = mysqli_fetch_array($sql);
        $deleteuserteamid = $row['team_id'];
        $sql = mysqli_query($db_conn, "SELECT team_id, is_captain FROM users WHERE steamid = $steamid;");
        $row = mysqli_fetch_array($sql);
        if(($row['team_id'] == $deleteuserteamid) && ($row['is_captain'] == 1))
        {
            $res = mysqli_query($db_conn, "UPDATE users SET team_id = 0 WHERE id = $id;");
            header("Location: teamedit.php");
        } 
        else {
            header("Location: teamedit.php");
        }
    }
    else {
        header("Location: teamedit.php");
    }
}
?>


