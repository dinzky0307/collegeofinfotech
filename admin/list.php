<?php
    include('include/header.php');
    include('include/sidebar.php');

    include('../database.php'); // Include the database connection code

    include('data/student_model.php');

    $studentModel = new Datastudent($connection); // Create an instance of the Datastudent class and pass the connection as a parameter

    if (isset($_GET['q'])) {
        $studentModel->$_GET['q']();
    }

    include('../Carbon.php');
    include('../DatabaseService.php');

    use Database\DatabaseService;
    use Carbon\Carbon;

    $dbService = new DatabaseService;
    $selects = 'userdata.level, consultations.id, CONCAT(userdata.fname, " ", userdata.lname) AS name, consultations.areas_concern, consultations.created_at';
    $joins = 'INNER JOIN consultations ON userdata.id  = consultations.student_id';
    $consultations = $dbService->fetch(
        "SELECT {$selects} from userdata {$joins} WHERE consultant_id = {$_SESSION['user_id']} ORDER BY created_at DESC"
    );

    $search = isset($_POST['search']) ? $_POST['search'] : null;
    $students = $studentModel->getstudent($search, null, null, null, null,$connection);

    function deleteConsult($consultId, $connection) {
        // Write your code to delete the subject with the given subject ID
        $sql = "DELETE FROM consultations WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$consultId]);
    }
    
        // Check if the delete button is clicked
    if (isset($_POST['deleteConsult']) && isset($_POST['consultId'])) {
        $consultId = $_POST['consultId'];
        $delete = deleteConsult($consultId, $connection);
    
        if($delete){
            // Redirect to the same page after the deletion
            echo "<script type='text/javascript'>window.location.href = 'list.php?r=deleted';</script>";
            exit(); // Make sure to exit after redirecting to prevent further code execution
        }else{
    
        }
    
    }
?>
<style>
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
<div id="page-wrapper">

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
        <div class="row">
            <div class="col-lg-12">
                <?php if(isset($_GET['r'])): ?>
                    <?php
                        $r = $_GET['r'];
                        if($r=='added'){
                            $class='success';   
                        }else if($r=='updated'){
                            $class='info';   
                        }else if($r=='deleted'){
                            $class='danger';   
                        }else{
                            $class='hide';
                        }
                    ?>
                    <div class="alert alert-<?php echo $class?> <?php echo $class; ?>">
                        <strong>Consultation successfully <?php echo $r; ?>!</strong>    
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="consultInformation">
                        <thead>
                            <tr>
                                <th class="text-center">Type</th>
                                <th class="text-center">Fullname</th>
                                <th class="text-center">Concern</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Delete</th>
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
                                    <td class="text-center"><a href="form.php?id=<?php echo $consultation['id']; ?>">View</a></td>
                                    <td class="text-center">
                                    <form class="delete-form" method="post">
                                            <input type="hidden" name="consultId" value="<?php echo $consultation['id']; ?>">
                                            <button type="submit" name="deleteConsult" class="delete-button" title="Remove" onclick="return confirm('Are you sure you want to delete this consult?');">
                                                <i class="fa fa-trash-o fa-lg text-danger"></i>
                                            </button>
                                        </form>
                                        <!-- <a href="data/data_model.php?q=delete&table=consultations&id=<?php echo $consultation['id']?>"title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<script>
    $(document).ready( function () {
        $.noConflict();
        $('#consultInformation').DataTable();
    });
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php'); ?>
