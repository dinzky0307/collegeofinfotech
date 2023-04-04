<?php
    include('include/header.php');  
    include('data/student_model.php');
    
    $id = $_GET['id'];
    $student = $student->getstudentbyid($id);
?>

<?php 



if (isset($_POST['grade'])) {

$prelim = $_POST['prelim'];
$midterm = $_POST['midterm'];
$final = $_POST['final'];
$rowid = $_POST['rowid'];
$avg = (number_format($prelim) + number_format($midterm) + number_format($final)) / 3;

$conn = new mysqli('localhost', 'root', '', 'grading');

if($avg > 3){
    $remarks = "Failed";
} else {
    $remarks = "Passed";
}

$sql = "UPDATE class SET prelim = '$prelim', midterm = '$midterm', final = '$final', average = '$avg', remarks = '$remarks' WHERE id=$rowid";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Successfully grade has been set.');</script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

}


 ?>


<div id="page-wrapper" x-data="StudentSubject()">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>STUDENT SUBJECT</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="studentlist.php">Students</a>
                    </li>
                    <li class="active">
                        Student Subject
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <?php while($row = mysql_fetch_array($student)): ?>
                <h4>Student ID :<b><?php echo $row['studid']; ?></h4>
                <h4>Name :<b> <?php echo $row['lname'].', '.$row['fname'].' '.$row['mname']; ?></h4>
                <h4>Course : <?php echo $row['course']; ?></h4>
                <h4>Year/Section : <?php echo $row['year'].' ,'.$row['section']; ?></h4>
                <h4>Semester : <?php echo $row['semester']; ?></h4>
                
                <?php endwhile; ?>
                
                <hr />
                <div class="table-responsive">
                <a href="print.php" target="_blank" class="btn btn-success pull-right"><span class="glyphicon glyphicon-print" style="margin-top:px;"></span> Print</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Description</th>
                            <th class="text-center">Units</th>
                            <th class="text-center">Prelim</th>
                            <th class="text-center">Midterm</th>
                            <th class="text-center">Final</th>
                            <th class="text-center">Average</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    
<?php
    $r1 = mysql_query("select * from studentsubject where studid=$id");
    while($row = mysql_fetch_array($r1)):
        $r2 = mysql_query('select * from class where id='.$row['classid'].'');
        while($rows = mysql_fetch_array($r2)):
            $r3 = mysql_query('select * from teacher where id='.$rows['teacher'].'');
            $teacher = null;
            if($r3){
                 $teacher = mysql_fetch_array($r3);
                $teacher = $teacher['fname'].' '.$teacher['lname'];
            }?>
            
                        <tr>
                            <td><?php echo $rows['subject']; ?></td>
                            <td><?php echo $teacher ?></td>
                            <td class="text-center"><?php echo $rows['unit']; ?></td>
                            <td class="text-center"><?php echo $rows['prelim']; ?></td>
                            <td class="text-center"><?php echo $rows['midterm']; ?></td>
                            <td class="text-center"><?php echo $rows['final']; ?></td>
                            <td class="text-center"><?php echo $rows['average']; ?></td>
                            <td class="text-center">
                                <?php
                                if ($rows['remarks'] == 'Failed') {
                                    echo "<font color='red'>Failed</font>";
                                } else if ($rows['remarks'] == 'Passed') {
                                    echo "<font color='green'>Passed</font>";
                                }
                                ?>
                            </td>
                            <td><a data-toggle="modal" data-target="#exampleModal" class="edit" href="#" id="<?php echo $rows['id']; ?>" @click="addGrades(<?php echo $rows['id']; ?>)">Add Grades</a></td>
                            <td class="text-center"><a href="data/student_model.php?q=removesubject&studid=<?php echo $id;?>&classid=<?php echo $rows['id']; ?>"><i class="fa fa-trash-o text-danger fa-lg confirmation"></i></a></td>
                        </tr>
                    
            <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Grade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST">
        	<input type="hidden" name="rowid" id="passrowid" x-ref="subject_id"">
        	<label>Prelim</label>
        	<input class="form-control" type="text" name="prelim" id="prelim">
        	<label>Midterm</label>
        	<input class="form-control" type="text" name="midterm" id="midterm">
        	<label>Final</label>
        	<input class="form-control" type="text" name="final" id="final">
            <br>
        	<button class="btn btn-success" name="grade" type="submit">SAVE</button>
        </form>
      </div>
<!--       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
        <?php endwhile;
    endwhile;
?>
                    </tbody>
                </table>    
            </div>
            </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->   

<script>
window.StudentSubject = () => {
    return {
        addGrades(id) {
            const input = this.$refs.subject_id
            input.value = id

            
        }
    }
}
</script>



	<center><button id="PrintButton" onclick="PrintPage()">Print</button></center>
<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	document.loaded = function(){
		
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.close() },750)
	});
</script>
<?php include('include/footer.php');

