<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('../database.php'); // Include the database connection code
    include('data/data_model.php');
    include('data/student_model.php');

    $student = new Datastudent($connection); // Create an instance of the Datastudent class and pass the connection as a parameter

    if (isset($_GET['q'])) {
        $student->$_GET['q']();
    }

    $data = new Data($connection);
    if (isset($_GET['q'])) {
        $data->$_GET['q']();
    }

    include '../DatabaseService.php';

    use Database\DatabaseService;
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $student = $student->getstudent($search);
    $dbService = new DatabaseService;

    $subjects = [
        'first_year' => [
            'first_semester' => $dbService->fetch('SELECT * from subject where semester = 1 AND year = 1'),
            'second_semester' => $dbService->fetch('SELECT * from subject where semester = 2 AND year = 1')
        ],
        'second_year' => [
            'first_semester' => $dbService->fetch('SELECT * from subject where semester = 1 AND year = 2'),
            'second_semester' => $dbService->fetch('SELECT * from subject where semester = 2 AND year = 2')
        ],
        'third_year' => [
            'first_semester' => $dbService->fetch('SELECT * from subject where semester = 1 AND year = 3'),
            'second_semester' => $dbService->fetch('SELECT * from subject where semester = 2 AND year = 3'),
            'summer' => $dbService->fetch('SELECT * from subject where semester = 3 AND year = 3')
        ],
        
        'fourth_year' => [
            'first_semester' => $dbService->fetch('SELECT * from subject where semester = 1 AND year = 4'),
            'second_semester' => $dbService->fetch('SELECT * from subject where semester = 2 AND year = 4')
        ],
    ];

    // $firstsem = $subject->getsubject('1st',$id);    
    // $secondsem = $subject->getsubject('2nd',$id);

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
                    <li class="<?php echo isset($_GET['page']) ? '' : 'active'; ?>"><a href="#data2" role="tab" data-toggle="tab">Grade Slip and Prospectus</a></li>
                    <li class="<?php echo isset($_GET['page']) ? 'active' : ''; ?>"><a href="#data1" role="tab" data-toggle="tab">Prospectus</a></li>
                </ul>
                <!-- Tab panes -->
                
                <div class="tab-content" x-ref="print">
                    <div class="tab-pane <?php echo isset($_GET['page']) ? 'active' : ''; ?> print-container" id="data1">


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
                            <h3><b>FIRST YEAR</b></h3>
                            </center>

                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <tr> 
                                            <th colspan="7"><b> FIRST SEM </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['first_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['first_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                            <th colspan="7"><b> FIRST SEM </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['second_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['second_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                            <th colspan="7"><b> FIRST SEM </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['third_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['third_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['third_year']['summer']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                            <th colspan="7"><b> FIRST SEM </b></th>
                                        </tr>
                                        <th class="text-center">Subject Code</th>
                                        <th class="text-center">Descriptive Title</th>
                                        <th class="text-center">Lec Unit</th>
                                        <th class="text-center">Lab Unit</th>
                                        <th class="text-center">Total Units</th>
                                        <th class="text-center">Pre-requisites/s</th>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['fourth_year']['first_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $totalunits; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php if(count($subjects['fourth_year']['second_semester']) < 1): ?>
                                        <tr>
                                            <td colspan="4" class="bg-danger text-danger text-center">*** EMPTY ***</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <br/>
                            <br/>
                            <div style="margin-left: 20px;">
                                <h5>Prepared by:</h5>
                                <br/>
                                <h4><b><u>DINO L. ILUSTRISIMO, MIT</u></b></h4>
                                <h5>Head, IT Department</h5>
                            </div>
                            <div style="margin-right: 40px; float: right; margin-top: -80px;">
                                <h5>Noted by:</h5>
                                <br/>
                                <h4><b><u>Dr. FLORIPIS MONTECILLO, Ed.D.</u></b></h4>
                                <h5></h5>
                            </div>
                            
                        </div>
                    </div>
                    <!-- Gradesss -->
                    <div class="tab-pane <?php echo isset($_GET['page']) ? '' : 'active'; ?>" id="data2">
                        <br />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="listInformation">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">ID number</th>
                                        <th class="text-center">Fullname</th>
                                        <th class="text-center">Year and Section</th>
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Grade Slip</th>
                                        <th class="text-center">Prospectus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($students as $number => $student): ?>
                                        <tr>
                                            <td><?php echo $number + 1;?></td>
                                            <td class="text-center"><?php echo $student['studid'];?></a></td>
                                            <td><?php echo $student['fname'];?>, <?php echo $student['lname'];?> <?php echo $student['mname'];?></td>
                                            <td class="text-center"><?php echo $student['year'];?> -<?php echo $student['section'];?></td>
                                            <td class="text-center"><?php echo $student['semester'];?></td>
                                            <td class="text-center">                                       
                                                <a href="studentsubject.php?id=<?php echo $student['id'];?>" title="View Grades">View Grade Slip
                                            </td>
                                            <td class="text-center">                                       
                                                <a href="myprospectus.php?id=<?php echo $student['id'];?>" title="View Prospectus">View Prospectus
                                            </td>                           
                                        </tr>
                                    <?php endforeach; ?>
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
<script>
    $(document).ready( function () {
        $.noConflict();
        $('#listInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
