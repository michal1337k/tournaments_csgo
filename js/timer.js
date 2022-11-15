window.setTimeout(function () {executemonitor()}, 50000);

function executemonitor() {
    $("#timer").load("../tournaments/time.php");
}