<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the configuration file
include 'config.php';

// Start a session
//session_start();

// Function to generate a random string
function generateRandomString($length = 32) {
    return bin2hex(openssl_random_pseudo_bytes($length / 2));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $host = '127.0.0.1';
$user = 'u510162695_infotechMCC';
$pass = 'infotechMCC2023';
$db = 'u510162695_infotechMCC';

//mysql_connect($host, $user, $pass) or die(mysql_error());
//mysql_select_db($db);
    
    if ($username && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Create a connection
        $conn = new mysqli($host, $user, $pass, $db);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Generate a unique verification token
        $token = generateRandomString();

        // Save email and token to the database
        $stmt = $conn->prepare('INSERT INTO email_verifications (email, token) VALUES (?, ?)');
        $stmt->bind_param('ss', $email, $token);
        if ($stmt->execute()) {
            // Create verification link
            $verification_link = "http://collegeofinfotech.com/verify_email.php?token=$token";

            // Send verification email
            $subject = "Email Verification";
            $message = "Please click the following link to verify your email: $verification_link";
            $headers = 'From: noreply@collegeofinfotech.com' . "\r\n" .
                       'Reply-To: noreply@collegeofinfotech.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            if (mail($email, $subject, $message, $headers)) {
                echo "Verification email sent to $email";
            } else {
                echo "Failed to send verification email.";
            }
        } else {
            echo "Failed to save verification token.";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid email address or missing username.";
    }
}
?>
