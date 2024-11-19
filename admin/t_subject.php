<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');

// Initialize the teacher ID
$teacherId = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

// Validate teacher ID
if ($teacherId <= 0) {
    die("<div class='alert alert-danger text-center'>Invalid Teacher ID</div>");
}

// Fetch the active academic year
$activeAcademicYearQuery = "SELECT * FROM ay WHERE display = 1";
$stmt = $connection->prepare($activeAcademicYearQuery);
$stmt->execute();
$activeAcademicYear = $stmt->fetch(PDO::FETCH_ASSOC);

if ($activeAcademicYear) {
    $academic_year = $activeAcademicYear['academic_year'];
    $semester = $activeAcademicYear['semester'];
} else {
    die("<div class='alert alert-warning text-center'>No active academic year found</div>");
}

// Fetch teacher's full name
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

// Fetch class data along with student counts, grouped by teacher_id
$sql = "
    SELECT c.id AS class_id, c.subject, c.description, c.course, 
           CONCAT(c.year, '-', c.section) AS year_section, c.sem, c.SY,
           COUNT(ss.studid) AS total_students
    FROM class c
    LEFT JOIN studentsubject ss ON c.id = ss.classid
    WHERE c.teacher = :teacherId AND c.SY = :academicYear AND c.sem = :semester
    GROUP BY c.id
";
$stmt = $connection->prepare($sql);
$stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
$stmt->bindParam(':academicYear', $academic_year, PDO::PARAM_STR);
$stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
$stmt->execute();
$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                    <tr>
                                        <td><?= $index++; ?></td>
                                        <td><?= htmlspecialchars($class['subject']); ?></td>
                                        <td><?= htmlspecialchars($class['description']); ?></td>
                                        <td><?= htmlspecialchars($class['course']); ?></td>
                                        <td><?= htmlspecialchars($class['year_section']); ?></td>
                                        <td><?= htmlspecialchars($class['sem']); ?></td>
                                        <td><?= htmlspecialchars($class['SY']); ?></td>
                                         <td>
                                            <a href="classstudent.php?classid=<?= $class['id']; ?>&SY=<?= $class['SY']; ?>" title="View Students">View</a>
                                        </td>
                                        <td>
                                            <?= $class['total_students'] > 0 
                                                ? "{$class['total_students']} Students" 
                                                : "0/0"; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No class information found for this teacher.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#classInformation').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "responsive": true
        });
    });
</script>

