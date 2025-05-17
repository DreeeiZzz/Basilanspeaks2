<?php
// Start the session
session_start();

// Destroy all session data
session_unset();   // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to index.php
header("Location:selection.php");
exit(); // Make sure no further code is executed
?>
