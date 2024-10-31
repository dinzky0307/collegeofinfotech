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
                <!-- Panels Section -->
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
                                        <th>Subject</th>
                                        <th>Course</th>
                                        <th>Year</th>
                                        <th>Section</th>
                                        <th>Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($firstsem)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td class=""><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
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
                                        <th>Subject</th>
                                        <th>Course</th>
                                        <th>Year</th>
                                        <th>Section</th>
                                        <th>Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($secondsem)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
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
                                        <th>Subject</th>
                                        <th>Course</th>
                                        <th>Year</th>
                                        <th>Section</th>
                                        <th>Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 1; ?>
                                    <?php while($row = mysql_fetch_array($summer)): ?>
                                        <tr>
                                            <td><?php echo $c; ?></td>
                                            <td><?php echo $row['subject']; ?> - <?php echo $row['description']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['section']; ?></td>
                                            <td><a href="student.php?classid=<?php echo $row['id'];?>&y=<?php echo $row['year'];?>&sem=<?php echo $row['sem'];?>&sec=<?php echo $row['section'];?>&ay=<?php echo $row['SY'];?>&code=<?php echo $row['subject'];?>">View Students</a></td>
                                            
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
            <br><br> <br><br>  <br><br>
            <!-- /.container-fluid -->
             <footer class="container-fluid">
        <?php include 'include/footer.php'; ?>
      </footer>
        </section>
    </div>
      
</div>
