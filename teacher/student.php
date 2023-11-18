<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/subject_model.php');
    include('../database.php');
    include('data/student_model.php');
    $mysubject = $subject->getallsubject($id); 
    $classid = isset($_GET['classid']) ? $_GET['classid'] : null;
    $year = isset($_GET['y']) ? $_GET['y'] : "";
    $sem = isset($_GET['sem']) ? $_GET['sem'] : "";
    $sec = isset($_GET['sec']) ? $_GET['sec'] : "";
    $ay = isset($_GET['ay']) ? $_GET['ay'] : "";
    $code = isset($_GET['code']) ? $_GET['code'] : "";
    $search = isset($_POST['search']) ? $_POST['search'] : null; 


    
    if(isset($_POST['search'])){
        // $classid = $_POST['subject'];   
        $mystudent = $student->getstudbysearch($classid,$search,$year,$sem,$sec,$ay);
    }else{

         // Get subject code
         $sql_code = "SELECT id FROM subject WHERE code=:subid LIMIT 1";
         $pdo_statement_code = $connection->prepare($sql_code);
         $pdo_statement_code->bindParam(':subid', $code, PDO::PARAM_INT);
         $pdo_statement_code->execute();
         $rows = $pdo_statement_code->fetch(PDO::FETCH_ASSOC);
         $subjectcode = $rows['id'];

         // Use prepared statement to prevent SQL injection
        $stmt = $connection->prepare("SELECT student.studid, student.fname, student.lname, student.mname, student.id, studentsubject.year, studentsubject.semester, studentsubject.section, studentsubject.SY, studentsubject.subjectid FROM student JOIN studentsubject ON student.id = studentsubject.studid WHERE studentsubject.year = :year AND studentsubject.semester = :semester AND studentsubject.section = :section AND studentsubject.SY = :sy AND studentsubject.subjectid = :code");
        //$stmt->bindParam(':classid', $classid, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_STR);
        $stmt->bindParam(':semester', $sem, PDO::PARAM_STR);
        $stmt->bindParam(':section', $sec, PDO::PARAM_STR);
        $stmt->bindParam(':sy', $ay, PDO::PARAM_STR);
        $stmt->bindParam(':code', $subjectcode, PDO::PARAM_STR);
        $stmt->execute();
        $mystudent = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding">
                    <form action="student.php?classid=<?php echo $classid?>&y=<?php echo $year?>&sem=<?php echo $sem?>&sec=<?php echo $sec?>&ay=<?php echo $ay?>" method="post">
                        <input type="text" class="form-control" name="search" placeholder="Search by ID or Name">
                        <select name="subject" class="form-control" required>
                            <option value="">Select Subject...</option>                            
                            <?php while($row = mysql_fetch_array($mysubject)): ?>
                                <option value="<?php echo $row['id']?>" <?php if($row['id']==$classid) echo 'selected'; ?>><?php echo $row['subject'];?> - <?php echo $row['description'];?> - <?php echo $row['year'];?><?php echo $row['section'];?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>                       
                        <a href="print.php?classid=<?php echo $classid; ?>" target="_blank"><button type="button" name="submit" class="btn btn-success"><i class="fa fa-print"></i> Print</button></a>            
                    </form>
                </div>
            </div>
        </div> -->
        <hr />
        <div class="row">
            <div class="col-lg-12">                

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">ID Number</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Prelim</th>
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
                                    <td><?php echo $row['subjectid']; ?></td>   
                                    <td class="text-center"><?php echo $row['studid']; ?></td>    
                                    <td class="text-center"><?php echo $row['fname'].', '.$row['lname'].' '.$row['mname']; ?></td>  
                                    <?php $grade = $student->getstudentgrade($row['id'],$classid,$year,$sem,$sec,$ay); ?>
                                    <td class="text-center"><input type="number" class="box-size" value="<?php echo $grade['prelim'];?>" name="prelim_grade" id="prelim"></td>    
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
                                    <a data-classid="<?php echo $classid; ?>" data-subject="<?php echo $row['subjectid']; ?>" data-ay="<?php echo $row['SY']; ?>" 
                                        data-sec="<?php echo $row['section']; ?>" data-sem="<?php echo $row['semester']; ?>" data-year="<?php echo $row['year']; ?>" data-id="<?php echo $row['id']; ?>" 
                                        data-scode="<?php echo $code ?>" class="btn btn-success updategrade"><i class="fa fa-check fa-lg"></i> Save</a>
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
    var prelim = $('#'+dataid+' #prelim').val();
    var midterm = $('#'+dataid+' #midterm').val();
    var final = $('#'+dataid+' #final').val();
    var classid = $(this).attr('data-classid');
    var year = $(this).attr('data-year');
    var sem = $(this).attr('data-sem');
    var sec = $(this).attr('data-sec');
    var ay = $(this).attr('data-ay');
    var sub = $(this).attr('data-sub');
    var code = $(this).attr('data-scode');
    console.log(sub)
    // $('#'+dataid+' .updategrade').attr('href','updategrade.php?id='+dataid+'&p='+prelim+'&m='+midterm+'&f='+final+'&c='+classid+'&y='+year+'&s='+sem+'&e='+sec+'&a='+ay+'&b='+sub+'&cd='+code);
    $('.loading').show();

    
});
</script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
    $('#myTable thead th').each(function () {
    });

    // DataTable
    var table = $('#myTable').DataTable({
        searching: true,
        "columnDefs": [
            { "searchable": true, "targets": '_all' }
        ],
    });
</script>
