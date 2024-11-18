<?php
include 'database.php';
session_start(); // Start session

$alertScript = ""; // Initialize alert script

// Redirect already logged-in users
if (isset($_SESSION['level']) || isset($_COOKIE['level'])) {
    $redirectLevel = isset($_SESSION['level']) ? $_SESSION['level'] : $_COOKIE['level'];
    header('location:' . htmlspecialchars($redirectLevel, ENT_QUOTES, 'UTF-8') . '/index.php');
    exit();
}

// Initialize login attempt tracking
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}

if (isset($_POST['submit'])) {
    // Check if the user is locked out
    if ($_SESSION['login_attempts'] >= 3 && time() - $_SESSION['lockout_time'] < 60) {
        $alertScript = "
            Swal.fire({
                title: 'Too Many Attempts',
                text: 'You have been locked out due to many failed login attempts. Please try again after 1 minute.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        ";
    } else {
        // Sanitize user inputs
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
                // Reset login attempts on successful login
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lockout_time'] = null;

                $userId = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
                $fullName = htmlspecialchars($row['fname'] . ' ' . $row['lname'], ENT_QUOTES, 'UTF-8');

                // Store session and cookie data
                $_SESSION['id'] = $username;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $fullName;
                $_SESSION['level'] = htmlspecialchars($row['level'], ENT_QUOTES, 'UTF-8');

                $cookieExpire = time() + (1 * 60 * 60); // 1 hours expiration
                setcookie('id', $username, $cookieExpire, '/', '', false, true);
                setcookie('user_id', $row['id'], $cookieExpire, '/', '', false, true);
                setcookie('name', $fullName, $cookieExpire, '/', '', false, true);
                setcookie('level', $_SESSION['level'], $cookieExpire, '/', '', false, true);

                // Check if the user is new (display == 0)
                if ($row['display'] == 0) {
                    // Redirect new users to complete their profile
                    $alertScript = "
                        Swal.fire({
                            title: 'Welcome!',
                            text: 'Redirecting to complete your profile.',
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: true
                        }).then(() => {
                            window.location.href = 'new_user.php?user=" . urlencode($user) . "';
                        });
                    ";
                } else {
                    // Redirect users based on their level
                    $redirectUrl = $_SESSION['level'] === 'admin' ? 'admin/index.php' :
                        ($_SESSION['level'] === 'teacher' ? 'teacher/index.php' : 'students/index.php');

                    $alertScript = "
                        Swal.fire({
                            title: 'Login Successful',
                            text: 'Welcome back, $fullName!',
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: true
                        }).then(() => {
                            window.location.href = '$redirectUrl';
                        });
                    ";
                }
            } else {
                // Increment login attempts on failure
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= 3) {
                    $_SESSION['lockout_time'] = time();
                }

                $alertScript = "
                    Swal.fire({
                        title: 'Login Failed',
                        text: 'Invalid username or password. Attempt {$_SESSION['login_attempts']} of 3.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                ";
            }
        } catch (PDOException $e) {
            // Handle database errors
            error_log("Database error: " . $e->getMessage());
            $alertScript = "
                Swal.fire({
                    title: 'Error',
                    text: 'Something went wrong. Please try again later.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            ";
        }
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="css/style1.css" />
</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
      <form action="" method="post" class="sign-in-form">
  <h2 class="title">Log In</h2>
  <div class="form-group" style="color: red;">
   
  </div>
  <div class="input-field">
    <i class="fas fa-user"></i>
    <input type="text" placeholder="ID number" name="user" 
           value="<?php if (isset($_SESSION['username'])) { echo $_SESSION['username']; } ?>" required />
  </div>
  <div class="input-field">
  <input type="password" placeholder="Password" name="pass" id="password" required />
  <i class="fas fa-eye" id="togglePassword"></i>
</div>
  <input type="submit" value="Login" name="submit" class="btn solid" />
  <p style="display: flex; justify-content: center; align-items: center; margin-top: 20px;">
    <a href="forgot_password.php" style="color: #4590ef;">Forgot Password?</a>
  </p>
</form>

<script>
  // Toggle password visibility
  const togglePassword = document.getElementById('togglePassword');
  const passwordField = document.getElementById('password');

  togglePassword.addEventListener('click', () => {
    // Toggle the type attribute
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    
    // Toggle the eye icon
    togglePassword.classList.toggle('fa-eye-slash');
  });
</script>

      </div>
    </div>


    <div class="panels-container">
      <div class="panel left-panel" style="max-height: 87%">
        <div class="content">
          <img src="img/mcc.png" alt="mcc" width="190" height="180"
            style="padding: 0 4px; margin-bottom: 95px; margin-right: auto; margin-top: auto;">
          <p class="p" style=" text-shadow: 1px 2px 3px black;
  color: #f0f0f0;"></p>
          <h2 class="h2" style=" text-shadow: 1px 3px 3px black;
  color: #f0f0f0; margin-left: 50px;  ">College of Computer Studies</h2>
        </div>
        <!-- <img src="img/register.svg" class="image" alt=""/> -->
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <!--   <script src="app.js"></script> -->
  <script>
    window.addEventListener('DOMContentLoaded', (event) =>
    {
      function focusOnUsername()
      {
        const usernameInput = document.querySelector('input[name="user"]');
        if (usernameInput)
        {
          usernameInput.focus(); // Focus on the username input field
        }
      }

      const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
      if (isMobile)
      {
        focusOnUsername(); // Call the focus function for mobile devices
      }
    });
  </script>
  <script>
    <?php if (!empty($alertScript)) echo $alertScript; ?>
  </script>
</body>
<style>
  .input-field {
    position: relative;
    display: flex;
    align-items: center;
  }

  .input-field input[type="password"],
  .input-field input[type="text"] {
    width: 100%;
    padding: 10px;
    padding-right: 40px; /* Space for the eye icon */
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
    transition: border-color 0.2s;
  }

  .input-field input:focus {
    border-color: #1877f2;
  }

  .input-field i {
    position: absolute;
    right: 10px;
    cursor: pointer;
    color: #666;
    font-size: 18px;
  }
</style>
</html>
