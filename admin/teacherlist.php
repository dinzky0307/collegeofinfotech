<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/teacher_model.php');
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $teacher = $teacher->getteacher($search);

?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>INSTRUCTOR'S LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Instructor's List
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding" style="float: right">
                    <form action="teacherlist.php" method="post">            
                        <a href="addteacher.php"><button type="button" class="btn btn-success" ><i class="fa fa-user"></i> Add Teacher</button></a>                    
                        <!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addteacher"><i class="fa fa-user"></i> Add Instructor</button> -->
                    </form>
                </div>
            </div>
        </div>
        <!--/.row -->
        <hr />   
        <div class="row">
            <div class="col-lg-12">
                <?php if(isset($_GET['r'])): ?>
                    <?php
                        $r = $_GET['r'];
                        if($r=='added'){
                            $class='success';   
                        }else if($r=='updated'){
                            $class='info';   
                        }else if($r=='deleted'){
                            $class='danger';   
                        }else if($r=='added an account'){
                            $class='success';   
                        }else{
                            $class='hide';
                        }
                    ?>
                    <div class="alert alert-<?php echo $class?> <?php echo $classs; ?>">
                        <strong>1 instructor successfully <?php echo $r; ?>!</strong>    
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="instructorInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Instructor ID</th>
                                <th class="text-center">Lastname</th>
                                <th class="text-center">Firstname</th>
                                <th class="text-center">Middlename</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($teachers as $number => $teacher): ?>            
                                <tr>
                                    <td><?php echo $number;?></td>
                                    <td class="text-center"><a href="edit.php?type=teacher&id=<?php echo $teacher['id']?>"><?php echo $teacher['teachid'];?></a></td>
                                    <td><?php echo $teacher['lname'];?></td>
                                    <td><?php echo $teacher['fname'];?></td>
                                    <td><?php echo $teacher['mname'];?></td>
                                    <td class="text-center"><?php echo $teacher['sex'];?></td>
                                    <td><?php echo $teacher['email'];?></td>
                                    <td class="text-center">
                                        <!-- <a href="data/settings_model.php?q=addaccount&level=teacher&id=<?php echo $teacher['id']?>" class="confirmacc"><i class="fa fa-user-plus fa-lg text-warning"></i></a> -->
                                        &nbsp;
                                        <a href="teacherload.php?id=<?php echo $teacher['id'];?>" title="View Subjects"><i class="fa fa-book fa-lg text-success"></i></a> 
                                        &nbsp;||
                                        <a href="data/data_model.php?q=delete&table=teacher&id=<?php echo $teacher['id']?>" title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></td>
                                        
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
        $('#instructorInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>