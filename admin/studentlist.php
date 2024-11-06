<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/student_model.php');

$studentModel = new Datastudent($connection);
if (isset($_GET['q'])) {
    $studentModel->$_GET['q']();
}

include '../DatabaseService.php';

use Database\DatabaseService;

$dbService = new DatabaseService;

// Fetch the active academic year from the database
$activeAcademicYear = $dbService->fetchRow("SELECT * FROM ay WHERE display = 1");

// Check if the active academic year exists and set the variables accordingly
if ($activeAcademicYear) {
    $academic_year = $activeAcademicYear['academic_year'];
    $semester = $activeAcademicYear['semester'];
    $academicYearActive = true;
} else {
    $academic_year = null;
    $semester = null;
    $academicYearActive = false;
}

$search = isset($_POST['search']) ? $_POST['search'] : null;
$year = isset($_POST['year']) ? $_POST['year'] : null;
$section = isset($_POST['section']) ? $_POST['section'] : null;
$students = [];
if ($academicYearActive) {
    $students = $studentModel->getstudent($search, $year, $section, $academic_year, $semester);
}

// Extract the available years and sections from the retrieved data
$years = array_unique(array_column($students, 'year'));
$sections = array_unique(array_column($students, 'section'));
sort($years);
sort($sections);

// Function to handle the deletion of a student
function deleteStudent($studentId, $connection)
{
    $sql = "DELETE FROM student WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$studentId]);
}

if (isset($_POST['deleteStudent']) && isset($_POST['studentId'])) {
    $studentId = $_POST['studentId'];
    deleteStudent($studentId, $connection);
?>
    <script type="text/javascript">
        alert("Student successfully deleted");
        window.location.href = "studentlist.php";
    </script>
<?php
    exit();
}

$filteredStudents = array_filter($students, function ($student) use ($year, $section) {
    return ($year === null || $year === $student['year']) &&
        ($section === null || $section === $student['section']);
});
?>
<style>
    thead input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>STUDENT'S LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">Student's List</li>
                </ol>
            </div>
        </div>

        <!-- Student List Table -->
        <div class="row print-container">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="studentInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">ID number</th>
                                <th class="text-center">Fullname</th>
                                <th class="text-center">Year and Section</th>
                                <th class="text-center">Semester</th>
                                <th class="text-center">Subjects</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $number = 1;
                            foreach ($students as $student) {
                                // Fetch total subjects for the student
                                $stmt = $connection->prepare("SELECT COUNT(*) AS enrolled_subjects FROM studentsubject WHERE semester = ? AND year = ? AND studid = ? AND section = ? AND SY = ?");
                                $stmt->execute([$student['semester'], $student['year'], $student['studid'], $student['section'], $student['ay']]);
                                $d = $stmt->fetch(PDO::FETCH_ASSOC);
                                $totalsubs = $d['enrolled_subjects'];
                            ?>
                                <tr>
                                    <td><?php echo $number++; ?></td>
                                    <td class="text-center"><?php echo $student['studid']; ?></td>
                                    <td><?php echo $student['lname'] . ", " . $student['fname'] . " " . $student['mname']; ?></td>
                                    <td class="text-center"><?php echo $student['year'] . " - " . $student['section']; ?></td>
                                    <td class="text-center"><?php echo $student['semester']; ?></td>
                                    <td class="text-center">
                                        <a href="viewsubjects.php?type=student&id=<?php echo $student['studid']; ?>">Enrolled Subjects | <?php echo $totalsubs; ?></a>
                                    </td>
                                    <td class="text-center"><?php echo $student['email']; ?></td>
                                    <td class="text-center">
                                        <div style="display: inline-block;">
                                            <a href="edit.php?type=student&id=<?php echo $student['id']; ?>" title="Update"><i class="fa fa-edit fa-lg text-primary"></i></a>
                                        </div>
                                        &nbsp; || &nbsp;
                                        <form class="delete-form" method="post" style="display: inline-block;">
                                            <input type="hidden" name="studentId" value="<?php echo $student['id']; ?>">
                                            <button type="submit" name="deleteStudent" class="delete-button" title="Remove" onclick="return confirm('Are you sure you want to delete this student?');">
                                                <i class="fa fa-trash-o fa-lg text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#studentInformation').DataTable();
    });
</script>

<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>
