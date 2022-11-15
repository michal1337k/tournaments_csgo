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
    if (isset($_GET['id'])) {
        $id  = $_GET['id'];
        $idproductt = mysqli_query($db_conn, "SELECT id, cena FROM products WHERE id = $id;");
        if($productid = mysqli_fetch_array($idproductt))
        {
            $kasa = mysqli_query($db_conn, "SELECT id, stan_konta FROM users WHERE steamid = $steamid;");
            $stan_konta = mysqli_fetch_array($kasa);
            if($productid['cena'] <= $stan_konta['stan_konta']){
                $kupiono = $stan_konta['stan_konta'] - $productid['cena'];
                $sql = mysqli_query($db_conn, "UPDATE users SET stan_konta = '$kupiono' WHERE steamid = $steamid;");
                $userid = $stan_konta['id'];
                $prodid = $productid['id'];
                $sql = mysqli_query($db_conn, "INSERT INTO product_user SET id_product = '$prodid', id_user = '$userid';");
                $_SESSION['kupiono'] = 1;
                header("Location: product.php?id=$id");
            } else{
                $_SESSION['zamalokasy'] = 1;
                header("Location: product.php?id=$id");
            }
        }
    }
    else{
        header("Location: ../main.php");
    }
}
?>
