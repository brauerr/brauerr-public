<?php

  //inspiration taken from:
  //http://www.sanwebe.com/2013/04/username-live-check-using-ajax-php
  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  include 'storedInfo.php'; //for storing password and other secure data
  
  $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
  if ($myConnection->connect_errno) {
    echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
  } 
  
  //code to handle user check login request
  if(isset($_POST['inputUsername'])) {
    //ensure this is an ajax request
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
      die();
    }
    
    //sanitize username and password passed via post
    $username = trim($_POST["inputUsername"]);
    $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $password = trim($_POST["inputPassword"]);
    $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    if (!($myUsers = $myConnection->query("SELECT username, password, user_id, name FROM map_users WHERE username = '$username' AND password = '$password'"))) {
      echo "Query failed<br>";
    }

    $rowCount = $myUsers->num_rows;
    $data = $myUsers->fetch_array(MYSQLI_ASSOC);
    
    //start session
    if ($rowCount == 1) {
      session_start();
      $_SESSION['username'] = $username;
      $_SESSION['user_id'] = $data["user_id"];
      $_SESSION['name'] = $data["name"];
    }
    echo $rowCount; //should be 1
    $myConnection->close();
  }
  
  //code to handle create user request
  if(isset($_POST['newUsername'])) {
    $username = $_POST['newUsername'];
    $email = $_POST['newEmail'];
    $name = $_POST['newName'];
    $password = $_POST['newPassword'];
    
    if(!($stmt = $myConnection->prepare("INSERT INTO map_users(username, email, name, password) VALUES (?,?,?,?)"))) {
      echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if(!$stmt->bind_param("ssss", $username, $email, $name, $password)) {
      echo "Binding parameters failed: (" . $myConnection->errno . ") " . $myConnection->error;
    }
    if(!$stmt->execute()) {
      //check for duplicate records
      if ($myConnection->errno == 1062) {
        echo 'duplicate';
      } else {
        echo "Execute failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
    }
    $stmt->close();
    echo 1;
  }
  
  
?>