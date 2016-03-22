<?include("../include.php");

if(!$_SESSION["user_id"]){   // current sessin confirm   
 ?>
 	<meta charset="utf-8">
    <script>
        alert("please login");
        location.replace("../../html/Main.php");

    </script>
    <?
}

if(trim($_POST["user_id"]) == ""){ // /if not input id,back to before page
    ?>
    <script>
        alert("insert user ID");
        history.back();
    </script>
    <?
    exit;
    
}

if($_POST["pwd"] == ""){ //if not input password,back to before page
    ?>
    <script>
        alert("insert PW");
        history.back();
    </script>
    <?
    exit;
    
}

$chk_sql = "SELECT * FROM user WHERE user_id = '".trim($_POST["user_id"])."'"; //find user_id in database
$chk_result = sql_query($chk_sql); // execute sql
$chk_data = mysql_fetch_array($chk_result); // save database date in array



if($chk_data["user_id"]){ //if user id exist in database,
    if(md5($_POST["pwd"]+$chk_data["salt"]) == $chk_data["hashed_pw"] && $chk_data["flag"]==1){
     //confirm to input password with confirm password is same.
	    ?>
	    <script>	         
        alert("This is Admin Page.");
        location.replace("../../html/database.php"); //move the repassword page          
        </script>
        <?
        exit;
	
        
        
    }else{
        ?>
        <script>
            alert("Don't match Password"); //if not matched password, back to before page.
            history.back();
        </script>
        <?
        exit;
    }
 }else{
    // 8. 아이디가 존재하지 않으면
    ?>
    <script>
        alert("Don't match email"); //if not matched id, back to before page.
        history.back();
    </script>
    <?
    exit;
}
?>
