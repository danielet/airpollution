<?php
include_once '../psl-config.php';   // As functions.php is not included
$MAC_ADDRESS = $_GET['mac'];
$ID_STATION = $_GET['id'];


$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if ($stmt = $mysqli->prepare("SELECT Temperature, Humidity  FROM Data_Outdoor WHERE MAC_ADDRESS_STATION = ? AND ID_STATION = ? ORDER BY timestamp DESC LIMIT 144 ")) {
    $stmt->bind_param('si', $MAC_ADDRESS  ,$ID_STATION);  // Bind "$email" to parameter.
    
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();
	
    // $stmt->bind_result($timestamp, $Temperature, $Humidity);
    $stmt->bind_result($Temperature, $Humidity);
    $stmt->fetch();
    $Temperatures = array();
	$hum = array();
    while ($stmt->fetch()) {
    	// $row = array($timestamp, $Temperature, $Humidity);		        
    	$Temperatures[] = round($Temperature,3);		        
    	$hum[] 	= round($Humidity,3);
    }

	echo json_encode(array('temp'=>$Temperatures, 'hum'=>$hum));
}
else
{
	$rows=[1,2];	
	echo json_encode($rows);		   
}
?>


