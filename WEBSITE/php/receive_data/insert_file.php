<?php // not using
/*
	//include ("../include.php");
	//$uid = $_POST['uid'];				// It is used when insert session table.
	//$mac = $_POST['mac'];				// It is used when get device_id.
	//$filename = $_POST['filename'];	// It is user when insert to file_info table
    $uid = "smsr1226@naver.com";
	$mac = "123";
	$filename = "www.csv";
	
	
	$con=mysql_connect("localhost","root","qhdksgkwk1") or die("Failed to connect with database!!!!");
	mysql_select_db("iiot", $con);
	
	// set path
	$path = "./csv/".$uid."/";
	// get device_id using mac address.
	$query = "select device_id from device where mac = '".$mac."';";
	$list = mysql_query($query);
	$info = mysql_fetch_array($list);
	$did = $info['device_id'];
	
	
	
	// get session_id using user id and device id.
	$query = "select session_id from session where user_id = '".$uid."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00';";
	$list = mysql_query($query);
	$info = mysql_fetch_array($list);
	$id = $info['session_id'];
	
	
	//$filename = $id.$did.".csv";
	
	// insert file information.
	$query = "insert into file_info (session_id,file_name,path) values ('".$id."','".$filename."','".$path."');";
	mysql_query($query); */ 
?> 