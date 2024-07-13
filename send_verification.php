<?php
// Database connection
$host = 'localhost';
$db = 'your_database';
$user = 'your_username';
$pass = 'your_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Generate a unique verification token
        $token = bin2hex(random_bytes(16));

        // Save email and token to the database
        $stmt = $pdo->prepare('INSERT INTO email_verifications (email, token) VALUES (?, ?)');
        $stmt->execute([$email, $token]);

        // Create verification link
        $verification_link = "http://yourdomain.com/verify_email.php?token=$token";

        // Send verification email
        $subject = "Email Verification";
        $message = "Please click the following link to verify your email: $verification_link";
        $headers = 'From: noreply@yourdomain.com' . "\r\n" .
                   'Reply-To: noreply@yourdomain.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        if (mail($email, $subject, $message, $headers)) {
            echo "Verification email sent to $email";
        } else {
            echo "Failed to send verification email.";
        }
    } else {
        echo "Invalid email address.";
    }
}
?>
