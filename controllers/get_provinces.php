<?php
// Assuming you have included the database connection file config/db_connect.php
include('../config/db_connect.php');

// Check if region_id is provided and it's a valid number
if(isset($_GET['region_id']) && is_numeric($_GET['region_id'])) {
    // Sanitize the input to prevent SQL injection
    $regionId = mysqli_real_escape_string($conn, $_GET['region_id']);

    // Query to fetch provinces based on the provided region_id
    $sql = "SELECT * FROM table_province WHERE region_id = $regionId";
    $result = mysqli_query($conn, $sql);

    // Check if there are any provinces found
    if(mysqli_num_rows($result) > 0) {
        $provinces = array();
        // Fetch province data and store in an array
        while($row = mysqli_fetch_assoc($result)) {
            $provinces[] = $row;
        }
        // Return provinces data as JSON
        echo json_encode($provinces);
    } else {
        // If no provinces found, return an empty array
        echo json_encode(array());
    }
} else {
    // If region_id is not provided or not valid, return an error message
    echo json_encode(array('error' => 'Invalid or missing region ID'));
}

// Close database connection
mysqli_close($conn);
?>
