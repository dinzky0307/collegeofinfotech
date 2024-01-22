<?php
    $student = new Datastudent();
    if(isset($_GET['q'])){
        $function = $_GET['q'];
        $student->$function();
    }
    
    class Datastudent {
        
        function __construct(){
            if(!isset($_SESSION['id'])){
                header('location:../../');   
            }
        }
        
        function getstudentbyclass($classid){
            $q = "select * from studentsubject where classid=$classid";
            $r = mysql_query($q);
            $student = array();
            if($classid != null){
               while($row = mysql_fetch_array($r)){
                    $q2 = 'select * from student where id='.$row['studid'].'';  
                    $r2 = mysql_query($q2);
                    $student[] = mysql_fetch_array($r2);    
                } 
            }
            return $student;
        }
        
        
        function getstudentbysearch($classid,$search){
            $q = "select * from student where fname like '%$search%' or lname like '%$search%' or studid like '%$search%'";
            $r = mysql_query($q);
            $student = array();
            while($row = mysql_fetch_array($r)){
                $q2 = 'select * from studentsubject where studid='.$row['id'].' and classid='.$classid.'';  
                $r2 = mysql_query($q2);
                if(mysql_num_rows($r2) > 0) {
                    $student[] = $row;
                }

            }
            return $student;        
        }
        function getstudbysearch($classid, $search, $year, $sem, $sec, $ay) {
          $q = "SELECT * FROM student WHERE fname LIKE '%$search%' OR lname LIKE '%$search%' OR studid LIKE '%$search%'";
          $r = mysql_query($q);
          $student = array();
          
          if ($r) { // Check if the query executed successfully
              while ($row = mysql_fetch_array($r)) {
                  $q2 = "SELECT * FROM studentsubject WHERE studid={$row['id']} AND classid={$classid} AND year={$year} AND semester={$sem} AND section={$sec} AND SY={$ay}"; 
                  $r2 = mysql_query($q2);
                  
                  if ($r2 && mysql_num_rows($r2) > 0) { // Check if the second query executed successfully
                      $student[] = $row;
                  }
              }
              
              mysql_free_result($r); // Free the result set for the first query
          } else {
              // Handle the query execution error here
          }
          
          return $student;
      }
      
        
        function getstudentgrade($id){
            $q = "select * from studentsubject where id='$id'";
            $r = mysql_query($q);
            //$data = array(); // Initialize $data to an empty array
            
            if($row = mysql_fetch_array($r)){
                $prelim_grade = ($row['prelim_grade']);
                $midterm_grade = ($row['midterm_grade']);
                $finals_grade = ($row['final_grade']);
                
                $prelim = $prelim_grade;
                $midterm = $midterm_grade;
                $final = $finals_grade;
                
                $total = (($prelim + $midterm) /2)* .30 + ($final) * .70;
                
                $data = array(
                  'prelim' => $prelim_grade,
                  'midterm' => $midterm_grade,
                  'final' => $finals_grade,
                  'total' => $total,
                  'eqtotal' => sprintf("%.1f", $total),
                  'prelim_grade' => $prelim_grade,
                  'midterm_grade' => $midterm_grade,
                  'finals_grade' => $finals_grade,
              );

                return $data;
            }

        }
        
        function getstudentbyid($studid){
            $q = "select * from student where id=$studid";   
            $r = mysql_query($q);
            $data = array();
            $data[] = mysql_fetch_array($r);
            return $data;
        }
        
        function gradeconversion($grade){
            $grade = round($grade);
            if($grade==0){
                 $data = 0;
            }else{
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
            return sprintf("%.1f",$data);
        }
    }
?>
