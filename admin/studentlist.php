<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/student_model.php');

    $search = isset($_POST['search']) ? $_POST['search']: null;
    $student = $student->getstudent($search);

?>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>STUDENT'S LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Student's List
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding" style="float: right;">
                    <form action="studentlist.php" method="GET">                                        
                        <a href="addstudent.php"><button type="button" class="btn btn-success" ><i class="fa fa-user"></i> Add Student</button></a>
                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addstudent"><i class="fa fa-user"></i> Add Student</button> -->
                        <!-- <button onclick="window.print();" type="button" class="btn btn-primary">
                            Print
                        </button> -->
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
                        }else if($r=='has already an account'){
                            $class='info';   
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
        <style>
            @media print{
                /* hide every other element */
                body* {
                    visibility: hidden;
                }
                /* then display print container */
                .print-container, .print-container *{
                    visibility: visible;
                }
                .print-container {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                }
            }
        </style>
        <div class="row print-container">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="studentInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">ID number</th>
                                <th class="text-center">Fullname</th>
                                <th class="text-center">Year and Section</th>
                                <th class="text-center">Semester</th>
                                <th class="text-center">Subjects</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $number => $student): ?>
                                <tr>
                                    <td><?php echo $number + 1;?></td>
                                    <td class="text-center"><a href="edit.php?type=student&id=<?php echo $student['id']?>"><?php echo $student['studid'];?></a></td>
                                    <td><?php echo $student['fname'];?>, <?php echo $student['lname'];?> <?php echo $student['mname'];?></td>
                                    <td class="text-center"><?php echo $student['year'];?> -<?php echo $student['section'];?></td>
                                    <td class="text-center"><?php echo $student['semester'];?></td>
                                    <td class="text-center"><a href="viewsubjects.php?type=student&id=<?php echo $student['id']?>">Enrolled Subjects | 
                                    <?php
                                    $result=mysql_query("SELECT COUNT(*) AS studid FROM studentsubject WHERE semester='$student[semester]'AND year='$student[year]' AND studid='$student[id]'");
                                    $d=mysql_fetch_assoc($result);
                                    echo $d['studid'];
                                    ?>
                                    </a></td>
                                    <td class="text-center">
                                        <a href="data/data_model.php?q=delete&table=student&id=<?php echo $student['id']?>" title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a>
                                    </td>
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
        $('#studentInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>
 
