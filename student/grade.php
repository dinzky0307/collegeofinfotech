<?php

$grade = new Datagrade();

function __construct()
{
     if (!isset($_SESSION['id'])) {
          header('location:../../');
     }
}

class Datagrade
{

     function __construct()
     {
          if (!isset($_SESSION['id'])) {
               header('location:../../');
          }
     }

     function getid()
     {
          $studid = $_SESSION['id'];
          $r = mysql_query("select * from student where studid='$studid'");
          $row = mysql_fetch_array($r);
          $id = $row['id'];
          return $id;
     }


     function getsubject()
    {
        $id = $this->getid();
        $q = "SELECT * FROM studentsubject WHERE studid=$id";

        if (isset($_GET['year_semester'])) {
            $year = $_GET['year'];
            $sem = $_GET['semester'];
            $q .= " AND year = '$year' AND semester = '$sem'";
        } else {
            $q .= " AND year = '1' AND semester = 'First Semester'";
        }

        $result = $this->conn->query($q);
        $data = array();
        
        while ($row = $result->fetch_assoc()) {
            $subjectid = $row['subjectid'];
            $classid = $row['classid'];
            $year = $row['year'];
            $section = $row['section'];
            $sem = $row['semester'];
            $SY = $row['SY'];

            $q3 = "SELECT * FROM subject WHERE id=$subjectid";
            $result3 = $this->conn->query($q3);

            while ($srow = $result3->fetch_assoc()) {
                $subjectcode = $srow['code'];

                $q2 = "SELECT * FROM class WHERE year=$year AND section='$section' AND sem='$sem' AND SY='$SY' AND subject='$subjectcode'";
                $result2 = $this->conn->query($q2);
                $data[] = $result2->fetch_assoc();
            }
        }
        return $data;
    }

     function getsubjectitle($code)
     {
          $q = "select * from subject where code='$code'";
          $r = mysql_query($q);
          $data = array();
          $data[] = mysql_fetch_array($r);
          return $data;
     }

     function getsemester()
     {

          $id = $this->getid();
          $q = "select * from student where studid=$id";
          $r = mysql_query($q);
          $data = array();
          while ($row = mysql_fetch_array($r)) {
               $classid = $row['classid'];
               $q2 = "select * from class where id=$classid";
               $r2 = mysql_query($q2);
               $data[] = mysql_fetch_array($r2);
          }
          return $data;
     }

 function getgrade($year, $section, $sem, $sy, $subject)
     {
          $studid = $this->getid();
          $data = array();

          $q3 = "SELECT * FROM subject WHERE code='$subject'";
          $r3 = mysql_query($q3);

          if ($r3) {
               $subjectcode = '';
               while ($srow = mysql_fetch_array($r3)) {
                    $subjectcode = $srow['id'];
               }

               $q = "SELECT * FROM studentsubject WHERE studid='$studid' AND year='$year' AND section='$section' AND semester='$sem' AND SY='$sy' AND subjectid='$subjectcode'";
               $r = mysql_query($q);

               if ($r) {
                    if ($row = mysql_fetch_array($r)) {
                         $prelim_grade = ($row['prelim_grade']);
                         $midterm_grade = ($row['midterm_grade']);
                         $finals_grade = ($row['final_grade']);

                         $prelim = $prelim_grade;
                         $midterm = $midterm_grade;
                         $final = $finals_grade;

                         $total = (($prelim + $midterm)/2) * .30 + ($final) * .70;

                         $data = array(
                              'eqprelim' => $this->gradeconversion($prelim),
                              'eqmidterm' => $this->gradeconversion($midterm),
                              'eqfinal' => $this->gradeconversion($final),
                              'eqtotal' => $this->gradeconversion($total),
                              'prelim' => round($prelim),
                              'midterm' => round($midterm),
                              'final' => round($final),
                              'total' => round($total),
                              'prelim_grade' => $row['prelim_grade'],
                              'midterm_grade' => $row['midterm_grade'],
                              'finals_grade' => $row['final_grade'],
                         );
                    }
               } else {
                    // Handle query execution error
                    echo "Query execution failed 1: " . mysql_error();
               }
          } else {
               // Handle query execution error
               echo "Query execution failed 2: " . mysql_error();
          }

          return $data;
     }

     function gradeconversion($grade)
     {
          $grade = round($grade);
          if ($grade == 0) {
               $data = 0;
          } else {
               switch ($grade) {
                    case 1.0:
                         $data = 1.0;
                         break;
                    case 1.1:
                         $data = 1.1;
                         break;
                    case 1.2:
                         $data = 1.2;
                         break;
                    case 1.3:
                         $data = 1.3;
                         break;
                    case 1.4:
                         $data = 1.4;
                         break;
                    case 1.5:
                         $data = 1.5;
                         break;
                    case 1.6:
                         $data = 1.6;
                         break;
                    case 1.7:
                         $data = 1.7;
                         break;
                    case 1.8:
                         $data = 1.8;
                         break;
                    case 1.9:
                         $data = 1.9;
                         break;
                    case 2.0:
                         $data = 2.0;
                         break;
                    case 2.1:
                         $data = 2.1;
                         break;
                    case 2.2:
                         $data = 2.2;
                         break;
                    case 2.3:
                         $data = 2.3;
                         break;
                    case 2.4:
                         $data = 2.4;
                         break;
                    case 2.5:
                         $data = 2.5;
                         break;
                    case 2.6:
                         $data = 2.6;
                         break;
                    case 2.7:
                         $data = 2.7;
                         break;
                    case 2.8:
                         $data = 2.8;
                         break;
                    case 2.9:
                         $data = 2.9;
                         break;
                    case 3.0:
                         $data = 3.0;
                         break;
                    case 3.1:
                         $data = 3.1;
                         break;
                    case 3.2:
                         $data = 3.2;
                         break;
                    case 3.3:
                         $data = 3.3;
                         break;
                    case 3.4:
                         $data = 3.4;
                         break;
                    case 3.5:
                         $data = 3.5;
                         break;
                    case 3.6:
                         $data = 3.6;
                         break;
                    case 3.7:
                         $data = 3.7;
                         break;
                    case 3.8:
                         $data = 3.8;
                         break;
                    case 3.9:
                         $data = 3.9;
                         break;
                    case 4.0:
                         $data = 4.0;
                         break;




                    default:
                         $data = 5.0;
               }
          }
          return sprintf("%.1f", $data);
     }
     function getteacher($teachid)
     {
          $r = mysql_query("select * from teacher where id=$teachid");
          $result = mysql_fetch_array($r);
          $data = $result['fname'] . ' ' . $result['lname'];
          return $data;
     }

}
?>
