<?php
include ("../include.php");
echo "<meta charset='utf-8'>";

$json = file_get_contents('php://input');
$obj = json_decode($json);
$uid = $obj->{'uid'};	     	 	// It is used when insert session table.
$mac = $obj->{'mac'};				// It is used when get device_id.
$filename = $obj->{'filename'};	   // It is user when insert to file_info table
$time = $obj->{'time'};		      // It is used when insert endtime to session table.

/*$uid = 'test@example.com';  	 	// It is used when insert session table.
$mac = 'AC:36:13:4F:AF:94';				// It is used when get device_id.
$filename = 'aaa';	   // It is user when insert to file_info table
$time ='2016-00-00 00:00:00';*/



	// set path, save a csv file name and location in database.
	$path = "../csv/";                                      
	// get device_id using mac address.
	$query = "SELECT device_id FROM device WHERE mac = '".$mac."';";
	$list = mysql_query($query);
	$info = mysql_fetch_array($list);
	$did = $info['device_id'];
	

	// get session_id using user id and device id.
	$query = "SELECT session_id FROM session WHERE user_id = '".$uid."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00';";
	$list = mysql_query($query);
	$info = mysql_fetch_array($list);
	$sid = $info['session_id'];	
	
	
	
	
	// INSERT FILE INFORMATION
	// $query = "INSERT INTO file_info (session_id,file_name,path) VALUES ('".$sid."','".$filename."','".$path."');";
	// mysql_query($query);
	
	
	// get session_id 
	$query 	= "SELECT session_id FROM session WHERE user_id = '".$uid."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00';";
	$list 	= mysql_query($query);
	
	// delete out put session_id's data.
	while($info = mysql_fetch_array($list)){
		$id = $info['session_id'];
		$query = "DELETE FROM tmp_save WHERE session_id = '".$sid."';";
		mysql_query($query);
	}
	$query = "DELETE FROM realtime WHERE session_id = '".$sid."';";
	mysql_query($query);
	

	
	// // get session_id from valid data of session table.
	// $query = "SELECT session_id FROM session WHERE user_id = '".$uid."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00';";
	// $list = mysql_query($query);
	// $info = mysql_fetch_array($list);
	// $sid = $info['session_id'];
	
	//echo $time.$sid.$did;
	
	// when session stop, insert endtime to session table. It means session is end.
	$query = "UPDATE session SET endtime = '".$time."' WHERE session_id = '" .$sid. "'";
	mysql_query($query);	
?>