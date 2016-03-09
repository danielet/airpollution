<?php
	include ("../include.php");

    
	$query = "select * from tmp_save where session_id = '$_POST[session]' order by time";
	$list = mysql_query($query);
	
	
	$rows = array();
	    $table = array();
		
		$table['cols'] = array(
	
	        array('label' => 'Time', 'type' => 'string'),
	        array('label' => 'CO', 'type' => 'number'),
	        array('label' => 'SO2', 'type' => 'number'),
	        array('label' => 'NO2', 'type' => 'number'),
	        array('label' => 'O3', 'type' => 'number'),
	        array('label' => 'temp', 'type' => 'number'),
	        array('label' => 'PM2.5', 'type' => 'number'),
	        array('label' => 'RR', 'type' => 'number'),
	
	    );
		
		while($r = mysql_fetch_array($list))
		{
			$temp = array();
	
	          // the following line will be used to slice the Pie chart
	
	          $temp[] = array('v' => $r['time']); 
	
	          // Values of each slice
	
	          $temp[] = array('v' =>  $r['co']);
	          $temp[] = array('v' =>  $r['so2']);
	          $temp[] = array('v' =>  $r['no2']);
	          $temp[] = array('v' =>  $r['o3']); 
	          $temp[] = array('v' =>  $r['temp']);
	          $temp[] = array('v' =>  $r['pm2d5']);
			  $temp[] = array('v' =>  $r['rr']);
			  
	          $rows[] = array('c' => $temp);
		}
		$table['rows'] = $rows;
		echo json_encode($table);
?>
