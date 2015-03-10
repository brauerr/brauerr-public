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
    
    <!--form to add an application (linked to applicator and org)-->
    <div class="container">
      <form class="form" role="form">
        <h2 class="form-signin-heading">Create a New Application:</h2>
        <div class="form-group">
          <label for="applicationDate">Date and Time of Application</label>
          <input class="form-control" type="datetime-local" id="applicationDate">
        </div>
        <!--add select drop down for field-->
        <div class="form-group">
          <label for="fieldName">Field Name</label>
          <select class="form-control" id="fieldName">
            <?php
              $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
              //$organization = $_SESSION['organization_id'];
              $organization = 2;
              if ($myConnection->connect_errno) {
                echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
              }
              if (!($stmt = $myConnection->prepare("SELECT f.field_id, f.field_name FROM field f
                  INNER JOIN organization o ON o.organization_id = f.fk_organization_id
                  WHERE o.organization_id = ?"))) {
                echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
              }
              if(!$stmt->bind_param("i", $organization)) {
                echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
              }
              if (!$stmt->execute()) {
              echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              if (!$stmt->bind_result($field_id, $field_name)) {
                echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              while ($stmt->fetch()) {
                echo "<option value=\"{$field_id}\">{$field_name}</option>";
              }
              $stmt->close();  
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="windSpeed">Wind Speed (mph)</label>
          <input class="form-control" type="text" id="windSpeed">
        </div>
        <div class="form-group">
          <label for="windDirection">Wind Direction (degrees 0-360)</label>
          <input class="form-control" type="text" id="windDirection">
        </div>
        <div class="form-group">
          <label for="temperature">Temperature (degrees F)</label>
          <input class="form-control" type="text" id="temperature">
        </div>
        <div class="form-group">
          <label for="applicationMethod">Application Method</label>
          <input class="form-control" type="text" id="temperature">
        </div>
        <div class="form-group">
          <label for="acres">Acres</label>
          <input class="form-control" type="number" id="acres">
        </div>
        <div class="form-inline">
          <label for="chemicalNameOne">Chemical Name</label>
          <select class="form-control" id="chemicalNameOne">
            <option value="0"></option>
            <?php
              if (!($stmt = $myConnection->prepare("SELECT chemical_id, product_name FROM chemical"))) {
                echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
              }
              if (!$stmt->execute()) {
              echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              if (!$stmt->bind_result($chemical_id, $product_name)) {
                echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              while ($stmt->fetch()) {
                echo "<option value=\"{$chemical_id}\">{$product_name}</option>";
              }
              $stmt->close(); 
            ?>
          </select>          
          <label for="rateOne">Rate</label>
          <input class="form-control" type="text" id="rateone">
        </div>  
        <div class="form-inline">
          <label for="chemicalNameTwo">Chemical Name</label>
          <select class="form-control" id="chemicalNameTwo">
            <option value="0"></option>
            <?php
              if (!($stmt = $myConnection->prepare("SELECT chemical_id, product_name FROM chemical"))) {
                echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
              }
              if (!$stmt->execute()) {
              echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              if (!$stmt->bind_result($chemical_id, $product_name)) {
                echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              while ($stmt->fetch()) {
                echo "<option value=\"{$chemical_id}\">{$product_name}</option>";
              }
              $stmt->close(); 
            ?>
          </select>          
          <label for="rateTwo">Rate</label>
          <input class="form-control" type="text" id="rateTwo">
        </div>  
        <div class="form-inline">
          <label for="chemicalNameThree">Chemical Name</label>
          <select class="form-control" id="chemicalNameThree">
            <option value="0"></option>
            <?php
              if (!($stmt = $myConnection->prepare("SELECT chemical_id, product_name FROM chemical"))) {
                echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
              }
              if (!$stmt->execute()) {
              echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              if (!$stmt->bind_result($chemical_id, $product_name)) {
                echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
              }
              while ($stmt->fetch()) {
                echo "<option value=\"{$chemical_id}\">{$product_name}</option>";
              }
              $stmt->close(); 
            ?>
          </select>          
          <label for="rateThree">Rate</label>
          <input class="form-control" type="text" id="rateThree">
        </div>  
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="button" id="createApplication" hidefocus="true">Create New Application Record</button>
        <span id="createApplicationMessage"></span><br>
      </form>
    </div>
    <br><br><br>
    
    
    <!--table with all historical appllications, sorted by application date, with a view/edit button-->
    <div class="container">
      <fieldset style="width:90% text-align:center">
        <legend>Application Record History</legend>
        <table class="table table-striped" style="padding:5px">
          <thead>
            <tr>
            <th>Field
            <th>Chemical Name
            <th>Application Date
          </thead>
          <tbody>
        <?php
            if (!($stmt = $myConnection->prepare("SELECT ar.application_record_id, ar.application_datetime, f.field_name, c.product_name FROM application_record ar
                INNER JOIN chemical_application_record car ON car.fk_application_record_id = ar.application_record_id
                INNER JOIN chemical c ON c.chemical_id = car.fk_chemical_id
                INNER JOIN field f ON f.field_id = ar.fk_field_id
                INNER JOIN organization o ON o.organization_id = f.fk_organization_id
                WHERE o.organization_id = ?
                ORDER BY ar.application_datetime DESC"))) {
              echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
            }
            if(!$stmt->bind_param("i", $organization)) {
              echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
            }
            if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->bind_result($ar_id, $application_date, $field, $chemical_name)) {
              echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            while ($stmt->fetch()) {
              echo "<tr><td>{$field}<td>{$chemical_name}<td>{$application_date}";
              echo "<td>
                <form action=\"EditApplications.php\" method=\"POST\">
                  <input type=\"hidden\" name=\"ar_id\" value=\"{$ar_id}\">
                  <input type=\"submit\" name=\"editProduct\" value=\"View/Edit\">
                </form>";
            }
            $stmt->close();  
            ?>
          </tbody>
        </table>
      </fieldset>
    </div>
  </body>
</html>