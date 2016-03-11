
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


google.load('visualization', '1', {'packages':['corechart']});
google.setOnLoadCallback(drawChart);


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
					
          neighborhoods=[];
					for(var i=0; i<arr.length; i++)
					{				    
            neighborhoods[i] = {lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng)};										
					}
          drop();
					chart_information(remember_id);
					
          //TO END
          // drawChart(chart); 										
          air_update(temp);				
				}
			});				
		},5000);	
	// });
// });

}

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: {lat: 32.899, lng: -117.22}
  });

  
}


//CHANGE THIS FUNCTION

function drop() {
  clearMarkers();
  clearCircle();
  
  for (var j = 0; j < neighborhoods.length; j++) {
    addMarkerWithTimeout(neighborhoods[j],0,j);
    //pollution_color(neighborhoods[j],j,"black");
    // var checkType = document.getElementsByName('pollution');
 //    if(checkType[0].checked == true){
 //    	if(0<=arr[j].sum_co/arr[j].count && 4.4>=arr[j].sum_co/arr[j].count){
 //    		pollution_color(neighborhoods[j],j,"green");   
 //  		}
 //    else if(4.5<=(arr[j].sum_co/arr[j].count) && 9.4>=(arr[j].sum_co/arr[j].count)){    	
 //    		pollution_color(neighborhoods[j],j,"yellow");   
 //  		} 
 //  	else if(9.5<=(arr[j].sum_co/arr[j].count) && 12.4>=(arr[j].sum_co/arr[j].count)){  		
 //    		pollution_color(neighborhoods[j],j,"orange");   
 //  		} 
 //  	else if(12.5<=(arr[j].sum_co/arr[j].count) && 15.4>=(arr[j].sum_co/arr[j].count)){  		
 //    		pollution_color(neighborhoods[j],j,"red");   
 //  		} 
 //  	else if(15.5<=(arr[j].sum_co/arr[j].count) && 30.4>=(arr[j].sum_co/arr[j].count)){ 		
 //    		pollution_color(neighborhoods[j],j,"purple");   
 //  		} 
 //  	else if(30.5<=(arr[j].sum_co/arr[j].count)){ 		
 //    		pollution_color(neighborhoods[j],j,"maroon");   
 //  		} 
    
 //  	}
  	
 //  	if(checkType[1].checked == true){
    	
 //    if(0<=arr[j].sum_no2/arr[j].count && 53>=arr[j].sum_no2/arr[j].count){
 //    		pollution_color(neighborhoods[j],j,"green");   
 //  		}
 //    else if(54<=arr[j].sum_no2/arr[j].count && 100>=arr[j].sum_no2/arr[j].count){    	
 //    		pollution_color(neighborhoods[j],j,"yellow");   
 //  		} 
 //  	else if(101<=arr[j].sum_no2/arr[j].count && 360>=arr[j].sum_no2/arr[j].count){  		
 //    		pollution_color(neighborhoods[j],j,"orange");   
 //  		} 
 //  	else if(361<=arr[j].sum_no2/arr[j].count && 649>=arr[j].sum_no2/arr[j].count){  		
 //    		pollution_color(neighborhoods[j],j,"red");   
 //  		} 
 //  	else if(650<=arr[j].sum_no2/arr[j].count && 1249>=arr[j].sum_no2/arr[j].count){ 		
 //    		pollution_color(neighborhoods[j],j,"purple");   
 //  		} 
 //  	else if(1250<=arr[j].sum_no2/arr[j].count){ 		
 //    		pollution_color(neighborhoods[j],j,"maroon");   
 //  		} 
 //  	}
  	
 //  	if(checkType[2].checked == true){
    	
 //    if(0<=arr[j].sum_so2/arr[j].count && 35>=arr[j].sum_so2/arr[j].count){
 //    		pollution_color(neighborhoods[j],j,"green");   
 //  		}
 //    else if(36<=arr[j].sum_so2/arr[j].count && 75>=arr[j].sum_so2/arr[j].count){    	
 //    		pollution_color(neighborhoods[j],j,"yellow");   
 //  		} 
 //  	else if(76<=arr[j].sum_so2/arr[j].count && 185>=arr[j].sum_so2/arr[j].count){  		
 //    		pollution_color(neighborhoods[j],j,"orange");   
 //  		} 
 //  	else if(186<=arr[j].sum_so2/arr[j].count && 304>=arr[j].sum_so2/arr[j].count){  		
 //    		pollution_color(neighborhoods[j],j,"red");   
 //  		} 
 //  	else if(305<=arr[j].sum_so2/arr[j].count && 604>=arr[j].sum_so2/arr[j].count){ 		
 //    		pollution_color(neighborhoods[j],j,"purple");   
 //  		} 
 //  	else if(605<=arr[j].sum_so2/arr[j].count){ 		
 //    		pollution_color(neighborhoods[j],j,"maroon");   
 //  		} 
 //  	}
  	
 //  	if(checkType[3].checked == true){
    	
 //    if(0<=arr[j].sum_o3/arr[j].count && 54>=arr[j].sum_o3/arr[j].count){
 //    		pollution_color(neighborhoods[j],j,"green");   
 //  		}
 //    else if(55<=arr[j].sum_o3/arr[j].count && 70>=arr[j].sum_o3/arr[j].count){    	
 //    		pollution_color(neighborhoods[j],j,"yellow");   
 //  		} 
 //  	else if(71<=arr[j].sum_o3/arr[j].count && 85>=arr[j].sum_o3/arr[j].count){  		
 //    		pollution_color(neighborhoods[j],j,"orange");   
 //  		} 
 //  	else if(86<=arr[j].sum_o3/arr[j].count && 105>=arr[j].sum_o3/arr[j].count){  		
 //    		pollution_color(neighborhoods[j],j,"red");   
 //  		} 
 //  	else if(106<=arr[j].sum_o3/arr[j].count && 200>=arr[j].sum_o3/arr[j].count){ 		
 //    		pollution_color(neighborhoods[j],j,"purple");   
 //  		} 
 //  	else if(201<=arr[j].sum_o3/arr[j].count){ 		
 //    		pollution_color(neighborhoods[j],j,"maroon");   
 //  		} 
 //  	}
 }
}

function addMarkerWithTimeout(position, timeout,sq) {
	
	
  
  window.setTimeout(function() {
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
  air_update(sq); 
  ctrlShowData = true;
	       // map.setCenter(marker.getPosition());
  });	
  }, timeout); 

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
    document.getElementById("info2").innerHTML = arr[temp].time
    document.getElementById("info3").innerHTML = arr[temp].lat
    document.getElementById("info4").innerHTML = arr[temp].lng
    document.getElementById("info5").innerHTML = arr[temp].co
    document.getElementById("info6").innerHTML = arr[temp].no2
    document.getElementById("info7").innerHTML = arr[temp].so2
    document.getElementById("info8").innerHTML = arr[temp].o3
    document.getElementById("info13").innerHTML = arr[temp].pm2d5

    document.getElementById("info9").innerHTML = Math.round(arr[temp].sum_co/arr[temp].count);
    document.getElementById("info10").innerHTML = Math.round(arr[temp].sum_no2/arr[temp].count);
    document.getElementById("info11").innerHTML = Math.round(arr[temp].sum_so2/arr[temp].count);
    document.getElementById("info12").innerHTML= Math.round(arr[temp].sum_o3/arr[temp].count);


    document.getElementById("info14").innerHTML = arr[temp].temp;
    
    document.getElementById("info15").innerHTML = arr[temp].rr;  
  }
  // info1.text = arr[temp].user_id;
  // info2.value = arr[temp].time;
  // info3.value = arr[temp].lat;
  // info4.value = arr[temp].lng;
  // info5.value = arr[temp].co;
  // info6.value = arr[temp].no2;
  // info7.value = arr[temp].so2;
  // info8.value = arr[temp].o3;
  
  // info9.value = arr[temp].sum_co/arr[temp].count;
  // info10.value = arr[temp].sum_no2/arr[temp].count;
  // info11.value = arr[temp].sum_so2/arr[temp].count;
  // info12.value = arr[temp].sum_o3/arr[temp].count;
  
  // info13.value = arr[temp].pm2d5;
  // info14.value = arr[temp].temp;
  // info15.value = arr[temp].rr;	 		
		  
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

function oneCheckbox(check){
        var obj = document.getElementsByName("pollution");
        for(var i=0; i<obj.length; i++){
            if(obj[i] != check){
                obj[i].checked = false;
            }
        }
    }
function chart_information(session){
	 	$(document).ready(function() {
		var url = "../php/map/realtime_chart.php"
		
		$.ajax({
			type: "POST",
			url: url,
			data:{session:session},
			success: function(response) {
				chart=response;
						
			}
		});
		return false;
	});

}

function drawChart(chart) {

  // Create our data table out of JSON data loaded from server.
  var data = new google.visualization.DataTable(chart);
  var options = {
      title: 'Air Pollution',
      is3D: 'true',
      width: 400,
      height: 300,
     // explorer : {}  // vertical size change
    };
   
  // Instantiate and draw our chart, passing in some options.
  // Do not forget to check your div ID
  // var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  // chart.draw(data, options);
}
   