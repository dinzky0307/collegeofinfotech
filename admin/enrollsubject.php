<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/data_model.php');
    include('connection.php');
    include('../DatabaseService.php');

    use Database\DatabaseService;

    $dbService = new DatabaseService;

    $y = $_GET['y'];
    $s = $_GET['s'];
    $id = $_GET['id'];
    if ($s == 'First Semester') {
        $s = 1;
    } else {
        $s = 2;
    }

    $sql = 'SELECT * FROM subject WHERE year = '.$y.' AND semester = '.$s.'';

    $query = $sql;
    $pdo_statement = $connection->prepare($query);
    $pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
    $pdo_statement->execute();
    $subjects = $pdo_statement->fetchAll();






if (isset($_POST['submit'])) {
    if(!empty($_POST['check_list']))
    {
     foreach($_POST['check_list'] as $subid){


//get subject code
        $results = mysql_query("SELECT code FROM subject WHERE id='$subid' LIMIT 1");
        $rows = mysql_fetch_assoc($results);
        $subjectcode = $rows['code'];

        $student = $dbService->fetchRow("SELECT* from student where id = {$id}");
        $section = $student['section'];

//get classid
        $result = mysql_query(
            "SELECT subject,id FROM class WHERE subject='$subjectcode' AND section='$section' LIMIT 1"
        );
        $row = mysql_fetch_assoc($result);
        $classid = $row['id'];

if ($s == '1') {
        $sm = "First Semester";
    } else if ($s == '2') {
        $sm = "Second Semester";
    } else {
        $sm = "Summer";
    }

        $sql = "INSERT INTO studentsubject (studid, subjectid, classid, year, semester) VALUES ('$id', '$subid', '$classid', '$y', '$sm')";
       if ($dbconnection->query($sql) === TRUE) {
        
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
               title: 'Subject Successfully Enrolled!',
               text: '',
               icon: 'success',
             })";
            echo "</script>";

        //   echo "<script>window.location.href = 'viewsubjects.php?type=student&id=".$id."';</script>";
        } else {
           echo "<script type='text/javascript'>";
            echo "Swal.fire({
               title: 'Enrolling subject failed!',
               text: '',
               icon: 'error',
             })";
            echo "</script>";
        }
     }
    }
}
?>        

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row hidden-print">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>SUBJECT LIST</small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                
                </ol>
            </div>
        </div>
        <style>
            @media print{
                .print-container {
                    position: center;
                    margin-top: 0px;
                }
                @media print 
                {
                    .print-container{
                        width: 260mm;
                        font-size: 17px;
                    }
                }
                @page { 
                    size: auto;
                    margin:50px;
                }

            }
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

        <SCRIPT language="javascript">
            $(function () {
                // add multiple select / deselect functionality
                $("#selectall").click(function () {
                    $('.name').attr('checked', this.checked);
                });

                // if all checkbox are selected, then check the select all checkbox
                // and viceversa
                $(".name").click(function () {       
                    if ($(".name").length == $(".name:checked").length) {
                        $("#selectall").attr("checked", "checked");
                    } else {
                        $("#selectall").removeAttr("checked");
                    }
                });
            });
        </SCRIPT>
        <!-- <form class="hidden-print">         
                <button onclick="window.print();" type="button" class="btn btn-primary" style="float: right; margin-bottom: 10px;">
                    Assign Subjects
                </button>
        </form> -->
        <!-- /.row -->
        <div class="row print-container">

            <div class="col-lg-12" >

                <center><b><h1>Subject List</h1></b></center>
                <br>
                <br>               
                <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>   
                                <th class="text-center"><input type="checkbox" id="selectall"/></th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center">Descriptive  Title</th>
                                <th class="text-center">Lec Unit</th>
                                <th class="text-center">Lab Unit</th>
                                <th class="text-center">Total Units</th>
                                <th class="text-center">Pre-requisites/s</th>
                        </tr>
                    </thead>
                    <tbody>
                    <form method="post" action="">
                    <?php foreach($subjects as $number => $subjects): ?>
                        <tr>
                            <!-- <td><?php echo $number + 1;?></td> -->
                            <td class="text-center"><input class="<?php echo $subjects['id']; ?> name" name="check_list[] name" type="checkbox" value="<?php echo $subjects['id']; ?>" /></td>
                            <td class="text-center"><?php echo $subjects['code']; ?></td>
                            <td class="text-center"><?php echo $subjects['title']; ?></td>
                            <td class="text-center"><?php echo $subjects['lecunit']; ?></td>
                            <td class="text-center"><?php echo $subjects['labunit']; ?></td>
                            <td class="text-center"><?php echo $subjects['totalunit']; ?></td>
                            <td class="text-center"><?php echo $subjects['pre']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> 

                <button type="button" class="btn btn-primary" style=" margin-top: 5px;">
                <a href="viewsubjects.php?type=student&id=<?php echo $id?>" style="text-decoration:none; color: white;">
                <i class="fa fa-arrow-left"></i> 
                Go Back
                </a>
                </button>
                <button type="submit" name="submit" class="btn btn-success" style=" margin-top: 5px;">
                <i class="fa fa-check"></i> 
                Save
                </button>
        </form>
            </div>
            </div>
        </div>
       
        

    </div>
    <!-- /.container-fluid -->

</div>

<!-- /#page-wrapper -->    
<?php include('include/footer.php'); ?>

<?php

$sqls = "SELECT subjectid FROM studentsubject WHERE studid = '$id' AND (prelim_grade+midterm_grade+final_grade)/3 <= 74";
  $results = mysqli_query($dbconnection,$sqls);
  while($ss = $results->fetch_assoc()) {
    $subjectid = $ss['subjectid'];
$sqlss = "SELECT * FROM subject WHERE id = '$subjectid'";
    $resultss = mysqli_query($dbconnection,$sqlss);
    while($sss = $resultss->fetch_assoc()) {
        $code = $sss['code'];
$sqlss = "SELECT * FROM subject WHERE pre = '$code'";
    $resultss = mysqli_query($dbconnection,$sqlss);
    while($sss = $resultss->fetch_assoc()) {
        echo "<script>$('.".$sss['id']."').prop('disabled', true);</script>";
        echo "<script type='text/javascript'>";
            echo "Swal.fire({
               title: 'This student has a failed subject.',
               icon: 'warning',
             })";
            echo "</script>";
    }
  }
}
?>

<?php 


  $sql = "SELECT subjectid FROM studentsubject WHERE studid = '$id'";

  $result = mysqli_query($dbconnection,$sql);
  while($s = $result->fetch_assoc()) {
    
    echo "<script>$('.".$s['subjectid']."').prop('disabled', true);</script>";
    //echo '<script>document.getElementsByClassName("'.$s["subjectid"].'").disabled = true; </script>';
            
  }

 ?>
