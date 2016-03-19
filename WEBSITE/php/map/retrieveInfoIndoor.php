<?php
include_once '../psl-config.php';   // As functions.php is not included
$MAC_ADDRESS = $_GET['mac'];
$ID_STATION = $_GET['id'];


$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if ($stmt = $mysqli->prepare("SELECT timestamp, Temperature, Humidity, CO2,Noise_Sound FROM Data_Indoor WHERE MAC_ADDRESS_STATION = ? AND ID_STATION = ? ORDER BY timestamp DESC LIMIT 144 ")) {
    $stmt->bind_param('si', $MAC_ADDRESS  ,$ID_STATION);  // Bind "$email" to parameter.
    
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();
	
    // $stmt->bind_result($timestamp, $Temperature, $Humidity);
    $stmt->bind_result($timestamp,$Temperature, $Humidity, $CO2, $Noise_Sound);
    $stmt->fetch();
    $Temperatures = array();
	$hum = array();
    $CO2Array = array();
    $NoiseArray = array();
    while ($stmt->fetch()) {    	
    	$Temperatures[]    = $Temperature;		        
    	$hum[] 	           = $Humidity;
        $CO2Array[]        = $CO2;
        $NoiseArray[]      = $Noise_Sound;
    }

	echo json_encode(array('CO2'=>$CO2Array, 'Noise'=>$NoiseArray));
}
else
{
	$rows=[1,2];	
	echo json_encode($rows);		   
}
?>


