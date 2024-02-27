<?php
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once("phpmailer/src/Exception.php");
require_once("phpmailer/src/PHPMailer.php");
require_once("phpmailer/src/SMTP.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $user = $_POST['email'];

    // Check if the provided ID number exists in the userdata table
    $query = "SELECT * FROM userdata WHERE username = '$name' AND email = '$user' ";
    $result = mysql_query($query);

    if ($result && mysql_num_rows($result) == 1) {
        // User exists, proceed with sending reset password email
        $data = mysql_fetch_assoc($result);
        $verification = uniqid(rand(2, 5));
        $id = $data['id'];
    } else {
        // User does not have an account, display an error message
        $errorMessage = "No account found with the provided Username and Email.";
    }
    // $email = $_POST['email'];

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0; // Enable verbose debug output
    $mail->isSMTP(); // Send using SMTP
    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'enelyntribunalo@gmail.com'; // SMTP username
    $mail->Password = 'tcuuujxrlvbxvdxn'; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port = 587; // TCP port to connect to

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->setFrom('enelyntribunalo@gmail.com', 'MCC Info Tech');
    $mail->addAddress($email);


    $session = $_SESSION['reset'] = $verification;

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Reset Password';
    $mail->Body    = "Click the link to reset password : <a href='https://infotechmcc.com/reset_pass.php?reset=$id&id=$session'>Click here</a>";


    $mail->send();

?>
    <script>
        alert("Please check your email account for confirmation")
        window.location.href = "forgot_password.php"
    </script>
<?php

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
                        <?php if (isset($errorMessage)) : ?>
                            <p style="color: red;"><?php echo $errorMessage; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="ID number" name="name" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="text" placeholder="Email" name="email" required />
                    </div>
                    <input type="submit" value="Submit" name="submit" class="btn solid" />
                    <button type="button" class="btn cancel" id="cancelButton" style="height: 44px;">Cancel</button>

                </form>
            </div>


            <div class="panels-container">
                <div class="panel left-panel" style="height: 87%">
                    <div class="content">
                        <img src="img/mcc.png" alt="mcc" width="230" height="200" style="padding: 0 4px; margin-bottom: 95px; margin-right: 50px; margin-top: 30px;">
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
            document.getElementById('cancelButton').addEventListener('click', function() {
                // Redirect the user to a cancellation page or any other desired action
                window.location.href = 'index.php'; // Replace 'cancel-reset.php' with the desired cancellation page
            });
        </script>
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <script src="app.js"></script>
</body>

</html>