<?php
include("../include.php");

if(trim($_POST["user_id"]) == ""){ //if not input id,back to before page
    ?>
    <script>
        alert("Insert email");
        history.back();
    </script>
    <?
    exit;
}

if($_POST["pwd"] == ""){ //if not input password,back to before page
    ?>
    <script>
        alert("Insert Password");
        history.back();
    </script>
    <?
    exit;
}

if($_POST["pwd2"] == ""){ //if not input confirm password,back to befor page
    ?>
    <script>
        alert("Insert Password");
        history.back();
    </script>
    <?
    exit;
}
 
$email=$_POST["user_id"];  //receive id in findinfo page
$password2=$_POST["pwd2"]; //receive confirm password in findinfo page
$count=$_POST["count"];    //receive count in findinfo page
$confirmcode=$_POST["confirm"]; //receive confirm in findinfo page
$chk_sql = "SELECT * FROM user WHERE user_id = '".trim($_POST[user_id])."'"; //existence confirm input id 
$chk_result = sql_query($chk_sql); //execute sql
$chk_data = mysql_fetch_array($chk_result); // save database date in array
$password=md5($_POST["pwd"]+$chk_data["salt"]); //recieved password, changed hash password


  
 if($_POST["pwd"]==$_POST["pwd2"] && $chk_data[$confirmcode]==$_POST["confirm"]) //check same to password and confirm code,
 {
  $sql = "UPDATE user SET hashed_pw='".$password."' WHERE user_id = '".trim($_POST["user_id"])."'"; //change the password, database update
  sql_query($sql); //save data
  $sql = "UPDATE user SET login_count='".$count."' WHERE user_id = '".trim($_POST["user_id"])."'"; //change the count database update.
  sql_query($sql);
   ?>   <script>
        alert("Complete!!.");
        </script>
		<meta charset="utf-8">;
		<script>
		location.replace("../../index.php");
		</script>
        <?
 }
   
 else if($_POST[pwd] != $_POST[pwd2]) // if not matched password with confirm password,back to before page.
 {	
 ?>
        <script>
        alert("Confirm Password");
        history.back();
        </script>
        <? 
 }
 else{
 	?>
 	<script>
        alert("Insert information");
        history.back();
        </script>
        <? 
	
 }
?> 
