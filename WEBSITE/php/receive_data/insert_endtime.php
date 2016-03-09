<?php // not using

	include ("../include.php");
    $uid = $_POST['uid'];			// It is used when get session_id.
	$mac = $_POST['mac'];			// It is used when get device_id.
	$time = $_POST['time'];		// It is used when insert endtime to session table.
	/*
	$uid = "smsr1226@naver.com";	
	$mac = "1111";					
	$time = "2016-02-11 10:40:20";	

	*/
	// get device_id using mac address.
	$query = "select device_id from device where mac = '".$mac."';";
	$list = mysql_query($query);
	$info = mysql_fetch_array($list);
	$did = $info['device_id'];
	
	// get session_id from valid data of session table.
	$query = "select session_id from session where user_id = '".$uid."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00';";
	$list = mysql_query($query);
	$info = mysql_fetch_array($list);
	$sid = $info['session_id'];
	
	//echo $time.$sid.$did;
	
	// when session stop, insert endtime to session table. It means session is end.
	$query = "update session set endtime = '".$time."' where session_id = '" .$sid. "'";
	mysql_query($query);
	
?>