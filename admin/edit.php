<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/data_model.php');
include('data/class_model.php');
include('data/student_model.php');
include('data/teacher_model.php');
$student = new Datastudent($connection); // Create an instance of the Datastudent class and pass the connection as a parameter
$data = new Data($connection);
$teacher = new Datateacher($connection);
$class = new Dataclass($connection);
if (isset($_GET['q'])) {
    $classData = $class->$_GET['q']();
}
if (isset($_GET['q'])) {
    $teacherData = $teacher->$_GET['q']();
}
if (isset($_GET['q'])) {
    $data->$_GET['q']();
}
if (isset($_GET['q'])) {
    $studentData = $student->$_GET['q']();
}
$id = $_GET['id'];
$subject = $data->getsubjectbyid($id);
$class = $class->getclassbyid($id);
$student = $student->getstudentbyid($id);
$teacher = $teacher->getteacherbyid($id);
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>EDIT</small>
                </h1>
                <?php
                $edit = new Edit();
                $type = $_GET['type'];
                if ($type == 'subject') {
                    $edit->editsubject($subject);
                } else if ($type == 'class') {
                    $edit->editclass($class);
                } else if ($type == 'student') {
                    $edit->editstudent($student);
                } else if ($type == 'teacher') {
                    $edit->editteacher($teacher);
                }
                ?>
            </div>
        </div>
        <!-- /.row -->




    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php include('include/footer.php');

class Edit
{

    function editsubject($subject)
    { ?>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="subject.php">Subject</a>
            </li>
            <li class="active">
                Edit
            </li>
        </ol>
        <hr />
        <div class="modal-body">
            <form action="ajax.php?action=updatesubject&id=<?php echo $subject['id']; ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" value="<?php echo $subject['code']; ?>" name="code" placeholder="Subject Code" style="font-size: 18px;" />
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="<?php echo $subject['title']; ?>" name="title" placeholder="Subject Description" style="font-size: 18px;" />
                        </div>
                        <div class="form-group">
                            <label>No. Of Lec Units</label>
                            <input type="number" min="1" max="5" class="form-control" value="<?php echo $subject['lecunit']; ?>" name="lecunit" placeholder="Lec Unit" style="font-size: 18px;" />
                        </div>
                        <div class="form-group">
                            <label>No. Of Lab Units</label>
                            <input type="number" min="1" max="5" class="form-control" value="<?php echo $subject['labunit']; ?>" name="labunit" placeholder="Lab Unit" style="font-size: 18px;" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. Of Total Units</label>
                            <input type="number" min="1" max="10" class="form-control" value="<?php echo $subject['totalunit']; ?>" name="totalunit" placeholder="Total Units" style="font-size: 18px;" />
                        </div>
                        <div class="form-group">
                            <label>Pre-requisites/s</label>
                            <input type="text" class="form-control" value="<?php echo $subject['pre']; ?>" name="pre" placeholder="Pre-requisites/s" style="font-size: 18px;" />
                        </div>
                        <div class="form-group">
                            <label>Year level</label>
                            <select name="year" class="form-control" required style="height: 32px; font-size: 18px;">
                                <option value="">Select Year...</option>
                                <option <?php if ($subject['year'] == '1')
                                            echo "selected" ?>>1</option>
                                <option <?php if ($subject['year'] == '2')
                                            echo "selected" ?>>2</option>
                                <option <?php if ($subject['year'] == '3')
                                            echo "selected" ?>>3</option>
                                <option <?php if ($subject['year'] == '4')
                                            echo "selected" ?>>4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <select name="semester" class="form-control" required style="height: 34px; font-size: 18px;">
                                <option value="">Select Semester...</option>
                                <option <?php if ($subject['semester'] == '1')
                                            echo "selected" ?> value="1">First Semester
                                </option>
                                <option <?php if ($subject['semester'] == '2')
                                            echo "selected" ?> value="2">Second Semester
                                </option>
                                <option <?php if ($subject['semester'] == '3')
                                            echo "selected" ?> value="3">Summer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="subject.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i>
                            Back</button></a>
                    <button type="submit" class="btn btn-primary" name="updateSubject"><i class="fa fa-check"></i>
                        Update</button>
            </form>
        </div>

    <?php }

    function editclass($class)
    { ?>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="class.php">Class Info</a>
            </li>
            <li class="active">
                Edit
            </li>
        </ol>
        <hr />
        <div class="modal-body">
            <?php while ($row = mysql_fetch_array($class)) : ?>
                <form action="ajax.php?action=updateclass&id=<?php echo $row['id'] ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="subject" class="form-control" required style="height:38px; font-size:18px">
                                    <option value="">Select Subject...</option>
                                    <?php
                                    $r = mysql_query("select * from subject");
                                    while ($re = mysql_fetch_array($r)) :
                                    ?>
                                        <option <?php if ($row['subject'] == $re['code'])
                                                    echo "selected" ?> value="<?php echo $re['code']; ?>">
                                            <?php echo $re['code']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="description" class="form-control" required style="height:38px; font-size:18px">
                                    <option value="">Subject Description...</option>
                                    <?php
                                    $r = mysql_query("select * from subject");
                                    while ($re = mysql_fetch_array($r)) :
                                    ?>
                                        <option <?php if ($row['subject'] == $re['code'])
                                                    echo "selected" ?> value="<?php echo $re['title']; ?>">
                                            <?php echo $re['title']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="teacher" class="form-control" required style="height:38px; font-size:18px">
                                    <option value="">Select Instructor...</option>
                                    <?php
                                    $r = mysql_query("select * from teacher");
                                    while ($re = mysql_fetch_array($r)) :
                                    ?>
                                        <option <?php if ($row['teacher'] == $re['id'])
                                                    echo "selected" ?> value="<?php echo $re['id']; ?>">
                                            <?php echo $re['fname']; ?>
                                            <?php echo $re['lname']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="course" class="form-control" required style="height:38px; font-size:18px">
                                    <option <?php if ($row['course'] == 'BSIT')
                                                echo "selected" ?>>BSIT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="year" class="form-control" required style="height:38px; font-size:18px">
                                    <option value="">Select Year...</option>
                                    <option <?php if ($row['year'] == '1')
                                                echo "selected" ?>>1</option>
                                    <option <?php if ($row['year'] == '2')
                                                echo "selected" ?>>2</option>
                                    <option <?php if ($row['year'] == '3')
                                                echo "selected" ?>>3</option>
                                    <option <?php if ($row['year'] == '4')
                                                echo "selected" ?>>4</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="section" class="form-control" required style="height:38px; font-size:18px">
                                    <option value="">Select Section...</option>
                                    <option <?php if ($row['section'] == 'North')
                                                echo "selected" ?>>North</option>
                                    <option <?php if ($row['section'] == 'South')
                                                echo "selected" ?>>South</option>
                                    <option <?php if ($row['section'] == 'East')
                                                echo "selected" ?>>East</option>
                                    <option <?php if ($row['section'] == 'West')
                                                echo "selected" ?>>West</option>
                                    <option <?php if ($row['section'] == 'North East')
                                                echo "selected" ?>>North East</option>
                                    <option <?php if ($row['section'] == 'North West')
                                                echo "selected" ?>>North West</option>
                                    <option <?php if ($row['section'] == 'South East')
                                                echo "selected" ?>>South East</option>
                                    <option <?php if ($row['section'] == 'South West')
                                                echo "selected" ?>>South West</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="sem" class="form-control" required style="height:38px; font-size:18px">
                                    <option value="">Select Semester...</option>
                                    <option <?php if ($row['sem'] == 'First Semester')
                                                echo "selected" ?>>First Semester</option>
                                    <option <?php if ($row['sem'] == 'Second Semester')
                                                echo "selected" ?>>Second Semester</option>
                                    <option <?php if ($row['sem'] == 'Summer')
                                                echo "selected" ?>>Summer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="sy" class="form-control" required style="height:38px; font-size:18px">
                                    <?php
                                    $r = mysql_query("select * from ay");
                                    while ($row = mysql_fetch_array($r)) :
                                    ?>
                                        <option value="<?php echo $row['academic_year']; ?>">Academic Year :
                                            <?php echo $row['academic_year']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="class.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i>
                                Back</button></a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
                </form>
            <?php endwhile; ?>
        </div>
    <?php
    }

    function editstudent($student)
    { ?>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="studentlist.php">Student's List</a>
            </li>
            <li class="active">
                Edit
            </li>
        </ol>
        <hr />
        <div class="modal-body">
            <?php while ($row = mysql_fetch_array($student)) : ?>
                <form action="ajax.php?action=updatestudent&id=<?php echo $row['id']; ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Student ID</label>
                                <input type="text" class="form-control" name="studid" value="<?php echo $row['studid']; ?>" style="height:40px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" class="form-control" name="lname" value="<?php echo $row['lname']; ?>" style="height:40px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" class="form-control" name="fname" value="<?php echo $row['fname']; ?>" style="height:40px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Middlename</label>
                                <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>" style="height:40px; font-size:18px" />
                            </div>
                        </div>

                        <!-- <div class="row"> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>" style="height:40px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Year level</label>
                                <select name="year" class="form-control" required style="height:40px; font-size:18px">
                                    <option value="">Select Year...</option>
                                    <option <?php if ($row['year'] == '1')
                                                echo "selected" ?>>1</option>
                                    <option <?php if ($row['year'] == '2')
                                                echo "selected" ?>>2</option>
                                    <option <?php if ($row['year'] == '3')
                                                echo "selected" ?>>3</option>
                                    <option <?php if ($row['year'] == '4')
                                                echo "selected" ?>>4</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Section</label>
                                <select name="section" class="form-control" required style="height:40px; font-size:18px">
                                    <option value="">Select Section...</option>
                                    <option <?php if ($row['section'] == 'North')
                                                echo "selected" ?>>North</option>
                                    <option <?php if ($row['section'] == 'South')
                                                echo "selected" ?>>South</option>
                                    <option <?php if ($row['section'] == 'East')
                                                echo "selected" ?>>East</option>
                                    <option <?php if ($row['section'] == 'West')
                                                echo "selected" ?>>West</option>
                                    <option <?php if ($row['section'] == 'North East')
                                                echo "selected" ?>>North East</option>
                                    <option <?php if ($row['section'] == 'North West')
                                                echo "selected" ?>>North West</option>
                                    <option <?php if ($row['section'] == 'South East')
                                                echo "selected" ?>>South East</option>
                                    <option <?php if ($row['section'] == 'South West')
                                                echo "selected" ?>>South West</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Semester</label>
                                <select name="semester" class="form-control" required style="height:40px; font-size:18px">
                                    <?php
                                    $r = mysql_query("select * from ay");
                                    while ($row = mysql_fetch_array($r)) :
                                    ?>
                                        <option value="<?php echo $row['semester']; ?>">
                                            <?php echo $row['semester']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="studentlist.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i>
                                Back</button></a>
                        <button type="submit" name="update_student" class="btn btn-primary"><i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            <?php endwhile; ?>
            <?php
            if (isset($_POST['update_student'])) {
                $id = $_GET['id'];
                $email = $_POST['email'];

                // Update student email in student table
                $query_student = "UPDATE student SET email='$email' WHERE id='$id'";
                $result_student = mysql_query($query_student);
                if (!$result_student) {
                    die("Student table update failed: " . mysql_error());
                }

                // Update student email in userdata table
                $query_userdata = "UPDATE userdata SET email='$email' WHERE id='$id'";
                $result_userdata = mysql_query($query_userdata);
                if (!$result_userdata) {
                    die("Userdata table update failed: " . mysql_error());
                }

                // Redirect or display success message
                // You can add appropriate redirection or message here
            }
            ?>
        </div>

    <?php
    }

    function editteacher($teacher)
    { ?>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="teacherlist.php">Instructor's List</a>
            </li>
            <li class="active">
                Edit Info
            </li>
        </ol>
        <hr />
        <div class="modal-body">
            <?php while ($row = mysql_fetch_array($teacher)) : ?>
                <form action="ajax.php?action=updateteacher&id=<?php echo $row['id']; ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" class="form-control" name="lname" value="<?php echo $row['lname']; ?>" style="height:38px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" class="form-control" name="fname" value="<?php echo $row['fname']; ?>" style="height:38px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Middlename</label>
                                <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>" style="height:38px; font-size:18px" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instructor ID</label>
                                <input type="text" class="form-control" name="teachid" value="<?php echo $row['teachid']; ?>" style="height:38px; font-size:18px" />
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" name="sex" value="<?php echo $row['sex']; ?>" style="height:38px; font-size:18px">
                                    <label for="sex">Select Gender....</label>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" style="height:40px; font-size:18px" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="teacherlist.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i>
                                Back</button></a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
                </form>
            <?php endwhile; ?>
        </div>

<?php
    }
}

?>