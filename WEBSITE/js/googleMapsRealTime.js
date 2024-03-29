
var remember_id; // when marker click, session_id restore
var chart; // marker session_id inpormation restore.
var temp;  // sq num, air_data sq.
var neighborhoods = new Array();
var markers = new Array();
var map;
var marker;
var arr = new Array();
var cityCircle = new Array();
var newCircle = new Array();
var ctrlShowData = false;


var selectedStation;

var dictMarker={};


var valueReturn = 0;

google.load('visualization', '1', {'packages':['corechart']});

//CHECK EVERY 5 SECONDS

function testMarker()
{

		var url = "../php/map/googlemap_array.php"	
			$.ajax({
				type: "POST",
				url: url,
				async: false, 
				success: function(response) {
					arr = JSON.parse(response);
					
					for(var i=0; i<arr.length; i++)
					{	          
            if(arr[i].name in dictMarker )
            {          
              latlng = dictMarker[arr[i].name];

              //check if lat and lng              
              if(latlng["lat"] == parseFloat(arr[i].lat) &&  latlng["lng"] == parseFloat(arr[i].lng)  ){
                dictMarker[arr[i].name]={lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng), print: 1};
              }
					  }
            else
            {
              dictMarker[arr[i].name]={lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng), print: 1};
              console.log("to add ");              
            }
          }
          drop();          
				}
			});				

  loopFunction();
}


function loopFunction()
{
  var url = "../php/map/googlemap_array.php"  
  setInterval(function (){
      $.ajax({
        type: "POST",
        url: url,
        async: false, 
        success: function(response) {
          arr = JSON.parse(response);
          
          // neighborhoods=[];
          for(var i=0; i<arr.length; i++)
          { 
            // neighborhoods[i] = {lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng)};                            
            if(arr[i].name in dictMarker )
            {          
              latlng = dictMarker[arr[i].name];

              //check if lat and lng              
              if(latlng["lat"] == parseFloat(arr[i].lat) &&  latlng["lng"] == parseFloat(arr[i].lng)  ){
                dictMarker[arr[i].name]={lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng), print: 0};
              }
            }
            else
            {
              dictMarker[arr[i].name]={lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng), print: 1};
              console.log("to add ");
              // addMarkerWithTimeout(arr[i], i);
            }
          }
          drop();
          //TO END
          if(ctrlShowData == true)
          {
            chart_information(remember_id);
            drawChart(chart , arr[selectedStation].name);                     
          }
          air_update(temp);       
        }
      });       
    },5000); 
}

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: {lat: 32.899, lng: -117.22}
  });

  
}

//CHANGE THIS FUNCTION
function drop() {  
  
  var count=0;
  for(var key in dictMarker)
  {
    addMarkerWithTimeout(dictMarker[key], count);
    count = count + 1;
  }
  

}
var beforeChecked = false
//ADD MARKER
function addMarkerWithTimeout(infoMarker , sq) {
	if(infoMarker["print"] == 1)
  {
    var pinColor = "FE7569";
    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" 
                            + pinColor,
                new google.maps.Size(21, 34),
                new google.maps.Point(0,0),
                new google.maps.Point(10, 34)
                );

    position ={lat: infoMarker["lat"] , lng: infoMarker["lng"] }
    marker = new google.maps.Marker(
      {
        position: position, 
        map:      map,
        sq:       sq,    // marker select sequence number.
        icon:pinImage
      });       

    markers.push(marker);     	     
    google.maps.event.addListener(marker, 'click', (function (marker, sq) {
      return function () {  
      
        remember_id = arr[sq].session_id;
        chart_information(arr[sq].session_id);                            
        if(valueReturn ==0)
        {      
          document.getElementById("alpha").style.background = "#f0ad4e";
        }
        else
        {
         document.getElementById("pm2d5").style.background = "#f0ad4e"; 
        }

        var pinColor = "008000";
        var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" 
                              + pinColor,
                  new google.maps.Size(21, 34),
                  new google.maps.Point(0,0),
                  new google.maps.Point(10, 34)
                  );
        marker.setIcon(pinImage);
        air_update(sq);
        
        ctrlShowData = true;

        chart_information(arr[sq].session_id);
        drawChart(chart , arr[sq].name);
        map.setCenter(marker.getPosition());

        //CHANGE THE COLOR
        if(beforeChecked == true)
        {          
          var pinColor = "FE7569";
          var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" 
                              + pinColor,
                  new google.maps.Size(21, 34),
                  new google.maps.Point(0,0),
                  new google.maps.Point(10, 34)
          );
          markers[selectedStation].setIcon(pinImage);
        }              
        selectedStation = sq;
        beforeChecked = true

      }
    })(marker, sq));;
  }

}


function changeChart(whichChart)
{

  if(valueReturn != whichChart)
  {
    valueReturn = whichChart;
    

  }
  
  chart_information(remember_id);
  drawChart(chart , arr[selectedStation].name);                     

  if(ctrlShowData == true)
  {
    if(valueReturn ==0)
      {      
        document.getElementById("alpha").style.background = "#f0ad4e";
        document.getElementById("pm2d5").style.background = "#c0c0c0";
      }
      else
      {
        document.getElementById("alpha").style.background = "#c0c0c0";
        document.getElementById("pm2d5").style.background = "#f0ad4e"; 
      }
  }


}

function setMapOnAll(map) {
  for (var k = 0; k < markers.length; k++) {
    markers[k].setMap(map);   
  }
}

function clearMarkers() {
 setMapOnAll(null);
 markers = [];
 
}

function Circle_setMapOnAll(map) {
  for (var k = 0; k < cityCircle.length; k++) {
    cityCircle[k].setMap(map);   
  }
}



function clearCircle(){
	Circle_setMapOnAll(null);
	cityCircle = [];
}

function air_update(sq)
{
	temp = sq;		
  
    // document.getElementById("info1").innerHTML = arr[temp].user_id
    document.getElementById("info2").innerHTML = arr[temp].time.split(" ")[1];
    document.getElementById("info3").innerHTML = Math.round((arr[temp].lat*100))/100;
    document.getElementById("info4").innerHTML = Math.round((arr[temp].lng*100))/100;
    document.getElementById("info5").innerHTML = arr[temp].co
    document.getElementById("info6").innerHTML = arr[temp].no2
    document.getElementById("info7").innerHTML = arr[temp].so2

    document.getElementById("info8").innerHTML = Math.round(arr[temp].o3*100)/100;
    document.getElementById("info13").innerHTML = arr[temp].pm2d5

    document.getElementById("info9").innerHTML = Math.round((arr[temp].sum_co/arr[temp].count)*100)/100;
    document.getElementById("info10").innerHTML = Math.round((arr[temp].sum_no2/arr[temp].count)*100)/100;
    document.getElementById("info11").innerHTML = Math.round((arr[temp].sum_so2/arr[temp].count)*100)/100;
    document.getElementById("info12").innerHTML= Math.round((arr[temp].sum_o3/arr[temp].count)*100)/100;


    document.getElementById("info14").innerHTML = arr[temp].temp;    
  
}

function chart_information(session){
	 	// $(document).ready(function() {
    // console.log(session)
		var url = "../php/map/realtime_chart.php"		

		$.ajax({
			type: "POST",    
			url: url,
      async:false,
      data:{session:session, dataReturn:valueReturn},
			success: function(response) {
        arr2 = JSON.parse(response);
				chart=arr2;        
			},

      error: function(textStatus, errorTrown){
        console.log(textStatus);
      }
		});
		return false;
	

}

function drawChart(chart, name) {

  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(chart);
  var stringYaxis = 'ppb';
  if(valueReturn != 0)
  {
    stringYaxis = 'ug/m^3';
  }
  
  var options = {
    title: 'Air Pollution ' + name,
    legend: { position: 'right', alignment: 'start' },
    // is3D: 'true',
    explorer : {},  // vertical size change
    // curveType: "function",
    backgroundColor: '#E4E4E4',
    series: {
            targetAxisIndex:0
    },
    
    vAxes: {            
        0:{title: stringYaxis}      
    },
    hAxes:{
      0:{title: 'Time 1 hour'},
      
    }         
    };
   

   
  // Instantiate and draw our chart, passing in some options.
  // Do not forget to check your div ID
  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
   
function pollution_color(neighborhoods,j,color){
      
      cityCircle.push(new google.maps.Circle({
      strokeColor: color,
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: color,
      fillOpacity: 0.35,
      map: map,
      center:neighborhoods,
      radius: Math.sqrt(1) * 100    
    }))  
}



