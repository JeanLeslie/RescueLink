<?php
session_start();
include('../config/db_connect.php');

// Check connection
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to check for new records
$sql = "SELECT COUNT(*) AS new_records FROM devicerecords WHERE DetectedDateTime > DATE_SUB(NOW(), INTERVAL 10 SECOND)";
if(isset($_SESSION['DevicesId']) && is_numeric($_SESSION['DevicesId'])) {
    $deviceId = mysqli_real_escape_string($conn, $_SESSION['DevicesId']);
    $sql .= " AND DeviceId = '$deviceId'";
}
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("Query execution failed: " . mysqli_error($conn));
}

// Fetch result
$row = mysqli_fetch_assoc($result);
print_r( $row);
// Check if there are new records
if ($row["new_records"] > 0) {
    echo "true"; // Send 'true' if there are new records
} else {
    echo "false"; // Send 'false' if there are no new records
}

// Close the connection
mysqli_close($conn);
?>
