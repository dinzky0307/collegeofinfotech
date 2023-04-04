<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/data_model.php');
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $subject = $data->getsubject($search);
?>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>SUBJECT'S LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Subject
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding" style="float: right;">
                    <form action="subject.php" method="post">                             
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addsubject"><i class="fa fa-book"></i> Add Subject</button>
                        
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
                        }else{
                            $class='hide';
                        }
                    ?>
                    <div class="alert alert-<?php echo $class?> <?php echo $class; ?>">
                        <strong>Subject successfully <?php echo $r; ?>!</strong>    
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="subjectInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Descriptive  Title</th>
                                <th class="text-center">Lec Unit</th>
                                <th class="text-center">Lab Unit</th>
                                <th class="text-center">Total Units</th>
                                <th class="text-center">Pre-requisites/s</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($subjects as $number => $subject): ?>                            
                                <tr>
                                    <td><?php echo $number + 1;?></td>
                                    <td class="text-center"><a href="edit.php?type=subject&id=<?php echo $subject['id']?>"><?php echo $subject['code'];?></a></td>
                                    <td><?php echo $subject['title'];?></td>
                                    <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                    <td class="text-center"><?php echo $subject['labunit'];?></td>
                                    <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                    <td class="text-center"><?php echo $subject['pre'];?></td>
                                    <td class="text-center"><a href="data/data_model.php?q=delete&table=subject&id=<?php echo $subject['id']?>"title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></td>
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
        $('#subjectInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>