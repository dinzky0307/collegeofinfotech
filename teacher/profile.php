<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/settings_model.php');
    include('../database.php');

    $username = isset($_GET['username']) ? $_GET['username'] : $_SESSION['id'];

    $sql = 'SELECT * FROM userdata ';
    $pdo_statement = $connection->prepare($sql);
    $pdo_statement->execute();
    $userdatas = $pdo_statement->fetchAll();

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
<div id="page-wrapper" x-data="PasswordHandler">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>PROFILE INFORMATION</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Profile
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
         <div class="row">
             <div class="col-lg-4">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>First Name</label>
                    <input type="text" class="form-control" name="fname" value="<?php echo $row['fname']; ?>" />
                    </div>
                </form>  
             </div>
             <div class="col-lg-4">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>Middle Name</label>
                    <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>" />
                    </div>
                </form>  
             </div>
             <div class="col-lg-4">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="lname" value="<?php echo $row['lname']; ?>" />
                    </div>
                </form>  
             </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>Instructor ID</label>
                        <input type="text" class="form-control" name="teachid" value="<?php echo $row['teachid']; ?>" />
                        </div>
                </form>
                </div>
                <div class="col-lg-4">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>Gender</label>
                        <input type="text" class="form-control" name="sex" value="<?php echo $row['sex']; ?>" />
                        </div>
                </form>  
                </div>
                <div class="col-lg-4">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>" />
                        </div>
                </form>    
             </div>
            </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-check" name="updateinfo"></i> Update Information</button>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');