<?php
include('include/header.php');
include('include/sidebar.php');
include('../database.php');
include('data/teacher_model.php');

$teacher = new Datateacher($connection);
if (isset($_GET['q'])) {
    $teacher->$_GET['q']();
}

$search = isset($_POST['search']) ? $_POST['search'] : null;
$teachers = $teacher->getteacher($search);

// Function to handle the deletion of a subject
function deleteTeacher($teacherId, $connection)
{
    // Write your code to delete the subject with the given subject ID
    $sql = "DELETE FROM teacher WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$teacherId]);

    // Delete from userdata table
    $sql_userdata = "DELETE FROM userdata WHERE id = ?";
    $stmt_userdata = $connection->prepare($sql_userdata);
    $stmt_userdata->execute([$teacherId]);
}

// Check if the delete button is clicked
if (isset($_POST['deleteTeacher']) && isset($_POST['teacherId'])) {
    $teacherId = $_POST['teacherId'];
    $delete = deleteTeacher($teacherId, $connection);

    // Redirect to the same page after the deletion

?>
    <script type="text/javascript">
        alert("Teacher successfully deleted")
        window.location.href = "teacherlist.php"
    </script>
<?php

    exit(); // Make sure to exit after redirecting to prevent further code execution

}

?>
<style>
    /* Custom styling for the delete button */
    .delete-button {
        border: none;
        background: none;
        padding: 0;
        color: #f00;
        /* Change the color as per your preference */
        cursor: pointer;
        outline: none;
        /* Remove outline on focus */
    }
</style>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>INSTRUCTOR'S LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Instructor's List
                    </li>
                </ol>
            </div>
        </div>
        <style>
            .search-form {
                width: 100%;
            }

            #searchInput {
                width: 32%;
            }

            .add-instructor-btn {
                float: right;
            }
        </style>

        <div class="row">
            <div class="col-lg-6">
                <!-- <form action="teacherlist.php" method="POST" class="search-form">
            <div class="form-group d-flex align-items-center">
                <label for="searchInput" class="mr-2">Search:</label>
                <input type="text" class="form-control" id="searchInput" name="search"
                    placeholder="Teacher ID, Firstname, or Lastname" />
            </div>
        </form> -->
            </div>
            <div class="col-lg-6">
                <div class="form-inline form-padding add-instructor-btn">
                    <a href="addteacher.php" class="btn btn-success"><i class="fa fa-user"></i> Add Instructor</a>
                </div>
            </div>
        </div>
        <div id="searchResults"></div>


        <!--/.row -->
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <?php if (isset($_GET['r'])) : ?>
                    <?php
                    $r = $_GET['r'];
                    if ($r == 'added') {
                        $class = 'success';
                    } else if ($r == 'updated') {
                        $class = 'info';
                    } else if ($r == 'deleted') {
                        $class = 'danger';
                    } else if ($r == 'added an account') {
                        $class = 'success';
                    } else {
                        $class = 'hide';
                    }
                    ?>
                    <div class="alert alert-<?php echo $class ?> <?php echo $classs; ?>">
                        <strong>1 instructor successfully
                            <?php echo $r; ?>!
                        </strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="instructorInformation">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Instructor ID</th>
                                <th class="text-center">Lastname</th>
                                <th class="text-center">Firstname</th>
                                <th class="text-center">Middlename</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (is_array($teachers)) : ?>
                                <?php foreach ($teachers as $number => $teacher) : ?>
                                    <tr>
                                        <td>
                                            <?php echo $number; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $teacher['teachid']; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $teacher['lname']; ?>
                                        </td>
                                        <td>
                                            <?php echo $teacher['fname']; ?>
                                        </td>
                                        <td>
                                            <?php echo $teacher['mname']; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $teacher['sex']; ?>
                                        </td>
                                        <td>
                                            <?php echo $teacher['email']; ?>
                                        </td>
                                        <td class="text-center">
                                            <div style="display: inline-block;">
                                                <a href="edit.php?type=teacher&id=<?php echo $teacher['id'] ?>" title="Update">
                                                    <i class="fa fa-edit fa-lg text-primary"></i>
                                                </a>
                                            </div>
                                            <!-- <a href="data/settings_model.php?q=addaccount&level=teacher&id=<?php echo $teacher['id'] ?>" class="confirmacc"><i class="fa fa-user-plus fa-lg text-warning"></i></a> -->
                                            <div style="display: inline-block; margin: 0 5px;">||</div>
                                            <div style="display: inline-block;">
                                                <a href="teacherload.php?id=<?php echo $teacher['id']; ?>" title="View Subjects">
                                                    <i class="fa fa-book fa-lg text-success"></i>
                                                </a>
                                            </div>
                                            <div style="display: inline-block; margin: 0 5px;">||</div>
                                            <form class="delete-form" method="post" style="display: inline-block;">
                                                <input type="hidden" name="teacherId" value="<?php echo $teacher['id']; ?>">
                                                <button type="submit" name="deleteTeacher" class="delete-button" title="Remove" onclick="return confirm('Are you sure you want to delete this teacher?');">
                                                    <i class="fa fa-trash-o fa-lg text-danger"></i>
                                                </button>
                                            </form>
                                            <!-- <a href="data/data_model.php?q=delete&table=teacher&id=<?php echo $teacher['id'] ?>" title="Remove"><i class="fa fa-trash-o fa-lg text-danger confirmation"></i></a></td> -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8">No teachers found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#instructorInformation thead th').each(function() {});
    // DataTable
    var table = $('#instructorInformation').DataTable({
        searching: true,
        "columnDefs": [{
            "searchable": true,
            "targets": '_all'
        }],
    });
</script>
<script>
    $(document).ready(function() {
        $.noConflict();
        $('#instructorInformation').DataTable();
    });

    // Function to trigger AJAX call on input change
    function triggerSearch() {
        var searchValue = $('#searchInput').val();
        $.ajax({
            url: 'search.php', // Replace 'search.php' with the actual PHP script to handle the search on the server
            type: 'POST',
            data: {
                search: searchValue
            },
            dataType: 'html',
            success: function(data) {
                $('#searchResults').html(data); // Update the search results div with the response from the server
            },
            error: function(xhr, status, error) {
                // Handle any errors if necessary
            }
        });
    }

    // Detect changes in the search input field
    $('#searchInput').on('input', function() {
        // Trigger the search immediately on input change
        triggerSearch();
    });
</script>
<!-- /#page-wrapper -->
<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>