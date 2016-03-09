<?php
include("../include.php");

$user_id=$_SESSION["user_id"]; //current connect id
$addr=$_POST["addr"]; // receive address
$name=$_POST["name"]; // receive name
$password2=$_POST["pwd2"]; // receive password (pw2=confirm password)
$count=$_POST["count"];  // receive login count

$chk_sql = "select * from user where user_id = '".trim($_SESSION["user_id"])."'"; //useid existence confrim in database
$chk_result = sql_query($chk_sql); // execute query
$chk_data = mysql_fetch_array($chk_result); // save database data in array
$password=md5($_POST["pwd"]+$chk_data["salt"]); // hashed password


  
 if($_POST["pwd"]==$_POST["pwd2"]  )
 {
  $sql = "update user set hashed_pw='".$password."' where user_id = '".trim($_SESSION["user_id"])."'"; // if input password with confirm password same, change new password
  sql_query($sql); //execute query
  $sql2 = "update user set addr='".$addr."' where user_id = '".trim($_SESSION["user_id"])."'"; //if input new address, change address
  sql_query($sql2); //execute query
  
   ?>   <script>
        alert("Complete!!."); // if change complete, alert message
        </script>
		<meta charset="utf-8">;
		<script>
		location.replace("../../html/Main.php"); //after change the password and addres, back the befor page
		</script>
        <?
 }   
 else if($_POST["pwd"] != $_POST["pwd2"])  //if input password with current password not matched, alert message
 {	
 ?>
        <script>
        alert("Confirm Password");
        history.back();
        </script>
        <? 
 }
?> 
