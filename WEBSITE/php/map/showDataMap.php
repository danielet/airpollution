<?
include_once "../include.php";

function dataFromTmpSave()
{
   $query = "SELECT session.user_id,session.session_id, time,lat,lng,co,no2,so2,o3,temp,pm2d5,rr 
   			 FROM tmp_save JOIN session 
   			 WHERE session.session_id = tmp_save.session_id";
   $result = sql_query($query);
   
   $fields = array();
   while($row = mysql_fetch_assoc($result)) {
     $fields[] = $row;
   }
  
   echo json_encode($fields);
}


function dataFromRealTime()
{
	$query = "SELECT session.session_id,user_id,time,lat,lng,co,no2,so2,o3,temp,pm2d5,rr,sum_co,sum_so2,sum_no2,sum_o3,sum_pm2d5,count
  			 FROM realtime JOIN session
  			 WHERE realtime.session_id = session.session_id";

	$result = sql_query($query);
   
	$fields = array();
	while($row = mysql_fetch_assoc($result)) {
		$fields[] = $row;
   	}
  
   echo json_encode($fields);
}

?>