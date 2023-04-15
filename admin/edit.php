<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/data_model.php');
    include('data/class_model.php');
    include('data/student_model.php');
    include('data/teacher_model.php');
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
                    if($type=='subject'){
                        $edit->editsubject($subject);
                    }else if($type=='class'){
                        $edit->editclass($class);
                    }else if($type=='student'){
                        $edit->editstudent($student);   
                    }else if($type=='teacher'){
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

class Edit {
    
    function editsubject($subject){ ?>
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
            <?php while($row = mysql_fetch_array($subject)): ?>
            <form action="data/data_model.php?q=updatesubject&id=<?php echo $row['id'];?>" method="post">
            
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" value="<?php echo $row['code']; ?>" name="code" placeholder="Subject Code" />
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" value="<?php echo $row['title']; ?>" name="title" placeholder="Subject Description" />
                </div>
                <div class="form-group">
                    <label>No. Of Lec Units</label>
                    <input type="number" min="1" max="5" class="form-control" value="<?php echo $row['lecunit']; ?>" name="lecunit" placeholder="Lec Unit" />
                </div>
                <div class="form-group">
                    <label>No. Of Lab Units</label>
                    <input type="number" min="1" max="5" class="form-control" value="<?php echo $row['labunit']; ?>" name="labunit" placeholder="Lab Unit" />
                </div>
                <div class="form-group">
                    <label>No. Of Total Units</label>
                    <input type="number" min="1" max="10" class="form-control" value="<?php echo $row['totalunit']; ?>" name="totalunit" placeholder="Total Units" />
                </div>
                <div class="form-group">
                    <label>Pre-requisites/s</label>
                    <input type="text" class="form-control" value="<?php echo $row['pre']; ?>" name="pre" placeholder="Pre-requisites/s" />
                </div>
                <div class="form-group">
                <label>Year level</label>
                    <select name="year" class="form-control" required>
                        <option value="">Select Year...</option>
                        <option <?php  if($row['year'] == '1') echo "selected"?>>1</option>
                        <option <?php  if($row['year'] == '2') echo "selected"?>>2</option>
                        <option <?php  if($row['year'] == '3') echo "selected"?>>3</option>
                        <option <?php  if($row['year'] == '4') echo "selected"?>>4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semester</label>
                    <select name="semester" class="form-control" required>
                        <option value="">Select Semester...</option>
                        <option <?php  if($row['semester'] == '1') echo "selected" ?> value="1">First Semester</option>
                        <option <?php  if($row['semester'] == '2') echo "selected" ?> value="2">Second Semester</option>
                        <option <?php  if($row['semester'] == '3') echo "selected" ?> value="3">Summer</option>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <a href="subject.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</button></a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            <?php endwhile; ?>
            </form>
        </div>
        
<?php    }
    
    function editclass($class){ ?>
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
            <?php while($row = mysql_fetch_array($class)): ?>
            <form action="data/class_model.php?q=updateclass&id=<?php echo $row['id']?>" method="post">
                <div class="form-group">  
                    <select name="subject" class="form-control" required>
                        <option value="">Select Subject...</option>
                    <?php 
                        $r = mysql_query("select * from subject");
                        while($re = mysql_fetch_array($r)):
                    ?>  
                        <option <?php  if($row['subject'] == $re['code']) echo "selected"?> value="<?php echo $re['code']; ?>"><?php echo $re['code']; ?></option>
                    <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">  
                    <select name="description" class="form-control" required>
                        <option value="">Subject Description...</option>
                    <?php 
                        $r = mysql_query("select * from subject");
                        while($re = mysql_fetch_array($r)):
                    ?>  
                        <option <?php  if($row['subject'] == $re['code']) echo "selected"?> value="<?php echo $re['title']; ?>"><?php echo $re['title']; ?></option>
                    <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">  
                    <select name="teacher" class="form-control" required>
                        <option value="">Select Instructor...</option>
                    <?php 
                        $r = mysql_query("select * from teacher");
                        while($re = mysql_fetch_array($r)):
                    ?>  
                        <option <?php  if($row['teacher'] == $re['id'] ) echo "selected"?> value="<?php echo $re['id']; ?>"><?php echo $re['fname']; ?> <?php echo $re['lname']; ?></option>
                    <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="course" class="form-control" required>
                        <option value="">Select Year...</option>
                        <option <?php  if($row['course'] == 'BSIT') echo "selected"?>>BSIT</option>
                
                    </select>
                </div>
                <div class="form-group">
                    <select name="year" class="form-control" required>
                        <option value="">Select Year...</option>
                        <option <?php  if($row['year'] == '1') echo "selected"?>>1</option>
                        <option <?php  if($row['year'] == '2') echo "selected"?>>2</option>
                        <option <?php  if($row['year'] == '3') echo "selected"?>>3</option>
                        <option <?php  if($row['year'] == '4') echo "selected"?>>4</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="section" class="form-control" required>
                        <option value="">Select Section...</option>
                        <option <?php  if($row['section'] == 'North') echo "selected"?>>North</option>
                        <option <?php  if($row['section'] == 'South') echo "selected"?>>South</option>
                        <option <?php  if($row['section'] == 'East') echo "selected"?>>East</option>
                        <option <?php  if($row['section'] == 'West') echo "selected"?>>West</option>
                        <option <?php  if($row['section'] == 'North East') echo "selected"?>>North East</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="sem" class="form-control" required>
                        <option value="">Select Semester...</option>
                        <option <?php  if($row['sem'] == 'First Semester') echo "selected"?>>First Semester</option>
                        <option <?php  if($row['sem'] == 'Second Semester') echo "selected"?>>Second Semester</option>
                        <option <?php  if($row['sem'] == 'Summer') echo "selected"?>>Summer</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="sy" class="form-control" required>
                    <?php 
                            $r = mysql_query("select * from ay");
                            while($row = mysql_fetch_array($r)):
                        ?>
                            <option value="<?php echo $row['academic_year']; ?>">Academic Year : <?php echo $row['academic_year']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <a href="class.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</button></a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            </form>
            <?php endwhile; ?>
        </div>
    <?php
    }
    
    function editstudent($student){ ?>
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
            <?php while($row = mysql_fetch_array($student)): ?>
            <form action="data/student_model.php?q=updatestudent&id=<?php echo $row['id'];?>" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="studid" value="<?php echo $row['studid']; ?>" />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="lname" value="<?php echo $row['lname']; ?>" />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="fname" value="<?php echo $row['fname']; ?>" />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>" />
                </div>
                <div class="form-group">
                    <select name="year" class="form-control" required>
                        <option value="">Select Year...</option>
                        <option <?php  if($row['year'] == '1') echo "selected"?>>1</option>
                        <option <?php  if($row['year'] == '2') echo "selected"?>>2</option>
                        <option <?php  if($row['year'] == '3') echo "selected"?>>3</option>
                        <option <?php  if($row['year'] == '4') echo "selected"?>>4</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="section" class="form-control" required>
                        <option value="">Select Section...</option>
                        <option <?php  if($row['section'] == 'North') echo "selected"?>>North</option>
                        <option <?php  if($row['section'] == 'South') echo "selected"?>>South</option>
                        <option <?php  if($row['section'] == 'East') echo "selected"?>>East</option>
                        <option <?php  if($row['section'] == 'West') echo "selected"?>>West</option>
                        <option <?php  if($row['section'] == 'North East') echo "selected"?>>North East</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="semester" class="form-control" required>
                        <?php 
                            $r = mysql_query("select * from ay");
                            while($row = mysql_fetch_array($r)):
                        ?>
                            <option value="<?php echo $row['semester']; ?>"><?php echo $row['semester']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <a href="studentlist.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</button></a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            </form>
            </form>
            <?php endwhile; ?>
        </div>

    <?php    
    }
    
    function editteacher($teacher){ ?>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="studentlist.php">Instructor's List</a>
            </li>
            <li class="active">
                Edit Info
            </li>
        </ol>
        <hr />
        <div class="modal-body">
            <?php while($row = mysql_fetch_array($teacher)): ?>
            <form action="data/teacher_model.php?q=updateteacher&id=<?php echo $row['id'];?>" method="post">
                <div class="form-group">
                <p>Instructor ID:</p>
                    <input type="text" class="form-control" name="teachid" value="<?php echo $row['teachid']; ?>" />
                </div>
                <div class="form-group">
                <p>Lastname:</p>
                    <input type="text" class="form-control" name="lname" value="<?php echo $row['lname']; ?>" />
                </div>
                <div class="form-group">
                <p>Firstname:</p>
                    <input type="text" class="form-control" name="fname" value="<?php echo $row['fname']; ?>" />
                </div>
                <div class="form-group">
                <p>Middlename:</p>
                    <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>" />
                </div>
                <div class="form-group">
                    <p>Gender:</p>
                    <select class="form-control" name="sex" value="<?php echo $row['sex'];?>">
                    <label for="sex">Select Gender....</label>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
                <div class="form-group">
                <p>Email:</p>
                    <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" />
                </div>
                <div class="form-group">
                
        </div>
        <div class="modal-footer">
            <a href="teacherlist.php"><button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</button></a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
            </form>
            <?php endwhile; ?>
        </div>

    <?php    
    }
}

?>
