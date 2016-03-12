<?php 
include ("../include.php");
	
   
   $query = "SELECT session.session_id, session.starttime, session.user_id, session.device_id,
                    realtime.time, realtime.lat, realtime.lng, realtime.co,realtime.no2,realtime.so2,realtime.o3,realtime.pm2d5, 
                    realtime.sum_co,realtime.sum_no2,realtime.sum_so2,realtime.sum_o3,realtime.sum_pm2d5,
                    realtime.rr, realtime.temp, realtime.count, realtime.temp,
                    sensorboard.name,sensorboard.wifi_mac,sensorboard.ble_mac
  			     FROM realtime JOIN session JOIN sensorboard
  			     WHERE realtime.session_id = session.session_id AND session.board_id = sensorboard.board_id";

   $result = sql_query($query);
   
   $fields = array();
   while($row = mysql_fetch_assoc($result)) {
     $fields[] = $row;
   }
  
   echo json_encode($fields);
?>
