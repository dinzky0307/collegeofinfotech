<?php
include('include/header.php');
include('include/sidebar.php');

include('../database.php'); // Include the database connection code

include('data/data_model.php');

$data = new Data($connection);
if (isset($_GET['q'])) {
    $data->$_GET['q']();
}

$search = '';

// Fetch the active academic year from the database
include '../DatabaseService.php';
use Database\DatabaseService;

$dbService = new DatabaseService;

$activeAcademicYear = $dbService->fetchRow("SELECT * FROM ay WHERE display = 1");

// Check if the active academic year exists and set the variables accordingly
if ($activeAcademicYear) {
    $academicYearActive = true;
    $semester = $activeAcademicYear['semester']; // Get the active semester (e.g., 'First Semester', 'Second Semester', 'Final Semester')
} else {
    $academicYearActive = false;
    $semester = ''; // Set the semester to an empty string or handle as needed
}

// Create a mapping array for semester values
$semesterMapping = [
    'First Semester' => 1,
    'Second Semester' => 2,
    'Final Semester' => 3,
];

// Map the active semester to the corresponding value
$semesterValue = isset($semesterMapping[$semester]) ? $semesterMapping[$semester] : null;
 $firstsem = $subject->getsubject('First Semester',$id);    
    $secondsem = $subject->getsubject('Second Semester',$id);
    $summer = $subject->getsubject('Summer',$id);  
$subjects = [];
if ($academicYearActive) {
    $subjects = $data->getsubject($search, $semesterValue); // Modify this to fetch subjects based on the active semester value
}

// Function to handle the deletion of a subject
function deleteSubject($subjectId, $connection)
{
    // Write your code to delete the subject with the given subject ID
    $sql = "DELETE FROM subject WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$subjectId]);
}

// Check if the delete button is clicked
if (isset($_POST['deleteSubject']) && isset($_POST['subjectId'])) {
    $subjectId = $_POST['subjectId'];
    $delete = deleteSubject($subjectId, $connection);

    if ($delete) {
        // Redirect to the same page after the deletion
        echo "<script type='text/javascript'>window.location.href = 'subject.php?r=deleted';</script>";
        exit(); // Make sure to exit after redirecting to prevent further code execution
    } else {

    }

}
?>
<style>
    /* Custom styling for the delete button */
    .delete-button {
        border: none;
        background: none;
        padding: 0;
        color: #f00;
        /* Change the color as per your preference */
        cursor: pointer;
        outline: none;
        /* Remove outline on focus */
    }
</style>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>INSTRUCTOR SUBJECT'S LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Subject
                    </li>
                </ol>
            </div>
        </div>
        
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding" style="float: right;">
                    <form action="subject.php" method="post">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addsubject"><i
                                class="fa fa-book"></i> Add Subject</button>

                    </form>
                </div>
            </div>
        </div>
       
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <?php if (isset($_GET['r'])): ?>
                    <?php
                    $r = $_GET['r'];
                    if ($r == 'added') {
                        $class = 'success';
                    } else if ($r == 'updated') {
                        $class = 'info';
                    } else if ($r == 'deleted') {
                        $class = 'danger';
                    } else {
                        $class = 'hide';
                    }
                    ?>
                    <div class="alert alert-<?php echo $class ?> <?php echo $class; ?>">
                        <strong>Subject successfully
                            <?php echo $r; ?>!
                        </strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
             <div class="col-lg-12">
                <!-- <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#data1" role="tab" data-toggle="tab">First Semester</a></li>
                    <li><a href="#data2" role="tab" data-toggle="tab">Second Semester</a></li>
                    <li><a href="#data3" role="tab" data-toggle="tab">Summer</a></li>
                </ul> -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="<?php echo isset($_GET['page']) ? 'active' : ''; ?>" style="border: 1px solid black; border-radius: 7px; margin-right: 10px"><a href="#data1" role="tab" data-toggle="tab">First Semester</a></li>
                    <li class="<?php echo isset($_GET['page']) ? '' : ''; ?>" style="border: 1px solid black; border-radius: 7px; margin-right: 10px"><a href="#data2" role="tab" data-toggle="tab">Second Semester</a></li>
                    <li class="<?php echo isset($_GET['page']) ? '' : ''; ?>" style="border: 1px solid black; border-radius: 7px; margin-right: 10px"><a href="#data3" role="tab" data-toggle="tab">Summer</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- <div class="tab-pane active" id="data1"> -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? 'active' : ''; ?>" id="data1">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($firstsem)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td class=""><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                        </tr>
                                    <?php $c++; ?>
                                    <?php endwhile; ?>
                                    <?php if(mysql_num_rows($firstsem) < 1): ?>
                                        <tr><td colspan="6" class="text-center text-danger"><strong>*** EMPTY ***</strong></td></tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="tab-pane" id="data2"> -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? '' : ''; ?>" id="data2">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($secondsem)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                        </tr>
                                    <?php $c++; ?>
                                    <?php endwhile; ?>
                                    <?php if(mysql_num_rows($secondsem) < 1): ?>
                                        <tr><td colspan="6" class="text-center text-danger"><strong>*** EMPTY ***</strong></td></tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="tab-pane active" id="data3"> -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? '' : ''; ?>" id="data3">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($summer)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                            
                                        </tr>
                                    <?php $c++; ?>
                                    <?php endwhile; ?>
                                    <?php if(mysql_num_rows($summer) < 1): ?>
                                        <tr><td colspan="6" class="text-center text-danger"><strong>*** EMPTY ***</strong></td></tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>   
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<script>
    $('#subjectInformation thead th').each( function () {
    } );
    // DataTable
    var table = $('#subjectInformation').DataTable({
    searching: true,
    "columnDefs": [
        { "searchable": true, "targets": '_all' }
    ],
    });
</script>
<script>
    $(document).ready(function () {
        $.noConflict();
        $('#subjectInformation').DataTable();
    });

    function handleSearchInput(event) {
        // Get the form element
        const form = document.getElementById('searchForm');
        
        // Submit the form
        form.submit();
    }

    // Listen for the "keyup" event on the search input and call the handleSearchInput function
    const searchInput = document.querySelector('input[name="search"]');
    searchInput.addEventListener('keyup', handleSearchInput);
</script>
<!-- /#page-wrapper -->
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>
