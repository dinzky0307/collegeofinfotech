<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/class_model.php');
include '../DatabaseService.php';

use Database\DatabaseService;

// Initialize the teacher ID
$teacherId = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

// Validate teacher ID
if ($teacherId <= 0) {
    die("<div class='alert alert-danger text-center'>Invalid Teacher ID</div>");
}

// Create an instance of DatabaseService
$dbService = new DatabaseService();

// Fetch the active academic year
$activeAcademicYear = $dbService->fetchRow("SELECT * FROM ay WHERE display = 1");

if ($activeAcademicYear) {
    $academic_year = $activeAcademicYear['academic_year'];
    $semester = $activeAcademicYear['semester'];
    $academicYearActive = true;
} else {
    $academicYearActive = false;
    die("<div class='alert alert-warning text-center'>No active academic year found</div>");
}

// Fetch teacher's full name
$teacherFullName = '';
$teacherQuery = "SELECT fname, mname, lname FROM teacher WHERE id = :teacherId";
$stmt = $connection->prepare($teacherQuery);
$stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
$stmt->execute();
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if ($teacher) {
    $teacherFullName = htmlspecialchars($teacher['lname'] . ', ' . $teacher['fname'] . ' ' . $teacher['mname']);
} else {
    die("<div class='alert alert-danger text-center'>Teacher not found</div>");
}

// Fetch class data
$classData = [];
if ($academicYearActive) {
    $sql = "SELECT c.*, t.fname, t.mname, t.lname 
            FROM class c
            INNER JOIN ay a ON c.SY = a.academic_year AND c.sem = a.semester
            INNER JOIN teacher t ON c.teacher = t.id
            WHERE t.id = :teacherId AND c.SY = :academicYear AND a.semester = :semester";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
    $stmt->bindParam(':academicYear', $academic_year, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
    $stmt->execute();
    $classData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>INSTRUCTOR CLASS INFORMATION (<?= $teacherFullName; ?>)</small>
                </h1>
                <ol class="breadcrumb">
                    <li><i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a></li>
                    <li class="active">Class</li>
                </ol>
            </div>
        </div>

        <!-- Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="classInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Code</th>
                                <th>Subject Description</th>
                                <th>Course</th>
                                <th>Year & Section</th>
                                <th>Semester</th>
                                <th>S.Y.</th>
                                <th>Students</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($classData)): ?>
                                <?php $index = 1; ?>
                                <?php foreach ($classData as $class): ?>
                                    <?php
                                    // Fetch total students for the class
                                    $studentCountQuery = "
                                        SELECT COUNT(*) AS total_students 
                                        FROM class_student 
                                        WHERE class_id = :classId
                                    ";
                                    $stmt = $connection->prepare($studentCountQuery);
                                    $stmt->bindParam(':classId', $class['id'], PDO::PARAM_INT);
                                    $stmt->execute();
                                    $studentCountResult = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalStudents = $studentCountResult['total_students'] ?? 0;

                                    // Define status based on total students
                                    $status = $totalStudents > 0 ? "Active ($totalStudents Students)" : "No Students";
                                    ?>
                                    <tr>
                                        <td><?= $index++; ?></td>
                                        <td><?= htmlspecialchars($class['subject']); ?></td>
                                        <td><?= htmlspecialchars($class['description']); ?></td>
                                        <td><?= htmlspecialchars($class['course']); ?></td>
                                        <td><?= htmlspecialchars($class['year'] . '-' . $class['section']); ?></td>
                                        <td><?= htmlspecialchars($class['sem']); ?></td>
                                        <td><?= htmlspecialchars($class['SY']); ?></td>
                                        <td>
                                            <a href="classstudent.php?classid=<?= $class['id']; ?>&SY=<?= $class['SY']; ?>" title="View Students">View</a>
                                        </td>
                                        <td><?= $status; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No class information found for this teacher.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




