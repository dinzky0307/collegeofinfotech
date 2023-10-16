<?php
    include('include/header.php');
    include('include/sidebar.php');  
    include '../DatabaseService.php';
    use Database\DatabaseService;

    $dbService = new DatabaseService;

    // Fetch the active academic year from the database
        $activeAcademicYear = $dbService->fetchRow("SELECT * FROM ay WHERE display = 1");

        // Check if the active academic year exists and set the variables accordingly
        if ($activeAcademicYear) {
            $academic_year = $activeAcademicYear['academic_year'];
            $semester = $activeAcademicYear['semester'];
            $academicYearActive = true;
        } else {
            // If no active academic year, set default values or handle as you prefer
            $academic_year = "Default Year";
            $semester = "Default Semester";
            $academicYearActive = false;
        }

        $newAcademicYear = "$academic_year";
        $newSemester = "$semester";


// Map the 'ay' table semester values to 'subject' table semester values
$semesterMapping = [
    'First Semester' => 1,
    'Second Semester' => 2,
    'Final Semester' => 3,
];

// Map the active semester to the corresponding value
$semesterValue = isset($semesterMapping[$semester]) ? $semesterMapping[$semester] : null;

// Fetch subjects based on the active semester value
$subjects = [];
if ($academicYearActive) {
    $query = "SELECT * from subject where semester = {$semesterValue}";
    // echo "Query: $query\n";
    $subjects = $dbService->fetch($query);
}
// print_r($subjects);

    $baseCountQuery = 'SELECT count(*) from student';
    
    $statistics = [
        'population' => [
            'first' => $dbService->countRows($baseCountQuery . " WHERE year = 1 and ay = '$newAcademicYear' and semester = '$newSemester'"),
            'second' => $dbService->countRows($baseCountQuery . " WHERE year = 2 and ay = '$newAcademicYear' and semester = '$newSemester'"),
            'third' => $dbService->countRows($baseCountQuery . " WHERE year = 3 and ay = '$newAcademicYear' and semester = '$newSemester'"),
            'fourth' => $dbService->countRows($baseCountQuery . " WHERE year = 4 and ay = '$newAcademicYear' and semester = '$newSemester'")
        ],
        'consultation' => [
            'academic' => 0,
            'behaviour' => 0,
            'soc' => 0,
            'others' => 0
        ]
    ];

    $consultations = $dbService->fetch("SELECT* from consultations");

    foreach ($consultations as $consultation) {
        $concerns = explode(',',$consultation['areas_concern']);

        $academic = in_array('Concerns about academic performance', $concerns);
        $behaviour = in_array('Concerns about behavior', $concerns);
        $soc = in_array('Concerns about recent social changes such as family and or peer relationships', $concerns);

        if ($academic) {
            $statistics['consultation']['academic'] += 1;
        }

        if ($behaviour) {
            $statistics['consultation']['behaviour'] += 1;
        }

        if ($soc) {
            $statistics['consultation']['soc'] += 1;
        }

        foreach ($concerns as $concern) {
            if (
                $concern !== 'Concerns about academic performance' &&
                $concern !== 'Concerns about behavior' &&
                $concern !== 'Concerns about recent social changes such as family and or peer relationships'
            ) {
                $statistics['consultation']['others'] += 1;
            }
        }
    }
    
    // Check if the display value is set in the $year array and it is turned on (1).
    $academicYearVisible = isset($year['display']) && $year['display'] == 1;

    // Check if the academic year and semester are set in session variables.
    $academicYearSet = isset($_SESSION['academic_year']) && isset($_SESSION['semester']);
    $academic_year = $academicYearSet ? $_SESSION['academic_year'] : '';
    $semester = $academicYearSet ? $_SESSION['semester'] : '';
    
    $ay = $dbService->fetchRow("SELECT * from ay WHERE display = 1");
    $academic_year = $ay['academic_year'];
    $semester = $ay['semester'];

    if ($academicYearActive) {
    $query = "SELECT count(id) from student where semester = '{$semester}' and ay = '{$activeAcademicYear['academic_year']}'";
    $r1 = mysql_query($query);
    $count1 = mysql_fetch_array($r1);
} else {
    // If academic year is turned off, set the student count to zero
    $count1 = [0];
}

    $r2 = mysql_query('select count(*) from subject');
    $count2 = mysql_fetch_array($r2);

    $r3 = mysql_query('select count(*) from teacher');
    $count3 = mysql_fetch_array($r3);

    $r4 = mysql_query('select count(*) from userdata');
    $count4 = mysql_fetch_array($r4);

?>
<link rel="icon" href="img/mcc.png">
<div id="page-wrapper">    
    <div class="container-fluid">
        
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                 Dashboard <small> Overview</small>
                </h1>

                
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </li>
                </ol>
                <ol class="breadcrumb">
                    <li class="">
                    <center>
                    <h1>
                    Current Academic Year and Semester:
                    <?php if ($activeAcademicYear) { ?>
                        
                            <b> <?php echo $activeAcademicYear['academic_year']; ?> | <?php echo $activeAcademicYear['semester']; ?></b>
                        </h1>
                    <?php } ?>
                </center>
                    </li>
                </ol>         
            </div>  
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($subjects); ?></div>
                                <div>Subjects!</div>
                            </div>
                        </div>
                    </div>
                    <a href="subject.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $count1[0]; ?></div>
                                <div>Students!</div>
                            </div>
                        </div>
                    </div>
                    <a href="studentlist.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $count3[0]; ?></div>
                                <div>Instructors!</div>
                            </div>
                        </div>
                    </div>
                    <a href="teacherlist.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-gear fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $count4[0]; ?></div>
                                <div>Users!</div>
                            </div>
                        </div>
                    </div>
                    <a href="users.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="col-md-6">
                <div class="panel graph">
                    <div class="panel-heading">
                    <canvas id="myChart"></canvas>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel graph">
                    <div class="panel-heading">
                    <canvas id="myChart2"></canvas>
                    </div>
                    
                </div>
            </div>
        </div>
        <hr>
        <style>
            .graph {
                border: 2px solid lightgray;
                margin: 0px;
            }
        </style>
    </div>
    <!-- /.container-fluid -->
</div>

<script>
const statistics = <?php echo json_encode($statistics); ?>

const { population, consultation } = statistics

var ctx = document.getElementById('myChart').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['1st year', '2nd year', '3rd year', '4th year'],
        datasets: [{
            label: 'Student Population',
            data: [population.first, population.second,  population.third, population.fourth],
            backgroundColor: [
                '#ff0800',
                '#ffb516',
                '#029b02',
                '#0088ff',
            ],
            borderColor: [

                
                
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    }

    }
});
var ctx = document.getElementById('myChart2').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Acad Per', 'Behavior', 'Soc Changes', 'Others'],
        datasets: [{
            label: 'Areas of Concerns',
            data: [consultation.academic ?? 0 , consultation.behaviour ?? 0, consultation.soc ?? 0, consultation.others ?? 0],
            backgroundColor: [
                '#0088ff',
                '#029b02',
                '#ffb516',
                '#ff0800',
            ],
            borderColor: [
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    }
    }
});
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
