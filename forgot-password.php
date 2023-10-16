<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $user = $_POST['user'];
    
    // Check if the provided ID number exists in the userdata table
    $query = "SELECT * FROM userdata WHERE username='$user'";
    $result = mysql_query($query);
    
    if ($result && mysql_num_rows($result) == 1) {
        header('location: reset-pass.php');
        $_SESSION['username'] = $user;
    } else {
        // User does not have an account, display an error message
        $errorMessage = "No account found with the provided ID number.";
    }
}
?>
<style>
    .btn {
    margin-right: 20px; /* Adjust this value to control the spacing */
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" method="post" class="sign-in-form">
                    <h2 class="title">Forgot Password</h2>
                    <div class="form-group">
                        <?php if(isset($errorMessage)): ?>
                            <p style="color: red;"><?php echo $errorMessage; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="ID Number" name="user"  required />
                    </div>
                    <input type="submit" value="Submit" name="submit" class="btn solid" />
                    <button type="button" class="btn cancel" id="cancelButton" style="height: 44px;">Cancel</button>

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

