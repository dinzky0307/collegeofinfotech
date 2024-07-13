<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the configuration file
//include 'config.php';

// Start a session
//session_start();

$host = '127.0.0.1';
$user = 'u510162695_infotechMCC';
$pass = 'infotechMCC2023';
$db = 'u510162695_infotechMCC';

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process verification token
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $stmt = $conn->prepare('SELECT email FROM email_verifications WHERE token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "Email " . $user['email'] . " has been successfully verified.";

        // Optionally, delete the token from the database or mark it as used
        $delete_stmt = $conn->prepare('DELETE FROM email_verifications WHERE token = ?');
        $delete_stmt->bind_param('s', $token);
        $delete_stmt->execute();
    } else {
        echo "Invalid or expired token.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No token provided.";
}
?>
