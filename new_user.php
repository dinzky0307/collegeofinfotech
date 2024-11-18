<?php
// Include the configuration file
include 'config.php';
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['level'])) {
    // If not logged in, redirect to login page
    header('Location: index.php');
    exit();
}

// Get the username from the URL parameter
$user = isset($_GET['user']) ? $_GET['user'] : '';

// Validate the username and fetch the `display` status from the database
$result = mysql_query("SELECT * FROM userdata WHERE username = '$user'");
$row = mysql_fetch_assoc($result);

if (!$row || $row['display'] != 0) {
    // If user does not exist or `display` is not 0, redirect based on their level
    $redirectUrl = $_SESSION['level'] === 'admin' ? 'admin/index.php' :
        ($_SESSION['level'] === 'teacher' ? 'teacher/index.php' : 'students/index.php');
    header('Location: ' . $redirectUrl);
    exit();
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

        .new-user input[type="email"],
        .new-user input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .new-user input[type="email"]:focus,
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
    </style>
      <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="new-user">
            <h2>Microsoft 365 Account Verification</h2> 
            <center>
                <h5 style="color: white; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">
                    It seems that you're a new user,
                    <br>Enter your MS 365 Username to receive a registration link.
                </h5>
            </center>
            <form action="send_verification.php" method="post">
                <input type="hidden" name="username" value="<?php echo $user; ?>">
                <input type="email" name="email" id="email" placeholder="Please enter your MS 365 Email." required>
                 <div id="validationServerUsernameFeedback" class="invalid-feedback"></div>
                <input type="submit" name="registration_link" value="Send Verification Link">
                
            </form>
        </div>
    </div>
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
