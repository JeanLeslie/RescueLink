<?php
// Assuming you have included the database connection file config/db_connect.php
include('../config/db_connect.php');

// Check if region_id is provided and it's a valid number
if(isset($_GET['municipality_id']) && is_numeric($_GET['municipality_id'])) {
    // Sanitize the input to prevent SQL injection
    $municipality_id = mysqli_real_escape_string($conn, $_GET['municipality_id']);

    // Query to fetch municipalitys based on the provided municipality_id
    $sql = "SELECT * FROM table_barangay WHERE municipality_id = $municipality_id";
    $result = mysqli_query($conn, $sql);

    // Check if there are any municipalitys found
    if(mysqli_num_rows($result) > 0) {
        $barangays = array();
        // Fetch municipality data and store in an array
        while($row = mysqli_fetch_assoc($result)) {
            $barangays[] = $row;
        }
        // Return municipalitys data as JSON
        echo json_encode($barangays);
    } else {
        // If no municipalitys found, return an empty array
        echo json_encode(array());
    }
} else {
    // If municipality_id is not provided or not valid, return an error message
    echo json_encode(array('error' => 'Invalid or missing municipality ID'));
}

// Close database connection
mysqli_close($conn);
?>
