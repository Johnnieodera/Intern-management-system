<?php
require('../config.php'); //  the config.php file, which contains the code to connect to the database

// SQL query
/*"SELECT department.department_id, department.department_name, faculty.faculty_name AS faculty_name": 
This part tells the database to select three pieces of information:
department.department_id: The ID of the department.
department.department_name: The name of the department.
faculty.faculty_name AS faculty_name: The name of the faculty, with the AS faculty_name part giving it a shorter, easier-to-use name in the result.

"FROM department": This part tells the database which table to get the information from—in this case, the department table.

"LEFT JOIN faculty ON department.faculty_id = faculty.faculty_id": This part tells the database to also include information from the faculty table, matching rows where the faculty_id in the department table is the same as the faculty_id in the faculty table.

 The LEFT JOIN means that even if there is no matching faculty, the department information will still be included.


*/
$sql = "SELECT department.department_id, department.department_name, faculty.faculty_name AS faculty_name
        FROM department
        LEFT JOIN faculty ON department.faculty_id = faculty.faculty_id";

$result = mysqli_query($con, $sql); // This line sends the SQL query to the database using the mysqli_query() function, which runs the query on the database connection ($con).
 // The results of the query (the selected department and faculty information) are stored in the $result variable.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department and Faculty Information</title>
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
            <h1>Department and Faculty Information</h1>
        </header>
        <section class="content">
            <table>
                <thead>
                    <tr>
                        <th>Department ID</th>
                        <th>Department Name</th>
                        <th>Faculty Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) //This line checks if there are any rows (records) in the $result variable, which contains the data from your SQL query.
                    //The mysqli_num_rows($result) function counts how many rows were returned by the query.
                    //If the count is greater than 0, it means there are records to display, and the code inside this if block will run.
                    {
                        while ($row = mysqli_fetch_assoc($result)) //This line starts a while loop that will run as long as there are rows of data to fetch from the $result variable.
                        //mysqli_fetch_assoc($result) fetches each row from the result set as an associative array, where the keys are the column names from the database. Each time the loop runs, a new row is fetched and stored in the $row variable.
                        {
                            echo "<tr>"; //This line creates a new table row (<tr>) in HTML. Each row in the table will represent one department record from the database.
                            echo "<td>" . $row['department_id'] . "</td>"; //This line creates a table cell (<td>) and inserts the value of department_id from the current row of data.
                            echo "<td>" . $row['department_name'] . "</td>"; //This line creates another table cell and inserts the value of department_name from the current row.
                            echo "<td>" . $row['faculty_name'] . "</td>"; //This line creates the third table cell and inserts the value of faculty_name from the current row.
                            echo "</tr>"; //This closes the table row (</tr>), meaning that all the cells for this row of data have been added to the table.
                        } //This closes the while loop, which means it will continue creating rows in the table until all the data in $result has been processed.
                    } else {

                        //If there are no records, this line creates a single table row with a cell spanning all three columns (colspan='3') that displays the message "No records found."
                        echo "<tr><td colspan='3'>No records found</td></tr>";
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
mysqli_close($con);
?>
