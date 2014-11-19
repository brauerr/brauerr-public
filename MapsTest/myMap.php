<?php
ini_set('display_errors', 'On');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>My Map</title>
    <link rel="stylesheet" href="mapStyle.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBE0SeF-edHBEFK5UyeeBZQkNPLMw0U640"></script>
    <script src="myMapScript.js"></script>
    <script>google.maps.event.addDomListener(window, 'load', initialize);</script>
    <script>window.onload = addControlButton();</script>
  </head>
  
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
