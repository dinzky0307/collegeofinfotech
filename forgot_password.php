<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "phpmailer/src/Exception.php";
require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $redirect = false;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid Email: Please enter a valid MS 365 email address.";
        $_SESSION['status_code'] = "error";
        $redirect = true;
    } else {
        // Verify domain
        $domain = substr(strrchr($email, "@"), 1);
        if ($domain !== 'mcclawis.edu.ph') {
            $_SESSION['status'] = "Invalid: Please enter an email address with the mcclawis.edu.ph domain.";
            $_SESSION['status_code'] = "error";
            $redirect = true;
        } else {
            // Check email usage in the database
            $stmt = $connection->prepare("SELECT used, username FROM ms_account WHERE username = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $_SESSION['status'] = "Email not found. Please visit the BSIT office to get MS 365 Account.";
                $_SESSION['status_code'] = "error";
                $redirect = true;
            } else {
                $used = $result['used'];
                $username = $result['username'];

                if ($used == 1) {
                    $_SESSION['status'] = "This email has already been used.";
                    $_SESSION['status_code'] = "error";
                    $redirect = true;
                }
            }
        }
    }

    if ($redirect) {
        header("Location: forgot_password.php");
        exit();
    }

    // Update email in userdata, student, and teacher tables
    $updateQuery = "UPDATE userdata SET email = :email, display = 0 WHERE username = :username";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        // Update other tables
        $tables = ['student', 'teacher'];
        foreach ($tables as $table) {
            $updateTableQuery = "UPDATE $table SET email = :email WHERE " . ($table == 'student' ? 'studid' : 'teachid') . " = :username";
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
                header("Location: forgot_password.php");
                exit();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                $_SESSION['status'] = "Unable to send the registration link at this moment.";
                $_SESSION['status_code'] = "error";
                header("Location: forgot_password.php");
                exit();
            }
        } else {
            error_log("MySQL execute error: " . $stmt->errorInfo()[2]);
            $_SESSION['status'] = "Database error. Please try again later.";
            $_SESSION['status_code'] = "error";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        error_log("Update query error: " . $stmt->errorInfo()[2]);
        $_SESSION['status'] = "Unable to update records. Please try again.";
        $_SESSION['status_code'] = "error";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request.";
    $_SESSION['status_code'] = "error";
    header("Location: forgot_password.php");
    exit();
}
?>


<style>
    .btn {
        margin-right: 20px;
        /* Adjust this value to control the spacing */
        /* height: 35px;  */
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/mcc.png">
    <title>InfoTech</title>
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" method="post" class="sign-in-form">
                    <h2 class="title">Forgot Password</h2>
                    <div class="form-group">
                        <?php if (isset($errorMessage)): ?>
                            <p style="color: red;"><?php echo $errorMessage; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="text" placeholder="Microsoft Account 365 Email" name="email" required />
                    </div>
                    <input type="submit" value="Submit" name="submit" class="btn solid" />
                    <button type="button" class="btn cancel" id="cancelButton" style="height: 44px;">Cancel</button>

                </form>
            </div>


            <div class="panels-container">
                <div class="panel left-panel" style="height: 87%">
                    <div class="content">
                        <img src="img/mcc.png" alt="mcc" width="230" height="200"
                            style="padding: 0 4px; margin-bottom: 95px; margin-right: 50px; margin-top: 30px;">
                        <p class="p" style=" text-shadow: 1px 2px 3px black;
  color: #f0f0f0;"></p>
                        <h2 class="h2" style=" text-shadow: 1px 3px 3px black;
  color: #f0f0f0;">College of Computer Studies</h2>
                    </div>
                    <!-- <img src="img/register.svg" class="image" alt=""/> -->
                </div>
            </div>
        </div>
        <script>
            // Add an event listener to the Cancel button
            document.getElementById('cancelButton').addEventListener('click', function ()
            {
                // Redirect the user to a cancellation page or any other desired action
                window.location.href = 'index.php'; // Replace 'cancel-reset.php' with the desired cancellation page
            });
        </script>
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <script src="app.js"></script>
</body>

</html>