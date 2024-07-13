<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';
$user = 'u510162695_infotechMCC';
$pass = 'infotechMCC2023';
$db = 'u510162695_infotechMCC';
$charset = 'utf8mb4';

// DSN (Data Source Name) for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Generate a unique verification token
            $token = bin2hex(random_bytes(16));

            // Save email and token to the database
            $stmt = $pdo->prepare('INSERT INTO email_verifications (email, token) VALUES (?, ?)');
            $stmt->execute([$email, $token]);

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
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo "Invalid email address.";
    }
}
?>
