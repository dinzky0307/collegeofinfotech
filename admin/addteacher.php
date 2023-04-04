<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/teacher_model.php');
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $teacher = $teacher->getteacher($search);
    
    if (isset($_POST['addTeacher'])) {
        $sql = "INSERT INTO teacher (teachid, fname, lname, mname, sex, email) VALUES (?, ?, ?, ?, ?, ?)";
        $connection->prepare($sql)->execute([
            $_POST['teachid'], 
            $_POST['fname'], 
            $_POST['lname'],
            $_POST['mname'],
            $_POST['sex'],
            $_POST['email'],
    
        ]);

        $username = $_POST['teachid'];                
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $password = $username;

        $q_create_user_teacher = "insert into userdata values(null,'$username','$password','$fname','$lname','teacher')";
        mysql_query($q_create_user_teacher);
    
        echo "<script type='text/javascript'>";
        echo "Swal.fire({
            title: 'add new teacher $name',
            icon: 'success',
            })";
        echo "</script>";
    }    
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
                                <input type="text" class="form-control" name="teachid" placeholder="Teacher ID" required/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="lname" placeholder="Lastname" required/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="fname" placeholder="Firstname" required/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="mname" placeholder="Middlename" required/>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="sex" placeholder="Gender" required>
                                    <option value="">Select Gender...</option>
                                    <option >Male</option>
                                    <option >Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email" required/>
                            </div>
                            
                            <input type="hidden" class="form-control" name="addTeacher" value="addTeacher" />
                    </div>
                    <div class="modal-footer">
                        <a href="teacherlist.php"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></a>   

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
        $('#teacherInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>