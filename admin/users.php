<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/settings_model.php');
    include('../database.php');
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $user = $settings->getuser($search);



    $sql = 'SELECT * FROM userdata ';

    $pdo_statement = $connection->prepare($sql);
    $pdo_statement->execute();
    $userdatas = $pdo_statement->fetchAll();
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>USERS ACCOUNT</small>
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
                <!-- <div class="form-inline form-padding">
                    <form action="users.php" method="post">
                        <input type="text" class="form-control" name="search" placeholder="Search by ID number">
                        <button type="submit" name="submitsearch" class="btn btn-success"><i class="fa fa-search"></i> Search</button>                                                                 
                    </form>
                </div> -->
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="userInformation">
                        <thead><tr>
                            <th class="text-center">ID Number</th>    
                            <th class="text-center">Name</th>    
                            <th class="text-center">Level</th>    
                            <th class="text-center">Password</th>    
                            <th class="text-center">Action</th>    
                        </tr></thead>
                        <tbody>
                        <?php foreach($userdatas as $number => $userdata): ?>
                            <tr>
                                <td class="text-center"><?php echo $userdata['username'];?></td>
                                <td class="text-center"><?php echo $userdata['lname'].', '.$userdata['fname']?></td>
                                <td class="text-center"><?php echo $userdata['level'];?></td>
                                <td class="text-center"><a href="settings.php?username=<?php echo $userdata['username'];?>">Update</a></td>
                                <td class="text-center"><a href="data/data_model.php?q=delete&table=userdata&id=<?php echo $userdata['id']?>" title="Remove" class="text-danger confirmation"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></a></td>
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