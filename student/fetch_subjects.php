<?php
// Include necessary files (connection, grade.php, etc.)
include('connection.php');
include('grade.php');

// Check if the year and semester parameters are set
if (isset($_GET['year']) && isset($_GET['semester'])) {
    // Get the year and semester values from the GET parameters
    $year = $_GET['year'];
    $semester = $_GET['semester'];

    // Call the getsubject function from grade.php to fetch subjects based on year and semester
    $mysubject = $grade->getsubject($year, $semester);

    // Output the fetched subjects as HTML table rows
    foreach ($mysubject as $row) {
        echo '<tr>';
        echo '<td>' . $row['subject'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        // Output other table cells as needed
        echo '</tr>';
    }
} else {
    // If year or semester parameters are not set, return an error message
    echo 'Error: Year and semester parameters are required.';
}
