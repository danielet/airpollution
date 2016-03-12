<?php
	include ("../include.php");

    
	$query = "SELECT * FROM tmp_save 
			  WHERE session_id = '$_POST[session]' 
			  ORDER BY time DESC LIMIT 750 ";
	$list = mysql_query($query);
	
	
	$rows = array();
	$table = array();
		
	$table['cols'] = array(
							array('label' => 'Time', 'type' => 'string'),
							
	        				array('label' => 'CO', 'type' => 'number'),
	        				// array('label' => 'SO2', 'type' => 'number'),
	        				array('label' => 'NO2', 'type' => 'number'),
	        				array('label' => 'O3', 'type' => 'number'),
	        				// array('label' => 'PM2.5', 'type' => 'number'),
	        // array('label' => 'RR', 'type' => 'number'),
	        				// array('label' => 'temp', 'type' => 'number'),
	);
		
	while($row = mysql_fetch_array($list))
	{
		$tmp 		= array();
		$timeValue 	= explode(" " , $row['time']);
		// echo $row['time'];
		$tmp[] 		= array('v' => $timeValue[1]); 
          // Values of each slice	
		
		$tmp[] 		= array('v' =>   $row['co']);
		// $tmp[] 		= array('v' =>   $row['so2']);
		$tmp[] 		= array('v' =>   $row['no2']);
		$tmp[] 		= array('v' =>  $row['o3']); 
		// $temp[] = array('v' =>  $r['temp']);
		// $tmp[] 		= array('v' =>   $row['pm2d5']);

		$rows[] 	= array('c' => $tmp);
	}
	$table['rows'] 	= array_reverse($rows);
	// $table['rows'] 	= $rows;
	echo json_encode($table);
?>
