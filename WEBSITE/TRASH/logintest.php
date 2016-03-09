<?include("../include.php");





$str = $_POST['email'];
$device = $_POST['device'];
$arr = explode(',', $str);
for ($i=0; $i<count($arr); $i++) {
    trim($arr[$i]);
}




$chk_sql = "SELECT * FROM test1 WHERE email = '".trim($arr[0])."'";
$chk_result = sql_query($chk_sql);
$chk_data = mysql_fetch_array($chk_result);


// 5. 아이디가 존재 하는 경우$if($chk_data[id]){
if($chk_data[email]){
    // 5. 입력된 비밀번호와 저장된 비밀번호가 같은지 비교해서
    if(md5($arr[1]+$chk_data[salt]) == $chk_data[pw]){
        // 6. 비밀번호가 같으면 세션값 부여 후 이동
        $_SESSION[email] = $chk_data[email];
        $_SESSION[name] = $chk_data[name];
		$chk_data[count];
		$change = $chk_data[count]+1;
		
		$countsql = "update test1 set count=".$change." where email = '".trim($arr[0])."'";		
		sql_query($countsql);
		
        //$mysqli->query($chk_countresult);
	  
        
        echo "Welcome!";
        //location.replace("Main.php");
        
        
        exit;
       
        
        
    }else{
        // 7. 비밀번호가 다르면
        ?>
        <script>
            echo "Don't match Password";           
        </script>
        <?
        exit;
    }
 }else{
    // 8. 아이디가 존재하지 않으면
    ?>
    <script>
        echo $arr[0];
        echo $arr[1];
    </script>
    <?
    exit;
}
?>
