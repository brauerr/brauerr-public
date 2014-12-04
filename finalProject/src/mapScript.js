var map;

function initialize() {
  var mapOptions = {
    zoom: 11,
    center: new google.maps.LatLng(33.678176, -116.242568),
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };
  
  window.map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
}

function addControlButton() {
// Create a div to hold the control.
var controlDiv = document.createElement('div');

// Set CSS styles for the DIV containing the control
// Setting padding to 5 px will offset the control
// from the edge of the map.
controlDiv.style.padding = '5px';
var myMap = document.getElementById('map-canvas');
myMap.appendChild(controlDiv);

// Set CSS for the control border.
var controlUI = document.createElement('div');
controlUI.style.backgroundColor = 'white';
controlUI.style.borderStyle = 'solid';
controlUI.style.borderWidth = '2px';
controlUI.style.cursor = 'pointer';
controlUI.style.textAlign = 'center';
controlUI.title = 'Click to set the map to Home';
controlDiv.appendChild(controlUI);

// Set CSS for the control interior.
var controlText = document.createElement('div');
controlText.style.fontFamily = 'Arial,sans-serif';
controlText.style.fontSize = '12px';
controlText.style.paddingLeft = '4px';
controlText.style.paddingRight = '4px';
controlText.innerHTML = '<strong>Home</strong>';
controlUI.appendChild(controlText);
}

function addRectangle(LatLng_1, LatLng_2, LatLng_3, LatLng_4, range, column) {
  var myRectangle = new google.maps.Polygon({
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    paths: [
      new google.maps.LatLng(33.671068, -116.25128),
      new google.maps.LatLng(33.671068, -116.233942),
      new google.maps.LatLng(33.685282, -116.233942),
      new google.maps.LatLng(33.685282, -116.25128)
      ]
  });
  
  var myOtherRectangle = new google.maps.Polygon({
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    paths: [
      new google.maps.LatLng(33.671068, -116.25128),
      new google.maps.LatLng(33.671068, -116.273942),
      new google.maps.LatLng(33.685282, -116.273942),
      new google.maps.LatLng(33.685282, -116.25128)
      ]
  });
  
  myRectangle.setMap(map);
  myOtherRectangle.setMap(map);
  myRectangle.data = '<b>some data here</b><br>';
  myOtherRectangle.data = '<b>someother data</b><br>';
  myRectangleInfo = new google.maps.InfoWindow();

  google.maps.event.addListener(myRectangle, 'click', function(event) {
    myRectangleInfo.setContent(this.data);
    myRectangleInfo.setPosition(event.latLng);
    myRectangleInfo.open(map);
  });
  
  google.maps.event.addListener(myOtherRectangle, 'click', function(event) {
    myRectangleInfo.setContent(this.data);
    myRectangleInfo.setPosition(event.latLng);
    myRectangleInfo.open(map);
  });
  
}
