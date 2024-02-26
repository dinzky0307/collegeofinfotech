<?php
// reset_password.php
include('../database.php');

if (isset($_POST['studentId'])) {
    $studentId = $_POST['studentId'];
    // Generate a new password (e.g., using random characters)
    $newPassword = generateRandomPassword();
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE userdata SET password = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$hashedPassword, $studentId]);

    // Retrieve student's email from the database
    $getEmailQuery = "SELECT email FROM student WHERE id = ?";
    $stmt = $connection->prepare($getEmailQuery);
    $stmt->execute([$studentId]);
    $emailRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $studentEmail = $emailRow['email'];

    // Send an email to the student with the new password
    $subject = 'Password Reset';
    $message = 'Your password has been reset. Your new password is: ' . $newPassword;
    $headers = 'From: collegeofinfotech2023@gmail.com'; // Change this to a valid sender email address
    mail($studentEmail, $subject, $message, $headers);

    // Return a response
    echo 'Password reset successfully! An email has been sent to the student with the new password.';
}

function generateRandomPassword()
{
    // Generate a random password (e.g., using random characters)
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8 / strlen($x)))), 1, 8);
}
