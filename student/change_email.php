<?php
// Add your database connection here
include('Config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_email'])) {
    // Retrieve user ID from session or wherever you store it
    $id = $_SESSION['id']; // Assuming the user ID is stored in the session

    // Update the email address in the database
    $newEmail = $_POST['new_email'];

    // Retrieve the current email from the database
    $getEmailQuery = "SELECT email FROM student WHERE studid = '$id'";
    $getEmailResult = mysqli_query($connection, $getEmailQuery);

    if ($getEmailResult && mysqli_num_rows($getEmailResult) > 0) {
        $row = mysqli_fetch_assoc($getEmailResult);
        $currentEmail = $row['email'];

        // Check if the new email is different from the current one
        if ($currentEmail !== $newEmail) {
            $updateEmailQuery = "UPDATE student SET email = '$newEmail' WHERE studid = '$id'";
            $updateResult = mysqli_query($connection, $updateEmailQuery);

            if ($updateResult) {
                // Email address updated successfully
                mysqli_close($connection); // Close the database connection
                echo "<script>alert('Email address updated successfully!'); window.location.href = 'index.php';</script>";
                exit(); // Ensure that no more output is sent
            } else {
                // Handle database update error
                echo "Error updating email address: " . mysqli_error($connection);
                mysqli_close($connection); // Close the database connection
            }
        } else {
            // No changes made to the email address
            mysqli_close($connection); // Close the database connection
            echo "<script>alert('No changes were made to the email address.');</script>";
        }
    } else {
        echo "Error fetching current email: " . mysqli_error($connection);
        mysqli_close($connection); // Close the database connection
        exit();
    }
} else {
    // Handle cases where required POST data is not received
    echo "Invalid request!";
}
?>
