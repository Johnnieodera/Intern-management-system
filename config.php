<?php
    $con = mysqli_connect("localhost","root","12345678","clearance_form");
    // connect to a MySQL database using the mysqli_connect() function
    // connection result is stored in the $con variable.If the connection is successful, $con will hold the connection resource;


    if (mysqli_connect_errno()) // line checks if there was an error during the connection attempt 
    
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>