필요 없음 <!--<?include("../include.php");
// 1. 공통 인클루드 파일
// 2. 로그인한 회원은 뒤로 보내기

$user_id = $_GET[user_id];
$name  = $_GET[name];
$confirm = $_GET[confirm]; 

if($_SESSION[user_id]){

 ?>
 	<meta charset="utf-8">
    <script>
        alert("present login");
        location.replace("../../html/Main.php");
    </script>
    <?
}
// 3. 넘어온 변수 검사
if(trim($_GET[user_id]) == ""){
    ?>
    <script>
        alert("insert email");
        history.back();
    </script>
    <?
    exit;
    
}

if($_GET[name] == ""){
    ?>
    <script>
        alert("insert name");
        history.back();
    </script>
    <?
    exit;
    
}

// 4. 같은 아이디가 있는지 검사
$chk_sql = "select * from user where user_id = '".trim($_GET[user_id])."'";
$chk_result = sql_query($chk_sql);
$chk_data = mysql_fetch_array($chk_result);


// 5. 아이디가 존재 하는 경우$if($chk_data[id]){
if($chk_data[user_id]){
    // 5. 입력된 비밀번호와 저장된 비밀번호가 같은지 비교해서
    if($_GET[name] == $chk_data[fname].$chk_data[lname]){
        // 6. 비밀번호가 같으면 세션값 부여 후 이동
    if($_GET[confirm] == $chk_data[confirmcode]){
        ?>
        <script>
            location.replace("../../html/repassword.php");
        </script>
        <?
        exit;
	}else{
		 ?>
        <script>
            alert("Don't match code");
            history.back();
        </script>
        <?
        exit;
	}  
    }else{
        // 7. 이름이 다르면
        ?>
        <script>
            alert("Don't match name");
            history.back();
        </script>
        <?
        exit;
    }
 }else{
    // 8. 이메일이 다르면
    ?>
    <script>
        alert("Don't match email");
        history.back;
    </script>
    <?
    exit;
}
?>
--->