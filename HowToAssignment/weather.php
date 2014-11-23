<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Annual Weather Summary</title>
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="http://dimplejs.org/dist/dimple.v2.1.0.min.js"></script>
    <link rel="stylesheet" href="weatherStyle.css">
    
    <?php
    //set up database containing all of the NORMAL_DLY stations with latitude
    //and longitude for searching closest to user location input
    //data in .txt format can be found at 
    //ftp://ftp.ncdc.noaa.gov/pub/data/normals/1981-2010/station-inventories/allstations.txt
    
    $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
    if ($myConnection->connect_errno) {
      echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
    } 
    
    if (isset($_GET['retrieveData'])) {
      $zip = $_GET['zipcode'];

      //convert zipcode to lat long with geocode service
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, "http://geocoder.us/service/csv/geocode?zip={$zip}");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $zipGeocode = curl_exec($curl);
      curl_close($curl);
      
      //convert csv result to an array, format will be like this:
      //40.82655, -96.62564, Lincoln, NE, 68505
      //latitude and longitude stored in the first two elements of the array
      $zipArray = str_getcsv($zipGeocode);
      $lat = (double)$zipArray[0];
      $long = (double)$zipArray[1];  
      
      //query database to find closest station, use haversine formula and sort by closest location
      //http://www.scribd.com/doc/2569355/Geo-Distance-Search-with-MySQL

      $minLat = $lat - 0.5;
      $maxLat = $lat + 0.5;
      $minLong = $long - 0.5;
      $maxLong = $long + 0.5;
      
      if(!($stmt = $myConnection->prepare("SELECT ghcnd, station_name, state, 3956 * 2 * ASIN(SQRT(POWER(SIN((
          ? - lat_dec) * pi() / 180 / 2), 2) + COS(? * pi() / 180) * COS(lat_dec * pi() / 180)
          * POWER(SIN((? - long_dec) * pi() / 180 / 2), 2))) as distance FROM ncdc_normal_dly_stations
          WHERE lat_dec between ? and ? and long_dec between ? and ? ORDER BY distance"))) {

        echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if(!$stmt->bind_param("ddddddd", $lat, $lat, $long, $minLat, $maxLat, $minLong, $maxLong)) {
        echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if(!$stmt->bind_result($ghcnd, $stationName, $state, $distance)){
        echo "Result binding failed: (" . $myConection->errno . ") " . $myConnection->error;
      } else {
        $stmt->fetch();
      }
      $stmt->close();
    
      //get data from noaa using cURL
      //dataset containing documentation on all fields in NORMAL_DLY at:
      //http://www1.ncdc.noaa.gov/pub/data/cdo/documentation/NORMAL_DLY_documentation.pdf
      //other dataset discovery at:
      //http://www.ncdc.noaa.gov/cdo-web/datasets
      //Beware missing data even in these datasets! example - utica ne temperature data 68456
    
      $curl = curl_init();
      curl_setopt ($curl, CURLOPT_URL,
        "http://www.ncdc.noaa.gov/cdo-web/api/v2/data?datasetid=NORMAL_DLY&stationid=GHCND:{$ghcnd}&datatypeid=DLY-TMAX-NORMAL&startdate=2010-01-01&enddate=2010-12-31&limit=365");
      curl_setopt ($curl, CURLOPT_HTTPHEADER, array("token:SVyCZAPbcpzDMPOohQqfgtnYoffvejDQ"));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $maxTemp = json_decode(curl_exec($curl), true); //associative array format
      curl_close($curl);
      
      $curl = curl_init();
      curl_setopt ($curl, CURLOPT_URL,
        "http://www.ncdc.noaa.gov/cdo-web/api/v2/data?datasetid=NORMAL_DLY&stationid=GHCND:{$ghcnd}&datatypeid=DLY-TMIN-NORMAL&startdate=2010-01-01&enddate=2010-12-31&limit=365");
      curl_setopt ($curl, CURLOPT_HTTPHEADER, array("token:SVyCZAPbcpzDMPOohQqfgtnYoffvejDQ"));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $minTemp = json_decode(curl_exec($curl), true);
      curl_close($curl);
      
      $curl = curl_init();
      curl_setopt ($curl, CURLOPT_URL,
        "http://www.ncdc.noaa.gov/cdo-web/api/v2/data?datasetid=NORMAL_DLY&stationid=GHCND:{$ghcnd}&datatypeid=DLY-PRCP-PCTALL-GE001HI&startdate=2010-01-01&enddate=2010-12-31&limit=365");
      curl_setopt ($curl, CURLOPT_HTTPHEADER, array("token:SVyCZAPbcpzDMPOohQqfgtnYoffvejDQ"));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $chanceRain= json_decode(curl_exec($curl), true);
      curl_close($curl);

      for ($i = 0; $i < 365; $i++) {
        if (!empty($maxTemp)) {
          $weatherArray[] = array('Date'=>$maxTemp['results'][$i]['date'],
             'DataType'=>'MaxTemp', 'Value'=>$maxTemp['results'][$i]['value'] / 10);
        }
        if (!empty($minTemp)) {
          $weatherArray[] = array('Date'=>$minTemp['results'][$i]['date'],
            'DataType'=>'MinTemp', 'Value'=>$minTemp['results'][$i]['value'] / 10);
        }
        if (!empty($chanceRain)) {
          $weatherArray[] = array('Date'=>$chanceRain['results'][$i]['date'],
            'DataType'=>'ChanceRain', 'Value'=>$chanceRain['results'][$i]['value'] / 10);
        }
      }
      
      //create simple JSON array with weather data    
      $weatherJson = json_encode($weatherArray);
    }

    ?>
    
  </head>
  
  <body>
    <fieldset id="dataEntry">
      <legend>Enter Zipcode to Retrieve Normal Weather Data</legend>
      <form action="weather.php" method="GET">
          Zipcode: 
          <input type="text" name="zipcode">
          <br>
          <input type="submit" name="retrieveData" value="Retrieve Data">
          <br>
      </form>
    </fieldset>
    
    <div id="weatherChart">
      <p id="chartTitle"></p>
      
      <!--Div for inserting .svg graphic containing dimple chart of historical temp and precipitation -->
      <script type="text/javascript">
        
        var p = document.getElementById('chartTitle');
        p.innerHTML = "<?php echo "Station Name: " . $stationName . "<br>" .
            "State: " . $state . "<br>Distance from Zip: " . $distance . " miles"; ?>"; 
        var data = <?php echo $weatherJson; ?>;
        
        var svg = dimple.newSvg("#weatherChart", 600, 400);
        var myChart = new dimple.chart(svg, data);
        myChart.setBounds(60, 30, 505, 305);
        var x = myChart.addTimeAxis("x", "Date");
        x.addOrderRule("Date");
        myChart.addMeasureAxis("y", "Value");
        var s = myChart.addSeries("DataType", dimple.plot.line);
        myChart.addLegend(60, 10, 500, 20, "right");
        myChart.draw();
 
      </script>
    </div>

  </body>
</html>