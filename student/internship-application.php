<?php
require('../config.php'); //This line includes the config.php file, which is expected to contain the database connection setup.

//This line checks if the form was submitted using the POST method. This ensures that the code inside this block only runs when the form data is submitted.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	//These lines retrieve the form data submitted via POST and assign them to variables. The data is extracted from the $_POST array
	/* $place_of_internship: The place where the internship will take place.
$internship_department: The department within the place of internship.
$student_name: The name of the student applying for the internship.
$student_contact: The contact information of the student.
	
	*/
    $place_of_internship = $_POST['place_of_internship'];
    $internship_department = $_POST['internship_department'];
    $student_name = $_POST['student_name'];
    $student_contact = $_POST['student_contact'];

    // Prepare and bind
	//This block of code prepares an SQL statement to insert the collected data into the internships table
	//The prepare() method creates a prepared statement with placeholders (?) for the values to be inserted.
	
    $stmt = $con->prepare("INSERT INTO internships (place_of_internship, internship_department, student_name, student_contact) VALUES (?, ?, ?, ?)");



	//The bind_param() method binds the variables to the placeholders in the prepared statement. 
	//The "ssss" string indicates that all four parameters are strings.
    $stmt->bind_param("ssss", $place_of_internship, $internship_department, $student_name, $student_contact);

	//This block executes the prepared statement and checks if it was successful
	//If execute() returns true, it means the insertion was successful, and the $success_message variable is set to indicate success.
	//If execute() returns false, it means there was an error, and the $error_message variable is set to include the error message from the statement.
	
    if ($stmt->execute()) {
        $success_message = "Application submitted successfully";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

	//This block closes the prepared statement and the database connection
	//close() is called on the prepared statement to release the resources associated with it
	//mysqli_close($con) closes the connection to the database, freeing up resources and ensuring no further operations can be performed using this connection.
    $stmt->close();
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Application</title>
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
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            height: 100vh;
            padding: 10px;
        }
        .sidebar-header h2 {
            margin-top: 0;
        }
        .sidebar-nav ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar-nav ul li {
            margin: 10px 0;
        }
        .sidebar-nav ul li a {
            color: white;
            text-decoration: none;
        }
        .sidebar-nav ul li a:hover {
            text-decoration: underline;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .header h1 {
            margin-top: 0;
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
				<li><a href="../logout.php">logout</a></li>
                
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <header class="header">
            <h1>Internship Application</h1>
        </header>
        <section class="content">
            <?php

			//This line checks if the $success_message variable is set. isset() is used to determine if a variable has been defined and has a value other than null.
			//If $success_message is set, it means there is a success message to display.

            if (isset($success_message)) {

				//This line generates HTML to display the success message.
				//It creates a paragraph (<p>) with the class message and success. The success message stored in $success_message is inserted inside the paragraph.
				//The class='message success' part is used for styling the message with CSS, typically to show it in a distinctive way
                echo "<p class='message success'>$success_message</p>";
            }

			//This line checks if the $error_message variable is set.
			//If $error_message is set, it means there is an error message to display.
            if (isset($error_message)) {

				//This line generates HTML to display the error message.
				//It creates a paragraph (<p>) with the class message and error.
				//The error message stored in $error_message is inserted inside the paragraph.
				//The class='message error' part is used for styling the message with CSS, typically to show it in a distinctive way
                echo "<p class='message error'>$error_message</p>";
            }
            ?>
            <div class="form-container">
                <form method="POST" action="internship-application.php">
                    <label for="place_of_internship">Place of Internship:</label>
                    <input type="text" id="place_of_internship" name="place_of_internship" required>
                    <label for="internship_department">Department for Internship:</label>
                    <input type="text" id="internship_department" name="internship_department" required>
                    <label for="student_name">Name of Student:</label>
                    <input type="text" id="student_name" name="student_name" required>
                    <label for="student_contact">Contact of Student:</label>
                    <input type="tel" id="student_contact" name="student_contact" required>
                    <button type="submit">Submit Application</button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
