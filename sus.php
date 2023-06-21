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

// Function to retrieve records with filled arrival and null departure
function getRecordsWithArrivalAndNullDeparture() {
    global $conn, $currentDate;

    // Get the current date for table name
    $currentDate = date("Y_m_d");

    // Select records where arrival is filled and departure is null
    $sql = "SELECT * FROM $currentDate WHERE arrival IS NOT NULL AND departure IS NULL";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $records = getRecordsWithArrivalAndNullDeparture();
} else {
    $records = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find Records with Arrival and Null Departure</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Find Records with Arrival and Null Departure</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <button type="submit" class="btn btn-primary">Find Records</button>
        </form>

        <?php if (!empty($records)): ?>
            <h3 class="mt-4">Records with Arrival and Null Departure:</h3>
            <table class="table table-bordered mt-2">
                <thead class="thead-dark">
                    <tr>
                        <th>Index Number</th>
                        <th>Name</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo $record['index_number']; ?></td>
                            <td><?php echo $record['name']; ?></td>
                            <td><?php echo $record['arrival']; ?></td>
                            <td><?php echo $record['departure']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p class="mt-4">No records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
