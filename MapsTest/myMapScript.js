function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(-34.397, 150.644), 
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.SATELLITE
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
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

