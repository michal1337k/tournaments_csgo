<?php
$mysql_server = "localhost";
$mysql_admin = "root";
$mysql_pass = "";
$mysql_db = "exodivision";
$db_conn = @mysqli_connect($mysql_server, $mysql_admin, $mysql_pass, $mysql_db)
    or die('Brak połączenia z serwerem MySQL.');
?>