<?php
// Include required files
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('../DatabaseService.php'); // DatabaseService for secure database operations

use Database\DatabaseService;

// Initialize database service
$dbService = new DatabaseService();

// Validate and fetch teacher_id from the URL
$teacherId = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

if ($teacherId <= 0) {
    die("<div class='alert alert-danger text-center'>Invalid Teacher ID</div>");
}

// Fetch teacher's subjects securely based on their ID
try {
    $firstSemSubjects = $dbService->fetchAll("SELECT * FROM subject WHERE teacher_id = ? AND semester = 'First Semester'", [$teacherId]);
    $secondSemSubjects = $dbService->fetchAll("SELECT * FROM subject WHERE teacher_id = ? AND semester = 'Second Semester'", [$teacherId]);
    $summerSubjects = $dbService->fetchAll("SELECT * FROM subject WHERE teacher_id = ? AND semester = 'Summer'", [$teacherId]);
} catch (Exception $e) {
    die("<div class='alert alert-danger text-center'>Error fetching subjects: " . htmlspecialchars($e->getMessage()) . "</div>");
}
?>

<style>
    /* Custom styling for the delete button */
    .delete-button {
        border: none;
        background: none;
        padding: 0;
        color: #f00; /* Change the color if needed */
        cursor: pointer;
        outline: none; /* Remove focus outline */
    }
</style>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>INSTRUCTOR SUBJECT'S LIST</small>
                </h1>
            </div>
        </div>

        <!-- Tabs for Semesters -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#firstSem" role="tab" data-toggle="tab">First Semester</a></li>
            <li><a href="#secondSem" role="tab" data-toggle="tab">Second Semester</a></li>
            <li><a href="#summer" role="tab" data-toggle="tab">Summer</a></li>
        </ul>

        <div class="tab-content">
            <!-- First Semester -->
            <div class="tab-pane active" id="firstSem">
                <?php renderSubjectTable($firstSemSubjects, "First Semester"); ?>
            </div>

            <!-- Second Semester -->
            <div class="tab-pane" id="secondSem">
                <?php renderSubjectTable($secondSemSubjects, "Second Semester"); ?>
            </div>

            <!-- Summer -->
            <div class="tab-pane" id="summer">
                <?php renderSubjectTable($summerSubjects, "Summer"); ?>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Function to render a subjects table
 *
 * @param array $subjects - List of subjects for a given semester
 * @param string $semester - Semester name
 */
function renderSubjectTable($subjects, $semester)
{
    echo '<br><div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Section</th>
                        <th>Students</th>
                    </tr>
                </thead>
                <tbody>';

    if (empty($subjects)) {
        echo '<tr><td colspan="6" class="text-center text-danger"><strong>No subjects available for ' . htmlspecialchars($semester) . '.</strong></td></tr>';
    } else {
        $count = 1;
        foreach ($subjects as $subject) {
            echo '<tr>
                    <td>' . $count++ . '</td>
                    <td>' . htmlspecialchars($subject['subject']) . ' - ' . htmlspecialchars($subject['description']) . '</td>
                    <td>' . htmlspecialchars($subject['course']) . '</td>
                    <td>' . htmlspecialchars($subject['year']) . '</td>
                    <td>' . htmlspecialchars($subject['section']) . '</td>
                    <td>
                        <a href="student.php?classid=' . htmlspecialchars($subject['id']) . '&y=' . htmlspecialchars($subject['year']) . 
                        '&sem=' . htmlspecialchars($subject['semester']) . '&sec=' . htmlspecialchars($subject['section']) . 
                        '&ay=' . htmlspecialchars($subject['academic_year']) . '&code=' . htmlspecialchars($subject['subject']) . 
                        '" class="btn btn-primary btn-sm">View Students</a>
                    </td>
                  </tr>';
        }
    }

    echo '</tbody></table></div>';
}
?>

<!-- /#page-wrapper -->
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>
