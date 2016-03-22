<?php

	include_once("../include.php");
	$json = file_get_contents('php://input');

	$obj 		= json_decode($json);
	

	if($json != false)
	{
		
		
		$uid 		= $obj->{'uid'}; 	// It is used when insert session table.
		$mac 		= $obj->{'mac'};       // It is used when get device_id.
		$time 		= $obj->{'time'};      // It is used when insert session table.
		$sensorsmac = $obj->{'smac'};
		// echo $sensorsmac.$mac.$time.$uid;
		// get device_id using mac address.
		$query = "SELECT device_id FROM device WHERE mac = '".$mac."'";
		$list = mysql_query($query);
		$info = mysql_fetch_array($list);
		$did = $info['device_id'];
		
		
		//get board_id using board's mac address.
		$query = "SELECT board_id FROM sensorboard WHERE ble_mac = '$sensorsmac'";
		$list = mysql_query($query);
		$info = mysql_fetch_array($list);
		$board_id = $info['board_id'];
		
		// Insert session information. 
		// endtime->0000-00-00 00:00:00. This means that this session is progressed.  Later >>>> When session stop, endtime has real data.
		//CHECK WHEN I AM SENDING VALUE FROM OTHER BOARD AT THE SAME TIME
		$query = "INSERT INTO session(starttime,endtime,user_id,device_id,board_id) VALUES('".$time."','0000-00-00 00:00:00','".$uid."','".$did."','".$board_id."')";
		mysql_query($query);

		$session_id = mysql_insert_id();
		
		$return_value = array("session_id" => $session_id);		
	
	}
	else
	{
		$return_value = array("session_id" => $obj->{'smac'});		
	}

	echo json_encode($return_value);
?>