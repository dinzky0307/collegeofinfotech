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
<div id="page-wrapper" x-data="PasswordHandler">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Settings <small>Change Password</small>
                </h1>
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
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
