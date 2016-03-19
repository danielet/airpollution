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
                  markerSelected = marker;
                  //plot chart!
                  console.log(arrayID[0]);
                  $("#outdoor").prop("disabled",false);
      
                  if(marker.ownership == 1)
                  {
                    $("#indoor").prop("disabled",false);
                    drawChartIndoor(arrayID[0], arrayID[1], marker.ownership)                     
                  }
                  else
                  {
                    drawChartOutdoor(arrayID[0], arrayID[1], marker.ownership) 
                  }

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
           
      var arrayTime = ["00.00", "00.30" , "01.00","01.30", "02.00", "02.30", "03.00" , "03.30","04.00", "04.30",
                       "05.00", "05.30" , "06.00","06.30", "07.00", "07.30", "08.00" , "08.30","09.00", "09.30",
                       "10.00", "10.30" , "11.00","11.30", "12.00", "12.30", "13.00" , "14.30","15.00", "15.30",
                       "16.00", "16.30" , "17.00","17.30", "18.00", "18.30", "19.00" , "19.30","20.00", "20.30",
                       "21.00", "21.30" , "22.00","22.30", "23.00", "23.30"];
      console.log(macvar) 
      console.log(idvar)
      console.log(Ownership)                
      $.ajaxSetup({
          async: false
      });

      if(Ownership == 0)
      {
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
          for (var ii=0; ii < data.temp.length; ii++)
          {        
            test.addRow([ arrayTime[0] , data.temp[ii],data.hum[ii] ]);     
          }
          
                
          document.getElementById("outdoor").style.background = "#f0ad4e"; 
          document.getElementById("indoor").style.background = "#c0c0c0";
      
        });
            
        var options = {
            title: 'NetAtmo Outdoor',            
            legend: { position: 'bottom' },

            series: {
                0: {targetAxisIndex:0},
                1: {targetAxisIndex:1}
            },
             vAxes: {
              // Adds titles to each axis.
              0: {title: 'Temperature'},
              1: {title: 'Humidity'}
            },
        };

      }
      
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
         
  var arrayTime = ["00.00", "00.30" , "01.00","01.30", "02.00", "02.30", "03.00" , "03.30","04.00", "04.30",
                   "05.00", "05.30" , "06.00","06.30", "07.00", "07.30", "08.00" , "08.30","09.00", "09.30",
                   "10.00", "10.30" , "11.00","11.30", "12.00", "12.30", "13.00" , "14.30","15.00", "15.30",
                   "16.00", "16.30" , "17.00","17.30", "18.00", "18.30", "19.00" , "19.30","20.00", "20.30",
                   "21.00", "21.30" , "22.00","22.30", "23.00", "23.30"];
                  
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
    
    for (var ii=0; ii < data.CO2.length; ii++)
    {        
      test.addRow([ arrayTime[0] , data.CO2[ii],data.Noise[ii] ]);     
    }
    document.getElementById("outdoor").style.background ="#c0c0c0"; 
    document.getElementById("indoor").style.background =  "#f0ad4e";
  });
  

  var options = {
      title: 'NetAtmo Indoor',
      curveType: 'function',
      legend: { position: 'bottom' },

      series: {
          0: {targetAxisIndex:0},
          1: {targetAxisIndex:1}
      },
       vAxes: {
        // Adds titles to each axis.
        0: {title: 'CO2'},
        1: {title: 'Noise'}
      },
  };
  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(test, options);
}

