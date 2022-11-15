<html lang="pl-PL">
<head>
<meta charset="UTF-8">
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
        /* ----------------- LIST OF PRODUCTS ----------------- */
        $produkty = mysqli_query($db_conn, "SELECT id, nazwa, cena, img FROM products;");
        if($produkty){
            echo "<div class='productsarea'>";
            while($row = mysqli_fetch_array($produkty)){
                if(!$row['img'])
                {
                    $img = "<img src='../img/pyt.png' />";
                }
                else {
                    $img = "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."'/>";
                }
                echo "<table class='product'><th>".$row['nazwa']."</th><tr><td>$img</td></tr><tr><td class='cena'>".$row['cena']."$</td></tr><tr><td><a href=product.php?id=".$row['id']."><button>pokaż więcej</button></a></td></tr></table>";
            }
            echo "</div>";
        }
        /* ----------------- ------------- ----------------- */
    }
    ?>
</body>
</html>