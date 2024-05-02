<?php

class Dataclass
{
    private $connection;
    function __construct($connection)
    {

        $this->connection = $connection;

        if (!isset($_SESSION['id'])) {
            header('location:../../');
        }

    }

    //create logs
    function logs($act)
    {
        $date = date('m-d-Y h:i:s A');
        echo $q = "insert into log values(null,'$date','$act')";
        mysql_query($q);
        return true;
    }
    //get all class info
    function getclass($search)
    {
        if ($search === null) {
            $q = "SELECT * FROM class ORDER BY course, year, section, sem ASC";
        } else {
            $search = "%$search%";
            $q = "SELECT * FROM class WHERE course LIKE :search OR year LIKE :search OR section LIKE :search OR sem LIKE :search OR subject LIKE :search ORDER BY course, year, section, sem ASC";
        }

        $stmt = $this->connection->prepare($q);
        $stmt->bindParam(":search", $search);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            die("Error in fetching data: " . print_r($this->connection->errorInfo(), true));
        }

        return $result;
    }


    //get class by ID
    function getclassbyid($id)
    {
        $q = "select * from class where id=$id";
        $r = mysql_query($q);

        return $r;
    }
    //add class
    function addclass()
    {
        include ('../config.php');

        $course = $_POST['course'];
        $year = $_POST['year'];
        $section = $_POST['section'];
        $sem = $_POST['sem'];
        $subject = $_POST['subject'];
        $sy = $_POST['sy'];

        echo $q = "insert into class values('','$course','$year','$section','$sem','','$subject','$sy')";
        mysql_query($q);
        $act = "create new class $course $year - $section with the subject of $subject";
        $this->logs($act);
        // header('location:../class.php?r=added');
    }

    //update class
    function updateclass()
    {
        include ('../../config.php');
        $id = $_GET['id'];
        $course = $_POST['course'];
        $year = $_POST['year'];
        $section = $_POST['section'];
        $sem = $_POST['sem'];
        $subject = $_POST['subject'];
        $description = $_POST['description'];
        $teacher = $_POST['teacher'];
        $sy = $_POST['sy'];

        echo $q = "update class set course='$course', year='$year', section='$section', sem='$sem', subject='$subject', description='$description', teacher='$teacher', SY='$sy' where id=$id";
        mysql_query($q);
        $act = "update class $course $year - $section with the subject of $subject";
        $this->logs($act);
        header('location:../class.php');
    }

    //get all students in that class
    function getstudentsubject()
    {
        $classid = $_GET['classid'];
        $q = "select * from studentsubject where classid=$classid";
        $r = mysql_query($q);
        $result = array();

        while ($row = mysql_fetch_array($r)) {
            $q2 = 'select * from student where id=' . $row['studid'] . '';
            $r2 = mysql_query($q2);
            $result[] = mysql_fetch_array($r2);
        }
        return $result;
    }
    //get all subect by student
    function getsubectbystudid($studid)
    {
        $q = 'select * from student where studid = $studid';
        $r = mysql_query(q);
        $result = array();

        while ($row = mysql_fetch_array($r)) {
            $q2 = 'select * from subject where year = ' . $row['year'];
            $r2 = mysql_query(q2);
            $result2[] = mysql_fetch_array($r2);
        }
        return $result2;
    }
    //add student to class
    function addstudent()
    {
        include ('../../config.php');
        $classid = $_GET['classid'];
        $studid = $_GET['studid'];

        $verify = $this->verifystudent($studid, $classid);

        if ($verify) {
            $q = "UPDATE studentsubject SET classid = '$classid' WHERE studid = '$studid';";
            mysql_query($q);

            header('location:../classstudent.php?r=success&classid=' . $classid);
        } else {
            header('location:../classstudent.php?r=duplicate&classid=' . $classid);
        }


        $tmp = mysql_query("select * from class where id=$classid");
        $tmp_row = mysql_fetch_array($tmp);
        $tmp_subject = $tmp_row['subject'];
        $tmp_class = $tmp_row['course'] . ' ' . $tmp_row['year'] . '-' . $tmp_row['section'];

        $tmp = mysql_query("select * from student where id=$studid");
        $tmp_row = mysql_fetch_array($tmp);
        $tmp_student = $tmp_row['fname'] . ' ' . $tmp_row['lname'];

        $act = "add student $tmp_student to class $tmp_class with the subject of $tmp_subject";
        $this->logs($act);
    }
    //verify if he/she is enrolled
    function verifystudent($studid, $classid)
    {
        include ('../../config.php');
        $q = "select * from studentsubject where studid=$studid and classid=$classid";
        $r = mysql_query($q);
        if (mysql_num_rows($r) < 1) {
            return true;
        } else {
            return false;
        }
    }
    //remove student to the class
    function removestudent()
    {
        $classid = $_GET['classid'];
        $studid = $_GET['studid'];
        include ('../../config.php');
        $q = "delete from studentsubject where studid=$studid and classid=$classid";
        mysql_query($q);

        $tmp = mysql_query("select * from class where id=$classid");
        $tmp_row = mysql_fetch_array($tmp);
        $tmp_subject = $tmp_row['subject'];
        $tmp_class = $tmp_row['course'] . ' ' . $tmp_row['year'] . '-' . $tmp_row['section'];

        $tmp = mysql_query("select * from student where id=$studid");
        $tmp_row = mysql_fetch_array($tmp);
        $tmp_student = $tmp_row['fname'] . ' ' . $tmp_row['lname'];

        $act = "remove student $tmp_student from class $tmp_class with the subject of $tmp_subject";
        $this->logs($act);

        header('location:../classstudent.php?r=success&classid=' . $classid . '');
    }

    //update teacher
    function updateteacher()
    {
        $classid = $_GET['classid'];
        $teachid = $_GET['teachid'];
        include ('../../config.php');
        $q = "update class set teacher=$teachid where id=$classid";
        mysql_query($q);

        $tmp = mysql_query("select * from class where id=$classid");
        $tmp_row = mysql_fetch_array($tmp);
        $tmp_subject = $tmp_row['subject'];
        $tmp_class = $tmp_row['course'] . ' ' . $tmp_row['year'] . '-' . $tmp_row['section'];

        $tmp = mysql_query("select * from teacher where id=$teachid");
        $tmp_row = mysql_fetch_array($tmp);
        $tmp_teacher = $tmp_row['fname'] . ' ' . $tmp_row['lname'];

        $act = "assign teacher $tmp_teacher to class $tmp_class with the subject of $tmp_subject";
        $this->logs($act);

        header('location:../classteacher.php?classid=' . $classid . '&teacherid=' . $teachid . '');

    }

}

if (isset($_POST['addClass'])) {
    $sql = "INSERT INTO class (subject, description, course, year, section, sem, SY, teacher)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $connection->prepare($sql)->execute([
        $_POST['subject'],
        $_POST['description'],
        'BSIT',
        $_POST['year'],
        $_POST['section'],
        $_POST['sem'],
        $_POST['sy'],
        $_POST['teacher']
    ]);

    echo "<script type='text/javascript'>";
    echo "Swal.fire({
               title: 'Class successfully added!',
               icon: 'success',
             })";
    echo "</script>";
}

// if (isset($_POST['addstudent'])) {

//     if (strlen($_POST['$average']) > 3) {
//         echo "<script type='text/javascript'>";
//         echo "Swal.fire({
//            title: 'This Student has a Failed Subject',
//            icon: 'error',
//          })";
//         echo "</script>";
//     } else {
//          // Check existing ID
//     $existStatement = $connection->prepare("SELECT studid FROM student WHERE studid = '{$_POST['studid']}'");
//     $existStatement->execute();
//     $existStatement->setFetchMode(PDO::FETCH_ASSOC);
//     $exists = $existStatement->fetch();


//     if ($exists) {
//         echo "<script type='text/javascript'>";
//         echo "Swal.fire({
//            title: '{$_POST['studid']} ID already exists!',
//            icon: 'error',
//          })";
//         echo "</script>";
//     } else {
//         $sql = "INSERT INTO student (studid, fname, lname, mname, year, section, semester)
//         VALUES (?, ?, ?, ?, ?, ?, ?)";

//         $connection->prepare($sql)->execute([
//             $_POST['studid'], 
//             $_POST['lname'], 
//             $_POST['fname'],
//             $_POST['mname'],
//             $_POST['year'],
//             $_POST['section'],
//             $_POST['semester']
//         ]);

//         echo "<script type='text/javascript'>";
//         echo "Swal.fire({
//            title: 'Student successfully added!',
//            icon: 'success',
//          })";
//         echo "</script>";
//     }
//     }


// }
$servername = '127.0.0.1';
$username = 'u510162695_infotechMCC';
$password = 'u510162695_infotechMCC';
$dbname = 'infotechMCC2023';
$connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set the PDO error mode to exception
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch teachers
$teachersStatement = $connection->prepare("SELECT id, fname, lname FROM teacher");
$teachersStatement->execute();
$teachersStatement->setFetchMode(PDO::FETCH_ASSOC);
$teachers = $teachersStatement->fetchAll();


$sql = 'SELECT * FROM class WHERE year LIKE :keyword OR section LIKE :keyword OR sem LIKE :keyword ORDER BY sem,section,year ASC ';

$query = $sql;
$pdo_statement = $connection->prepare($query);
$pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
$pdo_statement->execute();
$classs = $pdo_statement->fetchAll();
