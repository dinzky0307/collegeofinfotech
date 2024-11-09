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
                header('location: new_user.php?user=' . urlencode($user));
                exit();
            } else {
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
            header('location:index.php?login=0');
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header('location:index.php?login=0');
    }
}

if (isset($_SESSION['level'])) {
    header('location:' . htmlspecialchars($_SESSION['level'], ENT_QUOTES, 'UTF-8'));
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
              <label class="text-danger">Invalid Username/Password try again</label>&nbsp;
            <?php endif; ?>
          </div>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="ID number" name="user" value="<?php
            if (isset($_SESSION['username'])) {
              echo $_SESSION['username'];
            }
            ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" id="password" name="pass" required />
            <i class="fas fa-eye eye-icon" id="togglePassword" onclick="togglePassword()"></i>
          </div>
          <input type="submit" value="Login" name="submit" class="btn solid" />
          <p style="display: flex;justify-content: center;align-items: center;margin-top: 20px;"><a
              href="forgot_password.php" style="color: #4590ef;">Forgot Password?</a></p>
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel" style="max-height: 87%">
        <div class="content">
          <img src="img/mcc.png" alt="mcc" width="230" height="200"
            style="padding: 0 4px; margin-bottom: 95px; margin-right: 50px; margin-top: 30px;">
          <p class="p" style=" text-shadow: 1px 2px 3px black;
  color: #f0f0f0;"></p>
          <h2 class="h2" style=" text-shadow: 1px 3px 3px black;
  color: #f0f0f0;">College of Computer Studies</h2>
        </div>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  
  <script>
    function togglePassword() {
        const passwordField = document.getElementById("password");
        const toggleIcon = document.getElementById("togglePassword");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
  </script>
</body>
</html>
