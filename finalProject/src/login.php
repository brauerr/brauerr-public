<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data

//check if logout is set to true from get request
//if so, destroy session, cookies etc.
session_start();
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['logout'])) {
  if (session_status() == PHP_SESSION_ACTIVE) {
    $_SESSION = array();
    session_destroy();
  }
}

?>

<!DOCTYPE html>
<html
  <head>
    <meta charset="utf-8">
    <title>Welcome to My Plot Mapper</title>
    
    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
    
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
    
    <div class="container">
      
      <form class="form-signin" role="form">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="button" id="Login" hidefocus="true">Log In</button>
        <span id="loginMessage"></span>
      </form>
    </div>
    
    <script>
      //do not allow null values for username or password
      $(document).ready(function() {
        $('#Login').click(function(){
          if ($('#inputUsername').val().length < 1) {
            $('#loginMessage').html('Please enter a username');
          } else if ($('#inputPassword').val().length < 1) {
            $('#loginMessage').html('Please enter a password');
          } else {
            checkUser();
          }
        });
      });
      
      function checkUser() {
        var username = $('#inputUsername').val();
        var password = $('#inputPassword').val();
        $.post("userCheck.php", {inputUsername : username, inputPassword : password}, function(result) {
          if (result == 1) {
            window.location.href = "map.php";
          } else {
            $('#loginMessage').html('Incorrect username and/or password');
          }
        });
      }
      
      //enable enter to login after filling in password box
      $("#inputPassword").keyup(function(event){
        if(event.keyCode == 13){
          $("#Login").click();
        }
      });
    </script
  
  </body>
</html>
