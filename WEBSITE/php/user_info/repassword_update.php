<?php
include("../include.php");

if(trim($_POST[user_id]) == ""){ //if not input id,back to before page
    ?>
    <script>
        alert("Insert email");
        history.back();
    </script>
    <?
    exit;
}
if($_POST[pwd] == ""){ //if not input password,back to before page
    ?>
    <script>
        alert("Insert Password");
        history.back();
    </script>
    <?
    exit;
}
if($_POST[pwd2] == ""){ //if not input confirm password,back to before page
    ?>
    <script>
        alert("Insert Password");
        history.back();
    </script>
    <?
    exit;
}

$email=$_POST[user_id]; //receive the user_id
$password2=$_POST[pwd2]; //receive the user password
$count=$_POST[count]; // receive the login count

$chk_sql = "select * from user where user_id = '".trim($_POST[user_id])."'"; //existence confirm input id 
$chk_result = sql_query($chk_sql); //execute sql
$chk_data = mysql_fetch_array($chk_result); // save database date in array
$password=md5($_POST["pwd"]+$chk_data["salt"]); //recieved password, changed hash password


 if($_POST[pwd]==$_POST[pwd2]) //if input password with confirm password, change password
 {
  $sql = "update user set hashed_pw='".$password."' where user_id = '".trim($_POST[user_id])."'";
  sql_query($sql); //save data
  $sql = "update user set login_count='".$count."' where user_id = '".trim($_POST[user_id])."'"; //after change password, reset login count.
  sql_query($sql); //save data
   ?>   <script>
        alert("Complete!!.");
        </script>
		<meta charset="utf-8">;
		<script>
		location.replace("../../html/Main.php");
		</script>
        <?
 }
  
 else if($_POST[pwd] != $_POST[pwd2]) // if not matched password with confirm password,back to before page
 {	
 ?>
        <script>
        alert("Confirm Password");
        history.back();
        </script>
        <? 
 }
?> 
