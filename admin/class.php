<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/class_model.php');
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $class = $class->getclass($search);
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>CLASS INFORMATION</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Class
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding" style="float: right;">
                    <form action="class.php" method="post">                              
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addclass">Add Class</button>
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
                            $classs='success';   
                        }else if($r=='updated'){
                            $classs='info';   
                        }else if($r=='deleted'){
                            $classs='danger';   
                        }else{
                            $classs='hide';
                        }
                    ?>
                    <div class="alert alert-<?php echo $classs?> <?php echo $classs; ?>">
                        <strong>Class info successfully <?php echo $r; ?>!</strong>    
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="classInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Subject Description</th>
                                <th class="text-center">Class Name</th>
                                <th class="text-center">Semester</th>
                                <th class="text-center">S.Y.</th>
                                <th class="text-center">Instructor</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($classs as $number => $class): ?>
                                <tr>
                                    <td><?php echo $number;?></td>
                                    <td><?php echo $class['subject'];?></td>
                                    <td><?php echo $class['description'];?></td>
                                    <td class="text-center"><?php echo $class['course'].'-'.$class['year'].''.$class['section'];?></td>
                                    <td class="text-center"><?php echo $class['sem'];?></td>                                
                                    <td class="text-center"><?php echo $class['SY'];?></td>                                
                                    <td class="text-center"><a href="classteacher.php?classid=<?php echo $class['id'];?>&teacherid=<?php echo $class['teacher'];?>" title="update teacher">View</a></td>
                                    <td class="text-center"><a href="classstudent.php?classid=<?php echo $class['id'];?>" title="update students" title="add student">View</a></td>
                                    <td class="text-center">                                                                               
                                        <a href="edit.php?type=class&id=<?php echo $class['id']?>" title="Update Class"><i class="fa fa-edit fa-lg text-primary"></i></a>
                                        <a href="data/data_model.php?q=delete&table=class&id=<?php echo $class['id']?>" title="Remove Class"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></td>
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
        $('#classInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>