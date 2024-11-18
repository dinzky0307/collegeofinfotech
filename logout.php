<?php
include('config.php');
session_start();

// Log the logout action
if (isset($_SESSION['id'])) {
    $act = $_SESSION['id'] . ' logged out.';
    $date = date('m-d-Y h:i:s A');
    mysql_query("INSERT INTO log VALUES (NULL, '$date', '$act')");
}

// Destroy session data
session_unset();
session_destroy();

// Clear cookies
setcookie('id', '', time() - 3600, '/');
setcookie('user_id', '', time() - 3600, '/');
setcookie('name', '', time() - 3600, '/');
setcookie('level', '', time() - 3600, '/');

// Use SweetAlert for logout confirmation
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
    Swal.fire({
        title: 'Logged Out',
        text: 'You have been successfully logged out.',
        icon: 'success',
        confirmButtonText: 'OK',
         timer: 1000,
      
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php';
        }
    });
</script>
</body>
</html>
";
exit();
?>
