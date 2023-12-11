<?php
// File: generate_template.php

// Set headers for file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_data_template.csv"');

// Create a file pointer
$output = fopen('php://output', 'w');

// Write the CSV header
$header = ["Student ID", "Last Name", "First Name", "Middle Name", "Email", "Year", "Section", "Semester", "Academic Year"];
fputcsv($output, $header);

// Close the file pointer
fclose($output);
exit();
?>
