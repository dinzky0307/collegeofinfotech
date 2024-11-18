<?php
// Include the configuration file
include 'config.php';

// Start the session to manage cookies and session data
session_start();

// Get the username from the URL parameter
$user = isset($_GET['user']) ? $_GET['user'] : '';

// Check if the cancel button was clicked or if the user is navigating back
if (isset($_GET['cancel'])) {
    // Destroy cookies
    setcookie('id', '', time() - 3600, '/');
    setcookie('user_id', '', time() - 3600, '/');
    setcookie('name', '', time() - 3600, '/');
    setcookie('level', '', time() - 3600, '/');
    
    // Destroy session
    session_unset();
    session_destroy();

    // Redirect to the login page or home page
    header('Location: index.php');
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
        .new-user input[type="submit"],
        .new-user input[type="button"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .new-user input[type="email"]:focus,
        .new-user input[type="submit"]:hover,
        .new-user input[type="button"]:hover {
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

        .new-user input[type="button"] {
            background-color: #031c24; /* Red color for cancel button */
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .new-user input[type="button"]:hover {
            background-color: #e53935;
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
                <!-- Cancel Button -->
                <input type="button" id="cancelButton" value="Cancel">
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

    <script>
        // When cancel button is clicked, submit the form with 'cancel' action
        document.getElementById('cancelButton').addEventListener('click', function() {
            var cancelButtonForm = document.createElement('form');
            cancelButtonForm.method = 'POST';
            var cancelInput = document.createElement('input');
            cancelInput.type = 'hidden';
            cancelInput.name = 'cancel';
            cancelButtonForm.appendChild(cancelInput);
            document.body.appendChild(cancelButtonForm);
            cancelButtonForm.submit();
        });
    </script>
</body>

</html>

