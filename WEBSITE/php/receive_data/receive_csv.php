<?php
include ("../include.php");
	

$file_path = "../csv/";                                           //csv file name save in database.
$file_path = $file_path . basename($_FILES['uploaded_file']['name']);

if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$file_path)) {
   
	echo "OK FILE UPLOADED"	;
}else{
	echo "FAILED TO UPLOAD FILE";
}
?>