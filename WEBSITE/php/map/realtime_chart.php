<?php
	include_once ("../include.php");

	
	$value2Return = $_POST['dataReturn'];


    if($value2Return == 0)
    {
		$query = "SELECT time, co, so2,no2,o3 FROM tmp_save 
		WHERE session_id = '$_POST[session]' 
		ORDER BY time DESC LIMIT 720 ";
		$list = mysql_query($query);
	
		$rows = array();
		$table = array();
		
		$table['cols'] = array(
							array('label' => 'Time', 'type' => 'string'),							
	        				array('label' => 'CO', 'type' => 'number'),	        				
	        				array('label' => 'NO2', 'type' => 'number'),
	        				array('label' => 'O3', 'type' => 'number'),
	        				array('label' => 'SO2', 'type' => 'number'));
		$countSample = 0;
		while($row = mysql_fetch_array($list))
		{

			if($countSample == 0)
			{
				$tmp 		= array();
				$timeValue 	= explode(" " , $row['time']);
				$timeValue 	= explode(":" , $timeValue[1]);
				$tmp[] 		= array('v' => $timeValue[0].":".$timeValue[1]); 

				$avgCO_min 		= 0;
				$avgNO2_min 	= 0;
				$avgO3_min 		= 0;
				$avgSO2_min 	= 0;
			}		
	        if($countSample == 12)
	        {
	        	$countSample 	= 0;
	        	$tmp[] 			= array('v' =>   round($avgCO_min/12*1000)/1000);
				$tmp[] 			= array('v' =>   round($avgNO2_min/12*1000)/1000);
				$tmp[] 			= array('v' =>  round($avgO3_min/12*1000)/1000); 	
				$tmp[] 			= array('v' =>  round($avgSO2_min/12*1000)/1000); 	
				$rows[] 		= array('c' => $tmp);
        	}
        	else
        	{
	        	$countSample 	= $countSample +1;
	        	$avgCO_min 		= $avgCO_min + $row['co'];
	        	$avgNO2_min 	= $avgNO2_min + $row['no2'];
	        	$avgO3_min 		= $avgO3_min + $row['o3'];	
	        	$avgSO2_min 		= $avgSO2_min + $row['so2'];	
	        }
				
		}

	}
	else
	{

		$query = "SELECT time, pm2d5 FROM tmp_save 
		WHERE session_id = '$_POST[session]' 
		ORDER BY time DESC LIMIT 720 ";
		$list = mysql_query($query);
	
		$rows = array();
		$table = array();
		
		$table['cols'] = array(
							array('label' => 'Time', 'type' => 'string'),							
	        				array('label' => 'pm2.5', 'type' => 'number'),	        				
	        				);
		$avgPM2d5_min 		= 0;	        				
		$countSample 		= 0;

		while($row = mysql_fetch_array($list))
		{

			if($countSample == 0)
			{
				$tmp 		= array();
				$timeValue 	= explode(" " , $row['time']);
				$timeValue 	= explode(":" , $timeValue[1]);
				$tmp[] 		= array('v' => $timeValue[0].":".$timeValue[1]); 

				$avgPM2d5_min 		= 0;
				
			}
		
	        if($countSample == 12)
	        {
	        	$countSample 	= 0;
	        	$tmp[] 			= array('v' =>   round($avgPM2d5_min/12*1000)/1000);
				
				$rows[] 		= array('c' => $tmp);
        	}
        	else
        	{
	        	$countSample 	= $countSample +1;
	        	$avgPM2d5_min 		= $avgPM2d5_min + $row['pm2d5'];
	        }
		}
	}
	
	$table['rows'] 	= array_reverse($rows);
	echo json_encode($table);
?>
