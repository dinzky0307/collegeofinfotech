<?php 
    include('../config.php'); 
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
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
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
.dropdown:hover .dropdown-content {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {background-color: orange;}
</style>
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">MADRIDEJOS COMMUNITY COLLEGE - BSIT</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <div class="navbar-form navbar-right">
                
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#changepass"><i class="fa fa-gear" ></i> Change Password</button>
                <a href="../logout.php"><button type="button" class="btn btn-danger" name="submit">Logout</button></a>
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
    <i class="fa fa-user" style="font-size: 30px;"></i> : <?php echo $stud['fname']; ?>,&nbsp;<?php echo $stud['lname']; ?>&nbsp;<?php echo $stud['mname']; ?>&nbsp;&nbsp;<?php echo $stud['year']; ?>-<?php echo $stud['section']; ?>&nbsp;
    </label>
      <!-- Example row of columns -->
    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Report of Grades</h2>
            <h4><b><?php echo $stud['semester']; ?></b></h4>
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

                    <?php foreach($mysubject as $row): ?>
                            <tr>
                                <td><?php echo $row['subject']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <?php $title = $grade->getsubjectitle($row['subject']);?>
                                <?php $mygrade = $grade->getgrade($row['id']); ?>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['prelim']); ?></td>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['midterm']); ?></td>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['final']); ?></td>
                                <td class="text-center"><?php echo $grade->gradeconversion($mygrade['total']); ?></td>
                                <td class="text-center">
                                
                                <?php
                                        if ($mygrade['eqtotal'] >3) {
                                            echo "<font color='red'>Failed</font>";
                                        } else if ($mygrade['eqtotal'] ==0) {
                                            echo "<font color='black'>NG</font>";
                                        }  else{
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
    <div class="container" style="margin-top:60px;">
      <!-- Example row of columns -->
    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Consultation</h2>
            <a href="form.php"><button type="button" class="btn btn-success" name="submit" style=" margin-bottom: 10px;">Consultation Form</button></a>
            <div class="">
                <table class="table table-bordered">
                    <thead>
                    <tr class="warning warning-info">
                            <th class="text-center">Type</th>
                            <th class="text-center">Consultant</th>
                            <th class="text-center">Concern</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($consultations as $consultation): ?>
                            <tr>
                                <td class="text-center"><?php echo $consultation['level']; ?></td>
                                <td class="text-center"><?php echo $consultation['name']; ?></td>
                                <td class="text-center"><?php echo $consultation['areas_concern']; ?></td>
                                <td class="text-center">
                                    <?php 
                                        echo Carbon::parse($consultation['created_at'])->format('F d, Y');
                                    ?>
                                </td>
                                <td class="text-center">
                                <a href="response.php?id=<?php echo $consultation['id']; ?>">View</a>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                    <input type="password" class="form-control" name="current" placeholder="Current Password"  x-ref="current" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="new" placeholder="New Password"  x-ref="new" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="confirm" placeholder="Confirm Password"  x-ref="confirm" />
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" @click="validate" class="btn btn-primary"><i class="fa fa-plus"></i> Change</button>
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





</form>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <script>
    window.PasswordHandler = () => {
        return {
            validate() {
                const currentPass = this.$refs.current
                const newPass = this.$refs.new;
                const confirmPass = this.$refs.confirm

                if (currentPass.value.length <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Current password is empty!'
                    })

                    return
                }

                if (newPass.value.length <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'New password is empty!'
                    })

                    return
                }
                
                if (newPass.value !== confirmPass.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Password type mismatched!'
                    })

                    return
                }

                this.$refs.passwordForm.submit()
            }
        }
    }
</script>
  </body>
</html>