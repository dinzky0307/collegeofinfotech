<?php
    include('include/header.php');
    include('data/student_model.php');

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
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $student = $student->getstudent($search);
?>
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
        <!--/.row -->
        <hr />      
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
                                    <a href="form.php?id=<?php echo $consultation['id']; ?>">View</a>&nbsp;
                                    <!-- <a href="data/data_model.php?q=delete&table=student&id=<?php echo $student['id']?>" title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></td> -->
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