var numberMarker = 0;
var myLatLng     = new Array();
/* call the php that has the php array which is json_encoded */
var ourStations     =[];  
var map;
var allMarkers  =[];


var markerSelected;

google.load('visualization', '1', {'packages':['corechart']});
function initMap() 
{
    map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: {lat: 32.899, lng: -117.22}
  }); 
}

var prevMarker ;

function createTableAndMarkers()
{
          
        var url = "../php/map/netatmoStations.php"   
        $.ajax({
          type: "POST",    
          url: url,
          async:true,
          success: function(response) {
            var arr2 = JSON.parse(response);
            for(var ii=0;ii<arr2.length;ii++)
            {
              var val = arr2[ii];              
              if(val.Ownership == 1)
              {
                var pinColor = "008000";                 
              }
              else
              {
                var pinColor = "FE7569";
              }
              var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" 
                            + pinColor,
                new google.maps.Size(21, 34),
                new google.maps.Point(0,0),
                new google.maps.Point(10, 34)
              );                  
      
              myLatLng[numberMarker]  = {lat: Number(val.Longitude), lng: Number(val.Latitude) };  

              var marker = new google.maps.Marker({   
                position: myLatLng[numberMarker],                    
                map: map,                  
                icon: pinImage,  
                sq:ii,
                ownership:val.Ownership
              });                   
        
              marker.set("id", val.MAC_ADDRESS_STATION+";"+val.ID_STATION);
              allMarkers.push(marker);            

              numberMarker = numberMarker+1;  
              google.maps.event.addListener(marker, 'click', (function (marker, ii) {
                return function () {                  
                  arrayID=marker.id.split(";");
                  
                  //plot chart!                  
                  $("#outdoor").prop("disabled",false);
      
                  if(marker.ownership == 1)
                  {
                    $("#indoor").prop("disabled",false);
                    drawChartIndoor(arrayID[0], arrayID[1], marker.ownership)                     
                  }
                  else
                  {
                    $("#indoor").prop("disabled",true);
                    drawChartOutdoor(arrayID[0], arrayID[1], marker.ownership) 
                  }

                  var pinColor = "000000";
                  var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" 
                            + pinColor,
                  new google.maps.Size(21, 34),
                  new google.maps.Point(0,0),
                  new google.maps.Point(10, 34)
                  );
                  marker.setIcon(pinImage);

                  if(markerSelected != null)
                  {
                    
                    var pinColor
                    if(markerSelected.ownership == 1)                    
                    {
                      pinColor = "008000";                 
                    }
                    else
                    {
                      pinColor = "FE7569";
                    }
                    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" 
                          + pinColor,
                    new google.maps.Size(21, 34),
                    new google.maps.Point(0,0),
                    new google.maps.Point(10, 34)
                    );                  
                    markerSelected.setIcon(pinImage);
                  }                
                  
                  markerSelected = marker;

                };
              })(marker, ii));
              }                          
            
        },
        error: function(textStatus, errorTrown){
          console.log(textStatus);
        }
      });
      
      $("#outdoor").prop("disabled",true);
      $("#indoor").prop("disabled",true);
    
}

  
function drawChartOutdoor(macvar, idvar, Ownership) 
{      
      var test = new google.visualization.DataTable();
                        
      $.ajaxSetup({
          async: false
      });

      // if(Ownership == 0)
      // {
        $.getJSON('../php/map/retrieveInfo.php',{mac:macvar,id:idvar} ,function(data) {
          format: "json"   

        })
        .fail(function() {
          console.log( "error" );
        })
        .done(function(data){        
          test.addColumn('string', 'Time');
          test.addColumn('number', 'Temperature');
          test.addColumn('number', 'Humidity');     
        
          stringData=data.timestamp[0].split(" ");
          for (var ii=0; ii < data.temp.length; ii++)
          {        
            test.addRow([ data.timestamp[ii].split(" ")[1]  , data.temp[ii],data.hum[ii] ]);     
          }
          
                
          document.getElementById("outdoor").style.background = "#f0ad4e"; 
          document.getElementById("indoor").style.background = "#c0c0c0";
      
        });
            
        var options = {
            title: 'NetAtmo Outdoor ' + stringData[0] + ' Station: '+ macvar,            
            legend: { position: 'rigth' },

            series: {
                0: {targetAxisIndex:0},
                1: {targetAxisIndex:1}
            },
             vAxes: {
              // Adds titles to each axis.
              0: {title: 'Temperature [C]'},
              1: {title: 'Humidity [Hg]'}
            },
        };

      // }
      
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(test, options);
    }

function changeChartOutdoor()
{

  arrayID=markerSelected.id.split(";");
  console.log(arrayID[0]);
  drawChartOutdoor(arrayID[0], arrayID[1], markerSelected.ownership) 

  if(markerSelected.ownership ==0)
  {
   $("#indoor").prop("disabled",true); 
  }
  document.getElementById("outdoor").style.background = "#f0ad4e"; 
  document.getElementById("indoor").style.background = "#c0c0c0";

}

function changeChartIndoor()
{

  arrayID=markerSelected.id.split(";");
  drawChartIndoor(arrayID[0], arrayID[1], markerSelected.ownership) 
}

function drawChartIndoor(macvar, idvar, Ownership) 
{  

  var test = new google.visualization.DataTable();
         
                  
  $.ajaxSetup({
      async: false
  });



  $.getJSON('../php/map/retrieveInfoIndoor.php',{mac:macvar,id:idvar} ,function(data) {
    format: "json"   

  })
  .fail(function() {
    console.log( "error " );
  })
  .done(function(data){        
    test.addColumn('string', 'Time');
    test.addColumn('number', 'CO2');
    test.addColumn('number', 'Noise');
    // var proof = JSON.parse(data);  
    stringData=data.timestamp[0].split(" ");
    for (var ii=0; ii < data.CO2.length; ii++)
    {        
      time = data.timestamp[ii].split(" ")
      test.addRow([ time[1], data.CO2[ii],data.Noise[ii] ]);     
    }
    document.getElementById("outdoor").style.background ="#c0c0c0"; 
    document.getElementById("indoor").style.background =  "#f0ad4e";
  });
  

  var options = {
      title: 'NetAtmo Indoor ' + stringData[0] + ' Station: '+ macvar,
      legend: { position: 'rigth' },

      series: {
          0: {targetAxisIndex:0},
          1: {targetAxisIndex:1}
      },
       vAxes: {
        // Adds titles to each axis.
        0: {title: 'CO2 [ppm]'},
        1: {title: 'Noise [dB]'}
      },
  };
  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(test, options);
}

