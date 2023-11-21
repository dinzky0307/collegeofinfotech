// <?php
//     // include('../database.php'); // Assuming this file contains the database connection code
    
//     class Datastudent {
        
//         private $connection; // Add a private property to store the connection

//         // Accept $connection as a parameter in the constructor
//         function __construct($connection) {
//             $this->connection = $connection;
            
//             if (!isset($_SESSION['id'])) {
//                 header('location:../../');   
//             }
//         }
        
//         //create logs
//         function logs($act){            
//             $date = date('m-d-Y h:i:s A');
//             echo $q = "insert into log values(null,'$date','$act')";   
//             mysql_query($q);
//             return true;
//         }
//         function getAvailableYears() {
//             $q = "SELECT DISTINCT year FROM student";
//             $result = mysql_query($q);
//             $years = array();
    
//             while ($row = mysql_fetch_assoc($result)) {
//                 $years[] = $row['year'];
//             }
    
//             return $years;
//         }

//         function getAvailableSections() {
//             $q = "SELECT DISTINCT section FROM student";
//             $result = mysql_query($q);
//             $sections = array();
    
//             while ($row = mysql_fetch_assoc($result)) {
//                 $sections[] = $row['section'];
//             }
    
//             return $sections;
//         }

//         function getstudent($search, $year = null, $section = null, $academic_year = null, $semester = null)
//         {
//             $query = "SELECT * FROM student";
//             $queryParams = array();
//             $hasConditions = false; // Keep track of whether any conditions have been added

//             // Add the WHERE clause only when searching or when academic year is specified
//             if (!empty($search)) {
//                 $query .= " WHERE (studid LIKE :search OR fname LIKE :search OR lname LIKE :search)";
//                 $queryParams[':search'] = '%' . $search . '%';
//                 $hasConditions = true;
//             }

//             // Add the conditions for year, section, and academic_year if they are not empty
//             if (!empty($year)) {
//                 $query .= $hasConditions ? " AND year = :year" : " WHERE year = :year";
//                 $queryParams[':year'] = $year;
//                 $hasConditions = true;
//             }

//             if (!empty($section)) {
//                 $query .= $hasConditions ? " AND section = :section" : " WHERE section = :section";
//                 $queryParams[':section'] = $section;
//                 $hasConditions = true;
//             }

//             if (!empty($academic_year) && !empty($semester)) {
//                 $query .= $hasConditions ? " AND ay = :academic_year AND semester = :semester" : " WHERE ay = :academic_year AND semester = :semester";
//                 $queryParams[':academic_year'] = $academic_year;
//                 $queryParams[':semester'] = $semester;
//             }

//             // if (!empty($semester)) {
//             //     $query .= $hasConditions ? " AND semester = :semester" : " WHERE semester = :semester";
//             //     $queryParams[':semester'] = $semester;
//             // }

//             $query .= " ORDER BY lname, fname, studid ASC";

//             $pdo_statement = $this->connection->prepare($query);

//             // Bind parameters only when they are present in the $queryParams array
//             foreach ($queryParams as $param => $value) {
//                 $pdo_statement->bindValue($param, $value);
//             }

//             $pdo_statement->execute();
//             $students = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

//             return $students;
//         }

//         function getStudentInfoById($studid)
//         {
//             $query = "SELECT * FROM student WHERE studid = :studid";
//             $stmt = $this->connection->prepare($query);
//             $stmt->bindParam(':studid', $studid, PDO::PARAM_INT);
//             $stmt->execute();
        
//             if ($stmt->rowCount() > 0) {
//                 return $stmt->fetch(PDO::FETCH_ASSOC);
//             }
        
//             return null;
//         }
        

//         //get class by ID
//         function getstudentbyid($id){
//             $q = "select * from student where id=$id";
//             $r = mysql_query($q);
            
//             return $r;
//         }
//         //message
//         function message(){
//             include('../../config.php');
//             $studid = $_POST['studid'];
//             $message = $_POST['message'];
            
            
//             $message = "insert into message values('','$studid','$message')";
//             mysql_query($message);
//             $act = "Send message $message";
//             $this->logs($act);
            
//             header('location:../chat.php?');
        
        
//         }


//         //add student
//         function addstudent(){
//             include('../../config.php');
//             $studid = $_POST['studid'];
//             $lname = $_POST['lname'];
//             $fname = $_POST['fname'];
//             $mname = $_POST['mname'];
//             $year = $_POST['year'];
//             $section = $_POST['section'];
//             $semester = $_POST['semester'];

            
            
//             $q = "insert into student values('','$studid','$fname','$lname','$mname','$year','$section','$semester')";


//             mysql_query($q);
//             $name = $fname.' '.$lname;
//             $act = "add new student $name";
//             $this->logs($act);
            
//             header('location:../addstudent.php?r=added');
//         }
        
//         //update student
//         function updatestudent(){
//             include('../../config.php');
//             $id = $_GET['id'];
//             $studid = $_POST['studid'];
//             $lname = $_POST['lname'];
//             $fname = $_POST['fname'];
//             $mname = $_POST['mname'];
//             $year = $_POST['year'];
//             $section = $_POST['section'];
//             $semester = $_POST['semester'];
           
//             $q = "update student set studid='$studid', lname='$lname', fname='$fname', mname='$mname', year='$year', section='$section', semester='$semester' where id=$id";
//             mysql_query($q);
            
//             $name = $fname.' '.$lname;
//             $act = "update student $name";
//             $this->logs($act);
            
//             header('location:../studentlist.php?r=updated');
//         }
//         //remove from class
//         function removesubject(){
//             include('../../config.php');
//             $studid = $_GET['studid'];
//             $classid = $_GET['classid'];
//             mysql_query("delete from studentsubject where studid=$studid and classid=$classid");
            
//             $tmp = mysql_query("select * from class where id=$classid");
//             $tmp_row = mysql_fetch_array($tmp);
//             $tmp_subject = $tmp_row['subject'];
//             $tmp_class = $tmp_row['course'].' '.$tmp_row['year'].'-'.$tmp_row['section'];
            
//             $tmp = mysql_query("select * from student where id=$studid");
//             $tmp_row = mysql_fetch_array($tmp);
//             $tmp_student = $tmp_row['fname'].' '.$tmp_row['lname'];
            
//             $act = "remove student $tmp_student from class $tmp_class with the subject of $tmp_subject";
//             $this->logs($act);
            
//             header('location:../studentsubject.php?id='.$studid.'');
//         }

        
//     }
// $sql = 'SELECT * FROM student WHERE studid LIKE :keyword OR fname LIKE :keyword OR lname LIKE :keyword ORDER BY lname,fname,studid ASC ';

// $query = $sql;
// $pdo_statement = $connection->prepare($query);
// $pdo_statement->bindValue(':keyword', '%' . '%', PDO::PARAM_STR);
// $pdo_statement->execute();
// $students = $pdo_statement->fetchAll();

