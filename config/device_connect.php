<?php
include('db_connect.php');
// Set timezone to Philippine Standard Time (PST)
date_default_timezone_set('Asia/Manila');

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');
// echo "Current Date and Time: " . $currentDateTime;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$logFile = 'ping_log.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the incoming ping message
    $message = file_get_contents('php://input');
    // Get the IP address of the sender
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Log the message with the IP address
    $logMessage = "Message: " . $message . " | IP: " . $ipAddress;
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);

    // Display the received message along with a confirmation
    echo "Ping received from IP: " . htmlspecialchars($ipAddress) . "\n";
    echo "Message: " . htmlspecialchars($message) . "\n";
    echo "Your ping message has been successfully logged.\n";
    
    // Optionally, display all messages or the latest message
    $allMessages = file($logFile); // Read all lines into an array
    echo "Latest Message:";
    echo "\n" . htmlspecialchars(end($allMessages)) . "\n"; // Display the last message
    
    
    // Get DetectionTypeId
    $sql_detectionType = "SELECT DetectionTypeId FROM detectiontypes WHERE PingCode = '$message'";
    $result_detectionType = mysqli_query($conn, $sql_detectionType);
    if ($result_detectionType) {
        $detectionTypeId = mysqli_fetch_assoc($result_detectionType)['DetectionTypeId'];
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }

    // Get DevicesId
    $sql_deviceId = "SELECT DevicesId FROM devicestable WHERE IPAddress = '$ipAddress'";
    $result_deviceId = mysqli_query($conn, $sql_deviceId);

    if ($result_deviceId) {
        $device = mysqli_fetch_assoc($result_deviceId);
        
        if (!$device) { // If no device is found, insert a new record
            $sql_insert_device = "INSERT INTO devicestable (IPAddress, CreatedDateTime, DeviceName) 
                                VALUES ('$ipAddress', NOW(), NULL)";

            // Insert into database and check the result
            if (mysqli_query($conn, $sql_insert_device)) {
                echo 'New device added. Please update records.';
                // Re-fetch the new device ID after inserting
                $sql_deviceId = "SELECT DevicesId FROM devicestable WHERE IPAddress = '$ipAddress'";
                $result_deviceId = mysqli_query($conn, $sql_deviceId);
                $device = mysqli_fetch_assoc($result_deviceId)['DevicesId'];
            } else {
                echo 'Insert error: ' . mysqli_error($conn);
            }
        }

        // Display deviceId if available
        if ($device) {
            echo 'Device ID: ' . $device['DevicesId'];
            $devicesId = $device['DevicesId'];
        } else {
            echo 'No device found or inserted.';
        }
        
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }

    // Get DetectionStatusId
    $OGsql_statusId = "SELECT DetectionStatusId FROM detectionstatuses WHERE StatusCode = 'OG'";
    $OGresult_statusId = mysqli_query($conn, $OGsql_statusId);
    $Fsql_statusId = "SELECT DetectionStatusId FROM detectionstatuses WHERE StatusCode = 'F'";
    $Fresult_statusId = mysqli_query($conn, $Fsql_statusId);

    if ($OGresult_statusId) {
        $OGdetectionStatusId = mysqli_fetch_assoc($OGresult_statusId)['DetectionStatusId'];
       
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
    if ($Fresult_statusId) {
        $FdetectionStatusId = mysqli_fetch_assoc($Fresult_statusId)['DetectionStatusId'];
       
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }

    //SELECT FROM LAST RECORD OF THE DevicesId IF THE SAME DetectionTypeId AS NOW 
    // IF SAME, DO NOT INSERT
    // IF NOT SAME, INSERT

    $sql_last_record = "SELECT recordId, typeId
    FROM devicerecords 
    WHERE DeviceId = $devicesId AND DetectionStatusId = $OGdetectionStatusId
    ORDER BY recordId DESC 
    LIMIT 1";

    $result_last_record = mysqli_query($conn, $sql_last_record);

    if ($result_last_record) {
        $lastDevice = mysqli_fetch_assoc($result_last_record);
        if ($lastDevice) {
            $lastRecordId = $lastDevice['recordId'];
            $lastTypeId = $lastDevice['typeId'];
            if($lastTypeId == $detectionTypeId){
                echo 'Last DevicesId with the same DetectionTypeId: ' . $lastDevice['recordId'];

            } else {
                // Now perform the INSERT with retrieved IDs
                $sql_records = "INSERT INTO devicerecords (DetectedDateTime, TypeId, DeviceId, ImageLink,DetectionStatusId,ModifiedDateTime)
                VALUES ('$currentDateTime', $detectionTypeId, $devicesId, NULL, $OGdetectionStatusId, NULL)";
    
                if (mysqli_query($conn, $sql_records)) {
                    echo "need to update_records";
                    $update_records = "UPDATE devicerecords 
                    SET DetectionStatusId = $FdetectionStatusId
                    WHERE RecordID = $lastRecordId";
                    echo "update_records: ". $update_records;
                    if (mysqli_query($conn, $update_records)) {
                    } else {
                    echo 'query error: ' . mysqli_error($conn);
                    }
                } else {
                echo 'query error: ' . mysqli_error($conn);
                }
            }
        } else {
            // Now perform the INSERT with retrieved IDs
            $sql_records = "INSERT INTO devicerecords (DetectedDateTime, TypeId, DeviceId, ImageLink,DetectionStatusId,ModifiedDateTime)
            VALUES ('$currentDateTime', $detectionTypeId, $devicesId, NULL, $OGdetectionStatusId, NULL)";

            if (mysqli_query($conn, $sql_records)) {
            // Success
            // header('Location: ../ping_test.html');
            } else {
            echo 'query error: ' . mysqli_error($conn);
            }
        }
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
} else {
    // If accessed directly, show the latest message (if exists)
    if (file_exists($logFile)) {
        $allMessages = file($logFile);
        if (!empty($allMessages)) {
            echo "<h1>Latest Message:</h1>";
    
            // Get the last message and trim any whitespace
            $lastLogEntry = trim(end($allMessages));
        
            // Extract the actual message from the log (before the " | IP: " part)
            $messagePart = explode(' | ', $lastLogEntry)[0]; // "Message: 10" part
        
            // Extract just the message number (strip "Message: ")
            $message = trim(str_replace('Message: ', '', $messagePart));
        
            echo "<pre>" . htmlspecialchars($lastLogEntry) . "</pre>"; // Display the full log entry
        
            // Initialize status message
            $status_message = "";

            switch ($message){
                case "00":
                    $status_message = "No Smoke and No Fire Detected.";
                    break;
                case "01":
                    $status_message = "No Smoke, Fire is Present.";
                    break;
                case "10":
                    $status_message = "Smoke is Present, No Fire Detected.";
                    break;
                case "11":
                    $status_message = "Smoke and Fire is Present.";
                    break;
            }
            echo "<pre>" . $status_message . "</pre>"; // Display the last message

        } else {
            echo "No messages received yet.";
        }
    } else {
        echo "Log file does not exist.";
    }
}
?>
