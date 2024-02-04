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
     
function getSubjectsAndGrades($studId)
{
    $data = [];
    $subjectsQuery = $this->fetchSubjects($studId);

    foreach ($subjectsQuery as $row) {
        $subjectCode = $row['code'];
        $grades = $this->getGrade($studId, $row['year'], $row['section'], $row['sem'], $row['SY'], $subjectCode);
        
        $data[] = [
            'subjectCode' => $subjectCode,
            'subjectDesc' => $row['description'],
            'prelim' => $grades['prelim'],
            // ... (add other grades here) ...
        ];
    }

    return $data;
}

    function getsubject()
    {
        $q = "SELECT subject.code, subject.description FROM subject";

        $data = array();
        $r = mysql_query($q);

        while ($row = mysql_fetch_array($r)) {
            $data[] = $row;
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
        $data = array();

        $q = "SELECT * FROM studentsubject WHERE year='$year' AND section='$section' AND semester='$sem' AND SY='$sy' AND subjectid='$subject'";
        $r = mysql_query($q);

        if ($r) {
            if ($row = mysql_fetch_array($r)) {
                $data = array(
                    'prelim_grade' => $row['prelim_grade'],
                    'midterm_grade' => $row['midterm_grade'],
                    'finals_grade' => $row['final_grade'],
                    // Add other fields you need
                );
            }
        } else {
            // Handle query execution error
            echo "Query execution failed: " . mysql_error();
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
