<?php
include 'config.php';


if(isset($_POST['submit'])){
    $pass = $_POST['pass'];
    $newPassword = $_POST['new_password'];
    
    // Verify that the new password matches the confirmation
    if ($pass === $newPassword) {
        // Get the user's username from the session or any other method you use for identifying the user
        $user = $_SESSION['username'];
        
        // Generate a new password hash for the user
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        $updateQuery = "UPDATE userdata SET password='$hashed' WHERE username='$user'";
        $result = mysql_query($updateQuery);
        
        if ($result) {
            // Password reset successful
            $_SESSION['message'] = "";
            
            // Update the user's session data with the new password hash
            // $_SESSION['pass'] = $passHash;

            ?>
            <script type="text/javascript">
                alert("Password successfully updated")
                window.location.href = "index.php"
            </script>
            <?php 
            
            // header('location: index.php'); // Redirect to the login page
            exit();
        } else {
            $_SESSION['message'] = "<p style='color: red;'>Password reset failed. Please try again.</p>";
        }
    } else {
        $_SESSION['message'] = "<p style='color: red;'>Passwords do not match. Please try again.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/mcc.png">
    <title>InfoTech</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" method="post" class="sign-in-form">
                    <!-- Add your password reset form content here -->
                    <h2 class="title">Reset Password</h2>
                    <div class="form-group">
                        <?php if(isset($_SESSION['message'])): ?>
                            <p><?php echo $_SESSION['message']; ?></p>
                            <?php unset($_SESSION['message']); ?>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="New Password" name="pass" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Confirm Password" name="new_password" required />
                    </div>
                    <input type="submit" value="Reset Password" name="submit" class="btn solid" />
                </form>
            </div>


            <div class="panels-container" >
      <div class="panel left-panel">
        <div class="content">
        <img src="img/mcc.png" alt="mcc" width="230" height="200" style="padding: 0 4px; margin-bottom: 95px; margin-right: 50px; margin-top: 30px;">
        <p class="p" style=" text-shadow: 1px 2px 3px black;
  color: #f0f0f0;">Madridejos Community College</p>
          <h2 class="h2" style=" text-shadow: 1px 3px 3px black;
  color: #f0f0f0;">College of Computer</h2>
        </div>
        <!-- <img src="img/register.svg" class="image" alt=""/> -->
      </div>
    </div>
  </div>
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <script src="app.js"></script>
</body>
</html>
