<?php
include('../database.php');
    $student = new Datastudent();
    if(isset($_GET['q'])){
        $student->$_GET['q']();
    }
    class Datastudent {
        
        function __construct(){
            if(!isset($_SESSION['id'])){
                header('location:../../');   
            }
        }
        
        //create logs
        function logs($act){            
            $date = date('m-d-Y h:i:s A');
            echo $q = "insert into log values(null,'$date','$act')";   
            mysql_query($q);
            return true;
        }
        
        //get all student info
        function getstudent($search){
            $q = "select * from student where studid like '%$search%' or fname like '%$search%' or lname like '%$search%' order by lname,fname,studid";
            $r = mysql_query($q);
            
            return $r;
        }
        
        //get class by ID
        function getstudentbyid($id){
            $q = "select * from student where id=$id";
            $r = mysql_query($q);
            
            return $r;
        }
        //message
        function message(){
            include('../../config.php');
            $studid = $_POST['studid'];
            $message = $_POST['message'];
            
            
            $message = "insert into message values('','$studid','$message')";
            mysql_query($message);
            $act = "Send message $message";
            $this->logs($act);
            
            header('location:../chat.php?');
        
        
        }


        //add student
        function addstudent(){
            include('../../config.php');
            $studid = $_POST['studid'];
            $lname = $_POST['lname'];
            $fname = $_POST['fname'];
            $mname = $_POST['mname'];
            $year = $_POST['year'];
            $section = $_POST['section'];
            $semester = $_POST['semester'];

            
            
            $q = "insert into student values('','$studid','$fname','$lname','$mname','$year','$section','$semester')";


            mysql_query($q);
            $name = $fname.' '.$lname;
            $act = "add new student $name";
            $this->logs($act);
            
            header('location:../addstudent.php?r=added');
        }
        
        //update student
        function updatestudent(){
            include('../../config.php');
            $id = $_GET['id'];
            $studid = $_POST['studid'];
            $lname = $_POST['lname'];
            $fname = $_POST['fname'];
            $mname = $_POST['mname'];
            $year = $_POST['year'];
            $section = $_POST['section'];
            $semester = $_POST['semester'];
           
            $q = "update student set studid='$studid', lname='$lname', fname='$fname', mname='$mname', year='$year', section='$section', semester='$semester' where id=$id";
            mysql_query($q);
            
            $name = $fname.' '.$lname;
            $act = "update student $name";
            $this->logs($act);
            
            header('location:../studentlist.php?r=updated');
        }
        //remove from class
        function removesubject(){
            include('../../config.php');
            $studid = $_GET['studid'];
            $classid = $_GET['classid'];
            mysql_query("delete from studentsubject where studid=$studid and classid=$classid");
            
            $tmp = mysql_query("select * from class where id=$classid");
            $tmp_row = mysql_fetch_array($tmp);
            $tmp_subject = $tmp_row['subject'];
            $tmp_class = $tmp_row['course'].' '.$tmp_row['year'].'-'.$tmp_row['section'];
            
            $tmp = mysql_query("select * from student where id=$studid");
            $tmp_row = mysql_fetch_array($tmp);
            $tmp_student = $tmp_row['fname'].' '.$tmp_row['lname'];
            
            $act = "remove student $tmp_student from class $tmp_class with the subject of $tmp_subject";
            $this->logs($act);
            
            header('location:../studentsubject.php?id='.$studid.'');
        }

        
    }
$sql = 'SELECT * FROM student WHERE studid LIKE :keyword OR fname LIKE :keyword OR lname LIKE :keyword ORDER BY lname,fname,studid ASC ';

$query = $sql;
$pdo_statement = $connection->prepare($query);
$pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
$pdo_statement->execute();
$students = $pdo_statement->fetchAll();