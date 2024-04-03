<?php
include ('include/header.php');
include ('include/sidebar.php');
include ('connection.php');

include ('../database.php'); // Include the database connection code

include ('data/data_model.php');

include '../DatabaseService.php';
use Database\DatabaseService;

$dbService = new DatabaseService;

$data = new Data($connection);
if (isset($_GET['q'])) {
    $data->$_GET['q']();
}

$y = isset($_GET['y']) ? $_GET['y'] : '';
$s = isset($_GET['s']) ? $_GET['s'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$section = isset($_GET['sec']) ? $_GET['sec'] : '';
$regular = isset($_GET['regular']) ? $_GET['regular'] : '';
$irregular = isset($_GET['irregular']) ? $_GET['irregular'] : '';
$sem = '';


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
// Initialize the semester variable based on the filter
if ($regular == 1) {
    if ($s == "First Semester") {
        $sem = 1;
    } else if ($s == "Second Semester") {
        $sem = 2;
    } else if ($s == "Summer") {
        $sem = 3;
    }

    // $s = 1; // Regular subjects
    // Get all subjects for regular students
    $subjects = $data->getSubjectsByFilter($y, $sem);
    echo "<script>
    $(function() {
        if ($regular == 1) {
            $('.name').prop('checked', true);
        }
    });
</script>";
} elseif ($irregular == 1) {
    if ($s == "First Semester") {
        $sem = 1;
    } else if ($s == "Second Semester") {
        $sem = 2;
    } else if ($s == "Summer") {
        $sem = 3;
    }
    // Create an array to store all the years from 1st to 4th year
    $years = array();
    for ($i = 1; $i <= 4; $i++) {
        $years[] = ($y + $i - 1) . '-' . ($y + $i);
    }
    // Get subjects for the selected semester in all years
    $subjects = array();
    foreach ($years as $year) {
        $subjects = array_merge($subjects, $data->getSubjectsByFilter($year, $sem));
    }
} else {
    if ($s == "First Semester") {
        $sem = 1;
    } else if ($s == "Second Semester") {
        $sem = 2;
    } else if ($s == "Summer") {
        $sem = 3;
    }
    // Get all subjects if no filter is applied
    $subjects = $data->getAllSubjects();
}
//print_r($sem);
if (isset($_POST['submit'])) {
    if (!empty($_POST['selected_subjects'])) {
        $selectedSubjects = explode(',', $_POST['selected_subjects']);

        // Loop through each selected subject
        foreach ($selectedSubjects as $subid) {

            // Get id of the student
            $sql_code = "SELECT id,section FROM student WHERE studid=:studid LIMIT 1";
            $pdo_statement_code = $connection->prepare($sql_code);
            $pdo_statement_code->bindParam(':studid', $id, PDO::PARAM_INT);
            $pdo_statement_code->execute();
            $rows = $pdo_statement_code->fetch(PDO::FETCH_ASSOC);
            $studid = $rows['id'];
            $section = $rows['section'];

            // Get subject code
            $sql_code = "SELECT code FROM subject WHERE id=:subid LIMIT 1";
            $pdo_statement_code = $connection->prepare($sql_code);
            $pdo_statement_code->bindParam(':subid', $subid, PDO::PARAM_INT);
            $pdo_statement_code->execute();
            $rows = $pdo_statement_code->fetch(PDO::FETCH_ASSOC);
            $subjectcode = $rows['code'];



            if ($sem == 1) {
                $sm = "First Semester";
            } else if ($sem == 2) {
                $sm = "Second Semester";
            } else {
                $sm = "Summer";
            }


            $sql_insert = "INSERT INTO studentsubject (studid, subjectid, year, semester, section, SY) VALUES (:id, :subid, :year, :semester, :section, :ay)";
            $pdo_statement_insert = $connection->prepare($sql_insert);
            $pdo_statement_insert->bindParam(':id', $studid, PDO::PARAM_INT);
            $pdo_statement_insert->bindParam(':subid', $subid, PDO::PARAM_INT);
            $pdo_statement_insert->bindParam(':ay', $academic_year, PDO::PARAM_STR);
            // $pdo_statement_insert->bindParam(':classid', $classid, PDO::PARAM_INT);
            $pdo_statement_insert->bindParam(':year', $y, PDO::PARAM_INT);
            $pdo_statement_insert->bindParam(':semester', $sm, PDO::PARAM_STR);
            $pdo_statement_insert->bindParam(':section', $section, PDO::PARAM_STR);

            if ($pdo_statement_insert->execute()) {
                echo "<script type='text/javascript'>";
                echo "Swal.fire({
                    title: 'Subject Successfully Enrolled!',
                    text: '',
                    icon: 'success',
                })";
                echo "</script>";
                // Redirect with parameters
                echo "<script type='text/javascript'>window.location.href = 'studentlist.php?';</script>";
            } else {
                // Print any error messages
                $errorInfo = $pdo_statement_insert->errorInfo();
                var_dump($errorInfo);

                echo "<script type='text/javascript'>";
                echo "Swal.fire({
                    title: 'Something went wrong!',
                    text: 'Please check Class maybe there are no following subject/s for this section',
                    icon: 'error',
                })";
                echo "</script>";
            }
        }
    } else {

        ?>
        <script type="text/javascript">
            alert("No subject been selected")
        </script>
        <?php
    }
}
// }
?>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row hidden-print">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>SUBJECT LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>

                </ol>
            </div>
        </div>
        <style>
            @media print {
                .print-container {
                    position: center;
                    margin-top: 0px;
                }

                @media print {
                    .print-container {
                        width: 260mm;
                        font-size: 17px;
                    }
                }

                @page {
                    size: auto;
                    margin: 50px;
                }

            }
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>




        <SCRIPT language="javascript">
            $(function ()
            {
                // add multiple select / deselect functionality
                $("#selectall").click(function ()
                {
                    $('.name').prop('checked', this.checked);
                    updateSelectedSubjects();
                });

                // if all checkbox are selected, then check the select all checkbox
                // and vice versa
                $(".name").click(function ()
                {
                    if ($(".name").length == $(".name:checked").length)
                    {
                        $("#selectall").prop("checked", "checked");
                    } else
                    {
                        $("#selectall").removeAttr("checked");
                    }
                    updateSelectedSubjects();
                });

                $('input[name="check_list[]"]').change(function ()
                {
                    updateSelectedSubjects();
                });

                function updateSelectedSubjects()
                {
                    // Get all the selected subject IDs
                    const selectedSubjects = [];
                    $('input[name="check_list[]"]:checked').each(function ()
                    {
                        selectedSubjects.push($(this).val());
                    });

                    // Update the value of the hidden input field with the selected subjects
                    $('#selected_subjects').val(selectedSubjects.join(','));
                }
            });


            $(function ()
            {
                // Handle regular and irregular checkboxes
                $("input[name='regular']").on('change', function ()
                {
                    if ($(this).prop('checked'))
                    {
                        $("input[name='irregular']").prop('checked', false);
                        $("#filterForm").submit(); // Submit the form when the checkbox is changed
                    }
                });

                $("input[name='irregular']").on('change', function ()
                {
                    if ($(this).prop('checked'))
                    {
                        $("input[name='regular']").prop('checked', false);
                        $("#filterForm").submit(); // Submit the form when the checkbox is changed
                    }
                });
            });
        </SCRIPT>


        <?php
        if (isset($_GET['regular']) == 1) {
            ?>
            <script type="text/javascript">
                $(function ()
                {
                    // add multiple select / deselect functionality
                    // if all checkbox are selected, then check the select all checkbox
                    // and vice versa

                    if ($(".name").length == $(".name:checked").length)
                    {
                        $("#selectall").prop("checked", "checked");
                    } else
                    {
                        $("#selectall").removeAttr("checked");
                    }
                    updateSelectedSubjects();


                    $('input[name="check_list[]"]').change(function ()
                    {
                        updateSelectedSubjects();
                    });

                    function updateSelectedSubjects()
                    {
                        // Get all the selected subject IDs
                        const selectedSubjects = [];
                        $('input[name="check_list[]"]:checked').each(function ()
                        {
                            selectedSubjects.push($(this).val());
                        });

                        // Update the value of the hidden input field with the selected subjects
                        $('#selected_subjects').val(selectedSubjects.join(','));
                    }
                });

            </script>
        <?php
        }
        ?>

        <!-- Subject list filter form -->
        <div class="row">
            <div class="col-lg-12">
                <form id="filterForm" method="get" action="">
                    <!-- Hidden input fields for existing parameters -->
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="y" value="<?php echo $y; ?>">
                    <input type="hidden" name="s" value="<?php echo $s; ?>">

                    <!-- Add the regular and irregular parameters to the filter form -->
                    <label>
                        <input type="checkbox" name="regular" value="1" <?php if ($regular)
                            echo 'checked'; ?> />
                        Regular
                    </label>
                    <label>
                        <input type="checkbox" name="irregular" value="1" <?php if ($irregular)
                            echo 'checked'; ?> />
                        Irregular
                    </label>
                    <!-- Add other filter options if needed -->
                    <button type="submit" name="filter" class="btn btn-primary" hidden>Apply Filter</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <hr>
                <h2>Subject List</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>

                                <th class="text-center"><input type="checkbox" id="selectall" <?php
                                if (isset($_GET['regular']) == 1) {
                                    echo "checked";
                                }
                                ?> /></th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Descriptive Title</th>
                                <th class="text-center">Lec Unit</th>
                                <th class="text-center">Lab Unit</th>
                                <th class="text-center">Total Units</th>
                                <th class="text-center">Pre-requisites/s</th>

                            </tr>

                        </thead>
                        <tbody>
                            <?php

                            foreach ($subjects as $subject) {
                                echo '<tr>';
                                echo '<td class="text-center">';
                                echo '<input class="' . $subject['id'] . ' name" name="check_list[]" type="checkbox" value="' . $subject['id'] . '" data-id="' . $subject['id'] . '"/>';
                                echo '</td>';
                                echo '<td class="text-center">' . $subject['code'] . '</td>';
                                echo '<td class="text-center">' . $subject['title'] . '</td>';
                                echo '<td class="text-center">' . $subject['lecunit'] . '</td>';
                                echo '<td class="text-center">' . $subject['labunit'] . '</td>';
                                echo '<td class="text-center">' . $subject['totalunit'] . '</td>';
                                echo '<td class="text-center">' . $subject['pre'] . '</td>';
                                echo '</tr>';

                                // Disable the subjects that the student has already enrolled in
                                $subjectid = $subject['id'];
                                if ($irregular != 1) {
                                    echo "<script>$('." . $subjectid . "').prop('enabled', true);</script>";
                                } else {
                                    echo "<script>$('." . $subjectid . "').prop('disabled', false);</script>";
                                }
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
                <form method="post" action="">
                    <!-- Add a hidden input field to store the selected subjects -->
                    <input type="hidden" name="selected_subjects" id="selected_subjects" value="">
                    <a href="addstudent.php">
                        <button type="button" class="btn btn-primary"
                            style="margin-top: 5px; text-decoration: none; color: white;">
                            <i class="fa fa-arrow-left"></i> Go Back
                        </button>
                    </a>
                    <button type="submit" name="submit" class="btn btn-success" style="margin-top: 5px;">
                        <i class="fa fa-check"></i> Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$sqls = "SELECT subjectid FROM studentsubject WHERE studid = '$id' AND (prelim_grade+midterm_grade+final_grade)/3 <= 74";
$results = mysqli_query($dbconnection, $sqls);
while ($ss = $results->fetch_assoc()) {
    $subjectid = $ss['subjectid'];
    $sqlss = "SELECT * FROM subject WHERE id = '$subjectid'";
    $resultss = mysqli_query($dbconnection, $sqlss);
    while ($sss = $resultss->fetch_assoc()) {
        $code = $sss['code'];
        $sqlss = "SELECT * FROM subject WHERE pre = '$code'";
        $resultss = mysqli_query($dbconnection, $sqlss);
        while ($sss = $resultss->fetch_assoc()) {
            echo "<script>$('." . $sss['id'] . "').prop('disabled', true);</script>";
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
                   title: 'This student has a failed subject.',
                   icon: 'warning',
                 })";
            echo "</script>";
        }
    }
}

$sql = "SELECT subjectid FROM studentsubject WHERE studid = '$id'";
$result = mysqli_query($dbconnection, $sql);
while ($s = $result->fetch_assoc()) {
    echo "<script>$('." . $s['subjectid'] . "').prop('disabled', true);</script>";
}
?>

<?php include ('include/footer.php'); ?>