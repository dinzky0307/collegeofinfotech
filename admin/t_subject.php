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
                    <small>SUBJECT'S LIST</small>
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
        <div class="row">

    <!--    <div class="col-lg-3">
    <form action="subject.php" method="POST" id="searchForm">
        <div class="form-group d-flex align-items-center">
            <input type="text" class="form-control" name="search" placeholder="Search by Subject code, or Descriptive Title" />
        </div>
    </form>
</div>!-->
        </div>
        <!--/.row -->
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

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="subjectInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Descriptive Title</th>
                                <th class="text-center">Lec Unit</th>
                                <th class="text-center">Lab Unit</th>
                                <th class="text-center">Total Units</th>
                                <th class="text-center">Pre-requisites/s</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $number => $subject): ?>
                                <tr>
                                    <td>
                                        <?php echo $number + 1; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $subject['code']; ?></a>
                                    </td>
                                    <td>
                                        <?php echo $subject['title']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $subject['lecunit']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $subject['labunit']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $subject['totalunit']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $subject['pre']; ?>
                                    </td>
                                    <td class="text-center"><a href="edit.php?type=subject&id=<?php echo $subject['id'] ?>"
                                            title="Update"><i class="fa fa-edit fa-lg text-primary"></i> </a></td>
                                    <td class="text-center">
                                        <form class="delete-form" method="post">
                                            <input type="hidden" name="subjectId" value="<?php echo $subject['id']; ?>">
                                            <button type="submit" name="deleteSubject" class="delete-button" title="Remove"
                                                onclick="return confirm('Are you sure you want to delete this subject?');">
                                                <i class="fa fa-trash-o fa-lg text-danger"></i>
                                            </button>
                                        </form>
                                        <!-- <a href="data/data_model.php?q=delete&table=subject&id=<?php echo $subject['id'] ?>"title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
