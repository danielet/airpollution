<?
include_once "../include.php";


sec_session_start();


if(isset($_POST["user_id"], $_POST["pwd"]) ){ // /if not input id,back to before page

    // CHECK IF THE USER EXISTS
    $userid     = $_POST["user_id"];
    $password   = $_POST["pwd"];
    if(login($userid, $password) == true)
    {
        header('Location: ../../html/googlemap.php'); //if login success, go to main page.        
    }
    else
    {
        header('Location: ../index.php'); //if login success, go to main page.    
    }
}
?>