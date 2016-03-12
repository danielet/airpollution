<?php 
include ("../include.php");

	   
   $query = "SELECT session.user_id,session.session_id,time,lat,lng,co,no2,so2,o3,temp,pm2d5,rr 
   			 FROM tmp_save JOIN session 
   			 WHERE session.session_id = tmp_save.session_id";
   $result = sql_query($query);
   
   $fields = array();
   while($row = mysql_fetch_assoc($result)) {
     $fields[] = $row;
   }
  
   echo json_encode($fields);
?>
