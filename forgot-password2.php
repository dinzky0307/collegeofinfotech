<?php
session_start(); // Ensure session is started
include 'database.php'; // Ensure this contains your database connection setup

// Check if the connection is established
if (!isset($connection)) {
    die("Database connection failed.");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "phpmailer/src/Exception.php";
require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $redirectRequired = false;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid Email: Please enter a valid MS 365 email address.";
        $_SESSION['status_code'] = "error";
        $redirectRequired = true;
    } else {
        // Verify domain
        $domain = substr(strrchr($email, "@"), 1);
        if ($domain !== 'mcclawis.edu.ph') {
            $_SESSION['status'] = "Invalid: Please enter an email address with the mcclawis.edu.ph domain.";
            $_SESSION['status_code'] = "error";
            $redirectRequired = true;
        } else {
            // Check email usage in the database
            $stmt = $connection->prepare("SELECT used, username FROM ms_account WHERE username = :email");
            $stmt->bindParam(':email', $email);
            if (!$stmt->execute()) {
                $_SESSION['status'] = "Database error while checking email.";
                $_SESSION['status_code'] = "error";
                $redirectRequired = true;
            } else {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$result) {
                    $_SESSION['status'] = "Email not found. Please visit the BSIT office to get an MS 365 Account.";
                    $_SESSION['status_code'] = "error";
                    $redirectRequired = true;
                } elseif ($result['used'] == 1) {
                    $_SESSION['status'] = "This email has already been used.";
                    $_SESSION['status_code'] = "error";
                    $redirectRequired = true;
                }
            }
        }
    }

    // Redirect if validation failed
    if ($redirectRequired) {
        header("Location: forgot_password.php");
        exit();
    }

    // Generate verification code
    $verification_code = md5(rand());

    // Update verification code in ms_account table
    $updateVerification = "UPDATE ms_account SET verification_code = :verification_code, created_at = NOW() WHERE username = :email";
    $stmt = $connection->prepare($updateVerification);
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'collegeofinfotech2023@gmail.com'; // Use environment variable for security
            $mail->Password = 'your_password'; // Use environment variable for security
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('collegeofinfotech2023@gmail.com', 'Infotech MCC Forgot Password Account');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Infotech MCC Forgot Password Account';
            $mail->Body = "
            <html>
            <body>
                <p>Greetings!</p>
                <p>To complete your Forgot Password process for the BSIT Grading Information Management System, click <a href='https://collegeofinfotech.com/forgot-password2.php?code=$verification_code&email=$email'>here</a>.</p>
                <p><b>Important Notice:</b> Never share this link with anyone to protect your account from unauthorized access.</p>
                <p>If you did not expect this message, please ignore this email.</p>
            </body>
            </html>";

            $mail->send();
            $_SESSION['status'] = "Registration link sent. Please check your email on Outlook.";
            $_SESSION['status_code'] = "success";
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            $_SESSION['status'] = "Unable to send the registration link at this moment.";
            $_SESSION['status_code'] = "error";
        }
    } else {
        error_log("MySQL execute error: " . implode(', ', $stmt->errorInfo()));
        $_SESSION['status'] = "Database error while updating verification code.";
        $_SESSION['status_code'] = "error";
    }

    // Final redirection after processing
    header("Location: forgot_password.php");
    exit();
} else {
    $_SESSION['status'] = "Invalid request.";
    header("Location: forgot_password.php");
    exit();
}
