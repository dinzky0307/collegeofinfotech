<?php
    include('../database.php');
    $data = new Data();
    if(isset($_GET['q'])){
        $data->$_GET['q']();
    }
    class Data {
        
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
        
        //get all subjects
        function getsubject($search){
            $q = "select * from subject where code like '%$search%' or title like '%$search%' order by code asc";
            $q = 'select * from subject where year = 1';
            $r = mysql_query($q);
            
            return $r;
        }
        //get subject by ID
        function getsubjectbyid($id){
            $q = "select * from subject where id=$id";
            $r = mysql_query($q);
            
            return $r;
        }
        
        //add subject
        function addsubject(){
            // include('../../config.php');
            // $code = $_POST['code'];
            // $title = $_POST['title'];
            // $lecunit = $_POST['lecunit'];
            // $labnunit = $_POST['labunit'];
            // $totalunit = $_POST['totalunit'];
            // $pre = $_POST['pre'];
            // $q = "insert into subject values('','$code','$title','$lecunit','$labunit','$totalunit','$pre')";
            // mysql_query($q);
        }
        
        //update subject
        function updatesubject(){
            include('../../config.php');

            $id = $_GET['id'];
            $code = $_POST['code'];
            $title = $_POST['title'];
            $lecunit = $_POST['lecunit'];
            $labunit = $_POST['labunit'];
            $totalunit = $_POST['totalunit'];
            $pre = $_POST['pre'];
            $semester = $_POST['semester'];
            $year = $_POST['year'];

            $q = "UPDATE subject set code='$code', title='$title', lecunit='$lecunit', labunit='$labunit', totalunit='$totalunit', pre='$pre', semester='$semester', year='$year' where id='$id'";
            mysql_query($q);
            $act = "update subject $code - $title";
            $this->logs($act);
            header('location:../subject.php');
        }
        
        //GLOBAL DELETION
        function delete(){
            include('../../config.php');
            $table = $_GET['table'];
            $id = $_GET['id'];
            $q = "delete from $table where id=$id";
            $r = null;
            
            $tmp = mysql_query("select * from $table where id=$id");
            $tmp_row = mysql_fetch_array($tmp);
            
            mysql_query($q);
            
            if($table=='subject'){
                $record = $tmp_row['code'];
                header('location:../subject.php?r=deleted');
                
            }else if($table=='class'){
                 $record = $tmp_row['subject'];
                header('location:../class.php');
               
            }else if($table=='student'){
                $record = $tmp_row['fname'];
                header('location:../studentlist.php?r=deleted');
               
            }else if($table=='teacher'){
               $record = $tmp_row['fname'];
                header('location:../teacherlist.php?r=deleted');
            }else if($table=='userdata'){
                $record = $tmp_row['username'];
                header('location:../users.php?r=deleted');
            }else if($table=='consultations'){
                $record = $tmp_row['student_id'];
                header('location:../list.php?r=deleted');
            }
                    
            $act = "delete $record from $table";
            $this->logs($act);
        }

    }

if (isset($_POST['addSubject'])) {
    $sql = "INSERT INTO subject (code, title, lecunit, labunit, totalunit, pre, semester, year)
    VALUES (?,?,?,?,?,?,?,?)";

    $connection->prepare($sql)->execute([
        $_POST['code'], 
        $_POST['title'], 
        $_POST['lecunit'],
        $_POST['labunit'],
        $_POST['totalunit'],
        $_POST['pre'],
        $_POST['semester'],
        $_POST['year'],
    ]);
    
    echo "<script type='text/javascript'>";
    echo "Swal.fire({
       title: 'add new subject $code - {$title}',
       icon: 'success',
     })";
    echo "</script>";
}

    $sql = 'SELECT * FROM subject WHERE year AND (id LIKE :keyword OR code LIKE :keyword OR title LIKE :keyword) ORDER BY title, code, id ASC ';

    $query = $sql;
    $pdo_statement = $connection->prepare($query);
    $pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
    $pdo_statement->execute();
    $subjects = $pdo_statement->fetchAll();

?>