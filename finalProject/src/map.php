<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
session_start();

  //if user not logged in (session not started) redirect to login page
  if (!isset($_SESSION['username'])) {
      $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
      $filePath = implode('/', $filePath);
      $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
      header("Location: {$redirect}/login.php");
  }
    
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
    <script>google.maps.event.addDomListener(window, 'load', function() {initialize(11, {lat : 44.5667, lng : 123.2833});});</script>

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
                <li><a href="login.php?logout=true">Logout/Login</a></li>
                <li><a href="createAccount.php">Create New Account</a></li>
              </ul>
            </li>
            <li><p id="current-user"><?php echo "Welcome to My Maps " . $_SESSION['name'] . ", Username: " . $_SESSION['username'] . ", UserID: " . $_SESSION['user_id']; ?></p></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a href="#" class="list-group-item active">Select Map</a>
            <li class="list-group-item">
              <!--<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Select Map <span class="caret"></span>-->
              <select class="form-control" data-style="btn-inverse" id="map_select" onchange="loadMap(value);">
              <?php
                //load maps for user
                //connect to database
                $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
                if ($myConnection->connect_errno) {
                  echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
                }
                
                $user_id = $_SESSION['user_id'];
                if (!($stmt = $myConnection->prepare("SELECT map_id, map_name FROM maps WHERE user_id = ?"))) {
                  echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
                }
                if(!$stmt->bind_param("i", $user_id)) {
                  echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
                }
                if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                if (!$stmt->bind_result($mapID, $mapName)) {
                  echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                while ($stmt->fetch()) {
                  echo "<li><option value=\"{$mapID}\">{$mapName}</option></li>";
                }
                $stmt->close();  
              ?>
            </select>
            </li>
            <a href="#" class="list-group-item active">Create New Map</a>
            <li class="list-group-item">
              <form role="form">
                <input type="text" class="form-control" id="map-name" placeholder="Map Name">
                <input type="text" class="form-control" id="origin-lat" placeholder="Origin Latitude">
                <input type="text" class="form-control" id="origin-long" placeholder="Origin Longitude">
                <input type="text" class="form-control" id="bearing" placeholder="Bearing in Degrees">
                <input type="text" class="form-control" id="num-ranges" placeholder="# Ranges Long">
                <input type="text" class="form-control" id="num-columns" placeholder="# Columns Long">
                <input type="text" class="form-control" id="rect-length" placeholder="Length (km)">
                <input type="text" class="form-control" id="rect-width" placeholder="Width (km)">
                <button class="btn btn-primary btn-block" type="button" id="create-map" onclick="createMap()">Create Map</button>
              </form>
              <span id="drawmap-message"></span>
            </li>
            <a href="#" class="list-group-item active">Delete Active Map</a>
            <li class="list-group-item">
              <form role="form">
                <button class="btn btn-prikmary btn-blcok" type="button" id="delete-map" onclick="deleteMap()">Delete Map</button>
              </form>
              <span id="deletemap-message"></span>
            </li>
          </div>
          
    </div><!--/.sidebar-offcanvas-->
 
    <div id="map-canvas"></div>
    
    <script>
      var mapSelect = document.getElementById('map_select');
      var map_id = mapSelect.options[mapSelect.selectedIndex].value;
      google.maps.event.addDomListener(window, 'load', function() {loadMap(map_id);});
    </script>
  </body>
</html>