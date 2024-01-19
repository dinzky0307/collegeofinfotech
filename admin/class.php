<?php
      include('include/header.php');
      include('include/sidebar.php');
      include('../database.php');
      include('data/class_model.php');      
      
      $dataclass = new Dataclass($connection);
      if (isset($_GET['q'])) {
          $dataclass->$_GET['q']();
      }

      $search = '';
      
        // Fetch the active academic year from the database
        include '../DatabaseService.php';
        use Database\DatabaseService;

        $dbService = new DatabaseService;

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

// Fetch class data only if academic year is active
$classData = [];
if ($academicYearActive) {
    $search = isset($_POST['search']) ? $_POST['search'] : null;
    // Modify the query to join the 'ay' and 'class' tables on the academic_year and SY columns
    $sql = "SELECT c.* FROM class c
            INNER JOIN ay a ON c.SY = a.academic_year AND c.sem = a.semester
            WHERE c.SY = :academicYear AND a.semester = :semester";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':academicYear', $academic_year, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
    $stmt->execute();
    $classData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


      function deleteClass($classId, $connection)
      {
          // Write your code to delete the class with the given class ID
          $sql = "DELETE FROM class WHERE id = :classId";
          $stmt = $connection->prepare($sql);
          $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
          $stmt->execute();
      }
      
      // Check if the delete button is clicked
      if (isset($_POST['deleteClass']) && isset($_POST['classId'])) {
          $classId = $_POST['classId'];
          $delete = deleteClass($classId, $connection);
      
    // Redirect to the same page after the deletion
    ?>
    <script type="text/javascript">
        alert("Class successfully deleted")
        window.location.href = "class.php"
    </script>
    <?php
    exit(); // Make sure to exit after redirecting to prevent further code execution
}
?>

<style>
    .modalCenter {
        top: 17% !important;
        transform: translateY(-17%) !important;
    }
    /* Custom styling for the delete button */
    .delete-button {
        border: none;
        background: none;
        padding: 0;
        color: #f00; /* Change the color as per your preference */
        cursor: pointer;
        outline: none; /* Remove outline on focus */
    }
    </style>
</style>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>CLASS INFORMATION</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Class
                    </li>
                </ol>
            </div>
        </div>

        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-inline form-padding" style="float: right;">
                    <form action="class.php" method="post">                              
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addclass">Add Class</button>
                    </form>
                </div>
            </div>
        </div>

        <!--/.row -->
        <hr />

        <div class="row">
            <div class="col-lg-12">
                <?php if(isset($_GET['r'])): ?>
                    <?php
                        $r = $_GET['r'];
                        if($r=='added'){
                            $classs='success';   
                        }else if($r=='updated'){
                            $classs='info';   
                        }else if($r=='deleted'){
                            $classs='danger';   
                        }else{
                            $classs='hide';
                        }
                    ?>
                    <div class="alert alert-<?php echo $classs?> <?php echo $classs; ?>">
                        <strong>Class info successfully <?php echo $r; ?>!</strong>    
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="classInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Subject Description</th>
                                <th class="text-center">Course</th>
                                <th class="text-center">Year & Section</th>
                                <th class="text-center">Semester</th>
                                <th class="text-center">S.Y.</th>
                                <th class="text-center">Instructor</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $index = 1;
                            foreach ($classData as $class): ?>
                                <tr>
                                    <td><?php echo $index++;?></td>
                                    <td><?php echo $class['subject'];?></td>
                                    <td><?php echo $class['description'];?></td>
                                    <td class="text-center"><?php echo $class['course'];?></td>
                                    <td class="text-center"><?php echo $class['year'].'-'.$class['section'];?></td>                                
                                    <td class="text-center"><?php echo $class['sem'];?></td>                                
                                    <td class="text-center"><?php echo $class['SY'];?></td>                                
                                    <td class="text-center"><a href="classteacher.php?classid=<?php echo $class['id'];?>&teacherid=<?php echo $class['teacher'];?>" title="update teacher">View</a></td>
                                    <td class="text-center"><a href="classstudent.php?classid=<?php echo $class['id'];?>&y=<?php echo $class['year'];?>&sem=<?php echo $class['sem'];?>&s=<?php echo $class['section'];?>&code=<?php echo $class['subject'];?>&SY=<?php echo $class['SY'];?>" title="update students" title="add student">View</a></td>
                                    <td class="text-center">  
                                    <div style="display: inline-block;">
                                        <a href="edit.php?type=class&id=<?php echo $class['id']?>" title="Update Class"><i class="fa fa-edit fa-lg text-primary"></i></a>
                                    </div>
                                        <form class="delete-form" method="post" style= "display: inline-block;">
                                            <input type="hidden" name="classId" value="<?php echo $class['id']; ?>">
                                            <button type="submit" name="deleteClass" class="delete-button" title="Remove" onclick="return confirm('Are you sure you want to delete this class?');">
                                                <i class="fa fa-trash-o fa-lg text-danger"></i>
                                            </button>
                                        </form>
                                        <!-- <a href="data/data_model.php?q=delete&table=class&id=<?php echo $class['id']?>" title="Remove Class"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></td> -->
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <!-- add modal for class info -->
    <div class="modal fade" id="addclass" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modalCenter">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add Class Info</h3>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <select name="subject" class="form-control" required style="font-size: 16px; height: 35px">
                                <option value="">Select Subject Code...</option>
                                <?php
                                $r = mysql_query("SELECT * FROM subject");
                                while ($row = mysql_fetch_array($r)) :
                                ?>
                                    <option value="<?php echo $row['code']; ?>" data-title="<?php echo $row['title']; ?>" data-year="<?php echo $row['year']; ?>" data-semester="<?php echo $row['semester']; ?>"><?php echo $row['code']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="description" class="form-control" readonly required placeholder="Subject Description" style="font-size: 16px; height: 35px">
                        </div>
                        <!-- Add hidden input fields for subject year level and semester -->
                        <input type="hidden" name="year_level" id="yearLevel">
                        <input type="hidden" name="subject_semester" id="subjectSemester">
                        <div class="form-group">
                            <select name="teacher" class="form-control" required style="font-size: 16px; height: 35px">
                                <option value="">Select Teacher...</option>
                                <?php foreach ($teachers as $teacher) : ?>
                                    <option value="<?php echo $teacher['id']; ?>">
                                        <?php echo "{$teacher['fname']} {$teacher['lname']}"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="year" class="form-control" readonly required placeholder="Year Level" style="font-size: 16px; height: 35px">
                                <option value="">Select Year level...</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="section" class="form-control" required style="font-size: 16px; height: 35px">
                                <option value="">Select Section...</option>
                                <option>North</option>
                                <option>South</option>
                                <option>East</option>
                                <option>West</option>
                                <option>North East</option>
                                <option>North West</option>
                                <option>South East</option>
                                <option>South West</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="sem" class="form-control" value="<?php echo $semester; ?>"
                                readonly style="font-size: 16px;">
                        </div>
                        <div class="form-group">
                            <select name="sy" class="form-control" required style="font-size: 16px; height: 35px">
                                <?php
                                $r = mysql_query("SELECT * FROM ay WHERE display = 1");
                                while ($row = mysql_fetch_array($r)) :
                                ?>
                                    <option value="<?php echo $row['academic_year']; ?>">Academic Year : <?php echo $row['academic_year']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="addClass" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.noConflict();
        $('#classInformation').DataTable();

        // Add event listener to the subject select element
        $('select[name="subject"]').change(function() {
            
            // Get the selected subject's title
            var subjectTitle = $(this).find('option:selected').data('title');
            // Update the description input with the subject title
            $('input[name="description"]').val(subjectTitle);

            // Get the selected subject's year level
            var yearLevel = $(this).find('option:selected').data('year');
            // If year level is available, update the year select element and the hidden input field
            if (yearLevel) {
                $('select[name="year"]').val(yearLevel);
                $('#yearLevel').val(yearLevel);
            } else {
                // If year level is not available, enable the year select element
                $('select[name="year"]').val('');
                $('#yearLevel').val('');
            }

            // Get the selected subject's semester
            var semester = $(this).find('option:selected').data('semester');
            // If semester is available, update the semester select element and the hidden input field
            if (semester) {
                $('select[name="sem"]').val(semester);
                $('#subjectSemester').val(semester);
            } else {
                // If semester is not available, enable the semester select element
                $('select[name="sem"]').val('');
                $('#subjectSemester').val('');
            }
        });

        // Trigger the change event on page load to populate the description, year level, and semester initially
        $('select[name="subject"]').trigger('change');
    });
</script>

<!-- /#page-wrapper -->    
<?php include('include/footer.php'); ?>
