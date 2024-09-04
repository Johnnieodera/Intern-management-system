<?php
require('config.php');
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['username'])) { // isset() function returns true if a variable is set and is not null.
    // Here, it is used to determine if the user is currently logged in by checking if the username session variable exists. If it exists, it means the user is logged in.

    
    // Destroy all session data
    session_destroy(); //used to log the user out by clearing all session data

    // Redirect to the login page
    header("Location: signin.html");
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: signin.html");
    exit();
}
?>
