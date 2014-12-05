<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
session_start();
?>

<!DOCTYPE html>
<html
  <head>
    <meta charset="utf-8">
    <title>Create/Modify Account</title>
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
            <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                My Account <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="login.php?logout=true">Logout/Login</a></li>
                <li><a href="createAccount.php">Create New Account</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container">
      <form class="form-signin" role="form">
        <h2 class="form-signin-heading">Create a New User:</h2>
        <input type="text" id="newName" class="form-control" placeholder="Your Name" required>
        <input type="email" id="newEmail" class="form-control" placeholder="Your Email" required>
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="newUsername" class="form-control" placeholder="New Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="newPassword" class="form-control" placeholder="New Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="button" id="createUser" hidefocus="true">Create User</button>
        <span id="createuserMessage"></span><br>
        <a href="login.php">Login</a>
      </form>
    </div>
    
    <script>
    $(document).ready(function() {
        $('#createUser').click(function(){
          
          //check email format (from http://www.w3schools.com/js/js_form_validation.asp)
          var email = $('#newEmail').val();
          var atpos = email.indexOf('@');
          var dotpos = email.indexOf('.');
          
          //ensure all fields are filled in
          if ($('#newUsername').val().length < 1) {
            $('#createuserMessage').html('Please enter a username');
          } else if ($('#newPassword').val().length < 1) {
            $('#createuserMessage').html('Please enter a password');
          } else if ($('#newName').val().length < 1) {
            $('#createuserMessage').html('Please enter your name');
          } else if ($('#newEmail').val().length < 1) {
            $('#createuserMessage').html('Please enter your email');
          }
          //ensure email is in email format
          else if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
            $('#createuserMessage').html('Ensure email is in correct format');
          }else {
             createUser();
          }
        });
      });
      
      function createUser() {
        var username = $('#newUsername').val();
        var password = $('#newPassword').val();
        var email = $('#newEmail').val();
        var name = $('#newName').val();
        $.post("userCheck.php", {newUsername : username, newPassword : password, newEmail : email,
            newName : name}, function(result) {
          if (result == 1) {
            $('#createuserMessage').html('New account succesfully created, please login');
          } else if (result == 'duplicate1') {
            $('#createuserMessage').html('Sorry, someone already has that username');
          } else {
            $('#createuserMessage').html('Create user failed');
          }
        });
      }
      
      //enable enter to login after filling in password box
      $("#newPassword").keyup(function(event){
        if(event.keyCode == 13){
          $("#createUser").click();
        }
      });
    </script
  
  </body>
</html>