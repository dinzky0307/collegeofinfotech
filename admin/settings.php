<?php
    include('include/header.php');
    include('include/sidebar.php');
    include '../DatabaseService.php';
    include 'connection.php';

    use Database\DatabaseService;

    $dbService = new DatabaseService;
    $username = isset($_GET['username']) ? $_GET['username'] : $_SESSION['id'];

    $ay = $dbService->fetchRow("SELECT * from ay");

    if (isset($_POST['new'])) {
        $dbService = new DatabaseService;
        $dbService->updatePassword([
            'id' => $_SESSION['user_id'],
            'password_confirmation' => $_POST['confirm'],
        ]);

        echo "<script type='text/javascript'>";
        echo "Swal.fire({
            title: 'Password changed!',
            icon: 'success',
        })";
        echo "</script>";
    }
?>

<?php 


if (isset($_POST['setacademicyear'])) {

    $academicyear = $_POST['academicyear'];
    $sql = "UPDATE ay SET academic_year='$academicyear'";

    if ($dbconnection->query($sql) === TRUE) {
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'Academic Year Successfully Set!',
           text: '',
           icon: 'success',
         })";
        echo "</script>";
    } else {
      echo "Error updating record: " . $dbconnection->error;
    }
}


if (isset($_POST['setsemester'])) {

    $semester = $_POST['semester'];
    $sql = "UPDATE ay SET semester='$semester'";

    if ($dbconnection->query($sql) === TRUE) {
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
           title: 'Semester Successfully Set!',
           text: '',
           icon: 'success',
         })";
        echo "</script>";
    } else {
      echo "Error updating record: " . $dbconnection->error;
    }
}

?>


<div id="page-wrapper" x-data="PasswordHandler">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Settings
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Settings
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post">
                    <hr>
                    <h2>Set Academic Year</h2>
                    <br>
                    <div class="form-group">
                        <h2>
                        <input type="text" name="academicyear" class="form-control" style="font-size: 20px;" value="<?php echo $ay['academic_year']; ?>">
                        </h2>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" name="setacademicyear"><i class="fa fa-check"></i> Save</button>
                    <hr>
                </form>  
             </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post">
                    <hr>
                    <h2>Set Semester</h2>
                    <br>
                    <div class="form-group">
                        <h4><input type="radio" name="semester" class="" value="First Semester"> First Semester</h4>
                    </div>
                    <div class="form-group">
                        <h4><input type="radio" name="semester" class="" value="Second Semester"> Second Semester</h4>
                    </div>
                    <div class="form-group">
                        <h4><input type="radio" name="semester" class="" value="Summer"> Summer</h4>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" name="setsemester"> <i class="fa fa-check"></i> Save</button>
                    <hr>
                </form>  
             </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="" method="post" x-ref="passwordForm">
                    <hr>
                    <h2>Change Password</h2>
                    <br>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new" class="form-control" x-ref="new">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm" class="form-control" x-ref="confirm">
                    </div>
                    <button type="button" @click="validate" class="btn btn-success btn-lg" name="changePassword"> <i class="fa fa-check"></i> Save</button>
                    <hr>
                </form>  
             </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->

</div>

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