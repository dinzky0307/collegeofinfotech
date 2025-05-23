<?php

    class Datateacher {

        
    private $connection; // Add a private property to store the connection
        
        function __construct($connection){
            $this->connection = $connection;
            
            if (!isset($_SESSION['id'])) {
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
        
        //get all teacher info
        // function getteacher($search){
        //     $q = "select * from teacher where teachid like '%$search%' or fname like '%$search%' or lname like '%$search%' order by lname,fname,teachid";
        //     $r = mysql_query($q);
            
        //     return $r;
        // }

        function getteacher($search)
        {
            $query = "SELECT * FROM teacher";
            $queryParams = array();
            $hasConditions = false; // Keep track of whether any conditions have been added

            // Add the WHERE clause only when searching or when academic year is specified
            if (!empty($search)) {
                $query .= " WHERE (teachid LIKE :search OR fname LIKE :search OR lname LIKE :search)";
                $queryParams[':search'] = '%' . $search . '%';
                $hasConditions = true;
            }

            $query .= " ORDER BY lname, fname, teachid ASC";

            $pdo_statement = $this->connection->prepare($query);

            // Bind parameters only when they are present in the $queryParams array
            foreach ($queryParams as $param => $value) {
                $pdo_statement->bindValue($param, $value);
            }

            $pdo_statement->execute();
            $students = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            return $students;
        }
        
        //get teacher by ID
        function getteacherbyid($id){
            $q = "select * from teacher where id=$id";
            $r = mysql_query($q);
            
            return $r;
        }
        //add teacher
        function addteacher(){
            include('../../config.php');
            $teachid = $_POST['teachid'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            
            $q = "insert into teacher values('','$teachid','$fname','$lname')";
            mysql_query($q);
            
            $name = $fname.' '.$lname;
            $act = "add new teacher $name";
            $this->logs($act);
            
            header('location:../teacherlist.php?r=added');
        }
        
        //update teacher
        function updateteacher(){
            include('../../config.php');
            $id = $_GET['id'];
            $teachid = $_POST['teachid'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $mname = $_POST['mname'];
            $sex = $_POST['sex'];
            $email = $_POST['email'];
            $q = "update teacher set teachid='$teachid', fname='$fname', lname='$lname', mname='$mname', sex='$sex', email='$email' where id=$id";
            mysql_query($q);
            
            $name = $fname.' '.$lname;
            $act = "update teacher $name";
            $this->logs($act);
            
            header('location:../teacherlist.php?r=updated');
        }
        
        //remove teacher from class
        function removesubject(){
            include('../../config.php');
            $classid = $_GET['classid'];
            $teachid = $_GET['teachid'];
            mysql_query("update class set teacher=null where id=$classid");
            header('location:../teacherload.php?id='.$teachid.'');
            
            $tmp = mysql_query("select * from class where id=$classid");
            $tmp_row = mysql_fetch_array($tmp);
            $tmp_subject = $tmp_row['subject'];
            $tmp_class = $tmp_row['course'].' '.$tmp_row['year'].'-'.$tmp_row['section'];
            
            $tmp = mysql_query("select * from teacher where id=$teachid");
            $tmp_row = mysql_fetch_array($tmp);
            $tmp_teacher = $tmp_row['fname'].' '.$tmp_row['lname'];
            
            $act = "remove teacher $tmp_teacher from class $tmp_class with the subject of $tmp_subject";
            $this->logs($act);
            
        }
        
    }

    function removeSubject($classId, $teachId, $connection){
    $sql = "UPDATE class SET teacher = NULL WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$classId]);

    // Log the action
    // ...

    return true; // Return true if deletion is successful, false otherwise
    }

    include('../database.php');

    $sql = 'SELECT * FROM teacher WHERE teachid LIKE :keyword OR fname LIKE :keyword OR lname LIKE :keyword ORDER BY lname,fname,teachid ASC ';

    $query = $sql;
    $pdo_statement = $connection->prepare($query);
    $pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
    $pdo_statement->execute();
    $teachers = $pdo_statement->fetchAll();


?>
