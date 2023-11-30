<?php
    include('include/header.php');
    include('include/sidebar.php');

    $tmp = $_SESSION['id'];
    $q = "select * from teacher where teachid='$tmp'";
    $r = mysql_query($q);
    $result = mysql_fetch_array($r);
    $teachid = $result[0];

    $r1 = mysql_query("select count(*) from class where teacher=$teachid");
    $count1 = mysql_fetch_array($r1);

    $r2 = mysql_query("select * from class where teacher=$teachid");
    $students = 0;
    while($row = mysql_fetch_array($r2)){
        $id = $row['id']."\n";   
        $r3 = mysql_query("select count(*) from studentsubject where classid=$id");
        $count3 = mysql_fetch_array($r3);
        $students = $students + $count3[0];
    }

    $get_teach = mysql_query("SELECT * FROM teacher WHERE teachid = '$tmp'");
    $fetch_teach = mysql_fetch_array($get_teach);
    $teach_id = $fetch_teach['id'];

    $get_class = mysql_query("SELECT * FROM class WHERE teacher = $teach_id");
    if (mysql_num_rows($get_class) > 0) {
        while ($fetch_class = mysql_fetch_array($get_class)) {
            $subject_code = $fetch_class['subject'];

            $get_subjects = mysql_query("SELECT * FROM subject WHERE code = '$subject_code'");
            if (mysql_num_rows($get_subjects) > 0) {
                $fetch_subjects = mysql_fetch_array($get_subjects);

                $subject_id = $fetch_subjects['id'];

                $cnt_students = mysql_query("SELECT COUNT(*) FROM studentsubject WHERE subjectid = $subject_id ");
                $all_students = mysql_fetch_array($cnt_students);

            }

        }
    }

    

?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">
                    Dashboard <small>Overview</small>
                </h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12 col-md-6">
                 <div class="panel"style= "background-color: #029b02; color: white;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $count1[0];?></div>
                                <div>Subjects!</div>
                            </div>
                        </div>
                    </div>
                   <a href="subject.php"style= "text-decoration: none; color: #029b02;">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 col-md-6">
                <div class="panel "style= "background-color: #ff0800; color: white; height: 110px;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo  $all_students[0]; ?></div>
                                <div>Students!</div>
                            </div>
                        </div>
                    </div>
<!--                     <a href="student.php"style= "text-decoration: none; color:  #ff0800;">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a> -->
                </div>
            </div>
<!--             <div class="col-lg-12 col-md-6">
                 <div class="panel"style= "background-color: #Edf52f; color: white;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-envelope fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $consultations;?></div>
                                <div>Consultations!</div>
                            </div>
                        </div>
                    </div>
                   <a href="subject.php"style= "text-decoration: none; color: #029b02;">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right fa-2x"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div> -->
        </div>
        <!-- /.row -->
        

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');
