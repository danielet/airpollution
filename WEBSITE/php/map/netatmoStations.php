<?php

//OPEN CONNECTION
include_once '../psl-config.php';   // As functions.php is not included
include_once '../db_connect.php';

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

$sql 	= "SELECT * FROM Stations";
$result = $mysqli->query($sql);
$rows = array();
if ($result->num_rows > 0) {
    // output data of each row    
    while($row = $result->fetch_assoc()) {
    	$rows[] = $row;
        
    }
} 
else 
{
    echo "0 results";
}
$mysqli->close();
echo json_encode($rows);
?>





