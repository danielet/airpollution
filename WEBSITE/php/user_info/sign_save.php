<?
include ("../include.php");

if($_SESSION["user_id"]){ // session existence confirm. if session exist, alert "present login".
    ?> 
    <script>
        alert("Present Login");
        history.back();
    </script>
    <?
}


if(trim($_POST["user_id"]) == ""){ //if not input id,back to before page
    ?>
    <script>
        alert("Insert user ID");
        history.back();
    </script>
    <?
    exit;
}

if(trim($_POST["lname"]) == "" || trim($_POST["fname"]) == ""){ //if not input name,back to before page
    ?>
    <script>
        alert("Insert Name");
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

if($_POST["pwd"] != $_POST["pwd2"]){ //if not input confirm password,back to before page
    ?>   
    <script>
        alert("Comfirm the Password");
        history.back();
    </script>
    <?
    exit;
}

$user_id=$_POST["user_id"]; // receive user id.
//TO CHANGE
$salt=rand(1,10);    // make a random salt.

$lname=$_POST["lname"]; //receive lname.(last name)
$fname=$_POST["fname"]; //receive fname.(first name)
$addr=$_POST["addr"]; //receive address.
$password=md5($_POST["pwd"]+$salt); //receive password and make the hashed password
$password2=$_POST["pwd2"];  // receive confirm password

$sex=$_POST["sex"]; //receive sex information

$reg_date=date('Y-m-d H:i:s'); // receive data information
$count=$_POST["count"]; // receive login count.
 
$chk_sql = "SELECT * FROM user WHERE user_id = '".trim($_POST["user_id"])."'"; // find a user_id in database
$chk_result = sql_query($chk_sql); //execute sql
$chk_data = mysql_fetch_array($chk_result); // save database date in array

// 5. 가입된 아이디가 있으면 되돌리기
if($chk_data["user_id"]){ // check overlap the userid in database
   ?>   
     <script>
        alert("already join");
        history.back();
    </script>
    <?
    exit;
}

 if($_POST['pwd']==$_POST['pwd2'] && $_POST['user_id'] != NULL && $_POST['lname'] != NULL && $_POST['fname'] != NULL){
  	 $sql = "insert into user (user_id, lname, fname, addr,hashed_pw,salt, login_count,sex,reg_date)
     values('$user_id','$lname','$fname','$addr','$password','$salt','$count','$sex','$reg_date')";
     sql_query($sql); // insert sign_up information in database
	 
   ?>
        <script>
        alert("Welcome");
        location.replace("../../index.php"); // complete user information.
        </script>
        <?
 }
?>
