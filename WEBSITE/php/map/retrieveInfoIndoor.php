<?php
include_once '../psl-config.php';   // As functions.php is not included
$MAC_ADDRESS = $_GET['mac'];
$ID_STATION = $_GET['id'];


$timestampToday = strtotime('today midnight');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if ($stmt = $mysqli->prepare("SELECT timestamp, Temperature, Humidity, CO2,Noise_Sound FROM Data_Indoor WHERE MAC_ADDRESS_STATION = ? AND ID_STATION = ? AND timestamp >= ? ")) {
    $stmt->bind_param('sii', $MAC_ADDRESS  ,$ID_STATION , $timestampToday);  // Bind "$email" to parameter.
    
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();
	
    // $stmt->bind_result($timestamp, $Temperature, $Humidity);
    $stmt->bind_result($time,$Temperature, $Humidity, $CO2, $Noise_Sound);
    $stmt->fetch();
    $timestamp = array();
    $Temperatures = array();
	$hum = array();
    $CO2Array = array();
    $NoiseArray = array();
    while ($stmt->fetch()) { 
        $timestamp[]       = date('Y/n/d H:i:s',$time);                   	
    	$Temperatures[]    = $Temperature;		        
    	$hum[] 	           = $Humidity;
        $CO2Array[]        = $CO2;
        $NoiseArray[]      = $Noise_Sound;
    }

	echo json_encode(array('timestamp' => $timestamp,'CO2'=>$CO2Array, 'Noise'=>$NoiseArray, 'Temperature' => $Temperatures, 'humidiy' => $hum));
}
else
{
	$rows=[1,2];	
	echo json_encode($rows);		   
}
?>


