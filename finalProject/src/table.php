<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
?>

<!DOCTYPE html>
<html
  <head>
    <meta charset="utf-8">
    <title>My Table View</title>
    <link rel="stylesheet" href="style.css">
    
    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    
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
            <li><a href="table.php">Table View</a></li>
            <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                My Account <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="login.php?logout=true">Logout</a></li>
                <li><a href="createAccount.php">Modify Settings</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container-fluid">
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
            <input type="button" onclick="addRectangle()">
      </div><!--/.sidebar-offcanvas-->
    
      <div id="map-table" class="table-responsive">
        <table class="table table-striped">
          <thead>
            <th>Something
            <th>something else
            <th>something more
          </thead>
          <tbody>
            <tr>
              <td>a
              <td>b
              <td>c
            <tr>
              <td>1
              <td>2
              <td>3
          </tbody>
        </table>
      
      </div>
    </div>
  
  
  </body>
</html>