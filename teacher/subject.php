<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/subject_model.php');
    
    
    $firstsem = $subject->getsubject('First Semester',$id);    
    $secondsem = $subject->getsubject('Second Semester',$id);
    $summer = $subject->getsubject('Summer',$id);   
    
    
    // echo $id;
?>
<?php
    // include '../DatabaseService.php';
    // use Database\DatabaseService;
    // $dbService = new DatabaseService;

    // $sem = $dbService->fetchRow("SELECT * from ay");

    // if($sem == $firstsem){

    // }else if($sem == $secondsem){

    // }else if($sem == $summer){

    // }


?>
    <style>
        /* Add this CSS to your stylesheet */
        .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
            /* background-color: #337ab7; Change this color to the desired fill color */
            color: red; /* Change this color to the desired text color */
        }
    </style>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>MY SUBJECTS</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        My Subjects
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
         <div class="row">
            <div class="col-lg-12">
                <!-- <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#data1" role="tab" data-toggle="tab">First Semester</a></li>
                    <li><a href="#data2" role="tab" data-toggle="tab">Second Semester</a></li>
                    <li><a href="#data3" role="tab" data-toggle="tab">Summer</a></li>
                </ul> -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="<?php echo isset($_GET['page']) ? 'active' : ''; ?>" style="border: 1px solid black; border-radius: 7px; margin-right: 10px"><a href="#data1" role="tab" data-toggle="tab">First Semester</a></li>
                    <li class="<?php echo isset($_GET['page']) ? '' : ''; ?>" style="border: 1px solid black; border-radius: 7px; margin-right: 10px"><a href="#data2" role="tab" data-toggle="tab">Second Semester</a></li>
                    <li class="<?php echo isset($_GET['page']) ? '' : ''; ?>" style="border: 1px solid black; border-radius: 7px; margin-right: 10px"><a href="#data3" role="tab" data-toggle="tab">Summer</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- <div class="tab-pane active" id="data1"> -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? 'active' : ''; ?>" id="data1">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="">Subject</th>
                                        <th class="text-center">Course</th>
                                        <th class="text-center">Year</th>
                                        <th class="text-center">Section</th>
                                        <th class="text-center">Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($firstsem)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td class=""><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td class="text-center"><?php echo $row['course']; ?></td>
                                            <td class="text-center"><?php echo $row['year']; ?></td>
                                            <td class="text-center"><?php echo $row['section']; ?></td>
                                            <td class="text-center"><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                        </tr>
                                    <?php $c++; ?>
                                    <?php endwhile; ?>
                                    <?php if(mysql_num_rows($firstsem) < 1): ?>
                                        <tr><td colspan="6" class="text-center text-danger"><strong>*** EMPTY ***</strong></td></tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="tab-pane" id="data2"> -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? '' : ''; ?>" id="data2">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="">Subject</th>
                                        <th class="text-center">Course</th>
                                        <th class="text-center">Year</th>
                                        <th class="text-center">Section</th>
                                        <th class="text-center">Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($secondsem)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td class=""><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td class="text-center"><?php echo $row['course']; ?></td>
                                            <td class="text-center"><?php echo $row['year']; ?></td>
                                            <td class="text-center"><?php echo $row['section']; ?></td>
                                            <td class="text-center"><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                        </tr>
                                    <?php $c++; ?>
                                    <?php endwhile; ?>
                                    <?php if(mysql_num_rows($secondsem) < 1): ?>
                                        <tr><td colspan="6" class="text-center text-danger"><strong>*** EMPTY ***</strong></td></tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="tab-pane active" id="data3"> -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? '' : ''; ?>" id="data3">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="">Subject</th>
                                        <th class="text-center">Course</th>
                                        <th class="text-center">Year</th>
                                        <th class="text-center">Section</th>
                                        <th class="text-center">Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($summer)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td class=""><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td class="text-center"><?php echo $row['course']; ?></td>
                                            <td class="text-center"><?php echo $row['year']; ?></td>
                                            <td class="text-center"><?php echo $row['section']; ?></td>
                                            <td class="text-center"><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                            
                                        </tr>
                                    <?php $c++; ?>
                                    <?php endwhile; ?>
                                    <?php if(mysql_num_rows($summer) < 1): ?>
                                        <tr><td colspan="6" class="text-center text-danger"><strong>*** EMPTY ***</strong></td></tr>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
        <!-- /.row -->
       


    </div>
    <!-- /.container-fluid -->

</div>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

<!-- /#page-wrapper -->    
<?php include('include/footer.php');
