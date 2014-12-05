<?php
  ini_set('display_errors', 'On');
  include 'storedInfo.php'; //for storing password and other secure data
  session_start();
  
  //handle post from createmap() js function
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createMap'])) {
    $originLat = $_POST['originLat'];
    $originLong = $_POST['originLong'];
    $bearing = $_POST['bearing'];
    $numRanges = $_POST['numRanges'];
    $numColumns = $_POST['numColumns'];
    $rectWidth = $_POST['rectWidth'];
    $rectLength = $_POST['rectLength'];
    $mapName = $_POST['mapName'];
    $mapType = "default"; //extend later to include other maptypes 

    //create map in database
    //connect to database
    $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
    if ($myConnection->connect_errno) {
      echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
    } 
    
    //prepared statement to add new map to maps table
    if(!($stmt = $myConnection->prepare("INSERT INTO maps(map_name, map_type, user_id) VALUES (?,?,?)"))) {
      echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if(!$stmt->bind_param("ssi", $mapName, $mapType, $_SESSION['user_id'])) {
      echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    $map_id = $myConnection->insert_id;
    $stmt->close();
    
    //count one over for every column
    //count one up for every range
    //create array of coordinates which will become vertices of rectangles in grid
    $coordArray = array([(double)$originLat, (double)$originLong]);
    $arcWidth = $rectWidth / 6371; //6371 earth radius in km
    $arcLength = $rectLength / 6371;
    $last = 0;
    
    for ($rng = 0; $rng <= $numRanges; $rng++) {
      if ($last > 0) {
        //one range up from first range
        $coordArray[] = calcCoordinates($coordArray[$last - $numColumns][0], $coordArray[$last - $numColumns][1], $arcLength, $bearing);
        $last++;
      }
      for ($col = 0; $col < $numColumns; $col++) {
        $coordArray[] = calcCoordinates($coordArray[$last][0], $coordArray[$last][1], $arcWidth, $bearing + 90);
        $last++;
      }
    }
    
    //translate array of coordinates to array of rectangles for database
    $rectArray = array();
    for ($numRect = 0; $numRect < $numRanges * $numColumns; $numRect++) {
      $column = $numRect % $numColumns + 1;
      $range = (int)($numRect / $numColumns) + 1;
      $lat1 = $coordArray[$column - 1 + ($range - 1) * ($numColumns + 1)][0]; 
      $lat2 = $coordArray[$column + ($range - 1) * ($numColumns + 1)][0];
      $lat4 = $coordArray[$column - 1 + $range * ($numColumns + 1)][0];
      $lat3 = $coordArray[$column + $range * ($numColumns + 1)][0];
      $long1 = $coordArray[$column - 1 + ($range - 1) * ($numColumns + 1)][1]; 
      $long2 = $coordArray[$column + ($range - 1) * ($numColumns + 1)][1];
      $long4 = $coordArray[$column - 1 + $range * ($numColumns + 1)][1];
      $long3 = $coordArray[$column + $range * ($numColumns + 1)][1];
      $rectArray[] = ["range" => $range, "column" => $column, "lat1" => $lat1, "lat2" => $lat2, "lat3" => $lat3,
          "lat4" => $lat4, "long1" => $long1, "long2" => $long2, "long3" => $long3, "long4" => $long4];
    }
    
    //create array in database - one record for each element in rectarray
    for ($i = 0; $i < sizeof($rectArray); $i++) {
      if(!($stmt = $myConnection->prepare("INSERT INTO map_rectangles(map_id, length_km, width_km, lat_1, lat_2, lat_3, lat_4,
          long_1, long_2, long_3, long_4, map_range, map_column) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"))) {
        echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if(!$stmt->bind_param("iddddddddddii", $map_id, $rectLength, $rectWidth, $rectArray[$i]["lat1"], $rectArray[$i]["lat2"],
          $rectArray[$i]["lat3"], $rectArray[$i]["lat4"], $rectArray[$i]["long1"], $rectArray[$i]["long2"], $rectArray[$i]["long3"],
          $rectArray[$i]["long4"], $rectArray[$i]["range"], $rectArray[$i]["column"])) {
        echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      $stmt->close();
    }
   
    echo $map_id;
    $myConnection->close();
  }
  
  //handle post from loadMap() js function
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loadMap'])) {
    $map_id = (int)$_POST['map_id'];
    
    //connect to database
    $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
    if ($myConnection->connect_errno) {
      echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
    } 
    
    //return data and add to associative array
    $outputArray = array();
    if (!($stmt = $myConnection->prepare("SELECT map_range, map_column, lat_1, long_1, lat_2, long_2, lat_3, long_3,
        lat_4, long_4 FROM map_rectangles WHERE map_id = ?"))) {
      echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if(!$stmt->bind_param("i", $map_id)) {
      echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->bind_result($range, $column, $lat1, $long1, $lat2, $long2, $lat3, $long3, $lat4, $long4)) {
      echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    while ($stmt->fetch()) {
      $outputArray[] = ["range" => $range, "column" => $column, "lat1" => $lat1, "long1" => $long1, "lat2" => $lat2,
          "long2" => $long2, "lat3" => $lat3, "long3" => $long3, "lat4" => $lat4, "long4" => $long4];
    }
    $stmt->close();
    
    echo json_encode($outputArray);
    
    $myConnection->close();
  }
  
  //handle post from deleteMap() js function
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteMap'])) {
    $deleteId = $_POST['map_id'];
    
    //connect to database
    $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
    if ($myConnection->connect_errno) {
      echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
    } 
    
    //delete rectangles from map (map_rectangles table)
    if (!($stmt = $myConnection->prepare("DELETE FROM map_rectangles WHERE map_id = ?"))) {
      echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if (!$stmt->bind_param("i", $deleteId)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
    
    //delete map from maps table
    if (!($stmt = $myConnection->prepare("DELETE FROM maps WHERE map_id = ?"))) {
      echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if (!$stmt->bind_param("i", $deleteId)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
    
    $myConnection->close();
    
    echo $deleteId;
  }
  
  //function to convert measurement in degrees to radians
  function toRad($degrees) {
    return $degrees * pi() / 180;
  }
  
  //function to determine coordinates of a new point given initial coordinates, distance in km,
  //and bearing in degrees using haversine formula
  function calcCoordinates($inputLat, $inputLong, $distance, $inputBearing) {
    $outputLat = asin(sin(toRad($inputLat)) * cos($distance) + cos(toRad($inputLat)) *
      sin($distance) * cos(toRad($inputBearing)));
    $outputLong = toRad($inputLong) + atan2(sin(toRad($inputBearing)) * sin($distance) * cos(toRad($inputLat)),
        cos($distance) - sin(toRad($inputLat)) * sin(toRad($outputLat)));

    return array($outputLat * 180 / pi(), $outputLong * 180 / pi());
  }
  
?>