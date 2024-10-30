<?php
require 'vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Increase memory limit and execution time
ini_set('memory_limit', '512M');
set_time_limit(300);

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['import']) && isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['file']['tmp_name'];

    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();

        // Connect to MySQL
        $conn = new mysqli('127.0.0.1', 'u510162695_infotechMCC', 'infotechMCC2023', 'u510162695_infotechMCC');
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $hasErrors = false;

        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $data = [];

            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            if (count($data) >= 4) {
                $checkSql = "SELECT * FROM ms_account WHERE ms_id = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bind_param('s', $data[0]);
                $checkStmt->execute();
                $result = $checkStmt->get_result();

                if ($result->num_rows == 0) {
                    $sql = "INSERT INTO ms_account (ms_id, firstname, lastname, username) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssss', $data[0], $data[1], $data[2], $data[3]);

                    if (!$stmt->execute()) {
                        $hasErrors = true;
                    }

                    $stmt->close();
                }

                $checkStmt->close();
            } else {
                $hasErrors = true;
            }
        }

        $conn->close();

        header("Location: https://collegeofinfotech.com/admin/ms_acc.php?status=" . ($hasErrors ? 'error' : 'success'));
        exit();

    } catch (Exception $e) {
        error_log("Error during import: " . $e->getMessage());
        header("Location: https://collegeofinfotech.com/admin/ms_acc.php?status=error");
        exit();
    }
} else {
    header("Location: https://collegeofinfotech.com/admin/ms_acc.php?status=file_error");
    exit();
}
?>
