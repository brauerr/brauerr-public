<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
session_start();
?>

<!DOCTYPE html>
<html
  <head>
    <meta charset="utf-8">
    <title>My Map View</title>
    <link rel="stylesheet" href="style.css">
    
    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBE0SeF-edHBEFK5UyeeBZQkNPLMw0U640"></script>
    <script src="mapScript.js"></script>
    <script>google.maps.event.addDomListener(window, 'load', initialize);</script>
    <script>window.onload = addControlButton();</script>
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
  </head>
  <body>
  
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">My Maps</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="map.php">Map View</a></li>
            <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                My Account <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="login.php?logout=true">Logout</a></li>
                <li><a href="createAccount.php">Modify Settings</a></li>
              </ul>
            </li>
            <li><p id="current-user"><?php echo "Welcome to My Maps " . $_SESSION['name'] . ", Username: " . $_SESSION['username'] . ", UserID: " . $_SESSION['user_id']; ?></p></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a href="#" class="list-group-item active">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
          </div>
          <input type="button" onclick="addRectangle()" value="add rect">
          <input type="button" onclick="createMap()" value = "create map">
          <input type="button" onclick="loadMap()" value = "load map">
          <input type="button" onclick="deleteMap()" value = "delete map">
          <br>
          <span id="drawmap-message"></span>
    </div><!--/.sidebar-offcanvas-->
    
    <script>
      <!-- Suite of Ajax calls from to drawMap file on server to create, modify, load, or delete maps -->
      function createMap() {
        var originLat = 33.671068;
        var originLong = -116.25128;
        var bearing = 1;
        var numRanges = 4;
        var numColumns = 12;
        var rectWidth = 0.1;
        var rectLength = 0.2;
        var mapType = "default";
        var mapName = "testMap";
        $('#drawmap-message').html('Creating new map...');
        $.post("drawMap.php", {originLat : originLat, originLong : originLong, bearing: bearing,
            numRanges : numRanges, numColumns : numColumns, rectWidth : rectWidth, rectLength : rectLength,
            mapType: mapType, mapName : mapName, createMap: true}, function(result) {
              if (result == 1) {
                //on result, update message, add new map to dropdown
                $('#drawmap-message').html('Map created successfully');
              } else {
                $('#drawmap-message').html('Map creation failed');
              }
            });
      }
      
      function deleteMap() {
        var mapID = testMap;
        $.post("drawMap.php", {mapID : mapID}, function(result) {
          //on result, delete current map and load most recent map
        });
      }
      
      function loadMap() {
        var mapID = testMap;
        //return array of 4 coordinate sets for drawing rectangles, along with range/column
        //call addRectangle a number of times equal to size of array
        
      }
    </script>
    <div id="map-canvas"></div>
  </body>
</html>