<?php
session_start();

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
            <form action="forgot-password2.php" method="post" class="sign-in-form">
    <h2 class="title">Forgot Password</h2>
    <div class="form-group">
        <?php if (isset($_SESSION['status'])): ?>
            <p style="color: red;"><?php echo $_SESSION['status']; unset($_SESSION['status']); ?></p>
        <?php endif; ?>
    </div>
    <div class="input-field">
        <i class="fas fa-envelope"></i>
        <input type="text" placeholder="Microsoft 365 Account Email" name="email" required />
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
        
     <!-- Include SweetAlert library -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         <!-- Display SweetAlert messages based on the session status -->
    <?php if (isset($_SESSION['status']) && isset($_SESSION['status_code'])): ?>
     <script>
         Swal.fire({
             icon: '<?php echo $_SESSION['status_code']; ?>', // "success" or "error"
             title: '<?php echo $_SESSION['status']; ?>'
           }).then((result) => {
             // If the status is "success", redirect to index.php
             if ('<?php echo $_SESSION['status_code']; ?>' === 'success') {
                window.location.href = "https://collegeofinfotech.com/index.php";
             }
             // If the status is "error", stay on the current page
            else {
                 window.location.href = `new_user.php?user=${user}`;
            }
        });
    </script>
    <?php
        // Clear the session message after displaying it
        unset($_SESSION['status']);
        unset($_SESSION['status_code']);
    ?>
<?php endif; ?>

</body>

</html>