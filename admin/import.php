<?php
require 'vendor/autoload.php';  // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Increase memory limit and allow a longer execution time
ini_set('memory_limit', '512M');  // Adjust the memory limit as needed
set_time_limit(300);  // Allow the script to run for 300 seconds (5 minutes)

if (isset($_POST['import'])) {
    $file = $_FILES['file']['tmp_name'];

    // Load the Excel file
    $spreadsheet = IOFactory::load($file);

    // Get the first sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Connect to MySQL
    $conn = new mysqli('localhost', 'root', '', 'infotech');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Flag to indicate if there were any issues
    $hasErrors = false;

    // Loop through each row in the Excel sheet
    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }

        // Check if the array has the correct number of elements
        if (count($data) >= 4) {  // Ensure that $data has at least 4 elements

            // Check if the record already exists in the database
            $checkSql = "SELECT * FROM ms_account WHERE ms_id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param('s', $data[0]);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // Record exists, skip it
                $hasErrors = true;
            } else {
                // Prepare your SQL query. Adjust the table name and columns as needed.
                $sql = "INSERT INTO ms_account (ms_id, firstname, lastname, username) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // Adjust the number and types of parameters according to your table schema
                $stmt->bind_param('ssss', $data[0], $data[1], $data[2], $data[3]);

                if (!$stmt->execute()) {
                    $hasErrors = true;
                }

                $stmt->close();  // Close the statement after use
            }

            $checkStmt->close();  // Close the statement after use
        } else {
            $hasErrors = true;
        }
    }

    $conn->close();

    // Redirect back to employee.php after import
    if ($hasErrors) {
        header("Location: ms_acc.php?status=error");
    } else {
        header("Location: ms_acc.php?status=success");
    }
    exit(); // Make sure to exit after the header redirect
}
?>
