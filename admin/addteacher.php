<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/teacher_model.php');

$teacher = new Datateacher($connection);
$search = isset($_POST['search']) ? $_POST['search'] : null;
$teacher = $teacher->getteacher($search);

$studid = "";
$lname = "";
$fname = "";
$mname = "";
$year = "";
$section = "";
$semester = "";
$ay = "";

if (isset($_POST['addTeacher'])) {
    $teachid = $_POST['teachid'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $sex = $_POST['sex'];
    $email = $_POST['email'];
    $level = "teacher";
    // Check if teachid already exists
    $teachIdExistStatement = $connection->prepare("SELECT teachid FROM teacher WHERE teachid = ?");
    $teachIdExistStatement->execute([$teachid]);
    $teachIdExists = $teachIdExistStatement->fetch(PDO::FETCH_ASSOC);

    // Check if email already exists
    $emailExistStatement = $connection->prepare("SELECT email FROM teacher WHERE email = ?");
    $emailExistStatement->execute([$email]);
    $emailExists = $emailExistStatement->fetch(PDO::FETCH_ASSOC);

    if ($teachIdExists) {
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: '{$teachid} ID already exists!',
           icon: 'error',
         })";
        echo "</script>";
    } elseif ($emailExists) {
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: '{$email} email already exists!',
           icon: 'error',
         })";
        echo "</script>";
    } else {
        $sql = "INSERT INTO teacher (teachid, fname, lname, mname, sex, email) VALUES (?, ?, ?, ?, ?, ?)";
        $connection->prepare($sql)->execute([$teachid, $fname, $lname, $mname, $sex, $email]);

        $username = $teachid;
        $password = password_hash($username, PASSWORD_DEFAULT);

        $q_create_user_teacher = "INSERT INTO userdata (username, password, fname, lname, level) VALUES (?, ?, ?, ?, ?)";
        $userInsertStatement = $connection->prepare($q_create_user_teacher);
        $userInsertStatement->execute([$username, $password, $fname, $lname, $level]);

        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'Instructor successfully added!',
           icon: 'success',
         })";
        echo "</script>";
    }
}
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
</style>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>ADD TEACHER</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="teacherlist.php">Teacher List</a>
                    </li>
                    <li class="active">
                        Add Teacher
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <hr />
        <div class="row">
            <div class="col-lg-12">
                <?php if (isset($_GET['r'])): ?>
                    <?php
                    $r = $_GET['r'];
                    if ($r == 'added') {
                        $class = 'success';
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
        <div class="row print-container">
            <!-- <div class="col-lg-12">
            <div class="row"> -->
            <div class="col-md-6">
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="lname" placeholder="Lastname" required style="height:40px"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="fname" placeholder="Firstname" required style="height:40px"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="mname" placeholder="Middlename" required style="height:40px"/>
                        </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="teachid" id="teachid" placeholder="Teacher ID"
                            required style="height:40px"/>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="sex" placeholder="Gender" required style="height:40px">
                            <option value="">Select Gender...</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email" required style="height:40px"/>
                    </div>

                    <input type="hidden" class="form-control" name="addTeacher" value="addTeacher" />
                </div>
                <div class="modal-footer">
                    <a href="teacherlist.php"><button type="button" class="btn btn-default"
                            data-dismiss="modal" style="font-size: 12px">Close</button></a>

                    <button type="submit" name="addTeacher" class="btn btn-success" style="font-size: 12px">  Save  <i class="fa fa-plu"></i>
                    </button></a>
                </div>
            </div></form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    // Get the input element
    var studidInput = document.getElementById("teachid");

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
</script> -->

<script>
    // Function to capitalize the first letter of a string
    function capitalizeFirstLetter(input) {
        return input.charAt(0).toUpperCase() + input.slice(1);
    }

    // Attach an event listener to the input fields
    document.addEventListener('DOMContentLoaded', function () {
        const lastNameInput = document.querySelector('input[name="lname"]');
        const firstNameInput = document.querySelector('input[name="fname"]');
        const middleNameInput = document.querySelector('input[name="mname"]');

        // Listen for input events
        lastNameInput.addEventListener('input', function () {
            this.value = capitalizeFirstLetter(this.value);
        });

        firstNameInput.addEventListener('input', function () {
            this.value = capitalizeFirstLetter(this.value);
        });

        middleNameInput.addEventListener('input', function () {
            this.value = capitalizeFirstLetter(this.value);
        });
    });
</script>
<script>
    $(document).ready(function () {
        $.noConflict();
        $('#teacherInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>
