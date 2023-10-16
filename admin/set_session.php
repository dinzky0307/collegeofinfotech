<?php

if (isset($_POST['id'])) {
    $studentId = $_POST['id'];

    // Assuming you have a database connection and a students table
    // Fetch the student details from the database based on the ID
    // For example:
    // $student = fetchStudentById($studentId);

    // Set the session ID based on the student's ID or any other identifier
    $_SESSION['id'] = $studentId;

    // Return a response if needed (optional)
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid student ID']);
}
?>

