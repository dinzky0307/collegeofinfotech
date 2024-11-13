<?php
include 'database.php';

if (isset($_POST['submit'])) {
    // Sanitize user inputs to prevent XSS
    $user = htmlspecialchars(trim($_POST['user']), ENT_QUOTES, 'UTF-8');
    $pass = $_POST['pass'];

    try {
        // Use a prepared statement to prevent SQL injection
        $stmt = $connection->prepare("SELECT * FROM userdata WHERE username = :user");
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user record was found and verify the password
        if ($row && password_verify($pass, $row['password'])) {
            if ($row['display'] == 0) {
                // Redirect to new user alert page safely
                header('location: new_user.php?user=' . urlencode($user));
                exit();
            } else {
                // User is not new, proceed with login
                session_start();
                $_SESSION['message'] = "You are now logged in.";
                $_SESSION['level'] = htmlspecialchars($row['level'], ENT_QUOTES, 'UTF-8');
                $_SESSION['id'] = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = htmlspecialchars($row['fname'] . ' ' . $row['lname'], ENT_QUOTES, 'UTF-8');
                header('location:' . $_SESSION['level']);
                exit();
            }
        } else {
            echo "<script>Swal.fire('Error', 'Invalid Username/Password. Please try again.', 'error');</script>";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "<script>Swal.fire('Error', 'An error occurred. Please try again later.', 'error');</script>";
    }
}

// If the user is already logged in, redirect them based on their level
if (isset($_SESSION['level'])) {
    header('location:' . htmlspecialchars($_SESSION['level'], ENT_QUOTES, 'UTF-8'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="img/mcc.png">
  <title>InfoTech</title>
  <link rel="stylesheet" href="css/style1.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="" method="post" class="sign-in-form">
          <h2 class="title">Log In</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="ID number" name="user" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="pass" id="password" required />
            <i class="fas fa-eye" id="togglePassword" style="cursor: pointer; margin-left: -30px;"></i>
          </div>
          <input type="submit" value="Login" name="submit" class="btn solid" />
          <p style="display: flex;justify-content: center;align-items: center;margin-top: 20px;">
            <a href="forgot_password.php" style="color: #4590ef;">Forgot Password?</a>
          </p>
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel" style="max-height: 87%">
        <div class="content">
          <img src="img/mcc.png" alt="mcc" width="230" height="200"
            style="padding: 0 4px; margin-bottom: 95px; margin-right: 50px; margin-top: 30px;">
          <h2 class="h2" style="text-shadow: 1px 3px 3px black; color: #f0f0f0;">College of Computer Studies</h2>
        </div>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <script>
    window.addEventListener('DOMContentLoaded', (event) => {
      // Show password toggle functionality
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('password');

      togglePassword.addEventListener('click', () => {
        // Toggle the type attribute
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        // Toggle the eye icon
        togglePassword.classList.toggle('fa-eye-slash');
      });
    });
  </script>
</body>
</html>
