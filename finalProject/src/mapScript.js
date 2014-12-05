var map;

function initialize(zoom, myLatLong) {
  var mapOptions = {
    zoom: zoom,
    center: myLatLong,
    mapTypeId: google.maps.MapTypeId.SATELLITE
  };
  
  window.map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
}

// Suite of Ajax calls from to drawMap file on server to create, modify, load, or delete maps
function createMap() {
  var originLat = $('#origin-lat').val();
  var originLong = $('#origin-long').val();
  var bearing = $('#bearing').val();
  var numRanges = $('#num-ranges').val();
  var numColumns = $('#num-columns').val();
  var rectWidth = $('#rect-width').val();
  var rectLength = $('#rect-length').val();
  var mapName = $('#map-name').val();
  var mapType = "default";
  var map_id;
  
  //first check input for data quality!!
  //
  //
  //
  //
  //
  
  $('#drawmap-message').html('Creating new map...');
  $.post("drawMap.php", {originLat : originLat, originLong : originLong, bearing: bearing,
      numRanges : numRanges, numColumns : numColumns, rectWidth : rectWidth, rectLength : rectLength,
      mapType: mapType, mapName : mapName, createMap: true}, function(result) {
        if ($.isNumeric(result)) {
          //on result, update message, add new map to dropdown
          $('#drawmap-message').html('Map created successfully'); 
          map_id = result;
          var opt = document.createElement("option");
          opt.text = mapName;
          opt.value = map_id;
          var mapSelect = document.getElementById('map_select');
          mapSelect.add(opt);
          $('#origin-lat').val('');
          $('#origin-long').val('');
          $('#bearing').val('');
          $('#num-ranges').val('');
          $('#num-columns').val('');
          $('#rect-width').val('');
          $('#rect-length').val('');
          $('#map-name').val('');
          //from http://stackoverflow.com/questions/4324141/select-select-item-by-value
          var myOptions = mapSelect.options;
          for (var i = 0; i < myOptions.length; i++) {
            if (myOptions[i].value == map_id) {
              mapSelect.selectedIndex = i;
            }
          }
        } else {
          $('#drawmap-message').html('Map creation failed');
        }
      });
}
      
function deleteMap() {
  var result = confirm('Are you sure you want to delete this map?');
  if (result) {
    var mapSelect = document.getElementById('map_select');
    var map_id = mapSelect.options[mapSelect.selectedIndex].value;
    $('#deletemap-message').html('Deleting map...'); //set message Deleting Map...
    $.post("drawMap.php", {map_id : map_id, deleteMap : true}, function(result) {
      if ($.isNumeric(result)) {
        //success from php function - set message delete succeeded
        $('#deletemap-message').html('Map successfully deleted');
        //load next map in list, if it exists, else do default initialize
        mapSelect.remove(mapSelect.selectedIndex);
        map_id = mapSelect.options[mapSelect.selectedIndex].value;
        loadMap(map_id);
      } else {
        $('#deletemap-message').html('Map deletion failed' + result);
      }
    });
  }
}
      
function loadMap(map_id) {
  //return array of 4 coordinate sets for drawing rectangles, along with range/column
  //call addRectangle a number of times equal to size of array
  $.post("drawMap.php", {map_id : map_id, loadMap : true}, function(result) {
    //result is the array
    var myResult = JSON.parse(result);
    
    //clear any polygons from current map - reload map
    //zoom to extent of new map
    var i = 0;
    initialize(11, {lat : myResult[i]["lat1"], lng : myResult[i]["long1"]});
    for (i = 0; i < myResult.length; i++) {
      addRectangle({lat : myResult[i]["lat1"], lng : myResult[i]["long1"]}, {lat : myResult[i]["lat2"], lng : myResult[i]["long2"]},
          {lat : myResult[i]["lat3"], lng : myResult[i]["long3"]}, {lat : myResult[i]["lat4"], lng : myResult[i]["long4"]},
          myResult[i]["range"], myResult[i]["column"]);
    }
  });
}

function addRectangle(LatLng_1, LatLng_2, LatLng_3, LatLng_4, range, column) {
  var myRectangle =  new google.maps.Polygon({
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0,
    paths: [LatLng_1, LatLng_2, LatLng_3, LatLng_4]
  });
  
  myRectangle.setMap(map);
  myRectangle.data = '<b>Range: ' + range + ', Column: ' + column + '</b><br>';
  myRectangleInfo = new google.maps.InfoWindow();

  google.maps.event.addListener(myRectangle, 'click', function(event) {
    myRectangleInfo.setContent(this.data);
    myRectangleInfo.setPosition(event.latLng);
    myRectangleInfo.open(map);
  });
  
}
