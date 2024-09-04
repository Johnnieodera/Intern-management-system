<?php

require('config.php');
session_start(); //function starts a new session or resumes an existing session

$_SESSION['success'] = ""; //initializes the success session variable with an empty string used later to store a success message.

// prepare user data for insertion to db
if (isset($_REQUEST['username'])) { // line checks if the username parameter has been sent via a request (POST)If username is set, the code inside this if block will execute.


    // retrieve the username, email, and phone_number from the request
    $username = stripslashes($_REQUEST['username']);
    $email = stripslashes($_REQUEST['email']);
    $phone = stripslashes($_REQUEST['phone_number']);

    $role = "student"; // sets the user's role to "student"
    $password = stripslashes($_REQUEST['password']); //retrieves the password from the request after stripping any slashes.

    $create_datetime = date("Y-m-d H:i:s");

    // Insert new users into db
    $query = "INSERT into users (username, email,role, phone_number , password , create_datetime)
              VALUES ('$username', '$email', '$role','$phone',
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
               '" . md5($password) . "', '$create_datetime')";

    $result = mysqli_query($con, $query); // runs query against db and store result if otheriwse its false

    if ($result) {
        // Retrieve the user's ID after registration
        $user_id = mysqli_insert_id($con);

        // Storing user ID in session variable
        $_SESSION['user_id'] = $user_id;

        // Welcome message
        $_SESSION['success'] = "You have registered!";

        header("Location: signin.html");
    }else {
        echo "Failed to register";
    }
}

?>