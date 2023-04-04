<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/data_model.php');
    include('connection.php');

    include('../DatabaseService.php');

    use Database\DatabaseService;
    use Carbon\Carbon;
    
    $dbService = new DatabaseService;

    $id = $_GET['id'];
    $stud = $dbService->fetchRow("SELECT* from student where id = {$id}");

?>
        
          
<?php
if(isset($_POST["delete"])) {
$rowid = $_POST['rowid'];
$sql = "DELETE FROM studentsubject WHERE id='$rowid'";

if ($dbconnection->query($sql) === TRUE) {
  echo "<script type='text/javascript'>";
            echo "Swal.fire({
               title: 'Subject Successfully Remove',
               icon: 'success',
             })";
            echo "</script>";
} else {
  echo "Error Deleting record: " . $dbconnection->error;
}
}
?>      

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row hidden-print">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>ENROLLED SUBJECTS</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="studentlist.php">Students List</a>
                    </li>
                    <li class="active">
                        Subject Enrolled
                    </li>
                </ol>
            </div>
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
        <form class="hidden-print">         
                <a href="enrollsubject.php?id=<?php echo $id; ?>&y=<?php echo $stud['year']; ?>&s=<?php echo $stud['semester']; ?>"><button type="button" class="btn btn-primary" style="float: right; margin-bottom: 10px;">
                Assign Subjects
                </button></a>
        </form>
        <!-- /.row -->
        <div class="row print-container">

            <div class="col-lg-12" >

                <br>
                <br/>
                <br/>
                <br/>
                <h4>Name : <b><u> <?php echo $stud['lname'].', '.$stud['fname'].' '.$stud['mname']; ?></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Course : <b><u>BSIT</u></b>&nbsp;&nbsp;&nbsp;
                Year & Section : <b> <u><?php echo $stud['year'].'-'.$stud['section']; ?></u></b>&nbsp;&nbsp;&nbsp;&nbsp;Semester :<b><u><?php echo $stud['semester']; ?></u></b></h4>
                <br>
                <br>
                <center><b><h1>Subjects Enrolled</h1></b></center>

            
                
                <div class="table-responsive">
                <table class="table table-striped table-bordered">
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
<?php 

$y = $stud['year'];
$s = $stud['semester'];


  $sql = "SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE studentsubject.studid = '$id' AND studentsubject.year='$y' AND studentsubject.semester='$s' ";
  $result = mysqli_query($dbconnection,$sql);
   $number = 1;
  while($subjects = $result->fetch_assoc()) { ?>
                         
                        <tr>
                        <td><?php echo $number++;?></td>
                            <td class="text-center"><?php echo $subjects['code']; ?></td>
                            <td class="text-center"><?php echo $subjects['title']; ?></td>
                            <td class="text-center"><?php echo $subjects['lecunit']; ?></td>
                            <td class="text-center"><?php echo $subjects['labunit']; ?></td>
                            <td class="text-center"><?php echo $subjects['totalunit']; ?></td>
                            <td class="text-center"><?php echo $subjects['pre']; ?></td>
                            <td class="text-center">
                                <form action="" method="POST">
                                <input type="hidden" name="rowid" value="<?php echo $subjects['id']; ?>">
                                <button type="submit" name="delete" style="border: none;"><i class="fa fa-times-circle text-danger fa-2x confirmation"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table> 
                <form class="hidden-print">         

                <button type="button" class="btn btn-success" style=" margin-top: 5px;">
                <a href="studentlist.php" style="text-decoration:none; color: white;">
                <i class="fa fa-arrow-left"></i> 
                Go Back
                </a>
                </button>
        </form>
            </div>
            </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->    
<?php
$result=mysql_query("SELECT COUNT(*) AS studid FROM studentsubject WHERE studid='$stud[id]'");
$d=mysql_fetch_assoc($result);
echo $d['studid'];
// $studid = $dbService->fetchRow("SELECT COUNT(*) FROM table_name WHERE status = 'canceled';");

// echo "<script>alert(".$studid['studid'].");</script>";
// echo "<script>alert(".$studid['COUNT(studid)'].");</script>";

?>
<?php include('include/footer.php');

