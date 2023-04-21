<?php
    
    $grade = new Datagrade();

    function __construct(){
            if(!isset($_SESSION['id'])){
                header('location:../../');   
            }
        }

    class Datagrade {
        
        function __construct(){
            if(!isset($_SESSION['id'])){
                header('location:../../');   
            }
        }
        
        function getid(){
            $studid = $_SESSION['id'];
            $r = mysql_query("select * from student where studid='$studid'");
            $row = mysql_fetch_array($r);
            $id = $row['id'];
            return $id;
        }

        
        function getsubject(){  
          $id = $this->getid();
          $q = "select * from studentsubject where studid=$id AND year = '1' AND semester = 'First Semester'";

          if (isset($_GET['year_semester'])) {
               $year = $_GET['year'];
               $sem = $_GET['semester'];
               $q.= " AND year = '$year' AND semester = '$sem'";
           }
          //  var_dump($q);
          //  die();
            $r = mysql_query($q);
            $data = array();
            while($row = mysql_fetch_array($r)){
                $classid = $row['classid'];
                $q2 = "select * from class where id=$classid";
                $r2 = mysql_query($q2);  
                $data[] = mysql_fetch_array($r2);
            }
            return $data;
        }
        
        function getsubjectitle($code){
            $q = "select * from subject where code='$code'";
            $r = mysql_query($q);
            $data = array();
            $data[] = mysql_fetch_array($r);
            return $data;
        }

        function getsemester(){  

          $id = $this->getid();
          $q = "select * from student where studid=$id";
          $r = mysql_query($q);
          $data = array();
          while($row = mysql_fetch_array($r)){
              $classid = $row['classid'];
              $q2 = "select * from class where id=$classid";
              $r2 = mysql_query($q2);  
              $data[] = mysql_fetch_array($r2);
          }
          return $data;
      }
        
        function getgrade($classid){
            $studid = $this->getid();
            $q = "select * from studentsubject where studid='$studid' and classid='$classid'";
            $r = mysql_query($q);
            if($row = mysql_fetch_array($r)){

               $prelim_grade = ($row['prelim_grade']);
               $midterm_grade = ($row['midterm_grade']);
               $finals_grade = ($row['final_grade']);
               
               $prelim = $prelim_grade;
               $midterm = $midterm_grade;
               $final = $finals_grade; 
                
               $total = ($prelim * .30) + ($midterm * .30) + ($final * .40);
                
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
            
            return $data;
          }
          
        function gradeconversion($grade){
            $grade = round($grade);
            if($grade==0){
                 $data = 0;
               }else{
                switch ($grade) {
                     case $grade > 94:
                         $data = 1.0;
                         break;
                     case 94:
                         $data = 1.1;
                         break;
                    case 93:
                         $data = 1.2;
                         break;
                    case 92:
                         $data = 1.3;
                         break;
                    case 91:
                         $data = 1.4;
                         break;
                    case 90:
                         $data = 1.5;
                         break;
                    case 89:
                         $data = 1.6;
                         break;
                    case 88:
                         $data = 1.7;
                         break;
                    case 87:
                         $data = 1.8;
                         break;
                    case 86:
                         $data = 1.9;
                         break;
                    case 85:
                         $data = 2.0;
                         break;
                    case 84:
                         $data = 2.1;
                         break;
                    case 83:
                         $data = 2.2;
                         break;
                    case 82:
                         $data = 2.3;
                         break;
                    case 81:
                         $data = 2.4;
                         break;
                    case 80:
                         $data = 2.5;
                         break;
                   case 79:
                         $data = 2.6;
                         break;
                    case 78:
                         $data = 2.7;
                         break;
                    case 77:
                         $data = 2.8;
                         break;
                    case 76:
                         $data = 2.9;
                         break;
                    case 75:
                         $data = 3.0;
                         break;                

                     default:
                         $data = 5.0;
                }
            }
            return $data;
        }
        function getteacher($teachid){
            $r = mysql_query("select * from teacher where id=$teachid");
            $result = mysql_fetch_array($r);
            $data = $result['fname'].' '.$result['lname'];
            return $data;
        }
        
    }
?>