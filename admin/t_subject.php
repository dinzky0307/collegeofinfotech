<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');

// Initialize and validate the teacher ID
$teacherId = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;
if ($teacherId <= 0) {
    echo "<div class='alert alert-danger text-center'>Invalid Teacher ID</div>";
    exit;
}

try {
    // Fetch active academic year and teacher details in one query
    $query = "
        SELECT 
            ay.academic_year, ay.semester, 
            t.fname, t.mname, t.lname 
        FROM 
            ay 
        CROSS JOIN 
            teacher t 
        WHERE 
            t.id = :teacherId AND ay.display = 1
    ";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "<div class='alert alert-warning text-center'>No active academic year or teacher found</div>";
        exit;
    }

    $academic_year = $result['academic_year'];
    $semester = $result['semester'];
    $teacherFullName = htmlspecialchars("{$result['lname']}, {$result['fname']} {$result['mname']}");

    // Fetch class data with total students for each class
    $sql = "
        SELECT 
            c.id AS id,
            c.subject AS subject,
            c.description AS description,
            c.course AS course,
            CONCAT(c.year, ' ', c.section) AS year_section,
            c.sem AS sem,
            c.SY AS SY,
            COUNT(ss.studid) AS total_students
        FROM 
            class c
        LEFT JOIN 
            studentsubject ss ON c.id = ss.classid
        WHERE 
            c.teacher = :teacherId
        GROUP BY 
            c.id, c.subject, c.year, c.section, c.sem, c.SY
        ORDER BY 
            c.year, c.section, c.subject;
    ";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
    $stmt->execute();
    $classData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<div class='alert alert-danger text-center'>Error fetching data: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Instructor Class Information (<?= $teacherFullName; ?>)</small>
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
                                <th>Status</th>
                                <th>Actions</th>
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
                                        <td><?= $class['total_students'] > 0 
                                                ? "{$class['total_students']} Students" 
                                                : "0"; ?>
                                        </td>
                                        <td>
                                            <a href="classstudent.php?classid=<?= $class['id']; ?>&SY=<?= $class['SY']; ?>" title="View Students">View</a>
                                        </td>
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
