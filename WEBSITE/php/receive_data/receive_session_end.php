<?php
include ("../include.php");
// echo "<meta charset='utf-8'>";
$path = "../csv/";
$json = file_get_contents('php://input');
	if($json != false)
	{

		$obj 		= json_decode($json);
		$uid 		= $obj->{'uid'};	     	 	// It is used when insert session table.
		$mac 		= $obj->{'mac'};				// It is used when get device_id.
		$filename 	= $obj->{'filename'};	   // It is user when insert to file_info table
		$time 		= $obj->{'time'};		      // It is used when insert endtime to session table.

		if(isset($obj->{'session_id'}))
		{
			$sid = $obj->{'session_id'};	
			$query = "DELETE FROM tmp_save WHERE session_id = '".$sid."';";
			mysql_query($query);
			$query = "DELETE FROM realtime WHERE session_id = '".$sid."';";
			mysql_query($query);
			$query = "INSERT INTO file_info (session_id,file_name, path) VALUES ('".$sid."','".$filename."','".$path."');";
			mysql_query($query);
		}
		else
		{


		// set path, save a csv file name and location in database.
		
		// get device_id using mac address.
			$query = "SELECT device_id FROM device WHERE mac = '".$mac."';";
			$list = mysql_query($query);
			$info = mysql_fetch_array($list);
			$did = $info['device_id'];
			

			// get session_id using user id and device id.
			$query = "SELECT session_id FROM session WHERE user_id = '".$uid."' AND device_id = '".$did."' AND endtime = '0000-00-00 00:00:00';";
			$list = mysql_query($query);
			$info = mysql_fetch_array($list);
			$sid = $info['session_id'];	
			
			
			// get session_id 
			$query 	= "SELECT session_id FROM session WHERE user_id = '".$uid."' AND device_id = '".$did."' AND endtime = '0000-00-00 00:00:00';";
			$list 	= mysql_query($query);
			
			// delete out put session_id's data.
			while($info = mysql_fetch_array($list))
			{
				$id = $info['session_id'];
				$query = "DELETE FROM tmp_save WHERE session_id = '".$sid."';";
				mysql_query($query);
			}
			$query = "DELETE FROM realtime WHERE session_id = '".$sid."';";
			mysql_query($query);
		}

	
	// // get session_id from valid data of session table.
	// $query = "SELECT session_id FROM session WHERE user_id = '".$uid."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00';";
	// $list = mysql_query($query);
	// $info = mysql_fetch_array($list);
	// $sid = $info['session_id'];
	
	//echo $time.$sid.$did;
	
	// when session stop, insert endtime to session table. It means session is end.
		$query = "UPDATE session SET endtime = '".$time."' WHERE session_id = '" .$sid. "'";
		mysql_query($query);	
		$return_value = array("ctrl_msg" => 0);		
	}
	else
	{
		$return_value = array("ctrl_msg" => -1);		
	}

	echo json_encode($return_value);
?>