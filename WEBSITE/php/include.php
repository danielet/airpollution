<?
$db_host = "localhost"; // db host ip
$db_user = "mdaniele"; // db admin name
$db_pass = "Esedra#84"; // db admin password
$db_name = "airpollutionTeamC"; // db name
define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");
define("SECURE", FALSE);    // FOR DEVELOPMENT ONLY!!!!
define("PATH_CSV", "../../../SESSION_FILES/");    // FOR DEVELOPMENT ONLY!!!!


function sql_connect($db_host, $db_user, $db_pass, $db_name) // my database connect
{
    $result = mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    return $result;
}
$connect = sql_connect($db_host, $db_user, $db_pass, $db_name); // connect db
function sql_query($sql) // sql query call
{
    global $connect;	
    $result = @mysql_query($sql, $connect) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
    return $result;
}


function sql_total($sql)
{
    global $connect;
    $result_total = sql_query($sql, $connect);
    $data_total = mysql_fetch_array($result_total);
    $total_count = $data_total[cnt];
    return $total_count;
}
function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id(true);    // regenerated the session, delete the old one. 
    
}
function login($userid , $password)
{
    $chk_sql        = "SELECT * FROM user WHERE user_id = '".$userid."'"; //find user_id in database    
    $chk_result     = sql_query($chk_sql); // execute sql
    $chk_data       = mysql_fetch_array($chk_result); // save database date in array
    $user_browser   = $_SERVER['HTTP_USER_AGENT'];
    $password       = md5($password+$chk_data["salt"]);
    if($chk_data["user_id"]){ //if user id exist in database,
        if( $password== $chk_data["hashed_pw"]){ //confirm to input password with confirm password is same.       
            $_SESSION["userid"]        = $chk_data["user_id"]; //create session id because login success.
            $_SESSION["username"]       = $chk_data["fname"]." ".$chk_data["lname"]; //create session name because login success.
            $_SESSION['login_string']   = md5($password + $user_browser);    
            return true;
        }
    }
    return false;
}
function login_check() {
    // Check if all session variables are set 
    if (isset($_SESSION['userid'], $_SESSION['username'], $_SESSION['login_string'])) 
    {
        $userid        = $_SESSION['userid'];
        $login_string   = $_SESSION['login_string'];
        $username       = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $chk_sql        = "SELECT hashed_pw FROM user WHERE user_id = '".$userid."'"; //find user_id in database
        $chk_result     = sql_query($chk_sql); // execute sql
        $chk_data       = mysql_fetch_array($chk_result); // save database date in array
        // if( $password== $chk_data["hashed_pw"])
        // { //confirm to input password with confirm password is same.
        // $login_check = hash('sha512', $chk_data["hashed_pw"] . $user_browser);
        $login_check    = md5($chk_data["hashed_pw"] + $user_browser);
        if($login_check == $login_string)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}
function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}
?>