<?php
    include('../../config.php');
    $studid = $_GET['studid'];
    $classid = $_GET['classid'];
    $term = $_GET['term'];
    
    $grade = new Datagrade();
    if($term == 1){
        $grade->prelim($studid,$classid);   
    }else if($term == 2){
        $grade->midterm($studid,$classid);   
    }else if($term == 3){
        $grade->finalterm($studid,$classid);   
    }
    class Datagrade {
        
        function __construct(){
            if(!isset($_SESSION['id'])){
                header('location:../../');   
            }
        }
        
        function logs($act){            
            $date = date('m-d-Y h:i:s A');
            echo $q = "insert into log values(null,'$date','$act')";   
            mysql_query($q);
            return true;
        }
        
        function prelim($studid,$classid){
            $prelim_grade = $_POST['prelim_grade'];            
            $q = "update studentsubject set prelim_grade=$prelim_grade where studid=$studid and classid=$classid";
            mysql_query($q);
            $term = 'prelim';
            $this->createlog($studid,$classid,$term);
                        
            header('location:../calculate.php?studid='.$studid.'&classid='.$classid.'&status=1');
        }
        
        function midterm($studid,$classid){
            $midterm_grade = $_POST['midterm_grade'];            
            $q = "update studentsubject set midterm_grade=$midterm_grade where studid=$studid and classid=$classid";
            mysql_query($q);
            $term = 'midterm';
            $this->createlog($studid,$classid,$term);
            header('location:../calculate.php?studid='.$studid.'&classid='.$classid.'&status=1');
        }
        
        function finalterm($studid,$classid){
            $finals_grade = $_POST['finals_grade'];         
            $q = "update studentsubject set finals_grade=$finals_grade where studid=$studid and classid=$classid";
            mysql_query($q);
            $term = 'final';
            $this->createlog($studid,$classid,$term);
            header('location:../calculate.php?studid='.$studid.'&classid='.$classid.'&status=1');
        }
        
        function createlog($studid,$classid,$term){
            $student = mysql_query("select * from student where id=$studid");
            $student = mysql_fetch_array($student);
            $student = $student['fname'].' '.$student['lname'];
            
            $subject = mysql_query("select * from class where id=$classid");
            $subject = mysql_fetch_array($subject);
            $subject = $subject['subject'];
            
            $act = $_SESSION['id'].' calculated the grades of '.$student.' in '.$subject.' in '.$term.'';
            $this->logs($act);
            return true;
        }
    }
?>