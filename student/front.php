<?php
    include('include/header.php');
    include('include/sidebar.php');

    $level = isset($_SESSION['level']) ? $_SESSION['level']: null;
    if($level == null){
        header('location:../index.php');
    }else if($level != 'student'){
        header('location:../'.$level.'');
    }
    include('../Carbon.php');
    include('../DatabaseService.php');

    use Carbon\Carbon;
    use Database\DatabaseService;

    $dbService = new DatabaseService;
    $selects = 'userdata.level, consultations.id, CONCAT(userdata.fname, " ", userdata.lname) AS name, consultations.areas_concern, consultations.created_at';
    $joins = 'LEFT JOIN consultations ON userdata.id  = consultations.consultant_id';
    $consultations = $dbService->fetch(
        "SELECT {$selects} from userdata {$joins} WHERE student_id = {$_SESSION['user_id']} ORDER BY created_at DESC"
    );

    if (isset($_POST['confirm'])) {
        $dbService = new DatabaseService;

        $currentPasswordMatched = $dbService->fetchRow(
            "SELECT* from userdata WHERE password = '{$_POST['current']}' AND id = {$_SESSION['user_id']}"
        );

        if (!$currentPasswordMatched) {
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
                title: 'Current password is incorrect!',
                icon: 'error',
            })";
            echo "</script>";
        } else {
            $dbService->updatePassword([
                'id' => $_SESSION['user_id'],
                'password_confirmation' => $_POST['confirm'],
            ]);
    
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
                title: 'Password changed!',
                icon: 'success',
            })";
            echo "</script>";
        }
    }
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Dashboard <small>Statistics Overview</small>
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $count1[0];?></div>
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
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $students; ?></div>
                                <div>Students!</div>
                            </div>
                        </div>
                    </div>
                    <a href="student.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>            
        </div>
        <!-- /.row -->
        

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');