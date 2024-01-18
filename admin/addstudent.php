<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php'); // Include the database connection code

include('data/student_model.php');

$student = new Datastudent($connection); // Create an instance of the Datastudent class and pass the connection as a parameter

if (isset($_GET['q'])) {
    $studentModel->$_GET['q']();
}

$search = isset($_POST['search']) ? $_POST['search'] : null;
$student = $student->getstudent($search, null, null, null, $connection);

$studid = "";
$lname = "";
$fname = "";
$mname = "";
$email = "";
$year = "";
$section = "";
$semester = "";
$ay = "";

if (isset($_POST['addStudent'])) {
    if (strlen($_POST['studid']) > 9 || strlen($_POST['studid']) < 9) {
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'ID must not be less or greater than 9 characters',
           icon: 'error',
         })";
        echo "</script>";
    } else {
        $studid = $_POST['studid'];
        $lname = $_POST['lname'];
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
		$email = $_POST['email'];
        $year = $_POST['year'];
        $section = $_POST['section'];
        $semester = $_POST['semester'];
        $ay = $_POST['sy'];


// Check existing ID and academic year
$existStatement = $connection->prepare("SELECT studid FROM student WHERE studid = ? AND ay = ? AND semester = ?");
$existStatement->execute([$_POST['studid'], $_POST['sy'], $_POST['semester']]);
$existStatement->setFetchMode(PDO::FETCH_ASSOC);
$exists = $existStatement->fetch();

        if ($exists) {
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
               title: '{$_POST['studid']} ID already exists!',
               icon: 'error',
             })";
            echo "</script>";
        } else {
            $sql = "INSERT INTO student (studid, fname, lname, mname,email, year, section, semester,ay)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $connection->prepare($sql)->execute([
                $_POST['studid'],
                $_POST['lname'],
                $_POST['fname'],
                $_POST['mname'],
                $_POST['email'],
                $_POST['year'],
                $_POST['section'],
                $_POST['semester'],
                $_POST['sy']
            ]);
            $username = $_POST['studid'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $password = password_hash($username, PASSWORD_DEFAULT);

            $q_create_user_student = "insert into userdata values(null,'$username','$password','$fname','$lname','student')";
            $save = mysql_query($q_create_user_student);

            if ($save) {
                echo "<script type='text/javascript'>";
                echo "Swal.fire({
                   title: 'Student successfully added!',
                   icon: 'success',
                 })";
                echo "</script>";
                // Redirect with parameters
                echo "<script type='text/javascript'>window.location.href = 'enrollsubject.php?id=$studid&y=$year&s=$semester';</script>";
                exit;
            } else {
                echo "<script type='text/javascript'>";
                echo "Swal.fire({
                   title: 'Student not added!',
                   icon: 'warning',
                 })";
                echo "</script>";
            }
        }
    }
}
$activeAcademicYear = ""; // variable to store active academic year
$activeSemester = ""; // variable to store active semester

// Query database to fetch active academic year and semester
$query = "SELECT academic_year, semester FROM ay WHERE display = 1";
$result = mysql_query($query);
if ($row = mysql_fetch_assoc($result)) {
    $activeAcademicYear = $row['academic_year'];
    $activeSemester = $row['semester'];
}

?>

<?php
include '../DatabaseService.php';

use Database\DatabaseService;

$dbService = new DatabaseService;

$ay = $dbService->fetchRow("SELECT * from ay");
?>
<style>
    .form-control {
        height: 45px;
        font-size: 17px;
    }
    
    .modal-content {
        margin-top: 20% !important;
        position: absolute;
        float: left;
        left: 60%;
        top: 45%;
        transform: translate(-50%, -50%);
    }

    .modal-dialog {
        max-width: 1090px;
        margin: 2rem auto;
    }
    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .form-control {
        border: 1px solid lightblue;
        border-bottom: 2px solid #ccc;
        font-size: 16px;
        padding: 10px;
        transition: all 0.3s;
    }

    .form-label {
        position: absolute;
        top: 0;
        left: 0;
        pointer-events: none;
        transition: all 0.3s;
        color: #333;
    }

    .form-control:focus {
        outline: none;
        border-bottom-color: #3c50eb; /* Change the border color on focus */
    }

    .form-control:focus + .form-label,
    .form-control:not(:placeholder-shown) + .form-label {
        top: -20px; /* Move the label up */
        font-size: 12px; /* Reduce the font size when moved up */
        color: #3c50eb; /* Change label color on focus and when not empty */
    }
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>ADD STUDENT</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="studentlist.php">Student List</a>
                    </li>
                    <li class="active">
                        Add Student
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <hr />

        <div class="row">
            <div class="col-lg-12">
                <?php if(isset($_GET['r'])): ?>
                    <?php
                        $r = $_GET['r'];
                        if($r=='added'){
                            $class='success';     
                        }else{
                            $class='hide';
                        }
                    ?>
                    <div class="alert alert-<?php echo $class?> <?php echo $classs; ?>">
                        <strong>1 student successfully <?php echo $r; ?>!</strong>    
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row print-container">
            <div class="col-md-6">
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="studid" id="studid" placeholder="Student ID" value="<?php echo $studid ?>" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lname" placeholder="Lastname" value="<?php echo $lname ?>" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="fname" placeholder="Firstname" value="<?php echo $fname ?>" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="mname" placeholder="Middlename" value="<?php echo $mname ?>" />
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $email ?>" required/>
                        </div>
                    </div>
            </div>

            <div class="col-md-6">
                <div class="modal-body">
                    <div class="form-group" style="height:44px">
                        <select class="form-control" name="year" placeholder="Year level" required style="height: 45px">
                            <option value="">Select Year level...</option>
                            <option value="1" <?php echo $year == "1" ? "selected" : "" ?> >1</option>
                            <option value="2" <?php echo $year == "2" ? "selected" : "" ?> >2</option>
                            <option value="3" <?php echo $year == "3" ? "selected" : "" ?> >3</option>
                            <option value="4" <?php echo $year == "4" ? "selected" : "" ?> >4</option>
                        </select>
                    </div>
                    <div class="form-group" style="height:44px">
                        <select class="form-control" name="section" placeholder="Section" required style="height: 45px">
                            <option value="">Select Section...</option>
                            <option value="North" <?php echo $section == "North" ? "selected" : "" ?> >North</option>
                            <option value="South" <?php echo $section == "South" ? "selected" : "" ?> >South</option>
                            <option value="East" <?php echo $section == "East" ? "selected" : "" ?> >East</option>
                            <option value="West" <?php echo $section == "West" ? "selected" : "" ?> >West</option>
                            <option value="South East" <?php echo $section == "South East" ? "selected" : "" ?> >South East</option>
				<option value="South West" <?php echo $section == "South West" ? "selected" : "" ?> >South West</option>
                            <option value="North East" <?php echo $section == "North East" ? "selected" : "" ?> >North East</option>
				<option value="North West" <?php echo $section == "North West" ? "selected" : "" ?> >North West</option>
                        </select>
                    </div>
                    <div class="form-group" style="height:44px">
                        <select class="form-control" name="semester" placeholder="Semester" required style="height: 45px">
                            <option value="<?php echo $activeSemester; ?>"><?php echo $activeSemester; ?></option>
                            <!-- <option value="Second Semester" <?php echo $semester == "Second Semester" ? "selected" : "" ?> >Second Semester</option>
                            <option value="Summer" <?php echo $semester == "Summer" ? "selected" : "" ?> >Summer</option> -->
                        </select>
                    </div>
                    <div class="form-group" style="height:44px">
                        <select name="sy" class="form-control" required style="height: 45px">
                            <option value="<?php echo $row['academic_year']; ?>"<?php echo $activeAcademicYear == $row['academic_year'] ? "selected" : ""; ?>>Academic Year : <?php echo $row['academic_year']; ?></option>
                        </select>
                    </div>
                    <input type="hidden" class="form-control" name="addStudent" value="addStudent" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="modal-footer">
                    <a href="studentlist.php"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></a>
                    <!-- <input type="hidden" name="year" value="<?php echo $year; ?>" />
                    <input type="hidden" name="semester" value="<?php echo $semester; ?>" /> -->
                    <button type="submit" class="btn btn-primary">Next <i class="fa fa-arrow-right"></i></button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get the input element
    var studidInput = document.getElementById("studid");

    // Add an input event listener to the input field
    studidInput.addEventListener("input", function () {
        // Get the current input value
        var inputValue = this.value;

        // Remove any non-numeric characters from the input
        var numericValue = inputValue.replace(/\D/g, '');

        // Check if the numeric value has a length of 4
        if (numericValue.length >= 4) {
            // Format the value as "yyyy-xxxx"
            var formattedValue = numericValue.slice(0, 4) + "-" + numericValue.slice(4, 8);
            this.value = formattedValue;
        }
    });
});

</script>

<script>
// Function to capitalize all letters in a string
function capitalizeAllLetters(input) {
    return input.toUpperCase();
}

// Attach an event listener to the input fields
document.addEventListener('DOMContentLoaded', function () {
    const lastNameInput = document.querySelector('input[name="lname"]');
    const firstNameInput = document.querySelector('input[name="fname"]');
    const middleNameInput = document.querySelector('input[name="mname"]');

    // Listen for input events
    lastNameInput.addEventListener('input', function () {
        this.value = capitalizeAllLetters(this.value);
    });

    firstNameInput.addEventListener('input', function () {
        this.value = capitalizeAllLetters(this.value);
    });

    middleNameInput.addEventListener('input', function () {
        this.value = capitalizeAllLetters(this.value);
    });
});
</script>
<script>
    $(document).ready(function () {
        $.noConflict();
        $('#studentInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>
