<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/student_model.php');
include('data/class_model.php');

$class = new Dataclass($connection);
if (isset($_GET['q'])) {
    $class->$_GET['q']();
}
$student = new Datastudent($connection);
if (isset($_GET['q'])) {
    $class->$_GET['q']();
}
$classid = isset($_GET['classid']) ? $_GET['classid'] : "";
$year = isset($_GET['y']) ? $_GET['y'] : "";
$section = isset($_GET['s']) ? $_GET['s'] : "";
$sem = isset($_GET['sem']) ? $_GET['sem'] : "";
$code = isset($_GET['code']) ? $_GET['code'] : "";
$SY = isset($_GET['SY']) ? $_GET['SY'] : "";
// print_r($code);

$search = isset($_POST['search']) ? $_POST['search'] : null;

// if (isset($_POST['submitsearch'])) {
//     $searchQuery = $_POST['search'];
//     echo "Search Query: " . $searchQuery;
// }

if (isset($_POST['search'])) {
    $studentDataArray = $student->getstudent($search);

} else {

    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare("SELECT * FROM subject Where code = :code");
    //  $stmt->bindParam(':classid', $classid, PDO::PARAM_INT);
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $subjectArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($subjectArr as $subjectData) {
        $subjectcode = $subjectData['id'];
    }
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare("SELECT student.studid, student.fname, student.lname, student.id FROM student JOIN studentsubject ON student.id = studentsubject.studid WHERE studentsubject.year = :year AND studentsubject.section = :section AND studentsubject.semester = :semester AND studentsubject.subjectid = :code AND studentsubject.SY = :SY");
    //  $stmt->bindParam(':classid', $classid, PDO::PARAM_INT);
    $stmt->bindParam(':year', $year, PDO::PARAM_STR);
    $stmt->bindParam(':section', $section, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $sem, PDO::PARAM_STR);
    $stmt->bindParam(':code', $subjectcode, PDO::PARAM_INT);
    $stmt->bindParam(':SY', $SY, PDO::PARAM_STR);
    $stmt->execute();
    $studentDataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// print_r($section);
function deleteStudent($studentId, $classId, $connection)
{
    // Prepare the SQL statement
    $sql = "DELETE FROM studentsubject WHERE studid = :studentId AND classid = :classId";
    $stmt = $connection->prepare($sql);

    // Bind the values to the prepared statement
    $stmt->bindParam(":studentId", $studentId, PDO::PARAM_INT);
    $stmt->bindParam(":classId", $classId, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->rowCount() > 0) {
        echo "Student deleted successfully.";
    } else {
        echo "No student found with the specified ID and class ID.";
    }
    // Close the statement
    $stmt->closeCursor();
}
// Check if the delete button is clicked
if (isset($_POST['deleteStudent']) && isset($_POST['studentId']) && isset($_POST['classId'])) {
    $studentId = $_POST['studentId'];
    $classId = $_POST['classId'];

    $delete = deleteStudent($studentId, $classId, $connection);

    if ($delete) {
        // Redirect to the same page after the deletion
        echo "<script type='text/javascript'>window.location.href = 'classstudent.php?r=deleted&classid=' . $classid . '';</script>";
        exit(); // Make sure to exit after redirecting to prevent further code execution
    } else {

    }

}
function updateStudent($studentId, $classId, $connection, $year, $section, $sem, $code, $SY)
{

    $stmt = $connection->prepare("SELECT * FROM subject Where code = :code");
    //  $stmt->bindParam(':classid', $classid, PDO::PARAM_INT);
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $subjectArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $subjectcode = '';
    foreach ($subjectArr as $subjectData) {
        $subjectcode = $subjectData['id'];
    }
    print_r($subjectcode);
    // Prepare the SQL statement
    $sql = "UPDATE studentsubject SET classid = :classID WHERE studid = :studentId AND year = :year AND section = :section AND semester = :sem AND subjectid = :code AND SY = :SY";
    $stmt = $connection->prepare($sql);

    // Bind the values to the prepared statement
    $stmt->bindParam(":classID", $classId, PDO::PARAM_STR);
    $stmt->bindParam(":studentId", $studentId, PDO::PARAM_STR);
    $stmt->bindParam(":year", $year, PDO::PARAM_STR);
    $stmt->bindParam(":section", $section, PDO::PARAM_STR);
    $stmt->bindParam(":sem", $sem, PDO::PARAM_STR);
    $stmt->bindParam(":code", $subjectcode, PDO::PARAM_STR);
    $stmt->bindParam(":SY", $SY, PDO::PARAM_STR);


    // Execute the statement
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->rowCount() > 0) {
        echo "Student updated successfully.";
    } else {
        echo "";
    }
    // Close the statement
    $stmt->closeCursor();
}
// Check if the delete button is clicked
if (isset($_POST['updateStud']) && isset($_POST['studId']) && isset($_POST['clId'])) {
    $studentId = $_POST['studId'];
    $classId = $_POST['clId'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $sem = $_POST['sem'];
    $code = $_POST['code'];
    $SY = $_POST['SY'];

    $update = updateStudent($studentId, $classid, $connection, $year, $section, $sem, $code, $SY);


    if ($update) {
        // Redirect to the same page after the deletion
        echo "<script type='text/javascript'>window.location.href = 'classstudent.php?r=deleted&classid=' . $classid . '';</script>";
        exit(); // Make sure to exit after redirecting to prevent further code execution
    } else {

    }

}
$rc = mysql_query("select * from class where id=$classid");
$rc = mysql_fetch_array($rc);
$subject = $rc['subject'];
// print_r($rc);

?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>CLASS STUDENTS</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="class.php">Class Info</a>
                    </li>
                    <li class="active">
                        Class Students (Subject:
                        <?php echo $subject; ?>)
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding">
                    <form
                        action="classstudent.php?classid=<?php echo $classid; ?>"
                        method="post">
                        <input type="hidden" name="classid" value="<?php echo $classid; ?>">
                        <input type="hidden" name="y" value="<?php echo $year; ?>">
                        <input type="hidden" name="s" value="<?php echo $section; ?>">
                        <input type="hidden" name="sem" value="<?php echo $sem; ?>">
                        <input type="hidden" name="code" value="<?php echo $code; ?>">
                        <input type="hidden" name="SY" value="<?php echo $SY; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Search by ID # or Name..."
                            required autofocus>
                        <button type="submit" name="submitsearch" class="btn btn-success"><i class="fa fa-search"></i>
                            Search</button>
                    </form>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <?php if ($search) { ?>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentDataArray as $studentData): ?>
                                    <tr>
                                        <td>
                                            <?php echo $studentData['studid']; ?>
                                        </td>
                                        <td>
                                            <?php echo $studentData['lname']; ?>
                                        </td>
                                        <td>
                                            <?php echo $studentData['fname']; ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <a href="data/class_model.php?q=addstudent&studid=<?php echo $studentData['id']; ?>&classid=<?php echo $classid; ?>"
                                                class="btn btn-warning">Add to class</a>
                                        </td> -->
                                        <td class="text-center">
                                            <form class="update-form" method="post" style="display: inline-block;">
                                                <input type="hidden" name="studId" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="clId" value="<?php echo $classid; ?>">
                                                <input type="hidden" name="year" value="<?php echo $year; ?>">
                                                <input type="hidden" name="section" value="<?php echo $section; ?>">
                                                <input type="hidden" name="sem" value="<?php echo $sem; ?>">
                                                <input type="hidden" name="code" value="<?php echo $code; ?>">
                                                <input type="hidden" name="SY" value="<?php echo $SY; ?>">
                                                <button type="submit" name="updateStud" class="update-button" title="Update"
                                                    onclick="return confirm('Are you sure you want to add this student?');">
                                                    <i class="fa fa-plus-circle fa-2x text-success"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($studentData)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-danger"><strong>*** NO RESULT ***</strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    <?php } else { ?>
                        <?php if (isset($_GET['r'])): ?>
                            <?php if ($_GET['r'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <strong>Successfull!</strong>
                                </div>
                            <?php } else if ($_GET['r'] == 'duplicate') { ?>
                                    <div class="alert alert-warning">
                                        <strong>Student already on the list!</strong>
                                    </div>
                            <?php } ?>
                        <?php endif; ?>
                        <!--<?php print_r($studentDataArray); ?>!-->

                        <table class="table table-striped">
                            <thead>
                                <th>Student ID</th>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th class="text-center">Remove</th>
                            </thead>
                            <tbody>
                                <?php foreach ($studentDataArray as $row): ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['studid']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['lname']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['fname']; ?>
                                        </td>
                                        <!-- Form to handle subject deletion -->
                                        <td class="text-center">
                                            <form class="delete-form" method="post" style="display: inline-block;">
                                                <input type="hidden" name="studentId" value="<?php echo $row['id']; ?>">
                                                <!-- <input type="hidden" name="classId" value="<?php echo $classid; ?>"> -->
                                                <button type="submit" name="deleteStudent" class="delete-button" title="Remove"
                                                    onclick="return confirm('Are you sure you want to delete this student?');">
                                                    <i class="fa fa-times-circle fa-2x text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <!-- <td class="text-center"><a
                                                href="data/class_model.php?q=removestudent&studid=<?php echo $row['id']; ?>&classid=<?php echo $classid; ?>"
                                                class="confirmation"><i class="fa fa-times-circle fa-2x text-danger"></i></a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (!$studentDataArray): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-danger"><strong>*** EMPTY ***</strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>


                    <?php } ?>
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<?php include('include/footer.php');
