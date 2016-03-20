<?php
include_once '../psl-config.php';   // As functions.php is not included
$MAC_ADDRESS = $_GET['mac'];
$ID_STATION = $_GET['id'];

$timestampToday = strtotime('today midnight');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if ($stmt = $mysqli->prepare("SELECT timestamp, Temperature, Humidity  FROM Data_Outdoor WHERE MAC_ADDRESS_STATION = ? AND ID_STATION = ? AND timestamp >= ?")) {
    $stmt->bind_param('sii', $MAC_ADDRESS  ,$ID_STATION, $timestampToday);  // Bind "$email" to parameter.
    
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();
	
    $stmt->bind_result($time ,$Temperature, $Humidity);
    $stmt->fetch();
    $Temperatures = array();
	$hum = array();
    $timestamp = array();
    while ($stmt->fetch()) {    	
        $timestamp[]       = date('Y/n/d H:i:s',$time);                
    	$Temperatures[]    = round($Temperature,3);		        
    	$hum[] 	           = round($Humidity,3);
    }

	echo json_encode(array('timestamp'=>$timestamp, 'temp'=>$Temperatures, 'hum'=>$hum));
}
else
{
	$rows=[1,2];	
	echo json_encode($rows);		   
}
?>


