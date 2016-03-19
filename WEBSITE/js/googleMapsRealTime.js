
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
// google.setOnLoadCallback(drawChart);


//CHECK EVERY 5 SECONDS

function testMarker()
{
  // $(document).ready(function() {
  // $("#drop").click(function() {
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
              // console.log("to add ");
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
  
  // clearMarkers();

  // for (var j = 0; j < neighborhoods.length; j++) {
  //   addMarkerWithTimeout(neighborhoods[j],0,j);
  // }
  var count=0;
  for(var key in dictMarker)
  {
    addMarkerWithTimeout(dictMarker[key], 0, count);
    count = count + 1;
  }
  

}

//ADD MARKER
function addMarkerWithTimeout(infoMarker, timeout , sq) {
	if(infoMarker["print"] == 1)
  {
    position ={lat: infoMarker["lat"] , lng: infoMarker["lng"] }
    // window.setTimeout(function() {
      marker = new google.maps.Marker(
      {
        position: position, 
        map:      map,
        sq:       sq    // marker select sequence number.
      });       

    markers.push(marker);     	 
    
    marker.addListener('click', function() {

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
      air_update(sq);
      selectedStation = sq;
      ctrlShowData = true;

  	       // map.setCenter(marker.getPosition());
    });	
    // }, timeout); 
  }

}


function changeChart(whichChart)
{

  console.log(whichChart);
  if(valueReturn != whichChart)
  {
    valueReturn = whichChart;
  }
  
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
  
  if(ctrlShowData == true)
  {
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
    // document.getElementById("info15").innerHTML = arr[temp].rr;  
  }
  
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

// function oneCheckbox(check)
// {
//         var obj = document.getElementsByName("pollution");
//         for(var i=0; i<obj.length; i++)
//         {
//             if(obj[i] != check)
//             {
//                 obj[i].checked = false;
//             }
//         }
//     }

