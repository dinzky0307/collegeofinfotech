<?php
    include('include/header.php');
    include('include/sidebar.php');
    // include('data/student_model.php');
    include('../teacher/data/student_model.php');
    include('data/data_model.php');

    include('../DatabaseService.php');

    use Database\DatabaseService;
    use Carbon\Carbon;
    
    $dbService = new DatabaseService;

    $id = $_GET['id'];
    $stud = $dbService->fetchRow("SELECT * from student where id = {$id}");
$studsem = $stud['semester'];
    $grades = $dbService->fetch(
        "SELECT subject.code, subject.title, subject.title, studentsubject.prelim_grade, studentsubject.midterm_grade, studentsubject.final_grade from studentsubject INNER JOIN subject ON studentsubject.subjectid = subject.id WHERE studid = '$id' AND studentsubject.semester = '$studsem'"
    );


function gradeconversion($grade){
            $grade = round($grade);
            if($grade==0){
                 $data = 0;
            }else{
                switch ($grade) {
                     case $grade > 94:
                         $data = 1.0;
                         break;
                     case 94:
                         $data = 1.1;
                         break;
                    case 93:
                         $data = 1.2;
                         break;
                    case 92:
                         $data = 1.3;
                         break;
                    case 91:
                         $data = 1.4;
                         break;
                    case 90:
                         $data = 1.5;
                         break;
                    case 89:
                         $data = 1.6;
                         break;
                    case 88:
                         $data = 1.7;
                         break;
                    case 87:
                         $data = 1.8;
                         break;
                    case 86:
                         $data = 1.9;
                         break;
                    case 85:
                         $data = 2.0;
                         break;
                    case 84:
                         $data = 2.1;
                         break;
                    case 83:
                         $data = 2.2;
                         break;
                    case 82:
                         $data = 2.3;
                         break;
                    case 81:
                         $data = 2.4;
                         break;
                    case 80:
                         $data = 2.5;
                         break;
                   case 79:
                         $data = 2.6;
                         break;
                    case 78:
                         $data = 2.7;
                         break;
                    case 77:
                         $data = 2.8;
                         break;
                    case 76:
                         $data = 2.9;
                         break;
                    case 75:
                         $data = 3.0;
                         break;                

                     default:
                         $data = 5.0;
                }
            }
            return $data;
        }


    function getRemarks($average) {
        if ($average > 3) {
            return '<font color="red">Failed</font>';
        }

        if ($average === 0) {
            return '<font color="black">NG</font>';
        }

        return '<font color="green">Passed</font>';
    }
?>
        
                

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row hidden-print">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>SUBJECT GRADES</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="report.php">Students List</a>
                    </li>
                    <li class="active">
                        View Grades
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
                <button onclick="window.print();" type="button" class="btn btn-success" style="float: right; margin-bottom: 10px;">
                    Print Grade Slip
                </button>
        </form>
        <!-- /.row -->
        <div class="row print-container">

            <div class="col-lg-12" >
            <img src="../img/banner.jpg" alt="" style="width: 100%; height: 120px;">
                <br>
                <br/>
                <br/>
                <br/>
                <h4><b>Name</b> : <b> <?php echo $stud['fname'].', '.$stud['lname'].' '.$stud['mname']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Course : <b>BSIT</b>&nbsp;&nbsp;&nbsp;
                Year & Section : <b> <?php echo $stud['year'].'-'.$stud['section']; ?></h4>
                <center><b><h3>GRADE SLIP</h3></b></center>
                <h4><b> <?php echo $stud['semester']; ?></h4>

            
                
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