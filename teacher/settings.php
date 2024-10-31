<?php
    include('include/header.php');
    include('include/sidebar.php');
    include '../DatabaseService.php';
    include 'connection.php';

    use Database\DatabaseService;

    $username = isset($_GET['username']) ? $_GET['username'] : $_SESSION['id'];
 
    // if (isset($_POST['new'])) {
    //     $dbService = new DatabaseService;
    //     $dbService->updatePassword([
    //         'id' => $_SESSION['user_id'],
    //         'password_confirmation' => $_POST['confirm'],
    //     ]);

    //     echo "<script type='text/javascript'>";
    //     echo "Swal.fire({
    //         title: 'Password changed!',
    //         icon: 'success',
    //     })";
    //     echo "</script>";
    // }
?>
<style>

/* Ensure that the sidebar and content align responsively */
.wrapper {
    display: flex;
}

.main-sidebar {
    flex: 0 0 250px; /* Set a fixed width for the sidebar */
}

.content-wrapper {
    flex: 1;
    padding: 20px;
}

@media (max-width: 768px) {
    .main-sidebar {
        display: none; /* Hide sidebar on smaller screens */
    }

    .content-wrapper {
        padding: 10px; /* Adjust padding for smaller screens */
    }
}

</style> 
<div class="wrapper">
    <!-- Sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">   
        
            
            <ul class="nav navbar-nav side-nav">               
               
                <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li><a href="subject.php"><i class="fa fa-book"></i> <span>My Subjects</span></a></li>
                <li><a href="list.php"><i class="fa fa-envelope"></i> <span>Consultation</span></a></li>
                <li><a href="settings.php"><i class="fa fa-gear"></i> <span>Change Password</span></a></li>
                <li><a href="../logout.php"><i class="fa fa-power-off"></i> <span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <!-- Page Heading -->
              

                <!-- Panels Section -->
                <div id="page-wrapper" x-data="PasswordHandler">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
             
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Change Password
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
         <div class="row">
            <div class="col-lg-12">
                <form action="" method="post" x-ref="passwordForm">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new" class="form-control" x-ref="new">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm" class="form-control" x-ref="confirm">
                    </div>
                    <button type="submit" name="updatePass" @click="validate" class="btn btn-success btn-lg" name="changePassword">Update Password</button>
                </form>  
             </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->
</div>
                <!-- /.row -->
            </div>
            <br><br> <br><br>  <br><br>
            <!-- /.container-fluid -->
             <footer class="container-fluid">
        <?php include 'include/footer.php'; ?>
      </footer>
        </section>
    </div>
      
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
            $teacher = $_SESSION['user_id'];
            $pass_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->query("UPDATE userdata SET password = '$pass_hashed' WHERE   id = '$teacher'");
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