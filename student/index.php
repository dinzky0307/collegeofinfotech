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
if (isset($_SESSION['level']) == "student") {
    $consultations = $dbService->fetch(
        "SELECT {$selects} from userdata {$joins} WHERE student_id = {$_SESSION['user_id']} ORDER BY created_at DESC"
    );
}
if (isset($_POST['confirm'])) {

    $sql = mysql_query("SELECT * FROM userdata WHERE id = '" . $_SESSION['user_id'] . "' ");

    if (mysql_num_rows($sql) > 0) {

        $data = mysql_fetch_assoc($sql);

        if (!password_verify($_POST['current'], $data['password'])) {
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
                    title: 'Current password is incorrect!',
                    icon: 'error',
                })";
            echo "</script>";
        } else {

            $password = password_hash($_POST['confirm'], PASSWORD_DEFAULT);

            $update = mysql_query("UPDATE userdata SET password = '$password' WHERE id = '" . $_SESSION['user_id'] . "'  ");

            unset($_SESSION['level']);

            ?>
            <script>
                alert("Password successfully changed")
                window.location.href = "../index.php"
            </script>
        <?php


        }
    }
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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


</head>
<style>
    /* Dropdown Button */
    .dropbtn {
        background-color: ;
        color: black;
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

                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#changeEmailModal">
                        <i class="fa fa-envelope"></i> Change Email
                    </button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#changepass"><i
                            class="fa fa-gear"></i> Change Password</button>
                    <a href="../logout.php"><button type="button" class="btn btn-danger"
                            name="submit">Logout</button></a>
                </div>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>

    <?php
    include('connection.php');
    include('grade.php');
    $mysubject = $grade->getsubject();

    // $stud = $dbService->fetchRow("SELECT * from student");
    // $studsub = $dbService->fetchRow("SELECT * from studentsubject ");
    // $sub = $dbService->fetchRow("SELECT * FROM studentsubject JOIN class WHERE studentsubject.classid = class.id");
    
    ?>


    <div class="container" style="margin-top:60px;" x-data="PasswordHandler">
        <label class="text-primary" style="color: black; font-size: 20px;">
            <i class="fa fa-user" style="font-size: 30px;"></i> :
            <?php echo $_SESSION['name']; ?>&nbsp;&nbsp;
        </label>

        <!-- Example row of columns -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">Report of Grades</h2>
                <form action="">
                    <label for="cars">Select Year:</label>
                    <select name="year" id="year" class="dropbtn">
                        <option value="1" <?php echo isset($_GET['year']) && $_GET['year'] === '1' ? 'selected' : ''; ?>>
                            1st Year</option>
                        <option value="2" <?php echo isset($_GET['year']) && $_GET['year'] === '2' ? 'selected' : ''; ?>>
                            2nd Year</option>
                        <option value="3" <?php echo isset($_GET['year']) && $_GET['year'] === '3' ? 'selected' : ''; ?>>
                            3rd Year</option>
                        <option value="4" <?php echo isset($_GET['year']) && $_GET['year'] === '4' ? 'selected' : ''; ?>>
                            4th Year</option>
                    </select>
                    <label for="cars" style="margin-left: 20px;">Select Semester:</label>
                    <select name="semester" id="semester" class="dropbtn">
                        <option value="First Semester" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'First Semester' ? 'selected' : ''; ?>>First Semester</option>
                        <option value="Second Semester" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'Second Semester' ? 'selected' : ''; ?>>Second Semester</option>
                        <option value="Summer" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'Summer' ? 'selected' : ''; ?>>Summer</option>
                    </select>
                    <button type="search" name="year_semester" class="btn btn-outline-light"><i
                            class="fa fa-search"></i>
                        Search
                    </button>
                </form>
                <div class="">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="warning warning-info">
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Subject Description</th>
                                <th class="text-center">Prelim</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center">Midterm</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center">Final</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center">Final Ratings</th>
                                <th class="text-center">Final Remarks</th>
                                <!-- <th class="text-center">Units</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
// print_r($mysubject);
// print_r($row);
                                foreach ($mysubject as $row): ?> 
                                    
                                <tr>
                                    <td>
                                        <?php echo $row['subject']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['description']; ?>
                                    </td>
                                    <?php $title = $grade->getsubjectitle($row['subject']); ?>
                                    <?php $mygrade = $grade->getgrade($row['year'], $row['section'], $row['sem'], $row['SY'], $row['subject']); 
// print_r($mygrade);
                                    ?>
                                    <td class="text-center">
                                        <?php if (isset($mygrade['prelim_grade'])): ?>
                                            <?php echo $grade->gradeconversion($mygrade['prelim_grade']); ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        if (isset($mygrade['eqprelim'])) {
                                            if ($mygrade['eqprelim'] > 3) {
                                                echo "<font color='red'>Failed</font>";
                                            } else if ($mygrade['eqprelim'] == 0) {
                                                echo "<font color='black'>NG</font>";
                                            } else {
                                                echo "<font color='green'>Passed</font>";
                                            }
                                        } else {
                                            echo "<font color='black'>NG</font>";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (isset($mygrade['midterm_grade'])): ?>
                                            <?php echo $grade->gradeconversion($mygrade['midterm_grade']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if (isset($mygrade['eqmidterm'])) {
                                            if ($mygrade['eqmidterm'] > 3) {
                                                echo "<font color='red'>Failed</font>";
                                            } else if ($mygrade['eqmidterm'] == 0) {
                                                echo "<font color='black'>NG</font>";
                                            } else {
                                                echo "<font color='green'>Passed</font>";
                                            }
                                        } else {
                                            echo "<font color='black'>NG</font>";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (isset($mygrade['finals_grade'])): ?>
                                            <?php echo $grade->gradeconversion($mygrade['finals_grade']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if (isset($mygrade['eqfinal'])) {
                                            if ($mygrade['eqfinal'] > 3) {
                                                echo "<font color='red'>Failed</font>";
                                            } else if ($mygrade['eqfinal'] == 0) {
                                                echo "<font color='black'>NG</font>";
                                            } else {
                                                echo "<font color='green'>Passed</font>";
                                            }
                                        } else {
                                            echo "<font color='black'>NG</font>";
                                        }
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if (isset($mygrade['total'])): ?>
                                            <?php echo $grade->gradeconversion($mygrade['total']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if (isset($mygrade['eqtotal'])) {
                                            if ($mygrade['eqtotal'] > 3) {
                                                echo "<font color='red'>Failed</font>";
                                            } else if ($mygrade['eqtotal'] == 0) {
                                                echo "<font color='black'>NG</font>";
                                            } else {
                                                echo "<font color='green'>Passed</font>";
                                            }
                                        } else {
                                            echo "<font color='black'>NG</font>";
                                        }
                                        ?>
                                    </td>
                                    <!-- <td class="text-center"><?php echo $title[0]['unit']; ?></td>-->
                                </tr>
                                <!-- <td class="text-center"><?php echo $title[0]['unit']; ?></td>-->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container" style="margin-top:60px;">
            <!-- Example row of columns -->
            <?php

            if (isset($_POST["delete"])) {
                $id = $_POST['id'];
                $sql = "delete from consultations where id = '$id'";

                if ($dbconnection->query($sql) === TRUE) {
                    echo "<script type='text/javascript'>";
                    echo "Swal.fire({
                    title: 'Record Successfully Deleted',
                    icon: 'success',
                    })";
                    echo "</script>";
                } else {
                    echo "Error Deleting record: " . $dbconnection->error;
                }
            }
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-center">Consultation</h2>
                    <a href="form.php"><button type="button" class="btn btn-success" name="submit"
                            style=" margin-bottom: 10px;">Consultation Form</button></a>
                    <div class="">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="warning warning-info">
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Consulted to:</th>
                                    <th class="text-center">Concern</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">View</th>
                                    <!--                             <th class="text-center">Delete</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consultations as $consultation): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $consultation['level']; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $consultation['name']; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $consultation['areas_concern']; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            echo Carbon::parse($consultation['created_at'])->format('F d, Y');
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="response.php?id=<?php echo $consultation['id']; ?>">View</a>
                                        </td>
                                        <!--                                 <td class="text-center">
                            <form action="" method="POST">
                            <input type="hidden" name="id" value="<?php echo $consultation['id']; ?>">
                            <button type="submit" name="delete" style="border: none;"><i class="fa fa-trash-o text-danger fa-lg "></i></button>
                            </form></td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal for changing email -->
            <div class="modal fade" id="changeEmailModal" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Change Email</h3>
                        </div>
                        <div class="modal-body">
                            <form action="change_email.php" method="post">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="new_email"
                                        placeholder="New Email Address" required />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- add modal for subject -->
            <div class="modal fade" id="changepass" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Change Password</h3>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" x-ref="passwordForm">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="current"
                                        placeholder="Current Password" x-ref="current" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="new" placeholder="New Password"
                                        x-ref="new" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="confirm"
                                        placeholder="Confirm Password" x-ref="confirm" />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" @click="validate" class="btn btn-primary"><i class="fa fa-plus"></i>
                                Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /.col-md-8 -->
    </div>

    </div>


    </div>
    <?php

    $stud = $dbService->fetchRow("SELECT* from student");
    ?>




    </form>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <script>
        // window.PasswordHandler = () => {
        //     return {
        //         validate() {
        //             const currentPass = this.$refs.current
        //             const newPass = this.$refs.new;
        //             const confirmPass = this.$refs.confirm

        //             if (currentPass.value.length <= 0) {
        //                 Swal.fire({
        //                     icon: 'warning',
        //                     title: 'Current password is empty!'
        //                 })

        //                 return
        //             }

        //             if (newPass.value.length <= 0) {
        //                 Swal.fire({
        //                     icon: 'warning',
        //                     title: 'New password is empty!'
        //                 })

        //                 return
        //             }

        //             if (newPass.value !== confirmPass.value) {
        //                 Swal.fire({
        //                     icon: 'warning',
        //                     title: 'Password type mismatched!'
        //                 })

        //                 return
        //             }

        //             this.$refs.passwordForm.submit()
        //         }
        //     }
        // }
    </script>
</body>
</script>
</body>
<script type="text/javascript">
    $(".remove").click(function () {
        var id = $(this).parents("tr").attr("id");
        alert("Record deleted successfully");
    });


</script>

</html>
