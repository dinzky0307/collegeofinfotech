<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/subject_model.php');
    include('data/student_model.php');
    $mysubject = $subject->getallsubject($id); 
    $classid = isset($_GET['classid']) ? $_GET['classid'] : null;    
    $search = isset($_POST['search']) ? $_POST['search'] : null; 
    if(isset($_POST['search'])){
        $classid = $_POST['subject'];   
        $mystudent = $student->getstudentbysearch($classid,$search);
    }else{
        $mystudent = $student->getstudentbyclass($classid);
    }

    // if(isset($_POST['submit']))
    // {
    //  $SQL = "update studentsubject set prelim_grade='$prelim_grade', midterm_grade='$midterm_grade',final_grade='$final_grade' where studid=$studid and classid=$classid";
    //  $result = mysql_query($SQL);

    // }
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>MY STUDENTS</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="subject.php">My Subjects</a>
                    </li>
                    <li class="active">
                        Students
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding">
                    <form action="student.php?classid=<?php echo $classid?>" method="post">
                        <input type="text" class="form-control" name="search" placeholder="Search by ID or Name">
                        <select name="subject" class="form-control" required>
                            <option value="">Select Subject...</option>                            
                            <?php while($row = mysql_fetch_array($mysubject)): ?>
                                <option value="<?php echo $row['id']?>" <?php if($row['id']==$classid) echo 'selected'; ?>><?php echo $row['subject'];?> - <?php echo $row['description'];?> - <?php echo $row['year'];?><?php echo $row['section'];?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>                       
                        <!-- <a href="print.php?classid=<?php echo $classid; ?>" target="_blank"><button type="button" name="submit" class="btn btn-success"><i class="fa fa-print"></i> Print</button></a>             -->
                    </form>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">                

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">ID Number</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Midterm</th>
                                <th class="text-center">Final</th>
                                <th class="text-center">Average</th>
                                <th class="text-center">Equivalent</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $c=1; ?>
                            <?php foreach($mystudent as $row): ?>
                                <tr id="<?php echo $row['id']; ?>">
                                    <td><?php echo $c; ?></td>    
                                    <td class="text-center"><?php echo $row['studid']; ?></td>    
                                    <td class="text-center"><?php echo $row['fname'].', '.$row['lname'].' '.$row['mname']; ?></td>  
                                    <?php $grade = $student->getstudentgrade($row['id'],$classid); ?>   
                                    <td class="text-center"><input type="number" class="box-size" value="<?php echo $grade['midterm'];?>" name="midterm_grade" id="midterm"></td>    
                                    <td class="text-center"><input type="number" class="box-size" value="<?php echo $grade['final'];?>" name="finals_grade" id="final"></td>    
                                    <td class="text-center"><?php echo $grade['total'];?></td>
                                    <td class="text-center"><?php echo $grade['eqtotal'];?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($grade['eqtotal'] >3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($grade['eqtotal'] ==0) {
                                            echo "<font color='black'>NG</font>";
                                        }  else{
                                            echo "<font color='green'>Passed</font>";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                    <a data-classid="<?php echo $classid; ?>" data-id="<?php echo $row['id']; ?>" class="btn btn-success updategrade"><i class="fa fa-check fa-lg"></i> Save</a>
                                        <!-- <a href="calculate.php?studid=<?php echo $row['id']; ?>&classid=<?php echo $classid ?>" class="btn btn-primary"><i class="fa fa-plus fa-lg" title="Add Grades"></i></a> -->
                                        <!-- <a href="calculate.php?studid=<?php echo $row['id']; ?>&classid=<?php echo $classid ?>" class="btn btn-primary" style="background-color:green; color:black;"><i class="fa fa-eye fa-lg" title="calculate grade"></i></a>
                                        <a href="calculate.php?studid=<?php echo $row['id']; ?>&classid=<?php echo $classid ?>" class="btn btn-primary" style="background-color:red;"><i class="fa fa-trash-o fa-lg" title="calculate grade"></i></a> -->
                                    </td>    
                                </tr>
                            <?php $c++; ?>
                            <?php endforeach; ?>
                            <?php if(!$mystudent): ?>
                                <tr><td colspan="8" class="text-center text-danger"><strong>*** No Result ***</strong></td></tr>
                            <?php endif; ?>
                            
                        </tbody>
                        <!-- <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                        <button type="submit" class="btn btn-success">Update Grade</button> -->
                    </table>
                    
                </div>        
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
<img class="loading" style="position: fixed;top: 35vh;z-index: 4;right: 40vw;display: none;" src="loading.gif">
</div>

<!-- /#page-wrapper -->    

<?php include('include/footer.php'); ?>

<script>
$('.updategrade').click(function(){
    var dataid = $(this).attr('data-id');
    var classid = $(this).attr('data-classid');
    // var prelim = $('#'+dataid+' #prelim').val();
    var midterm = $('#'+dataid+' #midterm').val();
    var final = $('#'+dataid+' #final').val();
    $('#'+dataid+' .updategrade').attr('href','updategrade.php?id='+dataid+'&m='+midterm+'&f='+final+'&c='+classid);
    $('.loading').show();
});
</script>
