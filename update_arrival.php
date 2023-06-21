<?php
$servername = "localhost";
$username = "xtreamd1_senath";
$password = "Ssenath1234@";
$database = "xtreamd1_students";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current date and time in GMT+0530 timezone
date_default_timezone_set("Asia/Kolkata");
$currentDateTime = date("Y-m-d H:i:s");

// Get the current date for table name
$currentDate = date("Y_m_d");

// Check if the admission number is received via HTTP request
if (isset($_GET['admission_number'])) {
    $admissionNumber = $_GET['admission_number'];

    // Check the time condition
    $currentTime = strtotime($currentDateTime);
    $nineAM = strtotime(date("Y-m-d") . " 09:00:00");

    if ($currentTime < $nineAM) {
        // Update the arrival field to the current date and time for the admission number in the current date's table
        $sql = "UPDATE $currentDate SET arrival = '$currentDateTime' WHERE index_number = '$admissionNumber'";
    } else {
        // Update the departure field to the current date and time for the admission number in the current date's table
        $sql = "UPDATE $currentDate SET departure = '$currentDateTime' WHERE index_number = '$admissionNumber'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully\n";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Admission number not received";
}

// Close the database connection
$conn->close();
?>
