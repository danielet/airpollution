<?php
include ("../include.php");

$str = $_POST['data']; //variable code 'data' is receive data from android.

$arr = explode(',', $str); // , Plot function.
for ($i=0; $i<count($arr); $i++) 
{ // , plot, and user login information confirm and receive the mac,gap,compass,gyro.
    trim($arr[$i]);
}

$uid         = $arr[0]; // save in array
$pw          = $arr[1];
$mac         = $arr[2];
$device_num  = $arr[3];
$gps         = $arr[4];
$compass     = $arr[5];
$gyro        = $arr[6];
   
   
$chk_sql = "SELECT * FROM user WHERE user_id = '".trim($arr[0])."'"; //existence confirm input id 
$chk_result = sql_query($chk_sql); //execute sql
$chk_data = mysql_fetch_array($chk_result); // save database date in array


if($chk_data["user_id"])
{ // if user_id exist.
    if(md5($arr[1]+$chk_data["salt"]) == $chk_data["hashed_pw"])
    {  //check same to current password and input password.  
        
       
        $query = "SELECT count(device_id) as a FROM device WHERE mac = '".$mac."';"; //find mac information
        $list = mysql_query($query); // excute sql
        $info = mysql_fetch_array($list); // save database date in array.
        $count = $info['a']; // count Substituting.
        
        if($count == 0) //if not receive the data. this query start.
        {
          $query = "INSERT INTO device (mac,brand,gps,compass,gyroscope) VALUES ('".$mac."','".$device_num."','".$gps."','".$compass."','".$gyro."');";
          mysql_query($query); // device information save confirm
        }
        
        $query  = "SELECT device_id FROM device WHERE mac = '".$mac."';";
        $list   = mysql_query($query);
        $info   = mysql_fetch_array($list);
        $did    = $info['device_id'];
   
        $query  = "SELECT count(device_id) as a FROM devicetouser WHERE device_id = '".$did."' and user_id = '".$uid."';";
        $list   = mysql_query($query);
        $info   = mysql_fetch_array($list);
        $count  = $info['a'];
      
        if($count == 0) //if not receive the data. this query start.
        {
          $query = "INSERT INTO devicetouser (user_id,device_id) VALUES ('".$uid."','".$did."');";
          mysql_query($query);    
        }
        // echo "OK";
        echo "T"; //login complete
    }
    else
    {  //not matched password            
       echo "Don't match Password!";               
    }
    
}
else
{ // not matched id         
  echo "Error";
}
?>