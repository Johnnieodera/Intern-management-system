<?php
require('config.php'); // require is used to include and evaluate specific file

session_start(); // to store info across multiple pages

/*Check if form is submitted using POST method, script ensures that it only
  processes the form data if the form was submitted correctly.*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve username and password from the form submission
    // Stripslashes used removing any unnecessary escape characters that could possibly interfere with SQL queries or HTML output.
    $username = stripslashes($_POST["username"]);
    $password = stripslashes($_POST["password"]);
    $hashed_password = md5($password);  

    // SQL Query to select the user with the provided username and password
    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $result = mysqli_query($con, $query); // executes the SQL query against the database

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($con)); //the script stops execution and prints an error message if feedback is false
                              // mysqli_error helps in debugging by providing a specific error message if the query fails.

    }

    // Fetch user data
    $user = mysqli_fetch_assoc($result); //retrieves the next row from the result set as an associative array,you can access the values using column names as keys

    $rows = mysqli_num_rows($result); //returns the number of rows in the result set. Since the username should be unique, this will typically be '1' if the credentials are correct, or '0' if they are not.

    // Validate credentials for only one user with said details
    if ($rows == 1) {
        // Store user data in session to maintain user's state in different pages
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role to the dashboard
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else if ($user['role'] === 'student') {
            header("Location: student/dashboard.php");
            
        } else if ($user['role'] === 'supervisor') {
            header("Location: supervisor/dashboard.php");
        } 
        else {
            header("Location: signin.html");  
        }
    } else {
        echo "<div class='form'>
            <h3>Incorrect Username/Password.</h3><br/>
            <p class='link'>Click here to <a href='signin.html'>Login</a> again.</p>
            </div>"; // Handling incorrect credential, return to actual login
    }
}
?>
