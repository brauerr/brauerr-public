<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //check if GET request sent with action = end
    //if so destroy session and redirect to login.php
    //this is sent when user clicks "here" to logout from
    //properly generated content.php page
    //idea from post @276 on Piazza
    
    session_start();    
    if (isset($_GET['action']) && $_GET['action'] == 'end') {
        $_SESSION = array();
        session_destroy();
    }
    
    //Location redirect code adapted from
    //CS494 lecture
    //check to ensure username was posted else redirect to login.php
    if (!($_SERVER['REQUEST_METHOD'] == 'POST') || !isset($_POST['username'])) {
        $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
        $filePath = implode('/', $filePath);
        $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
        header("Location: {$redirect}/login.php");
    }    

    //update number of visits and name (or initialize if first visit in session)
    if (session_status() == PHP_SESSION_ACTIVE) {
        $_SESSION['username'] = $_POST['username'];
        
        if (!isset($_SESSION['visits'])) {
            $_SESSION['visits'] = 0;
        } else if ($_POST['username'] != "" && $_POST['username'] != null) {
            $_SESSION['visits']++;
        }
    }

    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<meta charset=\"utf=8\">";
    echo "<title>Content Test</title>";
    echo "</head>";
    echo "<body>";

    if ($_POST['username'] == "" || $_POST['username'] == null) {
        echo "A username must be entered click ";
        echo "<a href=\"login.php\">here </a>";
        echo "to return to the login screen";
    } else {
        echo "Hello $_SESSION[username] ";
        echo "you have visited this page $_SESSION[visits] times before.<br>";
        echo "Click ";
        echo "<a href=\"content.php?action=end\">here </a>";
        echo "to logout."; 
    }
        
    echo "</body>";
    echo "<html>";
?>