<?php
//the config.php file, which sets up the connection to the database by initializing the $con variable.
require('../config.php');

//This line starts a new session or resumes an existing one. 
//
session_start();

//This line retrieves the user_id stored in the session and assigns it to the $student_id variable.
//This user_id represents the ID of the student who is currently logged in.
$student_id = $_SESSION['user_id']; 

// SQL query to fetch tasks assigned to the logged-in student
// This line prepares an SQL query to select the task_name from the task table, where the student_id matches the ID of the logged-in student. 
// The ? is a placeholder for the student ID, which will be provided later.
$sql = "SELECT task_name FROM task WHERE student_id = ?";

//This line prepares the SQL query for execution by creating a prepared statement
// Prepared statements help prevent SQL injection attacks and are more secure when working with user-supplied data.
$stmt = $con->prepare($sql);

//This line binds the value of $student_id to the ? placeholder in the SQL query.
//The "i" indicates that the bound parameter is an integer.
$stmt->bind_param("i", $student_id);

//This line executes the prepared statement, running the SQL query with the bound student_id.
$stmt->execute();

//This line retrieves the result of the query and stores it in the $result variable. 
//The result contains all the tasks that have been assigned to the logged-in student.
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tasks</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 50px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
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
			<li><a href="internship-application.php">internship-application</a></li>
			<li><a href="task.php">Task</a></li>
            <li><a href="../logout.php">logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <header class="header">
            <h1>Your Tasks</h1>
        </header>
        <section class="content">
            <table>
                <thead>
                    <tr>
                        <th>Task Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //This line checks if there are any rows (tasks) in the $result variable, which contains the data retrieved from the database.
                    //The mysqli_num_rows($result) function counts the number of rows in the result set.
                    //If the count is greater than 0, it means there are tasks to display, and the code inside this if block will run.
                    if (mysqli_num_rows($result) > 0) {

                   // This line starts a while loop that will continue as long as there are rows of data to fetch from the $result variable.
                   //mysqli_fetch_assoc($result) fetches each row from the result set as an associative array, where the keys are the column names from the database. 
                   // Each row of data is stored in the $row variable during each loop iteration.
                        while ($row = mysqli_fetch_assoc($result)) {

                            //This line creates a new table row (<tr>) in HTML. Each row in the table represents one task.
                            echo "<tr>";

                            //This line creates a table cell (<td>) in the current row and inserts the value of task_name from the current row of data.
                            /* The htmlspecialchars() function is used to convert special characters to HTML entities, which helps prevent Cross-Site Scripting (XSS) attacks.
                            For example, if a task name contains HTML tags or special characters, they will be displayed as plain text rather than being interpreted as HTML.
                            
                            */
                            echo "<td>" . htmlspecialchars($row['task_name']) . "</td>";

                            //This closes the table row (</tr>), meaning that all the cells for this row have been added to the table.
                            echo "</tr>";
                        } //This closes the while loop, which will continue looping until all the rows in $result have been processed.

                    } else { //This starts an else block that will run if there are no tasks in $result (i.e., if mysqli_num_rows($result) is 0). 

                     /*If no tasks are found, this line creates a single table row with a cell that spans one column (colspan='1'). Inside this cell, it displays the message "No tasks assigned yet."
                     
                     */ 
                        echo "<tr><td colspan='1'>No tasks assigned yet</td></tr>";

                    } //This closes the if-else block.
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
</body>
</html>

<?php

//This line closes the prepared statement $stmt
//The close() method is called on the prepared statement object to free up the resources associated with it.
$stmt->close();


//The mysqli_close() function is used to close the database connection represented by the $con variable.
mysqli_close($con);
?>
