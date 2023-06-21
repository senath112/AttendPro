<?php
$servername = "localhost";
$username = "xtreamd1_senath";
$password = "Ssenath1234@";
$dbname = "xtreamd1_students";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO 2023_05_30 (index number, name)
VALUES ('29333', 'ft')";

if ($conn->query($sql) === TRUE) {
  $last_id = $conn->insert_id;
  echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
