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
                echo "<script>
                    window.location.href = 'new_user.php?user=' + encodeURIComponent('$user');
                </script>";
                exit();
            } else {
                // User is not new, proceed with login
                session_start();
                $_SESSION['message'] = "You are now logged in.";
                $_SESSION['level'] = htmlspecialchars($row['level'], ENT_QUOTES, 'UTF-8');
                $_SESSION['id'] = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = htmlspecialchars($row['fname'] . ' ' . $row['lname'], ENT_QUOTES, 'UTF-8');

                // Trigger SweetAlert for successful login
                echo "<script>
                    let loginSuccess = true;
                </script>";
            }
        } else {
            // Trigger SweetAlert for invalid login credentials
            echo "<script>
                let loginFailed = true;
            </script>";
        }
    } catch (PDOException $e) {
        // Handle database errors and trigger SweetAlert
        error_log("Database error: " . $e->getMessage());
        echo "<script>
            let dbError = true;
        </script>";
    }
}

// If the user is already logged in, redirect them based on their level
if (isset($_SESSION['level'])) {
    echo "<script>
        window.location.href = '" . htmlspecialchars($_SESSION['level'], ENT_QUOTES, 'UTF-8') . "';
    </script>";
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
    <?php if (isset($_GET['login'])): ?>
      <label class="text-danger">Invalid Username/Password. Try again.</label>&nbsp;
    <?php endif; ?>
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
    // Check for login success, failure, or database error and show SweetAlert notifications
    if (typeof loginSuccess !== 'undefined' && loginSuccess) {
      Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back!',
        showConfirmButton: false,
        timer: 1500
      }).then(() => {
        // Redirect after the SweetAlert notification
        window.location.href = '<?php echo htmlspecialchars($_SESSION['level'], ENT_QUOTES, 'UTF-8'); ?>';
      });
    }

    if (typeof loginFailed !== 'undefined' && loginFailed) {
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: 'Invalid Username or Password. Please try again.',
      });
    }

    if (typeof dbError !== 'undefined' && dbError) {
      Swal.fire({
        icon: 'error',
        title: 'Database Error',
        text: 'An error occurred while connecting to the database. Please try again later.',
      });
    }
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
