<?php
include('include/header.php');
include('include/sidebar.php');
include '../DatabaseService.php';
include 'connection.php';

use Database\DatabaseService;

$dbService = new DatabaseService;
$username = isset($_GET['username']) ? $_GET['username'] : $_SESSION['id'];

$ayResult = $dbconnection->query("SELECT * from ay");
$ay = [];
if ($ayResult) {
    while ($row = $ayResult->fetch_assoc()) {
        $ay[] = $row;
    }
}

if (isset($_POST['setacademicyear'])) {
    $academicyear = $_POST['academicyear'];
    $semester = $_POST['semester']; // Get the selected semester
    $currentDate = date('Y-m-d');

    // First, turn off all active academic years older than one year
    $sqlTurnOffOld = "UPDATE ay SET display = 0 WHERE display = 1 AND start_date < DATE_SUB('$currentDate', INTERVAL 1 YEAR)";
    $dbconnection->query($sqlTurnOffOld);

    // Then insert the new academic year and set it as active (display = 1) with the current start date
    $sql = "INSERT INTO ay (academic_year, semester, display, start_date) VALUES ('$academicyear', '$semester', 1, '$currentDate')";

    if ($dbconnection->query($sql) === TRUE) {
        $ayResult = $dbconnection->query("SELECT * FROM ay ORDER BY id DESC");
        $ay = [];
        if ($ayResult) {
            while ($row = $ayResult->fetch_assoc()) {
                $ay[] = $row;
            }
        }
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'Academic Year Successfully Created!',
           text: '',
           icon: 'success',
         })";
        echo "</script>";
    } else {
        echo "Error creating academic year: " . $dbconnection->error;
    }
}

if (isset($_POST['toggleDisplay'])) {
    $display = $_POST['toggleDisplay'];
    $year_id = $_POST['year_id'];

    $sql = "UPDATE ay SET display='$display' WHERE id='$year_id'";

    if ($dbconnection->query($sql) === TRUE) {
        // Update the display status in the $ay array
        foreach ($ay as &$year) {
            if ($year['id'] == $year_id) {
                $year['display'] = $display;
                break;
            }
        }

        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'Academic Year Display Updated!',
           text: '',
           icon: 'success',
         })";
        echo "</script>";
    } else {
        echo "Error updating academic year display: " . $dbconnection->error;
    }
}


if (isset($_POST['deleteYear'])) {
    $year_id = $_POST['year_id'];

    $sql = "DELETE FROM ay WHERE id='$year_id'";

    if ($dbconnection->query($sql) === TRUE) {
        // Remove the academic year from the $ay array
        foreach ($ay as $key => $year) {
            if ($year['id'] == $year_id) {
                unset($ay[$key]);
                break;
            }
        }

        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'Academic Year Deleted!',
           text: '',
           icon: 'success',
         })";
        echo "</script>";
    } else {
        echo "Error deleting academic year: " . $dbconnection->error;
    }
}

?>
<!-- ... -->
<style>
    /* ... */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    .toggle-input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: lightgray;
        transition: 0.4s;
        border-radius: 25px;
    }

    .slider:before {
        position: absolute;
        content: '';
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }

    input:checked + .slider:before {
        transform: translateX(25px);
    }

    .slider.round {
        border-radius: 26px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .slider.checked {
        background-color: #4CAF50;
    }

    .slider.checked:before {
        transform: translateX(26px);
    }

    .modal {
        top: 20% !important;
        transform: translateY(-17%) !important;
    }

</style>
<!-- ... -->

<div class="col-lg-12">
    <hr>
    <h2>Create New Year & Semester</h2>
    <br>
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#yearModal" style="background-color: green; max-width: 60px;">Create </button>
    <hr>
</div>

<div class="modal fade" id="yearModal" tabindex="-1" role="dialog" aria-labelledby="yearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" id="yearForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="yearModalLabel">Enter Academic Year</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <hr>
                        <hr>
                        <hr>
                        <label for="academicYearInput"><h2>Set Academic Year<h2></label>
                        <input type="text" name="academicyear" id="academicYearInput" class="form-control" placeholder="Enter academic year">
                    </div>
                    <hr>
                    <h2>Set Semester</h2>
                    <br>
                    <div class="form-group">
                        <label><input type="radio" name="semester" class="" value="First Semester"> First Semester</label>
                    </div>
                    <div class="form-group">
                        <label><input type="radio" name="semester" class="" value="Second Semester"> Second Semester</label>
                    </div>
                    <div class="form-group">
                        <label><input type="radio" name="semester" class="" value="Summer"> Summer</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="setacademicyear" id="saveButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#nextButton').click(function() {
            $('#nextButton').hide();
            $('#saveButton').show();
        });
    });
</script>

<div class="row">
    <div class="col-lg-12">
        <hr>
        <h2>Academic Years</h2>
        <?php if (!empty($ay)) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Semester</th> <!-- New column for displaying the semester -->
                        <!--<th>Status</th>!-->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ay as $year) { 
                                $academicYearVisible = isset($year['display']) && $year['display'] == 1;
                                $academicYearSet = isset($year['academic_year']) && isset($year['semester']);
                                $academic_year = $academicYearSet ? $year['academic_year'] : '';
                                $semester = $academicYearSet ? $year['semester'] : '';
                                ?>
                        <tr>
                            <td><?php echo $year['academic_year']; ?></td>
                            <td><?php echo $year['semester']; ?></td> <!-- Display the selected semester -->
                            <!--<td>
                            <?php if ($academicYearVisible && $academicYearSet) { ?>
                                    <span class="badge badge-success">Active</span>
                                <?php } else { ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php } ?>
                            </td>!-->
                            <td>
                            <form action="" method="post" style="display: inline;">
        <input type="hidden" name="year_id" value="<?php echo $year['id']; ?>">
        <input type="hidden" name="toggleDisplay" value="<?php echo isset($year['display']) && $year['display'] == 1 ? 0 : 1; ?>">
        <label class="switch">
            <input type="submit" class="toggle-input" />
            <span class="slider round <?php echo isset($year['display']) && $year['display'] == 1 ? 'checked' : ''; ?>"></span>
        </label>
    </form>
                                <!-- <form action="" method="post" style="display: inline;">
                                    <input type="hidden" name="year_id" value="<?php echo $year['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" name="deleteYear" onclick="return confirm('Are you sure you want to delete this academic year?');">Delete</button>
                                </form> -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No academic years created.</p>
        <?php } ?>
        <hr>
    </div>
</div>
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post" >
                    <hr>
                    <h2>Change Password</h2>
                    <br>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new" class="form-control" x-ref="new" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm" class="form-control" x-ref="confirm" required>
                    </div>
                    <button type="submit" name="updatePass" @click="validate" class="btn btn-success btn-lg" name="changePassword"> <i class="fa fa-check"></i> Save</button>
                    <hr>
                </form>  
             </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->

</div>


<?php 
    $conn = mysqli_connect("localhost", "root", "", "infotech");


    if (isset($_POST['updatePass'])) {
        $password = htmlspecialchars(stripslashes(trim($_POST['new'])));
        $re_pass = $_POST['confirm'];

        if ($password != $re_pass) {
            ?>
            <script type="text/javascript">
                alert("Password don't match")
            </script>
            <?php 
        }else{
            $admin = $_SESSION['user_id'];
            $pass_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->query("UPDATE userdata SET password = '$pass_hashed' WHERE   id = '$admin'");
            ?>
            <script type="text/javascript">
                alert("Password successfully updated")
                window.location.href = "settings.php"
            </script>
            <?php

        }


    }

?>

<script>
    window.PasswordHandler = () => {
        return {
            validate() {
                const newPass = this.$refs.new;
                const confirmPass = this.$refs.confirm

                if (newPass.value.length <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'New password is empty!'
                    })

                    return
                }
                
                if (newPass.value !== confirmPass.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Password type mismatched!'
                    })

                    return
                }

                this.$refs.passwordForm.submit()
            }
        }
    }
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
