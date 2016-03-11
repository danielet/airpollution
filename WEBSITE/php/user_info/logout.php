<?php
include_once"../include.php";

sec_session_start();

$_SESSION["userid"] = ""; // session close.
$_SESSION["name"] = "";

$_SESSION = array();

// get session parameters 
$params = session_get_cookie_params();

// Delete the actual cookie. 
setcookie(session_name(),'', time() - 42000, $params["path"],$params["domain"],$params["secure"],$params["httponly"]);

// Destroy session 
session_destroy();

header("Location: http://airpollution.calit2.net/WEBSITE/");
// header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
// exit;
?>

