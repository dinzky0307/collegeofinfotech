<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/student_model.php');
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $student = $student->getstudent($search);

    $studid = "";
    $lname = "";
    $fname = "";
    $mname = "";
    $year = "";
    $section = "";
    $semester = "";

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
            $year = $_POST['year'];
            $section = $_POST['section'];
            $semester = $_POST['semester'];
            // Check existing ID
            $existStatement = $connection->prepare("SELECT studid FROM student WHERE studid = '{$_POST['studid']}'");
            $existStatement->execute();
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
            $sql = "INSERT INTO student (studid, fname, lname, mname, year, section, semester)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $connection->prepare($sql)->execute([
                $_POST['studid'], 
                $_POST['lname'], 
                $_POST['fname'],
                $_POST['mname'],
                $_POST['year'],
                $_POST['section'],
                $_POST['semester']
            ]);
            $username = $_POST['studid'];                
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $password = $username;

            $q_create_user_student = "insert into userdata values(null,'$username','$password','$fname','$lname','student')";
            mysql_query($q_create_user_student);
            
            $studid = "";
            $lname = "";
            $fname = "";
            $mname = "";
            $year = "";
            $section = "";
            $semester = "";

            echo "<script type='text/javascript'>";
            echo "Swal.fire({
               title: 'Student successfully added!',
               icon: 'success',
             })";
            echo "</script>";
        }
        }
        
       
    }
?>
<?php 
include '../DatabaseService.php';

use Database\DatabaseService;
$dbService = new DatabaseService;

$ay = $dbService->fetchRow("SELECT * from ay");
?>
<style>
.modal {
  
}
    .modal-content{
    margin-top:20% !important;
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
            <div class="col-lg-12">
            <div class="row">
            <div class="col-lg-12">
                    <div class="modal-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" name="studid" placeholder="student ID" value="<?php echo $studid ?>" required/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="lname" placeholder="Lastname" value="<?php echo $lname ?>" required/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="fname" placeholder="Firstname" value="<?php echo $fname ?>" required/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="mname" placeholder="Middlename" value="<?php echo $mname ?>" required/>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="year" placeholder="Year level" required>
                                <option value="">Select Year level...</option>
                                    <option value="1" <?php echo $year == "1" ? "selected" : "" ?> >1</option>
                                    <option value="2" <?php echo $year == "2" ? "selected" : "" ?> >2</option>
                                    <option value="3" <?php echo $year == "3" ? "selected" : "" ?> >3</option>
                                    <option value="4" <?php echo $year == "4" ? "selected" : "" ?> >4</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="section" placeholder="Section" required>
                                <option value="">Select Section...</option>
                                    <option value="North" <?php echo $section == "North" ? "selected" : "" ?> >North</option>
                                    <option value="South" <?php echo $section == "South" ? "selected" : "" ?> >South</option>
                                    <option value="East" <?php echo $section == "East" ? "selected" : "" ?> >East</option>
                                    <option value="West" <?php echo $section == "West" ? "selected" : "" ?> >West</option>
                                    <option value="North East" <?php echo $section == "North East" ? "selected" : "" ?> >North East</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="semester" placeholder="Semester" required>
                                    <option><?php echo $ay['semester']; ?></option>
                                    <!-- <option value="Second Semester" <?php echo $semester == "Second Semester" ? "selected" : "" ?> >Second Semester</option>
                                    <option value="Summer" <?php echo $semester == "Summer" ? "selected" : "" ?> >Summer</option> -->
                                </select>
                            </div>
                            <input type="hidden" class="form-control" name="addStudent" value="addStudent" />
                    </div>
                    <div class="modal-footer">
                        <a href="studentlist.php"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></a>   

                        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    </div>
    <!-- /.container-fluid -->

</div>
<script>
    $(document).ready( function () {
        $.noConflict();
        $('#studentInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>