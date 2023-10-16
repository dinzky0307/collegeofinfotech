<?php
include('../config.php');
$level = isset($_SESSION['level']) ? $_SESSION['level'] : null;
if ($level == null) {
    header('location:../index.php');
} else if ($level != 'student') {
    header('location:../' . $level . '');
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
<?php

$stud = $dbService->fetchRow("SELECT * from student where id = {$id}");
$studsem = $stud['semester'];
$grades = $dbService->fetch(
    "SELECT subject.code, subject.title, subject.title, studentsubject.prelim_grade, studentsubject.midterm_grade, studentsubject.final_grade from studentsubject INNER JOIN subject ON studentsubject.subjectid = subject.id WHERE studid = '$id' AND studentsubject.semester = '$studsem'"
);


function gradeconversion($grade)
{
    $grade = round($grade);
    if ($grade == 0) {
        $data = 0;
    } else {
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


function getRemarks($average)
{
    if ($average > 3) {
        return '<font color="red">Failed</font>';
    }

    if ($average === 0) {
        return '<font color="black">NG</font>';
    }

    return '<font color="green">Passed</font>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../img/mcc.png">

    <title>InfoTech</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="mystyle.css" />

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<style>
    /* Dropdown Button */
    .dropbtn {
        background-color: orange;
        color: white;
        padding: 7px;
        font-size: 14px;
        border: none;
        border-radius: 3px;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 4px;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
        background-color: orange;
        border-radius: 4px;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
        background-color: orange;
    }
</style>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">MADRIDEJOS COMMUNITY COLLEGE - BSIT</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="navbar-form navbar-right">

                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#changepass"><i
                            class="fa fa-gear"></i> Change Password</button>
                    <a href="../logout.php"><button type="button" class="btn btn-danger"
                            name="submit">Logout</button></a>
                </div>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>

    <?php
    include('grade.php');
    $mysubject = $grade->getsubject();


    $stud = $dbService->fetchRow("SELECT * from student");
    $studsub = $dbService->fetchRow("SELECT * from studentsubject ");
    $sub = $dbService->fetchRow("SELECT * FROM studentsubject JOIN class WHERE studentsubject.classid = class.id");

    ?>


    <div class="container" style="margin-top:60px;" x-data="PasswordHandler">
        <label class="text-primary" style="color: black; font-size: 20px;">
            <i class="fa fa-user" style="font-size: 30px;"></i> :
            <?php echo $stud['fname']; ?>,&nbsp;
            <?php echo $stud['lname']; ?>&nbsp;
            <?php echo $stud['mname']; ?>&nbsp;&nbsp;
            <?php echo $stud['year']; ?>-
            <?php echo $stud['section']; ?>&nbsp;
        </label>
        <!-- Example row of columns -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">Report of Grades</h2>
                <h4><b>
                        <?php echo $stud['semester']; ?>
                    </b></h4>
                <div class="">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="warning warning-info">
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Subject Description</th>
                                <th class="text-center">Prelim</th>
                                <th class="text-center">Midterm</th>
                                <th class="text-center">Final</th>
                                <th class="text-center">Average</th>
                                <th class="text-center">Remarks</th>
                                <!-- <th class="text-center">Units</th>-->
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($mysubject as $row): ?>
                                <tr>
                                    <td>
                                        <?php echo $row['subject']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['description']; ?>
                                    </td>
                                    <?php $title = $grade->getsubjectitle($row['subject']); ?>
                                    <?php $mygrade = $grade->getgrade($row['id']); ?>
                                    <td class="text-center">
                                        <?php echo $grade->gradeconversion($mygrade['prelim']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $grade->gradeconversion($mygrade['midterm']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $grade->gradeconversion($mygrade['final']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $grade->gradeconversion($mygrade['total']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($mygrade['eqprelim'] > 3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqprelim'] == 0) {
                                            echo "<font color='black'>NG</font>";
                                        } else {
                                            echo "<font color='green'>Passed</font>";
                                        }
                                        ?>
                                        <?php
                                        if ($mygrade['eqmidterm'] > 3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqmidterm'] == 0) {
                                            echo "<font color='black'>NG</font>";
                                        } else {
                                            echo "<font color='green'>Passed</font>";
                                        }
                                        ?>
                                        <?php
                                        if ($mygrade['eqfinal'] > 3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqfinal'] == 0) {
                                            echo "<font color='black'>NG</font>";
                                        } else {
                                            echo "<font color='green'>Passed</font>";
                                        }
                                        ?>
                                        <?php
                                        if ($mygrade['eqtotal'] > 3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqtotal'] == 0) {
                                            echo "<font color='black'>NG</font>";
                                        } else {
                                            echo "<font color='green'>Passed</font>";
                                        }
                                        ?>
                                    </td>
                                    <!-- <td class="text-center"><?php echo $title[0]['unit']; ?></td>-->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </form>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>