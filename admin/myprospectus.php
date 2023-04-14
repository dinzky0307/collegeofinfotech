<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/data_model.php');
    include('data/student_model.php');
    include '../DatabaseService.php';

    use Database\DatabaseService;
    
    $search = isset($_POST['search']) ? $_POST['search']: null;

    $dbService = new DatabaseService;

    $id = $_GET['id'];
    $student = $dbService->fetchRow("SELECT* from student where id = {$id}");


    $subjects = [
        'first_year' => [
            'first_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 1 AND subject.year = 1 AND studentsubject.studid = '.$id.''),
            'second_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 2 AND subject.year = 1 AND studentsubject.studid = '.$id.'')
        ],
        'second_year' => [
            'first_semester' => $dbService->fetch('SELECT *, studentsubject.prelim_grade FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 1 AND subject.year = 2 AND studentsubject.studid = '.$id.''),
            'second_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 2 AND subject.year = 2 AND studentsubject.studid = '.$id.'')
        ],
        'third_year' => [
            'first_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 1 AND subject.year = 3 AND studentsubject.studid = '.$id.''),
            'second_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 2 AND subject.year = 3 AND studentsubject.studid = '.$id.''),
            'summer' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 3 AND subject.year = 3 AND studentsubject.studid = '.$id.'')
        ],
        
        'fourth_year' => [
            'first_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 1 AND subject.year = 4 AND studentsubject.studid = '.$id.''),
            'second_semester' => $dbService->fetch('SELECT * FROM subject INNER JOIN studentsubject ON studentsubject.subjectid = subject.id WHERE subject.semester = 2 AND subject.year = 4 AND studentsubject.studid = '.$id.'')
        ],
    ];

    // $firstsem = $subject->getsubject('1st',$id);    
    // $secondsem = $subject->getsubject('2nd',$id);

    function gradeconversion($grade){
            //$grade = round($grade);
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


        $ay = $dbService->fetchRow("SELECT * from ay");

?>
<div id="page-wrapper" x-data="Report">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row hidden-print">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>GENERATE REPORTS</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="report.php">Student list</a>
                    </li>
                    <li class="active">
                        Generate Reports
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
                        overflow:visible;
                    }
                }
                @page { 
                    size: auto;
                    margin:50px;
                }

            }
        </style>
        <!-- /.rowddw -->
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs hidden-print" role="tablist">
                    <li class="<?php echo isset($_GET['page']) ? '' : 'active'; ?>"><a href="#data1" role="tab" data-toggle="tab">Prospectus</a></li>
                </ul>
                <!-- Tab panes -->
                
                <div class="tab-content" x-ref="print">
                    <div class="tab-pane <?php echo isset($_GET['page']) ? '' : 'active'; ?> print-container" id="data1">


                        <div class="table-responsive">
                        <form class="hidden-print">         
                            <button onclick="window.print();" type="button" class="btn btn-success" style="float: right; margin-bottom: 10px; margin-top: 10px;">
                            Print Prospectus
                        </button>
                        </form>
                        <br>
                        <br/>
                            <img src="../img/banner.jpg" alt="" style="width: 100%; height: 120px;">
                            <br>
                            <br/>
                            <center><h4><b>BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</b></h4>
                            <h4><b>Effective Academic Year <?php echo $ay['academic_year']; ?></b></h4>

                            <br/>
                            
                            </center>

                            <br/>
                        
                            <!-- <h4>ID Number : <b><?php echo $student['studid']; ?></h4> -->
                            <h4><b>Name</b> : <b> <?php echo $student['fname'].', '.$student['lname'].' '.$student['mname']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Course : <b>BSIT</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Year & Section : <b> <?php echo $student['year'].'-'.$student['section']; ?></h4>
                            <center><h3><b>FIRST YEAR</b></h3></center>
                            <!-- <h4><b> <?php echo $student['semester']; ?></h4> -->

                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b> FIRST SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['first_year']['first_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center">
                                                <?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['first_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b>SECOND SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['first_year']['second_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center">
                                                <?php echo gradeconversion(($subject['midterm_grade']+$subject['final_grade'])/3);?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['first_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <center>
                            <br/>
                            <h3><b>SECOND YEAR</b></h3>
                            </center>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b> FIRST SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['second_year']['first_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['second_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b>SECOND SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['second_year']['second_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['second_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <center>
                            <br/>
                            <h3><b>THIRD YEAR</b></h3>
                            </center>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b> FIRST SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['third_year']['first_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['third_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b>SECOND SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['third_year']['second_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['third_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                                <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><center><h3><b>SUMMER </b></h3></center></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['third_year']['summer'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['third_year']['summer']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            </table>
                            <center>
                            <br/>
                            <h3><b>FOURTH YEAR</b></h3>
                            </center>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b> FIRST SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['fourth_year']['first_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['fourth_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b>SECOND SEMESTER </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
                                        <th class="text-center">Grades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalunits = 0; ?>
                                    <?php foreach($subjects['fourth_year']['second_semester'] as $key => $subject): ?>
                                        <?php $totalunits += $subject['totalunit']; ?>
                                        <tr>
                                            <td class="text-center"><?php echo $subject['code'];?></a></td>
                                            <td><?php echo $subject['title'];?></td>
                                            <td class="text-center"><?php echo $subject['lecunit'];?></td>
                                            <td class="text-center"><?php echo $subject['labunit'];?></td>
                                            <td class="text-center"><?php echo $subject['totalunit'];?></td>
                                            <td class="text-center"><?php echo $subject['pre'];?></td>
                                            <td class="text-center"><?php echo gradeconversion(($subject['prelim_grade']+$subject['midterm_grade']+$subject['final_grade'])/3);?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(count($subjects['fourth_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="7" class="bg-danger text-center text-white">*** EMPTY ***</td>
                                        </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <br/>
                            <br/>
                            <div style="margin-left: 20px;">
                                <h5>Prepared by:</h5>
                                <br/>
                                <h4><b>_____________________</b></h4>
                                <h5>Head, IT Department</h5>
                            </div>
                            <div style="margin-right: 40px; float: right; margin-top: -80px;">
                                <h5>Noted by:</h5>
                                <br/>
                                <h4><b>________________________</b></h4>
                                <h5></h5>
                            </div>
                            
                        </div>
                    </div>
                </div>   
            </div>
        </div>
        <!-- /.row -->
       


    </div>
    <!-- /.container-fluid -->

</div>
<script>
    $(document).ready( function () {
        $.noConflict();
        $('#listInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
