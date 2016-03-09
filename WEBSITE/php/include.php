<?session_start(); // session start.

$db_host = "localhost"; // db host ip
$db_user = "mdaniele"; // db admin name
$db_pass = "Esedra#84"; // db admin password
$db_name = "airpollutionTeamC"; // db name

// 2. DB 접속 및 데이터 베이스 선택 사용자 함수
function sql_connect($db_host, $db_user, $db_pass, $db_name) // my database connect
{
    $result = mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    return $result;
}

// 3. 디비 관련 기타 사용자 함수
// 쿼리 함수
function sql_query($sql) // sql query call
{
    global $connect;
	
    $result = @mysql_query($sql, $connect) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
    return $result;
}

// 갯수 구하는 함수
function sql_total($sql)
{
    global $connect;
    $result_total = sql_query($sql, $connect);
    $data_total = mysql_fetch_array($result_total);
    $total_count = $data_total[cnt];
    return $total_count;
}


$connect = sql_connect($db_host, $db_user, $db_pass, $db_name); // connect db


?>


