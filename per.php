<?php
$servername = "localhost";
        $username = "xtreamd1_senath";
        $password = "Ssenath1234@";
        $dbname = "xtreamd1_students";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to calculate the attendance percentage for a student
function calculateAttendancePercentage($admissionNumber, $currentMonthTables)
{
    global $conn;

    // Initialize variables
    $attendanceCount = 0;
    $totalTables = 0;

    // Iterate through each table
    foreach ($currentMonthTables as $table) {
        $sql = "SELECT arrival, departure FROM $table WHERE index_number = '$admissionNumber'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['arrival'] !== null || $row['departure'] !== null) {
                    $attendanceCount++;
                }
            }
        }

        $totalTables++;
    }

    // Calculate the attendance percentage
    $attendancePercentage = ($attendanceCount / $totalTables) * 100;

    return $attendancePercentage;
}

// Function to generate a CSV file with student attendance percentages
function generateCSVFile($data)
{
    // Generate a unique filename for the CSV file
    $filename = 'student_attendance_percentages_' . time() . '.csv';

    // Set the appropriate headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Create a file pointer
    $file = fopen('php://output', 'w');

    // Write the CSV headers
    fputcsv($file, array('Index Number', 'Attendance Percentage'));

    // Write the data rows
    foreach ($data as $row) {
        fputcsv($file, $row);
    }

    // Close the file pointer
    fclose($file);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the uploaded file details
    $file = $_FILES['csvfile']['tmp_name'];

    // Process the uploaded CSV file
    if (is_uploaded_file($file)) {
        // Read the CSV file
        $handle = fopen($file, 'r');

        // Initialize variables
        $studentAttendancePercentages = array();
        $currentMonthTables = array();

        // Get the current year and month
        $currentYearMonth = date('Y_m');

        // Iterate through each line in the CSV file
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $admissionNumber = $data[0];

            // Get all the tables for the current month
            $sql = "SHOW TABLES LIKE '$currentYearMonth%'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_row()) {
                    $currentMonthTables[] = $row[0];
                }
            }

            // Calculate the attendance percentage for the student
            $attendancePercentage = calculateAttendancePercentage($admissionNumber, $currentMonthTables);

            // Add the student and attendance percentage to the array
            $studentAttendancePercentages[] = array($admissionNumber, $attendancePercentage);
        }

        // Generate the CSV file for download
        generateCSVFile($studentAttendancePercentages);

        // Close the file handle
        fclose($handle);
    } else {
        // Invalid file upload
        echo "Invalid file upload";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calculate Student Attendance Percentage</title>
</head>
<body>
    <h1>Calculate Student Attendance Percentage</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="csvfile">Upload CSV File:</label>
        <input type="file" id="csvfile" name="csvfile" accept=".csv" required>
        <br><br>
        <input type="submit" name="submit" value="Calculate Percentage">
    </form>
</body>
</html>
