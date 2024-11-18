<?php
include 'database.php';
$alertScript = ""; // Initialize alert script

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
            $userId = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); // Get user ID
            $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); // Get username
            $fullName = htmlspecialchars($row['fname'] . ' ' . $row['lname'], ENT_QUOTES, 'UTF-8'); // Full name

            if ($row['display'] == 0) {
                // SweetAlert for new user login
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
                // Determine redirection based on the level
                $level = htmlspecialchars($row['level'], ENT_QUOTES, 'UTF-8');
                $redirectUrl = '';

                if ($level === 'admin') {
                    $redirectUrl = "../admin/index.php?user_id=$userId";
                } elseif ($level === 'teacher') {
                    $redirectUrl = "../teacher/index.php?user_id=$userId";
                } elseif ($level === 'student') {
                    $redirectUrl = "../students/index.php?user_id=$userId";
                } else {
                    $redirectUrl = 'default/index.php'; // Default redirection if level is unrecognized
                }

                // SweetAlert for successful login
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
            // SweetAlert for invalid login credentials
            $alertScript = "
                Swal.fire({
                    title: 'Error',
                    text: 'Invalid username or password. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            ";
        }
    } catch (PDOException $e) {
        // Handle database errors and trigger SweetAlert
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
