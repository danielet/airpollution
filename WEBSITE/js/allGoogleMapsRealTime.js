        
var remember_id; // when marker click, session_id restore
var chart; // marker session_id inpormation restore.
var temp;
var neighborhoods = new Array();
var markers = new Array();
var map;
var marker;
var value;
var arr = new Array();
var cityCircle = new Array();
var newCircle = new Array();
google.load('visualization', '1', {'packages':['corechart']});
google.setOnLoadCallback(drawChart);

$(document).ready(function() {
	$("#drop").click(function() {
	
		var url = "../php/map/all_googlemap_array.php"		
		setInterval(function (){
	
			$.ajax({
				type: "POST",
				url: url,
				async: false, //동기 방식
				success: function(response) {
					arr = JSON.parse(response);
					//10초마다 리플리쉬 시킨다 1000이 1초가 된다.
					neighborhoods=[];
					for(var i=0; i<arr.length; i++)
					{				
					neighborhoods[i] = 
					{lat: parseFloat(arr[i].lat), lng: parseFloat(arr[i].lng)};										
					 }
					drop();								
				}
			});				
		},5000);	
	});
});



function initMap() {
	 map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: {lat: 32.899, lng: -117.19}
  });
}

function drop() {
  clearMarkers();
  for (var j = 0; j < neighborhoods.length; j++) {
    addMarkerWithTimeout(neighborhoods[j],0,j);
    //pollution_color(neighborhoods[j],j,"black");
  	
 }
}

function addMarkerWithTimeout(position, timeout,sq) {
	var image="../img/marker1.png";
	/*if(sq==0){
	 image = "../images/icons/googlemap_icon.png";
	}
	if(sq==1){
	image = "../images/icons/googlemap_icon.png";
	}
	if(sq==2){
	 image = "../images/icons/googlemap_icon.png";
	}*/
	
  
  window.setTimeout(function() {
  	 marker = new google.maps.Marker(
    	{position: position, 
    	 map: map,
    	 icon: image,
    	 sq:sq    // marker select sequence number.
    	 }
    	);       
       	 markers.push(marker);       	 
    	 marker.addListener('click', function() {
   	 	 air_update(sq); 
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


function air_update(sq)
{
	temp = sq;
		// select marker, show data
    	     info1.value = arr[temp].user_id;
    	     info2.value = arr[temp].time;
             info3.value = arr[temp].lat;
		     info4.value = arr[temp].lng;
		 	 info5.value = arr[temp].co;
		 	 info6.value = arr[temp].no2;
		 	 info7.value = arr[temp].so2;
		 	 info8.value = arr[temp].o3;
		 	 info13.value = arr[temp].pm2d5;
		 	 info14.value = arr[temp].temp;
		 	 info15.value = arr[temp].rr;
		 	 session.value = arr[temp].session_id; 		
		  
}

function chart_information(){
	 	$(document).ready(function() {
		var url = "../php/map/search_chart.php"
		var form_data = {
			start_time : $("#start_time").val(),
			end_time : $("#end_time").val(),
			pollution: value,
			session_id: $("#session").val(),
			creteria: $("#creteria").val(),
			
		};
		$.ajax({
			type: "POST",
			url: url,
			data:form_data,
			success: function(response) {
				chart=response;
				drawChart(chart);
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
              height: 300
            };
           
          // Instantiate and draw our chart, passing in some options.
          // Do not forget to check your div ID
          var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
          chart.draw(data, options);
        }

function oneCheckbox(check){
        var obj = document.getElementsByName("pollution");
        for(var i=0; i<obj.length; i++){
            if(obj[i] != check){
                obj[i].checked = false;
            }
            else
            	value = obj[i].value;
        }
    }