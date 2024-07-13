<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the configuration file
include 'config.php';

// Get the username from the URL parameter
$user = isset($_GET['user']) ? $_GET['user'] : '';

// Check if the email submission form is submitted
if (isset($_POST['submitEmail'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Create a connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind statements to avoid SQL injection
    $stmt = $conn->prepare("UPDATE userdata SET email = ?, display = 1 WHERE username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE student SET email = ? WHERE studid = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE teacher SET email = ? WHERE teachid = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();

    // Fetch user data again
    $stmt = $conn->prepare("SELECT * FROM userdata WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Proceed with login
    $_SESSION['message'] = "You are now logged in.";
    $_SESSION['level'] = $row['level'];
    $_SESSION['id'] = $row['username'];
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['name'] = $row['fname'] . ' ' . $row['lname'];
    header('Location: ' . $row['level']);
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
            right: 60%;
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
</head>
<body>
    <div class="container">
        <div class="new-user">
            <center>
                <h5 style="color: white; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">
                    It seems that you're a new user,
                    <br>please provide your valid email address.
                </h5>
            </center>
            <form action="send_verification.php" method="post">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user); ?>">
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                <input type="submit" name="submitEmail" value="Send Verification Link">
            </form>
        </div>
    </div>
</body>
</html>
