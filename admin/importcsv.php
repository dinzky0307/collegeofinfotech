<?php
// importcsv.php

ob_start(); // Start output buffering

include('include/header.php');
include('include/sidebar.php');
include('../database.php'); // Include the database connection code

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile'];

    // Check if the file is a CSV file
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileType !== 'csv') {
        echo "<script>alert('Please upload a valid CSV file.');</script>";
    } else {
        // Read the CSV file
        $csvFile = fopen($file['tmp_name'], 'r');

        // Skip the header row
        fgetcsv($csvFile);

        // Flag to check if any ID already exists
        $idExists = false;

        // Loop through the CSV data
        while (($data = fgetcsv($csvFile)) !== false) {
            // Extract data from each row
            $studid = $data[0];
            $lname = $data[1];
            $fname = $data[2];
            $mname = $data[3];
            $email = $data[4];
            $year = $data[5];
            $section = $data[6];
            $semester = $data[7];
            $ay = $data[8];

            // Check if the student ID already exists
            $existStatement = $connection->prepare("SELECT studid FROM student WHERE studid = ?");
            $existStatement->execute([$studid]);
            $exists = $existStatement->fetch();

            if ($exists) {
                $idExists = true;
                $hashed_password = password_hash($studid, PASSWORD_DEFAULT);
                $display = "0";
                $level = "student";

                // Display alert for each existing ID
                echo "<script>alert('Student with ID $studid already exists.');</script>";
            } else {
                // Insert data into the 'student' table
                $sqlStudent = "INSERT INTO student (studid, lname, fname, mname, email, year, section, semester, ay) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $connection->prepare($sqlStudent)->execute([$studid, $lname, $fname, $mname, $email, $year, $section, $semester, $ay]);

                // Insert data into the 'userdata' table
                $hashed_password = password_hash($studid, PASSWORD_DEFAULT);
                $display = "0";
                $level = "student";
                $sqlUser = "INSERT INTO userdata (username, display, password, fname, lname, level) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                $connection->prepare($sqlUser)->execute([$studid, $display, $hashed_password, $fname, $lname, $level]);
            }
        }

        fclose($csvFile);

        // Display alert if any ID already exists
        if ($idExists) {
            echo "<script>alert('CSV file imported successfully, but some student IDs already exist.');</script>";
        } else {
            echo "<script>alert('CSV file imported successfully for all students.');</script>";
        }
    }
} else {
    // Display alert if CSV file is not uploaded
    echo "<script>alert('Please upload a CSV file.');</script>";
}

include('include/footer.php');

ob_end_flush(); // Flush the output buffer and send it to the browser
?>

<script>
    // Use JavaScript to perform the redirect after the alert is displayed
    setTimeout(function () {
        window.location.href = 'studentlist.php';
    }, 1000); // Redirect after 1 second
</script>
