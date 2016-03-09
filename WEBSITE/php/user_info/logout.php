<?php
include ("../include.php");

$_SESSION["user_id"] = ""; // session close.
$_SESSION["name"] = "";

header("Location: http://airpollution.calit2.net/TeamC/");
// header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
exit;
?>

