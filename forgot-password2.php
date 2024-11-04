<?php
session_start(); // Ensure session is started
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "phpmailer/src/Exception.php";
require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";

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
            $_SESSION['status'] = "Invalid: Please enter an email address with the mcclawis.edu.ph";
            $_SESSION['status_code'] = "error";
            $redirectRequired = true;
        } else {
            // Check email usage in the database
            $stmt = $connection->prepare("SELECT used, username FROM ms_account WHERE username = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $_SESSION['status'] = "Email not found. Please visit the BSIT office to get MS 365 Account.";
                $_SESSION['status_code'] = "error";
                $redirectRequired = true;
            } elseif ($result['used'] == 1) {
                $_SESSION['status'] = "This email has already been used.";
                $_SESSION['status_code'] = "error";
                $redirectRequired = true;
            }
        }
    }

    // Redirect if validation failed
    if ($redirectRequired) {
        header("Location: forgot_password.php");
        exit();
    }

    // Update email in userdata, student, and teacher tables
    $username = $result['username'];
    $updateQuery = "UPDATE userdata SET email = :email, display = 0 WHERE username = :username";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        // Other updates
        $tables = ['student', 'teacher'];
        foreach ($tables as $table) {
            $updateTableQuery = "UPDATE $table SET email = :email WHERE " . ($table === 'student' ? 'studid' : 'teachid') . " = :username";
            $stmt = $connection->prepare($updateTableQuery);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
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
                $mail->Username = getenv('collegeofinfotech2023@gmail.com');
                $mail->Password = getenv('ohwp vvlw pfyx xkfo');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom(getenv('collegeofinfotech2023@gmail.com'), 'Infotech MCC Forgot Password Account');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Infotech MCC Forgot Password Account';
                $mail->Body = "
                <html>
                <body>
                    <p>Greetings!</p>
                    <p>To complete your Forgot Password Account with BSIT Grading Information Management System, click <a href='https://collegeofinfotech.com/forgot-password2.php?code=$verification_code&email=$email'>Register</a>.</p>
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
            error_log("MySQL execute error: " . $stmt->errorInfo()[2]);
            $_SESSION['status'] = "Database error. Please try again later.";
            $_SESSION['status_code'] = "error";
        }
    } else {
        error_log("Update query error: " . $stmt->errorInfo()[2]);
        $_SESSION['status'] = "Unable to update records. Please try again.";
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

?>