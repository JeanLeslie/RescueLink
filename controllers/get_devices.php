<?php
// Assuming you have included the database connection file config/db_connect.php
include('../config/db_connect.php');

// // Check if region_id is provided and it's a valid number
// if(isset($_GET['province_id']) && is_numeric($_GET['province_id'])) {
    // Sanitize the input to prevent SQL injection
    // $province_id = mysqli_real_escape_string($conn);

    // Query to fetch provinces based on the provided province_id
    $sql = "SELECT dvt.DevicesId,dvt.DeviceName, dvt.IPAddress,b.barangay_name,m.municipality_name,p.province_name 
        FROM `devicestable` dvt 
        JOIN table_barangay b on b.barangay_id = dvt.BarangayId 
        JOIN table_municipality m on m.municipality_id = b.municipality_id 
        JOIN table_province p on p.province_id = m.province_id 
        ORDER BY dvt.DevicesId DESC";
    $result = mysqli_query($conn, $sql);

    // Check if there are any provinces found
    if(mysqli_num_rows($result) > 0) {
        $devices = array();
        // Fetch province data and store in an array
        while($row = mysqli_fetch_assoc($result)) {
            $devices[] = $row;
        }
        // Return provinces data as JSON
        echo json_encode($devices);
    } else {
        // If no provinces found, return an empty array
        echo json_encode(array());
    }
// } else {
//     // If province_id is not provided or not valid, return an error message
//     echo json_encode(array('error' => 'Invalid or missing province ID'));
// }

// Close database connection
mysqli_close($conn);
?>
