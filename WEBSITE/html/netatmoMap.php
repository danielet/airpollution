<?php

  include_once '../php/db_connect.php';
  include_once '../php/include.php';
  sec_session_start();
?>

<html>
  <head>
    <title>Test netatmpo map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>     
    <!-- // <script type="text/javascript" src="../js/googleMapsRealTime.js"></script>      -->
    <script type="text/javascript" src="../js/createMaps.js"></script>     
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/googleMapsCSS.css" rel="stylesheet">

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBG1PtQmrONJXlQFkAy0EQ5vQm6TEoEX8w&signed_in=true&callback=initMap&region=US"></script>
  </head>
  <body onload="createTableAndMarkers();">
  <?php
    if(login_check()!= true)
    {    
      header('Location: http://airpollution.calit2.net/WEBSITE/');
    }
  ?>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <span class="label navbar-left" id='titleReal'>Air Pollution Testbed</span>
  
  <form  class="navbar-form navbar-right">
  <a href="./admin_login.php"><input value="Admin"type="button" class="btn btn-success"></a>
  <a href="../php/user_info/logout.php"><input name="logout" value="Logout"type="button" placeholder="ID" class="btn btn-success"></a>
  <a href="./changeinfo.php"><button type="button" class="btn btn-success">Modify</button></a>                      
  <a href="../index.php"><button type="button" class="btn btn-success">Home</button></a>                      
  </form>
  </form>
  </nav>

  <div class="row">
  <div id="sidebar-wrapper">  
    <a href="./googlemap.php"><input value="Real Time"type="button" class="btn vert"></a>   
    <a href="../html/netatmoMap.php"><input value="Netatmo"type="button" class="btn btn-success  vert"></a> 
    <a href=""><input value="Airbeam"type="button" class="btn  vert"></a>               
    <a href="./all_googlemap.php"><input value="All sessions"type="button" class="btn vert"></a>
  </div>  
  
  <div id="map">
    
  </div>

  <div id ='s_right'>  
  </div>                         
          
  <div class='rowChar'>                           

  <div id='bottom_left_Netatmo'>
  <input value="Outdoor" type="button" id="outdoor" class="btn vert" onclick="changeChartOutdoor()">
  <input value="Indoor"  type="button" id="indoor" class="btn vert" onclick="changeChartIndoor()">
</div>

<div id='chart_div'>
</div>

<div id='bottom_right_Netatmo'>
</div>  

</div>  
  

  </body>
</html>
