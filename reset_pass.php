<?php
// Include the configuration file
include 'database.php';

// Get the email from the URL parameters
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Check if the form is submitted
if (isset($_POST['submit_verify'])) {
    // Sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $new_password = filter_var($_POST['new_password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    // Check if new_password and confirm_password match
    if ($new_password === $confirm_password) {
        // Hash the new password using password_hash
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in userdata table
        $updateQuery = "UPDATE userdata SET password = :password, email = :email, display = 1 WHERE email = :email";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Password Updated!',
                            text: 'Your password has been successfully updated.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = 'https://collegeofinfotech.com/index.php'; // Redirect to login page after success
                        });
                    });
                </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an issue updating your password. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
        }
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Password Mismatch!',
                        text: 'The new password and confirm password do not match.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/mcc.png">
    <title>InfoTech</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(209, 209, 209);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container:before {
            content: "";
            position: absolute;
            height: 1600px;
            width: 1600px;
            top: -10%;
            right: 70%;
            transform: translateY(-50%);
            background-image: linear-gradient(#000000, #000000);
            transition: 1.8s ease-in-out;
            border-radius: 50%;
            box-shadow: 1px 3px 2px 4px rgb(29, 33, 29);
            z-index: 6;
        }

        .container {
            max-width: 400px;
            padding: 20px;
            background-image: linear-gradient(to right bottom, #57595c, #46474b, #35363a, #25262a, #16171b);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .new-user {
            text-align: center;
        }

        .new-user h2 {
            margin-bottom: 20px;
            color: white;
        }

        .new-user form {
            display: flex;
            flex-direction: column;
        }

        .new-user input[type="text"],
        .new-user input[type="password"],
        .new-user input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .new-user input[type="text"]:focus,
        .new-user input[type="password"]:focus,
        .new-user input[type="submit"]:hover {
            border-color: #007bff;
        }

        .new-user input[type="submit"] {
            background-color: #031c24;
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .new-user input[type="submit"]:hover {
            background-color: #4d84e2;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .terms {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: white;
        }

        .terms input {
            margin-right: 10px;
        }

        .terms label {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="new-user">
            <h2>Forgot Password</h2> 

            <form action="" method="post" onsubmit="return checkTerms();">
                <input type="text" name="email" id="email" required value="<?php echo $email; ?>" readonly>

                <div class="password-container">
                    <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
                    <i class="fas fa-eye eye-icon" id="toggleNewPassword" onclick="togglePassword('new_password', 'toggleNewPassword')"></i>
                </div>
                <small id="passwordHelpBlock" class="form-text"></small>
                <div class="password-container">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                    <i class="fas fa-eye eye-icon" id="toggleConfirmPassword" onclick="togglePassword('confirm_password', 'toggleConfirmPassword')"></i>
                </div>

                <div class="terms">
                    <input type="checkbox" id="terms" required>
                    <label for="terms" onclick="showTerms()">I agree to the terms and conditions</label>
                </div>

                <input type="submit" name="submit_verify" value="Register">
            </form>
        </div>
    </div>

    <script>
        function togglePassword(passwordId, toggleId) {
            const passwordField = document.getElementById(passwordId);
            const toggleIcon = document.getElementById(toggleId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function showTerms() {
            Swal.fire({
                title: 'Terms and Conditions',
                html: `
                    <div style="text-align: left; max-height: 300px; overflow-y: auto;">
                        <p>By registering for an account on this platform, you agree to the following terms and conditions:</p>
                        <p><strong>1. Account Information:</strong> <br>
                        You are responsible for providing accurate and truthful information during the registration process. Any false or misleading information may result in the suspension or termination of your account.</p>
                        
                        <p><strong>2. Confidentiality:</strong> <br>
                        You are responsible for maintaining the confidentiality of your login credentials (username and password). Any unauthorized use of your account is your responsibility, and you must notify us immediately if you suspect any unauthorized activity.</p>
                        
                        <p><strong>3. Use of the Platform:</strong> <br>
                        You agree to use the platform for lawful purposes only. You may not use the platform to distribute any content that is unlawful, harmful, or violates the rights of others.</p>
                        
                        <p><strong>4. Termination:</strong> <br>
                        We reserve the right to terminate or suspend your account at our discretion if you violate any of these terms.</p>
                        
                        <p><strong>5. Limitation of Liability:</strong> <br>
                        We are not liable for any damages resulting from your use of the platform or your inability to access the platform.</p>
                        
                        <p><strong>6. Changes to Terms:</strong> <br>
                        We may update these terms and conditions from time to time. You will be notified of any significant changes.</p>
                    </div>
                `,
                confirmButtonText: 'Close',
                showCloseButton: true
            });
        }

        function checkTerms() {
            const termsChecked = document.getElementById('terms').checked;
            if (!termsChecked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Terms not accepted',
                    text: 'You must accept the terms and conditions to proceed.'
                });
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
