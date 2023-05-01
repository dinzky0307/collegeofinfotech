<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('connection.php');
    include('grade.php');
    $mysubject = $grade->getsubject();
    
    $id = $_SESSION['id'];
    $q = "select * from student where studid='$id'";
    $r = mysql_query($q);
    if($row = mysql_fetch_array($r)){
        $id = $row['id'];   
    }
   
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>MY GRADES</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        My Grades
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
        <div class="col-lg-12">
            <form action="">
                <label for="cars">Select Year:</label>
                <select name="year" id="year" class="dropbtn">
                    <option value="1" <?php echo isset($_GET['year']) && $_GET['year'] === '1' ? 'selected' : ''; ?>>1st Year</option>
                    <option value="2" <?php echo isset($_GET['year']) &&  $_GET['year'] === '2' ? 'selected' : ''; ?>>2nd Year</option>
                    <option value="3" <?php echo isset($_GET['year']) && $_GET['year'] === '3' ? 'selected' : ''; ?>>3rd Year</option>
                    <option value="4" <?php echo isset($_GET['year']) && $_GET['year'] === '4' ? 'selected' : ''; ?>>4th Year</option>
                </select>
                <label for="cars"  style="margin-left: 20px;">Select Semester:</label>
                <select name="semester" id="semester" class="dropbtn">
                    <option value="First Semester" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'First Semester' ? 'selected' : ''; ?>>First Semester</option>
                    <option value="Second Semester" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'Second Semester' ? 'selected' : ''; ?>>Second Semester</option>
                    <option value="Summer" <?php echo isset($_GET['semester']) &&  $_GET['semester'] === 'Summer' ? 'selected' : ''; ?>>Summer</option>
                </select>
                <button type="submit" name="year_semester" class="btn btn-success">
                    Submit
                </button>
            </form>  
            </br>
            <div class="">
                <table class="table table-bordered">
                    <thead>
                        <tr class="warning warning-info">
                            <th class="text-center">Subject Code</th>
                            <th class="text-center">Subject Description</th>
                            <!-- <th class="text-center">Prelim</th> -->
                            <th class="text-center">Midterm</th>
                            <th class="text-center">Remark</th>
                            <th class="text-center">Final</th>
                            <th class="text-center">Remark</th>
                            <th class="text-center">Final Ratings</th>
                            <th class="text-center">Final Remarks</th>
                           <!-- <th class="text-center">Units</th>-->
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach($mysubject as $row): ?>
                            <tr>
                                <td><?php echo $row['subject']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <?php $title = $grade->getsubjectitle($row['subject']);?>
                                <?php $mygrade = $grade->getgrade($row['id']); ?>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['midterm']); ?></td>
                                <td class="text-center">
                                <?php
                                        if ($mygrade['eqmidterm'] >3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqmidterm'] ==0) {
                                            echo "<font color='black'>NG</font>";
                                        }  else{
                                            echo "<font color='green'>Passed</font>";
                                        }
                                ?>
                                </td>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['final']); ?></td>
                                <td class="text-center">
                                <?php
                                        if ($mygrade['eqfinal'] >3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqfinal'] ==0) {
                                            echo "<font color='black'>NG</font>";
                                        }  else{
                                            echo "<font color='green'>Passed</font>";
                                        }
                                ?>
                            </td>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['total']); ?></td>
                                <td class="text-center">
                                <?php
                                        if ($mygrade['eqtotal'] >3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqtotal'] ==0) {
                                            echo "<font color='black'>NG</font>";
                                        }  else{
                                            echo "<font color='green'>Passed</font>";
                                        }
                                ?>
                            </td>
                               <!-- <td class="text-center"><?php echo $title[0]['unit']; ?></td>-->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>    
    </div> 
        <!-- /.row -->
       


    </div>
    <!-- /.container-fluid -->

</div>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row hidden-print">
        </div>
        <style>
            @media print{
                .print-container {
                    position: center;
                    margin-top: 0px;
                }
                @media print 
                {
                    .print-container{
                        width: 260mm;
                        font-size: 17px;
                    }
                }
                @page { 
                    size: auto;
                    margin:50px;
                }

            }
        </style>
        <!-- /.row -->
        <div class="row print-container">

            <div class="col-lg-12" >
            <img src="../img/banner.jpg" alt="" style="width: 100%; height: 120px;">
                <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>   
                            <th class="text-center">Subject Code</th>
                            <th class="text-center">Description</th>
                            <!-- <th class="text-center">Prelim</th> -->
                            <th class="text-center">Midterm</th>
                            <th class="text-center">Final</th>
                            <th class="text-center">Final Ratings</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($grades as $grade): ?>
                        <tr>
                            <td class="text-center"><?php echo $grade['code']; ?></td>
                            <td class="text-center"><?php echo $grade['title']; ?></td>
                            <!-- <td class="text-center"><?php echo $student->gradeconversion($grade['prelim_grade']); ?></td> -->
                            <td class="text-center"><?php echo $student->gradeconversion($grade['midterm_grade']); ?></td>
                            <td class="text-center"><?php echo $student->gradeconversion($grade['final_grade']); ?></td>
                            <td class="text-center"><?php echo $student->gradeconversion(array_sum([
                                $grade['midterm_grade'] * 0.3,
                                $grade['final_grade'] * 0.7
                            ])); ?></td>
                            <td class="text-center">
                                <?php echo getRemarks($student->gradeconversion(array_sum([
                                    $grade['midterm_grade'] * 0.3,
                                    $grade['final_grade'] * 0.7
                                ]))); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="float: right; margin-right: 30px;">
                    <h4><b><u>______________________________</u></b></h4>
                    <h5><center>Registrar</center></h5>
                </div>    
            </div>
            </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
