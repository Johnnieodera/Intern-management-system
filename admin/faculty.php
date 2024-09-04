<?php
require('../config.php'); //This line includes the config.php file, which contains the code needed to connect to the database

// SQL query
//This line creates an SQL query that selects all columns (*) from the faculty table in the database.
//The * wildcard is used to select every column in the table
$sql = "SELECT * FROM faculty";

//This line sends the SQL query to the database using the mysqli_query() function, which runs the query on the database connection ($con).
// The result of the query, which is a list of all records from the faculty table, is stored in the $result variable.
// This variable will hold the data retrieved from the database, which can be used later in the script (e.g., to display it in a table).
$result = mysqli_query($con, $sql); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Information</title>
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
            <h1>Faculty Information</h1>
        </header>
        <section class="content">
            <table>
                <thead>
                    <tr>
                        <th>Faculty ID</th>
                        <th>Faculty Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

            //This PHP code is used to display data from the faculty table in an HTML table format
            // This line checks if there are any rows (records) in the $result variable, which contains the data retrieved from the faculty table.
            //The mysqli_num_rows($result) function counts how many rows were returned by the query.
            //If the count is greater than 0, it means there are records to display, and the code inside this if block will execute.

            if (mysqli_num_rows($result) > 0)
                    
                    {
                        //This line starts a while loop that will continue as long as there are rows of data to fetch from the $result variable.
                        //mysqli_fetch_assoc($result) fetches each row from the result set as an associative array, where the keys are the column names from the database.
                        // Each row of data is stored in the $row variable during each loop iteration.
                        while ($row = mysqli_fetch_assoc($result)) {
                            
                            echo "<tr>"; //This line creates a new table row (<tr>) in HTML. Each row in the table represents one record (faculty) from the database.
                            echo "<td>" . $row['faculty_id'] . "</td>"; //This line creates a table cell (<td>) in the current row and inserts the value of faculty_id from the current row of data.
                            echo "<td>" . $row['faculty_name'] . "</td>";// This line creates another table cell in the same row and inserts the value of faculty_name from the current row of data.
                            echo "</tr>"; //This closes the table row (</tr>), meaning that all the cells for this row have been added to the table.
                        } //This closes the while loop, which will continue looping until all the rows in $result have been processed.
                    } else { //This starts an else block that will execute if there are no records in $result (i.e., if mysqli_num_rows($result) is 0).

                        //If no records are found, this line creates a single table row with a cell that spans both columns (colspan='2').
                        //Inside this cell, it displays the message "No records found."
                        echo "<tr><td colspan='2'>No records found</td></tr>";
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
//mysqli_close($con);: This function closes the connection to the database that was established earlier in the script.
//Closing the connection is important because it frees up resources on the server.
mysqli_close($con);
?>
