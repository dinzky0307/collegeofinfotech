<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/student_model.php');
    include('connection.php');


    include('../Carbon.php');
    include('../DatabaseService.php');

    use Database\DatabaseService;
    use Carbon\Carbon;

    $dbService = new DatabaseService;
    $selects = 'userdata.level, consultations.id, CONCAT(userdata.fname, " ", userdata.lname) AS name, consultations.areas_concern, consultations.created_at';
    $joins = 'INNER JOIN consultations ON userdata.id  = consultations.consultant_id';
    $consultations = $dbService->fetch(
        "SELECT {$selects} from userdata {$joins} WHERE student_id = {$_SESSION['user_id']} ORDER BY created_at DESC"
    );

    $selects = 'userdata.level, consultations.id, CONCAT(userdata.fname, " ", userdata.lname) AS name, consultations.areas_concern, consultations.created_at';
    $joins = 'INNER JOIN consultations ON userdata.id  = consultations.student_id';
    $consultationss = $dbService->fetch(
        "SELECT {$selects} from userdata {$joins} WHERE consultant_id = {$_SESSION['user_id']} ORDER BY created_at DESC"
    );
?>
<?php

        if(isset($_POST["delete"])) {
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
<style>

/* Ensure that the sidebar and content align responsively */
.wrapper {
    display: flex;
}

.main-sidebar {
    flex: 0 0 250px; /* Set a fixed width for the sidebar */
}

.content-wrapper {
    flex: 1;
    padding: 20px;
}

@media (max-width: 768px) {
    .main-sidebar {
        display: none; /* Hide sidebar on smaller screens */
    }

    .content-wrapper {
        padding: 10px; /* Adjust padding for smaller screens */
    }
}

</style> 
<div class="wrapper">
    <!-- Sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">   
        
            
            <ul class="nav navbar-nav side-nav">               
               
                <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li><a href="subject.php"><i class="fa fa-book"></i> <span>My Subjects</span></a></li>
                <li><a href="list.php"><i class="fa fa-envelope"></i> <span>Consultation</span></a></li>
                <li><a href="settings.php"><i class="fa fa-gear"></i> <span>Change Password</span></a></li>
                <li><a href="../logout.php"><i class="fa fa-power-off"></i> <span>Log Out</span></a></li>
            </ul>
        </section>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>CONSULTATION LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Consultation's List
                    </li>
                </ol>
            </div>
        </div>
       <hr /> 
                <div style="padding-bottom: 15px; display: flex; justify-content: end;" class="hidden-print">
                    <form class="hidden-print">         
                        <button type="button" class="btn btn-warning">
                        <a href="formfill.php" style="text-decoration: none; color: white;">Consultation Form</a>
                        </button>
                    </form>
                </div>  
                <!-- Panels Section -->
                <div class="row">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Consulted to:</th>
                                <th>Concern</th>
                                <th>Date</th>
                                <th>View</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($consultations as $consultation): ?>
                                <tr>
                                    <td><?php echo $consultation['level']; ?></td>
                                    <td><?php echo $consultation['name']; ?></td>
                                    <td><?php echo $consultation['areas_concern']; ?></td>
                                    <td>
                                        <?php 
                                            echo Carbon::parse($consultation['created_at'])->format('F d, Y');
                                        ?>
                                    </td>
                                    <td>
                                    <a href="form.php?id=<?php echo $consultation['id']; ?>">View</a></td>
                                    <td>
                                    <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $consultation['id']; ?>">
                                    <button type="submit" name="delete" style="border: none;"><i class="fa fa-trash-o text-danger fa-lg "></i></button>
                                    </form></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

         <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Consulted by:</th>
                                <th>Concern</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($consultationss as $consultations): ?>
                                <tr>
                                    <td><?php echo $consultations['level']; ?></td>
                                    <td><?php echo $consultations['name']; ?></td>
                                    <td><?php echo $consultations['areas_concern']; ?></td>
                                    <td>
                                        <?php 
                                            echo Carbon::parse($consultations['created_at'])->format('F d, Y');
                                        ?>
                                    </td>
                                    <td>
                                    <a href="form.php?id=<?php echo $consultations['id']; ?>">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                <!-- /.row -->
            </div>
            <br><br> <br><br>  <br><br>
            <!-- /.container-fluid -->
             <footer class="container-fluid">
        <?php include 'include/footer.php'; ?>
      </footer>
        </section>
    </div>
      
</div>

