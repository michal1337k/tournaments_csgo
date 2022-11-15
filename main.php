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
<link rel="stylesheet" href="css/posts.css" />
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
        /* ----------------- DISABLE FK CHECK ----------------- */
        $fkcheck = mysqli_query($db_conn, "SET GLOBAL foreign_key_checks=OFF;");
        /* ----------------- --------- ----------------- */

        /* ----------------- FRIST LOGIN ADD TO DATABASE ----------------- */
        $isuser = mysqli_query($db_conn, "SELECT steamid FROM users WHERE steamid = '$steamid';");
        if($row = mysqli_fetch_assoc($isuser))
        {
            if (isset($_SESSION['refnick'])){ 
                unset($_SESSION['refnick']);
                $refnick = mysqli_query($db_conn, "UPDATE users SET nickname = '$username' WHERE steamid = '$steamid';");
            }
        }
        else 
        {
            $adduser = mysqli_query($db_conn, "INSERT INTO users SET steamid = $steamid, nickname = '$username';");
            $statcheck = mysqli_query($db_conn, "SELECT id FROM users WHERE steamid = $steamid;");
            $sc = mysqli_fetch_array($statcheck);
            $iduser = $sc['id'];
            $addstats = mysqli_query($db_conn, "INSERT INTO stats_user SET id_user = $iduser;");
        }
        /* ----------------- --------- ----------------- */
        include ('parts/menu.php');
        

        /* ----------------- TEAM INVITE ----------------- */
        $invites = mysqli_query($db_conn, "SELECT team_users_request.id_users, team.nazwa, team.id FROM team_users_request, team WHERE team_users_request.id_team = team.id AND id_users = $steamid;");
        if($invite = mysqli_fetch_assoc($invites))
        {   
            $teamid = $invite['id'];
            echo "Zostałeś zaproszony do drużyny: ".$invite['nazwa']."<br>";
            echo "<form action='main.php' method='post'><input type='submit' value='Akceptuje' name='akcept'/></form><form action='main.php' method='post'><input type='submit' value='Odrzucam' name='odrzuc'/></form>";
        }
        /* ----------------- ----------- ----------------- */

        /* ----------------- TEAM INVITE ACCEPT ----------------- */
        if(isset($_POST['akcept']))
        {
            $del = mysqli_query($db_conn, "DELETE FROM team_users_request WHERE id_users = $steamid;");
            $addtoteam = mysqli_query($db_conn, "UPDATE users SET team_id = $teamid, is_captain = 0 WHERE steamid = $steamid;");
            unset($_POST['akcept']);
            header("Location: userprofile.php");
        }
        /* ----------------- ----------- ----------------- */

        /* ----------------- TEAM INVITE DECLINE ----------------- */
        if(isset($_POST['odrzuc']))
        {
            $del = mysqli_query($db_conn, "DELETE FROM team_users_request WHERE id_users = $steamid;");
            unset($_POST['odrzuc']);
            header("Refresh:0");
        }
        /* ----------------- ----------- ----------------- */

        /* ----------------- POSTS ----------------- */
        $limit = 4;  
        $postss = mysqli_query($db_conn, "SELECT title, body, timestamp FROM posts ORDER BY timestamp DESC");
        $total_rows = mysqli_num_rows($postss);   
        if($total_rows > 0)
        {
            $total_pages = ceil ($total_rows / $limit); 
            if (!isset ($_GET['page']) ) {  

                $page_number = 1;  
        
            } else {  
        
                $page_number = $_GET['page'];  
        
            }  
            $initial_page = ($page_number-1) * $limit;   
            $sql1 = mysqli_query($db_conn, "SELECT * FROM posts ORDER BY timestamp DESC LIMIT $initial_page, $limit;");    
            echo "<div class='topx'>";
            echo "<div class='pagination'>";
            for($page_number = 1; $page_number<= $total_pages; $page_number++) {  

                echo '<a href = "main.php?page='.$page_number .'">'.$page_number.'</a>';  
        
            } 
            echo "</div>";
            while($posts = mysqli_fetch_assoc($sql1)) {
                $datanapisania = new DateTime($posts['timestamp']);
                $data = date_format($datanapisania, 'd.m.Y, H:i');
                echo "<table class='post'><th class='header'></th><tr><td class='imgtext'><b class='imgtextcenter'>".$posts['title']."</b><img src='img/defaultbg.png'/></td></tr><tr><td class='text'>".$posts['body']."</td></tr><tr class='center'><td class='date'>$data</td></tr></table>";
            }
            echo "</div>";
        }
        /* ----------------- ----------- ----------------- */
        /* ----------------- RIGHT SIDE MENU ----------------- */
        echo "<table class='rightmenu'>
            <th>SOCIAL MEDIA</th>
            <tr><td><a href='https://discord.gg/PbagGAKCvx' target='_blank'><img src='img/social/dc.png' /></a></td></tr>
            <tr><td><a href='https://www.facebook.com/profile.php?id=100004658594201' target='_blank'><img src='img/social/fb.png' /></a></td></tr>
            <tr><td><a href='#''><img src='img/social/tw.png' /></a></td></tr>
            <tr><td><a href='https://www.instagram.com/exo_mw/' target='_blank'><img src='img/social/insta.png' /></a></td></tr>
            <tr><td><a href='https://www.youtube.com/channel/UC0gIkP6tNEaUoz_SQe1nZSg' target='_blank'><img src='img/social/yt.png' /></a></td></tr>
        </table>";
        /* ----------------- ----------- ----------------- */

    } 
?>
</body>
</html>