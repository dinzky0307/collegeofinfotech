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
                
                $total = (($prelim) + ($midterm)/2) * .30) + ($final * .70);
                
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
                      case 74:
                         $data = 3.1;
                         break;
                    case 73:
                         $data = 3.2;
                         break;
                    case 72:
                         $data = 3.3;
                         break;
                    case 71:
                         $data = 3.4;
                         break; 
                    case 70:
                         $data = 3.5;
                         break;
                    case 69:
                         $data = 3.6;
                         break;
                    case 68:
                         $data = 3.7;
                         break;
                    case 67:
                         $data = 3.8;
                         break;
                   case 66:
                         $data = 3.9;
                         break;
                   case 65:
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
