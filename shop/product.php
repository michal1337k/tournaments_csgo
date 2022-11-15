<html>
<head>
<link rel="stylesheet" href="../css/style.css" />   
</head>
<body>
<?php
require "../db/dbconn.php";
require '../steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {
    header("Location: index.php");
}  else {
if (isset($_GET['id'])) {
    include ('../steamauth/userInfo.php');
    $id  = $_GET['id'];
    include ('../parts/menu.php');
    $res = mysqli_query($db_conn, "SELECT id, nazwa, cena, opis, img FROM products WHERE id = '$id';");
    $row = mysqli_fetch_array($res);
    if(isset($row['id'])){
        if(!$row['img'])
        {
            $img = "<img src='../img/pyt.png' />";
        }
        else {
            $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."'/>";
        }
        echo "<div class='productsarea'><table class='bproduct'><th colspan='3'>".$row['nazwa']."</th><tr><td class='rline'>$img</td><td class='rline'>".$row['opis']."</td><td>wyglad w profilu</td></tr><tr><td class='cena' colspan='3'><p class='cenasize'>".$row['cena']."$</p></td></tr><tr><td colspan='3'><form action='bought.php?id=$id' method='post'><button type='submit'>Kup teraz</button></form></td></tr></table></div>";
    }
   else{
    header("Location: index.php");
   }

   /* ----------------- KUPIONO ----------------- */

    if(isset($_SESSION['kupiono'])){
        if($_SESSION['kupiono'] == 1)
        {
            echo "<div id='alert' class='alert success'>
            <span class='closebtn'>&times;</span>  
            <span class='textwarning'>Udało Ci się kupić przedmiot za ".$row['cena']."$!</span>
            </div>";
            unset($_SESSION['kupiono']);
        }
    }
    /* ----------------- -------- ----------------- */
    /* ----------------- BRAK KASY ----------------- */
    if(isset($_SESSION['zamalokasy'])){
        if($_SESSION['zamalokasy'] == 1)
        {
            echo "<div id='alert' class='alert'>
            <span class='closebtn'>&times;</span>  
            <span class='textwarning'>Nie masz wystarczającej ilości pieniędzy do kupna tego przedmiotu.</span>
            </div>";
            unset($_SESSION['zamalokasy']);
        }
    }
    /* ----------------- ---------- ----------------- */
        

}
}
?>
<script type="text/javascript" src="../js/alert.js"></script> <!-- skrypt zamykający okienka z infem po zakupieniu produktu -->
</body>
</html>

