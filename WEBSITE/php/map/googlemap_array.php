<?php 
include ("../include.php");
	
   
   $query = "select session.session_id,user_id,time,lat,lng,co,no2,so2,o3,temp,pm2d5,rr,sum_co,sum_so2,sum_no2,sum_o3,sum_pm2d5,count
  			 from realtime, session
  			 where realtime.session_id = session.session_id";
   $result = sql_query($query);
   
   $fields = array();
   while($row = mysql_fetch_assoc($result)) {
     $fields[] = $row;
   }
  
   echo json_encode($fields);
?>
