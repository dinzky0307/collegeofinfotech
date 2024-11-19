<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/class_model.php');
include '../DatabaseService.php';

use Database\DatabaseService;

// Initialize the teacher name
$teacherName = isset($_GET['teachername']) ? $_GET['teachername'] : '';

// Validate teacher name
if (empty($teacherName)) {
    die("<div class='alert alert-danger text-center'>Invalid Teacher Name</div>");
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

// Fetch class data based on teacher name and active academic year
$classData = [];
if ($academicYearActive) {
    $sql = "SELECT c.*, t.name AS teacher_name 
            FROM class c
            INNER JOIN ay a ON c.SY = a.academic_year AND c.sem = a.semester
            INNER JOIN teachers t ON c.teacher = t.id
            WHERE t.name = :teacherName AND c.SY = :academicYear AND a.semester = :semester";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':teacherName', $teacherName, PDO::PARAM_STR);
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
                    <small>INSTRUCTOR CLASS INFORMATION</small>
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
                                <th>Instructor</th>
                                <th>Students</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($classData)): ?>
                                <?php $index = 1; ?>
                                <?php foreach ($classData as $class): ?>
                                    <tr>
                                        <td><?= $index++; ?></td>
                                        <td><?= htmlspecialchars($class['subject']); ?></td>
                                        <td><?= htmlspecialchars($class['description']); ?></td>
                                        <td><?= htmlspecialchars($class['course']); ?></td>
                                        <td><?= htmlspecialchars($class['year'] . '-' . $class['section']); ?></td>
                                        <td><?= htmlspecialchars($class['sem']); ?></td>
                                        <td><?= htmlspecialchars($class['SY']); ?></td>
                                        <td><?= htmlspecialchars($class['teacher_name']); ?></td>
                                        <td>
                                            <a href="classstudent.php?classid=<?= $class['id']; ?>&SY=<?= $class['SY']; ?>" title="View Students">View</a>
                                        </td>
                                        <td>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="classId" value="<?= $class['id']; ?>">
                                                <button type="submit" name="deleteClass" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No class information found for this teacher.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

