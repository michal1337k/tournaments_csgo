<?php
if(!isset($_SESSION['steamid'])) {

    header("Location: ../index.php");
}
else{
    echo "<link rel='stylesheet' type='text/css' href='../css/menu_tournament.css' />"; 

    echo "
    <div class='menutourn'>
    <p><ul>
        <li><a href='../tournaments/tournament.php?id=$id'>informacje</a></li>
        <li><a href='../tournaments/ladder.php?id=$id'>drabinka</a></li>
        <li><a href='../tournaments/regulamin.php?id=$id''>regulamin</a></li>
    </ul>
    </p>
    <hr class='hr1' style='margin-left:-75%;float:left;width:250%;margin-top:5%;'>
    </div>    
  ";
}
?>
