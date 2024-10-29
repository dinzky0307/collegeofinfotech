<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/settings_model.php');
    include('../database.php');
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $user = $settings->getuser($search);



    $sql = 'SELECT * FROM ms_account ';

    $pdo_statement = $connection->prepare($sql);
    $pdo_statement->execute();
    $userdatas = $pdo_statement->fetchAll();

    function deleteUser($userId, $connection) {
        // Write your code to delete the subject with the given subject ID
        $sql = "DELETE FROM ms_account WHERE ms_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$userId]);
    }
    
        // Check if the delete button is clicked
    if (isset($_POST['deleteUser']) && isset($_POST['userId'])) {
        $userId = $_POST['userId'];
        $delete = deleteUser($userId, $connection);
    
        if($delete){
            // Redirect to the same page after the deletion
            echo "<script type='text/javascript'>window.location.href = 'users.php?r=deleted';</script>";
            exit(); // Make sure to exit after redirecting to prevent further code execution
        }else{
    
        }
    
    }
?>
<style>
    /* Custom styling for the delete button */
    .delete-button {
    border: none;
    background: none;
    padding: 0;
    color: #f00; /* Change the color as per your preference */
    cursor: pointer;
    outline: none; /* Remove outline on focus */
    }
</style>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Microsoft 365 Account</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Users
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <?php if(isset($_GET['r']) && $_GET['r']=='deleted'): ?>
                    <div class="alert alert-danger">
                        <strong>account successfully removed!</strong>
                    </div>
                <?php endif; ?>
               
            </div>
        </div>
        <hr />
         <form action="import.php" method="post" enctype="multipart/form-data" style="display: flex; align-items: center; margin-left: auto;">
                        <label for="file" style="margin-right: 10px;">Choose Excel file:</label>
                        <input type="file" name="file" id="file" accept=".xls, .xlsx" style="margin-right: 10px;" onchange="checkFileSelected()">
                        <button type="submit" name="import" class="btn btn-info btn-sm btn-flat" id="importButton" disabled>Import</button>
                    </form>
                    <script>
                        function checkFileSelected() {
                            var fileInput = document.getElementById('file');
                            var importButton = document.getElementById('importButton');
                            importButton.disabled = fileInput.files.length === 0;
                        }
                    </script>
            <br>
                                      
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="userInformation">
                        <thead><tr>
                            <th class="text-center">Firstname</th>    
                            <th class="text-center">LastName</th>    
                            <th class="text-center">Email</th>    
                            
                        </tr></thead>
                        <tbody>
                        <?php foreach($userdatas as $number => $userdata): ?>
                            <tr>
                                <td class="text-center"><?php echo $userdata['firstname'];?></td>
                                <td class="text-center"><?php echo $userdata['lastname'];?></td>
                                <td class="text-center"><?php echo $userdata['username'];?></td>
                              
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

</div>
<script>
    $(document).ready( function () {
        $.noConflict();
        $('#userInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
