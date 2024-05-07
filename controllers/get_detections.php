<?php
// Assuming you have included the database connection file config/db_connect.php
include('../config/db_connect.php');
session_start();
// Check if region_id is provided and it's a valid number


    // Query to fetch provinces based on the provided deviceId
    $sql = "SELECT DATE_FORMAT(dr.DetectedDateTime, '%Y/%m/%d') AS DateTime, 
    DATE_FORMAT(dr.DetectedDateTime, '%Y-%b-%d %h:%i:%s %p') AS FormattedDateTime, 
    dr.ImageLink, dtt.Name, dvt.IPAddress, b.barangay_name, m.municipality_name, p.province_name , ds.Status, ds.StatusCode
        FROM devicerecords dr
        JOIN devicestable dvt ON dr.DeviceId = dvt.DevicesId
        JOIN detectiontypes dtt ON dr.TypeId = dtt.DetectionTypeId
        JOIN table_barangay b ON b.barangay_id = dvt.BarangayId
        JOIN table_municipality m ON m.municipality_id = b.municipality_id
        JOIN table_province p ON p.province_id = m.province_id
        JOIN DetectionStatuses ds ON dr.DetectionStatusId  = ds.DetectionStatusId";
    if(isset($_SESSION['DevicesId']) && is_numeric($_SESSION['DevicesId'])) {
        // Sanitize the input to prevent SQL injection
        $deviceId = mysqli_real_escape_string($conn, $_SESSION['DevicesId']);
        $sql = $sql ." WHERE dr.DeviceId = ".$deviceId;
    }
    $sql = $sql ." ORDER BY dr.RecordId DESC";

    $result = mysqli_query($conn, $sql);

    // Check if there are any provinces found
    if(mysqli_num_rows($result) > 0) {
        $records = array();
        // Fetch province data and store in an array
        while($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        // Return provinces data as JSON
        echo json_encode($records);
    } else {
        // If no provinces found, return an empty array
        echo json_encode(array());
    }


// Close database connection
mysqli_close($conn);
?>
