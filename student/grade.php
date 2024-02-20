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
          $q = "SELECT ss.subjectid, s.code, s.title, ss.prelim_grade, ss.midterm_grade, ss.final_grade, ss.total 
                FROM studentsubject ss
                INNER JOIN subject s ON ss.subjectid = s.id
                WHERE ss.studid = $id";

          if (isset($_GET['year']) && isset($_GET['semester'])) {
               $year = $_GET['year'];
               $sem = $_GET['semester'];
               $q .= " AND ss.year = '$year' AND ss.semester = '$sem'";
          } else {
               $q .= " AND ss.year = '1' AND ss.semester = 'First Semester'";
          }

          $r = mysql_query($q);
          $data = array();
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
                         $prelim_grade = $row['prelim_grade'];
                         $midterm_grade = $row['midterm_grade'];
                         $finals_grade = $row['final_grade'];

                         $prelim = $prelim_grade;
                         $midterm = $midterm_grade;
                         $final = $finals_grade;

                         // Handle potential division by zero error
                         $total = ((($prelim + $midterm) / 2) * 0.30) + $final * 0.70;

                         $data = array(
                              'prelim_grade' => $prelim_grade,
                              'midterm_grade' => $midterm_grade,
                              'finals_grade' => $finals_grade,
                              'total' => $total,
                         );
                    }
               } else {
                    // Handle query execution error
                    echo "Query execution failed: " . mysql_error();
               }
          } else {
               // Handle query execution error
               echo "Query execution failed: " . mysql_error();
          }
          // print_r($total);
          return $data;
     }



     function gradeconversion($grade)
     {
          $data = 5.0;  // Default value

          switch ($grade) {
               case 1.0:
               case 1.1:
               case 1.2:
               case 1.3:
               case 1.4:
               case 1.5:
               case 1.6:
               case 1.7:
               case 1.8:
               case 1.9:
               case 2.0:
               case 2.1:
               case 2.2:
               case 2.3:
               case 2.4:
               case 2.5:
               case 2.6:
               case 2.7:
               case 2.8:
               case 2.9:
               case 3.0:
               case 3.1:
               case 3.2:
               case 3.3:
               case 3.4:
               case 3.5:
               case 3.6:
               case 3.7:
               case 3.8:
               case 3.9:
               case 4.0:
                    $data = $grade;
                    break;
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
