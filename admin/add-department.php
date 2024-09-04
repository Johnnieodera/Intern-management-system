<?php
require('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_name = $_POST['department_name']; // lines retrieve the department_name and faculty_id values submitted via the form using the POST method.
    $faculty_id = $_POST['faculty_id']; // values stored in dept name and faculty_id

    // an SQL query to insert the department_name and faculty_id into the department table. 
    $stmt = $con->prepare("INSERT INTO department (department_name, faculty_id) VALUES (?, ?)");

    // This binds the variables to the prepared SQL statement
    //"si" specifies the data types
    //s for string (department_name)
    //i for integer (faculty_id).
    $stmt->bind_param("si", $department_name, $faculty_id);

    //line executes the prepared statement. If the execution is successful, the code inside the if block will run.
    if ($stmt->execute()) {
        $success_message = "New department added successfully";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close(); //This closes the prepared statement
}

// Fetch faculties for the dropdown
// SQL query to fetch all faculty_id and faculty_name from the faculty table.
// result is stored in the $faculty_result 
//used to populate a dropdown list with faculties.
$faculty_result = mysqli_query($con, "SELECT faculty_id, faculty_name FROM faculty");

// Now, after all operations are done, close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
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
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <header class="header">
            <h1>Add Department</h1>
        </header>
        <section class="content">
            <?php

            //Checks if the $success_message variable is set and not null.
            // This condition is used to determine whether there is a success message to display. 
            //If $success_message has been set earlier in the script (e.g., after a successful form submission), this block will execute.
            if (isset($success_message)) {

                //: Outputs the success message in an HTML paragraph.
                //<p>: Creates a paragraph element in HTML.
                //class='message success': Adds CSS classes for styling purposes. message is a general class, while success might be used to apply specific styles for success messages 
                //$success_message: Displays the actual message text.

                echo "<p class='message success'>$success_message</p>";
            }
           //Checks if the $error_message variable is set and not null.
           //This condition is used to determine whether there is an error message to display.
           // If $error_message has been set (e.g., due to a form submission error), this block will execute.
            if (isset($error_message)) {

                //Outputs the error message in an HTML paragraph.
                //<p>: Creates a paragraph element in HTML.
                //class='message error': Adds CSS classes for styling purposes. message is a general class, while error might be used to apply specific styles for error messages
                //$error_message: Displays the actual message text.
                echo "<p class='message error'>$error_message</p>";
            }
            ?>
            <div class="form-container">
                <form method="POST" action="add-department.php">
                    <label for="department_name">Department Name:</label>
                    <input type="text" id="department_name" name="department_name" required>
                    <label for="faculty_id">Faculty:</label>
                    <select id="faculty_id" name="faculty_id" required>
                        
                        <?php
                        //  used to generate <option> elements for a dropdown menu
                        //  a while loop that will continue to run as long as there are rows of data to fetch from the $faculty_result variable.
                        // mysqli_fetch_assoc($faculty_result) fetches the next row from the result set as an associative array
                        // keys are the column names from the database table
                        // Each iteration of the loop fetches one row of data and stores it in the $row variable.
                        while ($row = mysqli_fetch_assoc($faculty_result)) {

                            //  This line outputs an <option> element for a dropdown list.
                            // "<option value='" . $row['faculty_id'] . "'>" creates an <option> tag where the value attribute is set to the faculty_id from the current row of data.
                            // " . $row['faculty_name'] . "" places the faculty_namefrom the current row between the opening and closing<option>` tags. This is the text that the user will see in the dropdown.
                            //The . operator is used to join together the different parts of the string.
                            echo "<option value='" . $row['faculty_id'] . "'>" . $row['faculty_name'] . "</option>";
                            
                        }
                        ?>
                    </select>
                    <button type="submit">Add Department</button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
