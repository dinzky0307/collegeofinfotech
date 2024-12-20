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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="mystyle.css" />
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<style>
        .box-size {
        text-align: center;
        width: 70px;
        height: 25px;
        padding: 6px 12px;
        color: #555;
        background-color: transparent;
        border: none;
        margin-top: -7px;
    }
        input::-webkit-inner-spin-button {
            -webkit-appearance: inner-spin-button;
            display: inline-block;
            cursor: default;
            flex: 0 0 auto;
            align-self: stretch;
            -webkit-user-select: none;
            opacity: 0;
            pointer-events: none;
            -webkit-user-modify: read-only;
        }
            .btn-text-right{
                text-align: right;
        }


        .dropdown-menu-right {
          text-align: left;
          min-width: 160px;
     }
.dropdown-menu a {
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    display: flex;
    align-items: center;
}
.dropdown-menu a i {
    margin-right: 8px;
}
.dropdown-menu a:hover {
    background-color: #f5f5f5;
}
.text-danger {
    color: #d9534f !important;
}
</style>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php" style="font-size: 15px;">MADRIDEJOS COMMUNITY COLLEGE - BSIT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle btn btn-primary text-white" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo $_SESSION['name']; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="index.php">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="form.php">
                                <i class="fa fa-envelope"></i> Consultation
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changepass">
                                <i class="fa fa-gear"></i> Change Password
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="../logout.php">
                                <i class="fa fa-power-off"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
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
        <!-- <label class="text-primary" style="color: black; font-size: 20px;">
            <i class="fa fa-user" style="font-size: 30px;"></i> :
            <?php echo $_SESSION['name']; ?>&nbsp;&nbsp;
        </label> -->

        <!-- Example row of columns -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center" style="margin-left: 90px; margin-top: 20px;">Report of Grades</h2>
                <form id="yearSemesterForm">
                    <label for="cars">Select Year:</label>
                    <select name="year" id="year" class="dropbtn" onchange="submitYearSemester()">
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
                    <select name="semester" id="semester" class="dropbtn" onchange="submitYearSemester()">
                        <option value="First Semester" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'First Semester' ? 'selected' : ''; ?>>First Semester</option>
                        <option value="Second Semester" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'Second Semester' ? 'selected' : ''; ?>>Second Semester</option>
                        <option value="Summer" <?php echo isset($_GET['semester']) && $_GET['semester'] === 'Summer' ? 'selected' : ''; ?>>Summer</option>
                    </select>
                </form>
                <div class="">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="warning warning-info">
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Subject Description</th>
                                <!-- <th class="text-center">Prelim</th> -->
                                <!-- <th class="text-center">Remark</th> -->
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
                            <?php foreach ($mysubject as $row) : ?>
                                <tr>
                                    <td><?php echo $row['code']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <!-- Display grades -->
                                    <!-- <td class="text-center"><?php echo isset($row['prelim_grade']) ? $row['prelim_grade'] : ''; ?></td> -->
                                    <!-- <td class="text-center"><?php echo isset($row['prelim_grade']) ? ($row['prelim_grade'] > 3 ? '<font color="red">Failed</font>' : '<font color="green">Passed</font>') : '<font color="black">NG</font>'; ?></td> -->
                                    <td class="text-center"><?php echo isset($row['midterm_grade']) ? $row['midterm_grade'] : ''; ?></td>
                                    <td class="text-center"><?php echo isset($row['midterm_grade']) ? ($row['midterm_grade'] > 3 ? '<font color="red">Failed</font>' : '<font color="green">Passed</font>') : '<font color="black">NG</font>'; ?></td>
                                    <td class="text-center"><?php echo isset($row['final_grade']) ? $row['final_grade'] : ''; ?></td>
                                    <td class="text-center"><?php echo isset($row['final_grade']) ? ($row['final_grade'] > 3 ? '<font color="red">Failed</font>' : '<font color="green">Passed</font>') : '<font color="black">NG</font>'; ?></td>
                                    <td class="text-center"><?php echo isset($row['total']) ? sprintf("%.1f", $row['total']) : ''; ?></td>
                                    <td class="text-center"><?php echo isset($row['total']) ? ($row['total'] > 3 ? '<font color="red">Failed</font>' : '<font color="green">Passed</font>') : '<font color="black">NG</font>'; ?></td>
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
                    <a href="form.php"><button type="button" class="btn btn-success" name="submit" style=" margin-bottom: 10px;">Consultation Form</button></a>
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
                                <?php foreach ($consultations as $consultation) : ?>
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
            <div class="modal fade" id="changeEmailModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Change Email</h3>
                        </div>
                        <div class="modal-body">
                            <form action="change_email.php" method="post">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="new_email" placeholder="New Email Address" required />
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
            <div class="modal fade" id="changepass" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Change Password</h3>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" x-ref="passwordForm">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="current" placeholder="Current Password" x-ref="current" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="new" placeholder="New Password" x-ref="new" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="confirm" placeholder="Confirm Password" x-ref="confirm" />
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
        function submitYearSemester() {
            document.getElementById("yearSemesterForm").submit();
        }
    </script>

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
    $(".remove").click(function() {
        var id = $(this).parents("tr").attr("id");
        alert("Record deleted successfully");
    });
</script>

</html>