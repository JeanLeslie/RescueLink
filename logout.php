<?php
// Start or resume the session
session_start();

// Check if the request contains the 'clicked' parameter
if(isset($_POST['clicked'])) {
    // Destroy the session and remove session data
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
} else {
    // Handle error (optional)
    echo "Error: 'clicked' parameter not found.";
}
?>
