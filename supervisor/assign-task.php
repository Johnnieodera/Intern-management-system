<?php
require('../config.php'); // the config.php file, which is expected to contain database connection 

//Checks if the form was submitted using the POST method.
//This ensures that the code inside this block only runs when data is sent via POST (e.g., from a form submission).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Retrieves data from the POST request and assigns it to variables
    //$task_name: The name of the task to be assigned.
   
    $task_name = $_POST['task_name'];

    //$student_id: The ID of the student to whom the task is assigned. 
    //intval() is used to convert the value to an integer to prevent SQL injection and ensure it is treated as a numeric value.

    $student_id = intval($_POST['student_id']); // Ensuring student_id is an integer

    // Check if the student_id exists and belongs to a student
    //Executes a query to check if a student with the given $student_id exists and has the role of 'student'
    //SELECT id FROM users WHERE id = $student_id AND role = 'student': The query selects the ID from the users table where the ID matches $student_id and the role is 'student'
    //mysqli_query($con, $query): Runs the query against the database
    $query = "SELECT id FROM users WHERE id = $student_id AND role = 'student'";
    $check_student = mysqli_query($con, $query);
    
    //Checks if the query returned any rows. mysqli_num_rows($check_student) counts the number of rows in the result set
    //If the count is 0, it means no matching student was found, so the script halts execution with an error message using die().
    if (mysqli_num_rows($check_student) == 0) {
        die("Error: Selected student does not exist or is not a student.");
    }

    // Prepare and bind the INSERT query
    // Prepares and binds parameters for the SQL INSERT query
    //$con->prepare(): Prepares an SQL statement for execution The query inserts the task name and student ID into the task table.
    $stmt = $con->prepare("INSERT INTO task (task_name, student_id) VALUES (?, ?)");

     //bind_param("si", $task_name, $student_id): Binds the variables to the placeholders in the query. 
    //"si" indicates that $task_name is a string and $student_id is an integer.
    $stmt->bind_param("si", $task_name, $student_id);

    //Executes the prepared statement and handles success or failure
    //If execute() returns true, the task was successfully inserted, and a success message is set.
    //If execute() returns false, an error message is set with details from $stmt->error
    if ($stmt->execute()) {
        $success_message = "Task assigned successfully";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    //If execute() returns false, an error message is set with details from $stmt->error
    $stmt->close();
}

// Fetch students for the dropdown
// Executes a query to fetch all students' IDs and usernames
//SELECT id, username FROM users WHERE role = 'student': Selects IDs and usernames of users whose role is 'student'.
//mysqli_query($con, "SELECT id, username FROM users WHERE role = 'student'"): Runs the query and stores the result in $student_result.
$student_result = mysqli_query($con, "SELECT id, username FROM users WHERE role = 'student'");

// Close the connection after all queries
// Closes the database connection to free up resources after all database operations are completed.
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
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
                
                <li><a href="assign-task.php">Assign Task</a></li>
                <li><a href="../logout.php">logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <header class="header">
            <h1>Assign Task</h1>
        </header>
        <section class="content">
            <?php

            //Checks if the variable $success_message is set and not null.
            // This condition is used to determine whether there is a success message to display
            //If the variable $success_message exists, it means there was a successful operation (like form submission or data processing).
            if (isset($success_message)) {

                // Outputs the success message in HTML format
                //<p>: Creates a paragraph element in HTML.
                //class='message success': Adds CSS classes for styling.
                // The message class could be used for general message styling, while success might be used for specific styling of success messages (e.g., green text).
                //$success_message: Displays the content of the success message.
                echo "<p class='message success'>$success_message</p>";
            }
            // Checks if the variable $error_message is set and not null.
            // This condition is used to determine whether there is an error message to display
            // If the variable $error_message exists, it means there was an error during the operation (such as a form submission error).
            if (isset($error_message)) {

                // Outputs the error message in HTML format.
                //<p>: Creates a paragraph element in HTML.
                //class='message error': Adds CSS classes for styling. 
                // The message class is for general message styling, and error might be used for specific styling of error messages (e.g., red text).
                //$error_message: Displays the content of the error message.
                echo "<p class='message error'>$error_message</p>";
            }
            ?>
            <div class="form-container">
                <form method="POST" action="assign-task.php">
                    <label for="task_name">Task Name:</label>
                    <input type="text" id="task_name" name="task_name" required>
                    <label for="student_id">Assign to Student:</label>
                    <select id="student_id" name="student_id" required>
                        
                        <?php

                        // This loop iterates through each row of the result set retrieved from the database.
                        //mysqli_fetch_assoc($student_result) fetches a row from the $student_result result set as an associative array
                        // Each key in the array corresponds to a column name in the result set, and each value corresponds to the data for that column in the current row.
                        //The while loop continues until there are no more rows to fetch, processing each row one by one.
                        while ($row = mysqli_fetch_assoc($student_result)) {

                            // Generates an HTML <option> element for each student.
                           //<option>: Creates an option within a dropdown menu.
                           //value='" . $row['id'] . "': Sets the value attribute of the <option> to the student's ID
                           //This is the value that will be sent when the form is submitted.
                           //>" . $row['username'] . "</option>: Sets the text displayed in the dropdown menu to the student's username.
                            echo "<option value='" . $row['id'] . "'>" . $row['username'] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Assign Task</button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
