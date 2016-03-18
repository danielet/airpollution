
<?php
include_once '../php/include.php';
sec_session_start();

?>


<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>     
    <script type="text/javascript" src="../js/googleMapsRealTime.js"></script>     


    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/googleMapsCSS.css" rel="stylesheet">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBG1PtQmrONJXlQFkAy0EQ5vQm6TEoEX8w&signed_in=true&callback=initMap&region=US"></script>
  <title>Real Time Data</title>

  </head>

  <body onload="testMarker();">      
  <!-- <body >       -->
<?php
    if(login_check()!= true)
    {    
      header('Location: http://airpollution.calit2.net/WEBSITE/');
    }
  ?>


  <nav class="navbar navbar-inverse navbar-fixed-top">
  <!-- <div  class="navbar-collapse collapse "> -->
    <span class="label navbar-left" id='titleReal'>Air Pollution Testbed</span>
  
  <form  class="navbar-form navbar-right">
  <a href="./admin_login.php"><input value="Admin"type="button" class="btn btn-success"></a>
  <a href="../php/user_info/logout.php"><input name="logout" value="Logout"type="button" placeholder="ID" class="btn btn-success"></a>
  <a href="./changeinfo.php"><button type="button" class="btn btn-success">Modify</button></a>                      
  <a href="../index.php"><button type="button" class="btn btn-success">Home</button></a>                      
  </form>
  </form>
  <!-- </div>   -->
  </nav>

<div class="row">
  <div id="sidebar-wrapper">  
    <a href="./googlemap.php"><input value="Real Time"type="button" class="btn btn-success vert"></a>   
    <a href=""><input value="Netatmo"type="button" class="btn  vert"></a> 
    <a href=""><input value="Airbeam"type="button" class="btn  vert"></a>               
    <a href="./all_googlemap.php"><input value="All sessions"type="button" class="btn vert"></a>
  </div>  
  <div id="map">
  </div>
<div id ='s_right'>  
    <!-- <h1><span class="label label-info" style="width:100%">INFO Real Time</span></h1> -->
  <!-- <div id='quick' >
  <span class="label label-info mapReal">ID</span>
  <span id ="info1" class="label info_value " aria-describedby="basic-addon1"> ID VALUE </span>   
  </div> -->     
  <div id='quick2'>
  <span class="label label-info mapReal">TIME</span>
  <span id="info2" name ="2"  class='label info_value'   aria-describedby="basic-addon1">TIME</span>      
  </div>      
  <div id = 'quick3'>
  <span class="label label-info mapReal">LAT</span>  
  <span id="info3" name="3" class='label info_value'   aria-describedby="basic-addon1">LAT</span>
  </div>
          
  <div id = 'quick4'>
  <span class="label label-info mapReal">LNG</span>
  <span id="info4" name="4" class='label info_value'  aria-describedby="basic-addon1">LNG</span>
  </div>
         
  <div id = 'quick5'>
  <span class="label label-info mapReal">CO</span>
  <span id="info5" name="4" class='label info_value'  aria-describedby="basic-addon1">CO</span>
  </div>
          
  <div id = 'quick6'>
  <span class="label label-info mapReal">NO2</span>
  <span id="info6" name="4" class='label info_value'  aria-describedby="basic-addon1">NO2</span>
  </div>         
  
       
  <div id='quick7'>              
  <span class="label label-info mapReal">SO2</span>
  <span id="info7" name="4" class='label info_value'  aria-describedby="basic-addon1">SO2</span>
  </div>
        
  <div id='quick8'>              
  <span class="label label-info mapReal">O3</span>
  <span id="info8" name="4" class='label info_value' aria-describedby="basic-addon1">CO</span>
  </div>
        
  <div id='quick13'>               
  <span class="label label-info mapReal">PM2.5</span>
  <span id="info13" name="4" class='label info_value'  aria-describedby="basic-addon1">PM2.5</span>
  </div>

  <div id='quick9'>              
  <span class="label label-primary mapReal">AVG_CO</span>
  <span id="info9" name="4" class='label info_value'  aria-describedby="basic-addon1">AVG_CO</span>
  </div>
          
  <div id='quick10'>               
  <span class="label label-primary mapReal">AVG_NO2</span>
  <span id="info10" name="4" class='label info_value'  aria-describedby="basic-addon1">AVG_NO2</span>
  </div>
          
  <div id='quick11'>               
  <span class="label label-primary mapReal">AVG_SO2</span>
  <span id="info11" name="4" class='label info_value'  aria-describedby="basic-addon1">AVG_SO2</span>
  </div>
          
  <div id='quick12'>               
  <span class="label label-primary mapReal">AVG_O3</span>
  <span id="info12" name="4" class='label info_value'  aria-describedby="basic-addon1">AVG_O3</span>
  </div>
          
  <div id='quick14'>               
  <span class="label label-info mapReal">Temp[C]</span>
  <span id="info14" name="4" class='label info_value'  aria-describedby="basic-addon1">TEMP</span>
  </div>                         
  
          
  <!-- <div id='quick15'>               
  <span class="label label-info mapReal" >RR</span>
  <span id="info15" class="label info_value" aria-describedby="basic-addon1">RR</span>
  </div> -->

</div>
  
  
      
  <!-- <form name="sendForm" method="get" >
  <input type="checkbox" name="pollution"  value="co" aria-describedby="basic-addon1" onclick="oneCheckbox(this);"> 
  <span class='label info_value' aria-describedby="basic-addon1">CO</span> </input>
  <input type="checkbox" name="pollution" value="no2" onclick="oneCheckbox(this);">
  <span class='label info_value' aria-describedby="basic-addon1">NO2</span> </input>
  <input type="checkbox" name="pollution" value="so2" onclick="oneCheckbox(this);">
  <span class='label info_value' aria-describedby="basic-addon1">SO2</span> </input>
  <input type="checkbox" name="pollution" value="o3" onclick="oneCheckbox(this);">
  <span class='label info_value' aria-describedby="basic-addon1">O3</span> </input>
  </form> -->
	 	   
  
  <!-- <button id="drop" class="btn btn-warning" style="width:100%;" onclick="drop();">Start</button>    -->
</div>      

<div class='rowChar'>                           

<div id='bottom_left'>
  <input value="Alphasense"type="button" id="alpha" class="btn vert" onclick="changeChart(0)">
  <input value="PM 2.5"     type="button" id="pm2d5" class="btn  vert" onclick="changeChart(1)">
</div>

<div id='chart_div'>
</div>

<div id='bottom_right'>
</div>  

</div>  
  

  </body>
</html>
