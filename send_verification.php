<?php
ini_set('session.cookie_httponly', 1);
ob_start();
session_start();
include('database.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['registration_link'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid Email: Please enter a valid MS 365 email address.";
        $_SESSION['status_code'] = "error";
        header("Location: new_user.php?user=$username");
        exit();
    }
    // Verify domain
    $domain = substr(strrchr($email, "@"), 1);
    if ($domain !== 'mcclawis.edu.ph') {
        $_SESSION['status'] = "Invalid: Please enter an email address with the mcclawis.edu.ph";
        $_SESSION['status_code'] = "error";
        header("Location: new_user.php?user=$username");
        exit();
    }

    // Check email usage in the database
    $stmt = $connection->prepare("SELECT used FROM ms_account WHERE username = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $used = $stmt->fetchColumn();

    if ($used === false) {
        $_SESSION['status'] = "Email not found. Please visit the BSIT office to get MS 365 Account.";
        $_SESSION['status_code'] = "error";
        header("Location: new_user.php?user=$username");
        exit();
    }

    if ($used == 1) {
        $_SESSION['status'] = "This email has already been used.";
        $_SESSION['status_code'] = "error";
        header("Location: new_user.php?user=$username");
        exit();
    }

    // Prepare and execute the update queries with prepared statements
    $updateQuery = "UPDATE userdata SET email = :email, display = 0 WHERE username = :username";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        // Update email in student table
        $updateStudentQuery = "UPDATE student SET email = :email WHERE studid = :username";
        $stmt = $connection->prepare($updateStudentQuery);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Update email in teacher table
        $updateTeacherQuery = "UPDATE teacher SET email = :email WHERE teachid = :username";
        $stmt = $connection->prepare($updateTeacherQuery);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Generate a verification code
        $verification_code = md5(rand());

        // Update the verification code in the database
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
                $mail->Password = 'ohwp vvlw pfyx xkfo'; // Use environment variable for security
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('collegeofinfotech2023@gmail.com', 'Infotech MCC Verification');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Infotech MCC Verification Account';
                $mail->Body = "
                <html>
                <body>
                    <p>Greetings!</p>
                    <p>To complete your account registration with BSIT Grading Information Management System, click <a href='https://collegeofinfotech.com/verify_email.php?code=$verification_code&email=$email&username=$username'>Register</a>.</p>
                    <p><b>Importance Notice: </b> Never share this link with anyone 
                    in order to protect your account from unathorized access.</p>
                    
                    <p>If you did not expect this message, please ignore this email.</p>
                </body>
                </html>
                ";

                $mail->send();
                $_SESSION['status'] = "Registration link sent. Please check your email on Outlook.";
                $_SESSION['status_code'] = "success";
                header("Location: new_user.php?user=$username");
                exit();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                $_SESSION['status'] = "Unable to send the registration link at this moment.";
                $_SESSION['status_code'] = "error";
                header("Location: new_user.php?user=$username");
                exit();
            }
        } else {
            error_log("MySQL execute error: " . $stmt->errorInfo()[2]);
            $_SESSION['status'] = "Database error. Please try again later.";
            $_SESSION['status_code'] = "error";
            header("Location: new_user.php?user=$username");
            exit();
        }
    } else {
        error_log("Update query error: " . $stmt->errorInfo()[2]);
        $_SESSION['status'] = "Unable to update records. Please try again.";
        $_SESSION['status_code'] = "error";
        header("Location: new_user.php?user=$username");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request.";
    $_SESSION['status_code'] = "error";
    header("Location: new_user.php?user=$username");
    exit();
}

ob_end_flush();
?>
