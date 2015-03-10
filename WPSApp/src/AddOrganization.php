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
    
  </body>
</html>