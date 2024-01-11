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
// print_r($academic_year);
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

// Function to handle the deletion of a subject
function deleteStudent($studentId, $connection)
{
    // Write your code to delete the subject with the given subject ID
    $sql = "DELETE FROM student WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$studentId]);
}

// Check if the delete button is clicked
if (isset($_POST['deleteStudent']) && isset($_POST['studentId'])) {
    $studentId = $_POST['studentId'];
    $delete = deleteStudent($studentId, $connection);


    // Redirect to the same page after the deletion
    ?>
    <script type="text/javascript">
        alert("Student successfully deleted")
        window.location.href = "studentlist.php"
    </script>
    <?php
    exit(); // Make sure to exit after redirecting to prevent further code execution


}
// Filter the students based on the selected year and section
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
<!-- <link rel="stylesheet" type="text/css" href="DataTables/DataTables-1.13.6/datatables.min.css"> -->
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
                    <li class="active">
                        Student's List
                    </li>
                </ol>
            </div>
        </div>

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

            .delete-button:hover {
                /* Add any hover styles if you want */
            }
        </style>

        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <form action="studentlist.php" method="POST">

                    <div class="form-group d-flex align-items-center">
                        <label for="year" class="mr-2">Year:</label>
                        <select name="year" id="year" class="form-control mr-4" style="max-width: 60px;"
                            onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach ($years as $availableYear): ?>
                                <option value="<?php echo $availableYear; ?>" <?php echo ($availableYear == $year) ? 'selected' : ''; ?>>
                                    <?php echo $availableYear; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="section" class="mr-2">Section:</label>
                        <select name="section" id="section" class="form-control mr-4" style="max-width: 100px;"
                            onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach ($sections as $availableSection): ?>
                                <option value="<?php echo $availableSection; ?>" <?php echo ($availableSection == $section) ? 'selected' : ''; ?>>
                                    <?php echo $availableSection; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="ml-auto">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#importCSVModal"><i class="fa fa-upload"></i> Import CSV</button>
<!--                               <a href="updatestudentsubject.php"><button type="button" class="btn btn-warning text-light"><i
                                        class="fa fa-user-edit"></i> Update Student</button></a> -->
                            <a href="addstudent.php"><button type="button" class="btn btn-success"><i
                                        class="fa fa-user"></i> Add Student</button></a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <div class="col-lg-12">
    <form action="studentlist.php" method="POST" id="searchForm" style="float: right">
        <div class="form-group d-flex align-items-center">
            <input type="text" class="form-control" name="search" placeholder="Search by ID, Fullname, Year, or Section" />
        </div>
    </form>
</div> -->
        </div>
        <!--/.row -->
        <hr />
        <!-- Import CSV Modal -->
        <div class="modal fade" id="importCSVModal" tabindex="-1" role="dialog" aria-labelledby="importCSVModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importCSVModalLabel">Import Students from CSV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your CSV import form here -->
                        <form action="importcsv.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="csvFile">Select CSV File:</label>
                                <input type="file" class="form-control-file" id="csvFile" name="csvFile" accept=".csv"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

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
                    } else if ($r == 'added an account') {
                        $class = 'success';
                    } else if ($r == 'has already an account') {
                        $class = 'info';
                    } else {
                        $class = 'hide';
                    }
                    ?>
                    <div class="alert alert-<?php echo $class ?> <?php echo $classs; ?>">
                        <strong>1 student successfully
                            <?php echo $r; ?>!
                        </strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            @media print {

                /* hide every other element */
                body * {
                    visibility: hidden;
                }

                /* then display print container */
                .print-container,
                .print-container * {
                    visibility: visible;
                }

                .print-container {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                }
            }
        </style>

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
                            $lastNames = array();

                            foreach ($students as $number => $student) {
                                $lastName = $student['lname'];
                                $lastNames[] = $lastName;
                            }
                            asort($lastNames);
                            ?>

                            <?php
                            $number = 1;
                            foreach ($lastNames as $index => $lastName): ?>
                                <?php $student = $students[$index];
                                $result = mysql_query("SELECT COUNT(*) AS enrolled_subjects FROM studentsubject WHERE semester='$student[semester]'AND year='$student[year]' AND studid='$student[id]' AND section='$student[section]'  AND SY='$student[ay]'");
                                $d = mysql_fetch_assoc($result);
                                $totalsubs = $d['enrolled_subjects'];

                                ?>
                                <tr>
                                    <td>
                                        <?php echo $number++; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $student['studid']; ?>
                                    </td>
                                    <td>
                                        <?php echo $student['lname']; ?>,
                                        <?php echo $student['fname']; ?>
                                        <?php echo $student['mname']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $student['year']; ?> -
                                        <?php echo $student['section']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $student['semester']; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="viewsubjects.php?type=student&id=<?php echo $student['studid']; ?>">Enrolled
                                            Subjects |
                                            <?php
                                            echo $totalsubs;
                                            ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $student['email']; ?>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: inline-block;">
                                            <a href="edit.php?type=student&id=<?php echo $student['id']; ?>" title="Update">
                                                <i class="fa fa-edit fa-lg text-primary"></i>
                                            </a>
                                        </div>
                                        <!-- Form to handle subject deletion -->
                                        <form class="delete-form" method="post" style="display: inline-block;">
                                            <input type="hidden" name="studentId" value="<?php echo $student['id']; ?>">
                                            <button type="submit" name="deleteStudent" class="delete-button" title="Remove"
                                                onclick="return confirm('Are you sure you want to delete this student?');">
                                                <i class="fa fa-trash-o fa-lg text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#studentInformation thead th').each(function () {
        //     var title = $('#studentInformation thead th').eq( $(this).index() ).text();
        //     if(title!=""){
        //         $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        //   }
    });

    // DataTable
    var table = $('#studentInformation').DataTable({
        searching: true,
        "columnDefs": [
            { "searchable": true, "targets": '_all' }
        ],
    });

    // // Apply the search
    // table.columns().eq( 0 ).each( function ( colIdx ) {
    //     if( !table.settings()[0].aoColumns[colIdx].bSearchable ){
    //     table.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
    // }
    //     $( 'input', table.column( colIdx ).header() ).on( 'keyup change', function () {
    //         table
    //             .column( colIdx )
    //             .search( this.value )
    //             .draw();
    //     } );
    // } );

</script>
<script>
    function formToggle(ID) {
        var element = document.getElementById(ID);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
</script>

<script>
    $(document).ready(function () {
        $.noConflict();
        $('#studentInformation').DataTable();
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
