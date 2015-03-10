<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
session_start();

  //if user not logged in (session not started) redirect to login page
  if (!isset($_SESSION['username'])) {
      $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
      $filePath = implode('/', $filePath);
      $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
      header("Location: {$redirect}/Login.php");
  }
  
?>

<!DOCTYPE html>
<html
  <head>
    <meta charset="utf-8">
    <title>Add Applicator</title>
    <link rel="stylesheet" href="style.css">
    
    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    
  </head>
  <body>
  
    <!--Top Nav Bar - identical for all pages-->
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">WPS Web</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="ViewREI.php">View REI</a></li>
            <li><a href="ManageApplications.php">Manage Applications</a></li>
            <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                Manage Data <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="AddApplicator.php">Add Applicator</a></li>
                <li><a href="AddField.php">Add Field</a></li>
                <li><a href="AddOrganization.php">Add Organization</a></li>
                <li><a href="AddChemicals.php">Add Chemicals</a></li>
              </ul>
            </li>
            <li><a href="Login.php?logout=true">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container">
      <div class="col-md-6" id="oldData">
        <h2 class="form-signin-heading">Current Application Data</h2>
        <?php
          $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
          $ar_id = $_POST['ar_id'];
          if ($myConnection->connect_errno) {
            echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
          }
          if (!($stmt = $myConnection->prepare("SELECT ar.wind_speed, ar.wind_direction, ar.application_method, ar.acres_applied, ar.comments,
              ar.temperature, ar.application_datetime, c.product_name, car.rate_applied, f.field_name FROM application_record ar
              INNER JOIN chemical_application_record car ON car.fk_application_record_id = ar.application_record_id
              INNER JOIN chemical c ON c.chemical_id = car.fk_chemical_id
              INNER JOIN field f ON f.field_id = ar.fk_field_id
              WHERE ar.application_record_id = ?"))) {
            echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
          }
          if(!$stmt->bind_param("i", $ar_id)) {
            echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
          }
          if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->bind_result($wind_speed, $wind_direction, $application_method, $acres_applied, 
            $comments, $temperature, $application_datetime, $product_name, $rate_applied, $field_name)) {
            echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          //set up main data
          $stmt->fetch();
          echo "<p>Field Name: {$field_name}</p>";
          echo "<p>Application Date Time: {$application_datetime}</p>";
          echo "<p>Acres: {$acres_applied}</p>";
          echo "<p>Wind Speed: {$wind_speed}</p>";
          echo "<p>Wind Direction: {$wind_direction}</p>";
          echo "<p>Temperature: {$temperature}</p>";
          echo "<p>Application Method: {$application_method}</p>";
          echo "<p>Comments: {$comments} </p>";
          echo "<p>Product Name: {$product_name}</p>";
          echo "<p>Rate Applied: {$rate_applied}</p>";
          //add additional chemical data if more than one chem in the application record
          while ($stmt->fetch()) {
            echo "<p>Product Name: {$product_name}</p>";
            echo "<p>Rate Applied: {$rate_applied}</p>";
          }
          $stmt->close();  
        ?>
      </div>
      <div class="col-md-6" id="newData">
        <form class="form" role="form">
          <h2 class="form-signin-heading">Edit Application:</h2>
          <div class="form-group">
            <label for="applicationDate">Date and Time of Application</label>
            <input class="form-control" type="datetime-local" id="applicationDate">
          </div>
        </form>
      </div>
    </div>
    
  </body>
</html>