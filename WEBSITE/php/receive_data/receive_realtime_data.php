<?
	include_once ("../include.php");
	
	$VALUE_HOUR = 720;

	$json 		= file_get_contents('php://input');
	$obj 		= json_decode($json);
	$user_id 	= $obj->{'user_id'};
	$mac 		= $obj->{'mac'};
	$time 		= $obj->{'time'};
	$lat 		= $obj->{'lat'};
	$lng 		= $obj->{'lng'};
	$co 		= $obj->{'co'};
	$so2 		= $obj->{'so2'};
	$no2 		= $obj->{'no2'};
	$o3 		= $obj->{'o3'};
	$temp 		= $obj->{'temp'};
	$pm2d5 		= $obj->{'pm2d5'};
	$hb 		= $obj->{'hb'};
	

	if(isset($obj->{'session_id'}))
	{
		$sid = $obj->{'session_id'};	
	}
	else
	{
		$query = "SELECT device_id FROM device WHERE mac = '".$mac."'";
		$list = mysql_query($query);
		$info = mysql_fetch_array($list);
		$did = $info['device_id'];
		   
		   
		// get session_id to userid and deviceid
		$query = "SELECT session_id FROM session WHERE user_id = '".$user_id."' and device_id = '".$did."' and endtime = '0000-00-00 00:00:00'";
		$list = mysql_query($query);
		$info = mysql_fetch_array($list);
		$sid = $info['session_id'];
	}


	
	// check update or insert in realtimedata and excute
	$sql = "SELECT sum_co, sum_so2, sum_no2, sum_o3, sum_pm2d5, count FROM realtime WHERE session_id = '$sid'";
	$qu = mysql_query($sql);
	
	if($r = @mysql_fetch_array($qu)){
		if($r['count'] == $VALUE_HOUR)
		{
			$sql = "UPDATE realtime SET time = '$time', lat = '$lat', lng = '$lng', co = '$co', so2 = '$so2', no2 = '$no2', o3 = '$o3',
						  pm2d5 = '$pm2d5',temp = '$temp',rr='$hb' ,sum_co = '$co', sum_so2 = '$so2', sum_no2 = '$no2', sum_o3 = '$o3', 
						  sum_pm2d5 = '$pm2d5', count = 1 WHERE session_id = '$sid'";
		}
		else 
		{
			$sql = "UPDATE realtime SET time = '$time', lat = '$lat', lng = '$lng', co = '$co', so2 = '$so2', no2 = '$no2', o3 = '$o3',
						   pm2d5 = '$pm2d5',temp = '$temp', rr='$hb'  ,sum_co = sum_co + '$co', sum_so2 = sum_so2+'$so2', sum_no2 = sum_no2+'$no2',
						   sum_o3 = sum_o3+'$o3', sum_pm2d5 = sum_pm2d5+'$pm2d5', count = count+1 WHERE session_id = '$sid'";
		}
	}
	else
	{
		$sql = "INSERT INTO realtime (session_id,time,lat,lng,co,no2,so2,o3,temp,pm2d5,sum_co,sum_no2,sum_so2,sum_o3,sum_pm2d5, count,rr) values('$sid','$time','$lat','$lng',  '$co','$no2','$so2','$o3','$temp','$pm2d5',  '$co','$no2','$so2','$o3','$pm2d5','1','$hb')";
	}
	mysql_query($sql);
	

	$sql 	= 	"SELECT COUNT(session_id) AS NUMROW FROM tmp_save WHERE session_id = '$sid'";
	$qu 	=	mysql_query($sql);
	$info	=	mysql_fetch_array($qu);
	
	if($info['NUMROW'] == $VALUE_HOUR )
	{
		$sql = "UPDATE tmp_save SET time= '$time', lat='$lat',lng='$lng',co='$co',no2='$no2',
				so2='$so2',o3='$o3',pm2d5='$pm2d5',temp='$temp'
				WHERE session_id ='$sid' ORDER BY time LIMIT 1";
	}
	else
	{
	// insert into tmp_save
		$sql = "INSERT INTO tmp_save (session_id,time,lat,lng,co,no2,so2,o3,pm2d5,temp,rr)
			VALUES('$sid','$time','$lat','$lng','$co','$no2','$so2','$o3','$pm2d5','$temp','$hb')";
	}
	mysql_query($sql);
	echo json_encode(array("status" => 1));
?>