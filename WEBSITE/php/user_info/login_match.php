<?
include("../include.php");
// if($_SESSION["user_id"]){   // current sessin confirm   
?>

 <!-- <meta charset="utf-8"> -->
    <!-- <script>
        // alert("present login");
        // location.replace("../../html/googlemap.php");

    </script>
    -->
    <?
// }

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
    
    if(md5($_POST["pwd"]+$chk_data["salt"]) == $chk_data["hashed_pw"]){ //confirm to input password with confirm password is same.
       
        $_SESSION["user_id"] = $chk_data["user_id"]; //create session id because login success.
        $_SESSION["name"] = $chk_data["fname"]." ".$chk_data["lname"]; //create session name because login success.
	$chk_data["login_count"]; //login count (if 3 time try to connect, change the password)
	$change = $chk_data["login_count"]+1; //change is login count increase
	$countsql = "UPDATE user SET login_count=".$change." WHERE user_id = '".trim($_POST["user_id"])."'";		
	sql_query($countsql); // save login count in database.
		
	if($change == 3){ // if Stacked up the login count =3 ,reset count =0, and move the repassword page
		$countsql = "UPDATE user SET login_count=0 WHERE user_id = '".trim($_POST["user_id"])."'";
		sql_query($countsql); // save login count in database.         
	    	?>
<!--
		<script>
	    	location.replace("../../html/repassword.php"); //move the repassword page
	    	</script>
-->
	    	<?
		}
        ?>
        <script>
        location.replace("../../html/googlemap.php"); //if login success, go to main page.
        </script>
        <?
       // exit;
       
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
    ?>
    <script>
        alert("Don't match email"); //if not matched id, back to before page.
        history.back;
    </script>
    <?
    exit;
}
?>
