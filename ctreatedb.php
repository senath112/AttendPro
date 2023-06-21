<?php

// Database credentials
$servername = "localhost";
$username = "xtreamd1_senath";
$password = "Ssenath1234@";
$dbname = "xtreamd1_students";

// Create a new database connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
}

// Close the connection
$conn->close();

// Establish a connection to the newly created database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current date for the table name
$date = date("Y_m_d");

// Create the table for the current date if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS `$date` (
    `index_number` VARCHAR(255) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `arrival` DATETIME DEFAULT NULL,
    `departure` DATETIME DEFAULT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

// Import records from CSV file
$csvFile = "students.csv"; // Replace with your CSV file path

// Open the CSV file for reading
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Skip the first line (header)
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $index_number = $data[0];
        $name = $data[1];

        // Insert the record into the current date's table
        $sql = "INSERT INTO `$date` (`index_number`, `name`)
                VALUES ('$index_number', '$name')";

        if ($conn->query($sql) === TRUE) {
            echo "Record imported successfully\n";
        } else {
            echo "Error importing record: " . $conn->error;
        }
    }

    // Close the CSV file
    fclose($handle);
}

// Close the connection
$conn->close();

?>
