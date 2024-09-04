<?php
require('../config.php'); //  line includes the config.php file, which likely contains the code needed to connect to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') //This checks if the form was submitted using the POST method. If it was, the code inside this block will run.

 {
    $faculty_name = $_POST['faculty_name']; //This line retrieves the faculty_name that was entered in the form and stores it in the $faculty_name variable.

    // Prepare and bind
    //This prepares an SQL statement to insert the new faculty_name into the faculty table in the database.
    // The ? is a placeholder for the actual value that will be inserted.
    $stmt = $con->prepare("INSERT INTO faculty (faculty_name) VALUES (?)");

    // This binds the value of $faculty_name to the ? placeholder in the SQL statement
    // The "s" indicates that the value is a string.
    $stmt->bind_param("s", $faculty_name);

    //This line tries to execute the SQL statement (inserting the new faculty name into the database)
    // If it works, the code inside this if block will run.
    if ($stmt->execute()) {
        $success_message = "New faculty added successfully";
    } else { 
        //If something goes wrong and the SQL statement fails, this block will run, and an error message will be created, explaining what went wrong.
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close(); // This closes the prepared statement, freeing up resources.
    mysqli_close($con); // This closes the connection to the database, which is important to do after you're done with database operations.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .form-container {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            max-width: 400px;
            margin: 0 auto;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
        }
        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Dashboard</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="faculty.php">Faculty</a></li>
                <li><a href="department.php">Department</a></li>
                <li><a href="add-faculty.php">Add Faculty</a></li>
                <li><a href="add-department.php">Add Department</a></li>
                <li><a href="../logout.php">logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <header class="header">
            <h1>Add Faculty</h1>
        </header>
        <section class="content">
            <?php
            //This line checks if a variable called $success_message has been set
            // The isset() function returns true if the variable exists and is not null
            //  If $success_message has a value, the code inside this if block will run.
            if (isset($success_message)) {

                //If $success_message is set, this line creates a paragraph (<p>) element in HTML with the class message success.
                //It then inserts the content of $success_message inside that paragraph and displays it on the webpage
                // echo used to output message on HTML
                echo "<p class='message success'>$success_message</p>";
            } // close block for the 'if' success msg

            if (isset($error_message)) //This line checks if a variable called $error_message has been set.
            // If $error_message has a value, the code inside this 'if' block will run.

            {
                echo "<p class='message error'>$error_message</p>"; //If $error_message is set, this line creates a paragraph (<p>) element in HTML with the class message error.
                //It then inserts the content of $error_message inside that paragraph and displays it on the webpage.
            } //these tags close the 'if' block for the error message and end the PHP script.
            ?> 
            <div class="form-container">
                <form method="POST" action="add-faculty.php">
                    <label for="faculty_name">Faculty Name:</label>
                    <input type="text" id="faculty_name" name="faculty_name" required>
                    <button type="submit">Add Faculty</button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
