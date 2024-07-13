<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
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
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->bind_result($email);

    if ($stmt->fetch()) {
        echo "Email " . $email . " has been successfully verified.";
        
        // Close the statement to free up the connection
        $stmt->close();

        // Optionally, delete the token from the database or mark it as used
        $delete_stmt = $conn->prepare('DELETE FROM email_verifications WHERE token = ?');
        if (!$delete_stmt) {
            die("Prepare for delete failed: " . $conn->error);
        }

        $delete_stmt->bind_param('s', $token);
        $delete_stmt->execute();
        $delete_stmt->close();

        // Add JavaScript to redirect after 3 seconds
        echo '<script>
            setTimeout(function() {
                window.location.href = "/student/grade.php";
            }, 3000);
        </script>';
    } else {
        echo "Invalid or expired token.";
        $stmt->close();
    }

    $conn->close();
} else {
    echo "No token provided.";
}
?>
