<?php
    include('include/header.php');
    include('include/sidebar.php');  
    include '../DatabaseService.php';
    use Database\DatabaseService;

    $dbService = new DatabaseService;
    $baseCountQuery = 'SELECT count(*) from student';

    $statistics = [
        'population' => [
            'first' => $dbService->countRows($baseCountQuery . ' WHERE year = 1'),
            'second' => $dbService->countRows($baseCountQuery . ' WHERE year = 2'),
            'third' => $dbService->countRows($baseCountQuery . ' WHERE year = 3'),
            'fourth' => $dbService->countRows($baseCountQuery . ' WHERE year = 4')
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

    $r1 = mysql_query('select count(*) from student');
    $count1 = mysql_fetch_array($r1);

    $r2 = mysql_query('select count(*) from subject');
    $count2 = mysql_fetch_array($r2);

    $r3 = mysql_query('select count(*) from teacher');
    $count3 = mysql_fetch_array($r3);

    $r4 = mysql_query('select count(*) from userdata');
    $count4 = mysql_fetch_array($r4);


    $ay = $dbService->fetchRow("SELECT * from ay");


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
                        <h1>
                        <marquee behavior="scroll" direction="left" style="" scrollamount="10"><b>Current Academic Year and Semester:</b> <?php echo $ay['academic_year']; ?> | <?php echo $ay['semester']; ?></marquee>
                        </h1>
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
                                <div class="huge"><?php echo $count2[0]; ?></div>
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