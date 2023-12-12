<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('../database.php');
    include('data/teacher_model.php');
    $teacher = new Datateacher($connection);
    include('data/data_model.php');
    
    $id = $_GET['id'];
    $teacher = $teacher->getteacherbyid($id);

if (isset($_POST['deleteSubject'])) {
    $classId = $_POST['classId'];
    $teachId = $_POST['teachId'];

    $deleted = removeSubject($classId, $teachId, $connection);

    if ($deleted) {
        ?>
        <script type="text/javascript">
            alert("Subject successfully deleted");
            window.location.href = "teacherload.php?id=<?php echo $teachId; ?>";
        </script>
        <?php
        exit(); // Exit to prevent further code execution
    }
}
    
?>

<style>
    .no-border {
    border: none;
    background: none; /* Optional: Remove background if necessary */
    /* Any additional styles you want to apply */
}
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>INSTRUCTORS'S LOAD</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="teacherlist.php">Instructors</a>
                    </li>
                    <li class="active">
                        Instructor's Load
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <?php while ($row = mysql_fetch_array($teacher)): ?>
                    <h4>Instructor ID :
                        <?php echo $row['teachid']; ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Name :
                        <?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; ?>
                    </h4>
                <?php endwhile; ?>
                <hr />
                <div class="table-responsive">
                <table class="table table-striped table-bordered" id="load">
                    <thead>
                        <tr>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Students</th>
                            <th class="text-center">Year & Section</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    $r1 = mysql_query("select * from class where teacher=$id");
    while($row = mysql_fetch_array($r1)):?>
            <tr>
                <td class="text-center"><?php echo $row['subject']?> - <?php echo $row['description']?></td>            
                <td class="text-center"><a href="classstudent.php?classid=<?php echo $row['id']?>" target="_blank">View</a></td>     
                <td class="text-center"><?php echo $row['year']?> - <?php echo $row['section']?></td>
                <td class="text-center"><?php echo $row['sem']?></td>
                <td class="text-center">
                <form class="delete-form" method="post" style="display: inline-block;">
                     <input type="hidden" name="classId" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="teachId" value="<?php echo $id; ?>">
                            <button type="submit" name="deleteSubject" class="delete-button no-border" title="Remove">
                                <i class="fa fa-times-circle text-danger fa-2x confirmation"></i>
                            </button>
                </form>
<!--<a href="data/teacher_model.php?q=removesubject&teachid=<?php echo $id;?>&classid=<?php echo $row['id']; ?>">
                    <i class="fa fa-times-circle text-danger fa-2x confirmation"></i></a></td> -->
            </tr>
    <?php endwhile;
?>
                    </tbody>
                </table>    
            </div>
            </div>
        </div>
       


    </div>
    <!-- /.container-fluid -->

</div>

<script>
    $('#load thead th').each(function () {
        //     var title = $('#studentInformation thead th').eq( $(this).index() ).text();
        //     if(title!=""){
        //         $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        //   }
    });

    // DataTable
    var table = $('#load').DataTable({
        searching: true,
        "columnDefs": [
            { "searchable": true, "targets": '_all' }
        ],
    });

</script>
<script>
    $(document).ready(function () {
        $.noConflict();
        $('#load').DataTable();
    });
    function handleSearchInput(event) {
        // Get the form element
        const form = document.getElementById('searchForm');
        // Submit the form
        form.submit();
    }
    // Listen for the "keyup" event on the search input and call the handleSearchInput function
    const searchInput = document.querySelector('input[name="search"]');
    searchInput.addEventListener('keyup', handleSearchInput);
</script>

<?php include('include/footer.php');
