<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> 
    <script type="text/javascript" src="../js/allGoogleMapsRealTime.js"></script>     
      
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>  
  

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBG1PtQmrONJXlQFkAy0EQ5vQm6TEoEX8w&signed_in=true&callback=initMap&region=KR"></script> 
 
    
    <title>Sessions Saved</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/googleMapsCSS.css" rel="stylesheet">
    
  </head>

  <body>
  <div id="navbar" class="navbar-collapse collapse">
    <h1><span class="label navbar-left">Air Pollution Testbed</span></h1> 
  <form name="login_form" method="post" action="./login_match.php" class="navbar-form navbar-right">
  <div class="form-group">
  <a href="../php/user_info/logout.php"><input name="logout" value="Logout"type="button" placeholder="ID" class="btn btn-success"></a>
  <a href="./changeinfo.php"><button type="button" class="btn btn-success">Modify</button></a>                      
  <a href="../index.php"><button type="button" class="btn btn-success">Home</button></a>                      
  </div>
  </form>

  </div><!--/.navbar-collapse -->

  <div id="sidebar-wrapper">  
    <a href="./admin_login.php"><input value="Admin"type="button" class="btn btn-success vert"></a>
    <a href="./all_googlemap.php"><input value="All sessions"type="button" class="btn btn-success vert"></a>
    <a href="./googlemap.php"><input value="Real Time"type="button" class="btn btn-success vert"></a>         
  </div>  
  

  <div id="map">
  </div>

  
  <div id ='s_right'>  
  <div id='quick' >
  <span class="label label-primary mapReal">ID</span>
  <span id ="info1" class="label info_value " aria-describedby="basic-addon1"> ID VALUE </span>   
  </div>
        
  <div id='quick2'>
  <span class="label label-primary mapReal">TIME</span>
  <span id="info2" name ="2"  class='label info_value'   aria-describedby="basic-addon1">TIME</span>      
  </div>
  
       
  <div id = 'quick3'>
  <span class="label label-primary mapReal">LAT</span>  
  <span id="info3" name="3" class='label info_value'   aria-describedby="basic-addon1">LAT</span>
  </div>
          
  <div id = 'quick4'>
  <span class="label label-primary mapReal">LNG</span>
  <span id="info4" name="4" class='label info_value'  aria-describedby="basic-addon1">LNG</span>
  </div>
         
  <div id = 'quick5'>
  <span class="label label-success mapReal">CO</span>
  <span id="info5" name="4" class='label info_value'  aria-describedby="basic-addon1">CO</span>
  </div>
          
  <div id = 'quick6'>
  <span class="label label-success mapReal">NO2</span>
  <span id="info6" name="4" class='label info_value'  aria-describedby="basic-addon1">NO2</span>
  </div>         
  
       
  <div id='quick7'>              
  <span class="label label-success mapReal">SO2</span>
  <span id="info7" name="4" class='label info_value'  aria-describedby="basic-addon1">SO2</span>
  </div>
        
  <div id='quick8'>              
  <span class="label label-success mapReal">O3</span>
  <span id="info8" name="4" class='label info_value' aria-describedby="basic-addon1">CO</span>
  </div>
        
  <div id='quick13'>               
  <span class="label label-success mapReal">PM2.5</span>
  <span id="info13" name="4" class='label info_value'  aria-describedby="basic-addon1">PM2.5</span>
  </div>

<div id='quick14'>               
  <span class="label label-success mapReal">Temp[C]</span>
  <span id="info14" name="4" class='label info_value'  aria-describedby="basic-addon1">TEMP</span>
  </div>                         
  
          
  <div id='quick15'>               
  <span class="label label-success mapReal" >RR</span>
  <span id="info15" class="label info_value" aria-describedby="basic-addon1">RR</span>
  </div>



    	<div class="input-group">			 					
      </div>
      

      <form name="search_data" method="post" class="navbar-form navbar-right">           	
           	<br>
           	<br>
           <span class="label label-primary">SESSION</span>
           <input id="session" type="text" name="session_id" class="form-control" style="width:50px; left: 70px; top: -28px; height:20px;" aria-describedby="basic-addon1">
           <br>
           <input id ="pollution" type="checkbox" name="pollution" value="co" onclick="oneCheckbox(this);">CO
		   <input id ="pollution" type="checkbox" name="pollution" value="no2" onclick="oneCheckbox(this);">NO2
	 	   <input id ="pollution" type="checkbox" name="pollution" value="so2" onclick="oneCheckbox(this);">SO2
	 	   <input id ="pollution" type="checkbox" name="pollution" value="o3" onclick="oneCheckbox(this);">O3
	 	   <input id="start_time" name="start_time" type="date">
	 	   <input id="end_time" name="end_time" type="date">
	 	   <br>
	 	   <br>
	 	   <select id="creteria" name="creteria">
   		   
    	   <option value="minutely">minutely</option>
   	       <option value="hourly">hourly</option>
           <option value="daily">daily</option>
           <option value="monthly">monthly</option>
           <option value="yearly">yearly</option>
		   </select>		 
		   <input type="button" class="btn btn-primary" value="search" onclick="chart_information();"> 		   
	 	   </form>

	 	   <button id="drop" class="btn btn-warning" style="margin-left:-10px; width:200px;" onclick="drop();">Start</button>
	 	   <br>
          


          <div id="chart_div"></div>                           
          </div>
         </div>
  </body>
</html>