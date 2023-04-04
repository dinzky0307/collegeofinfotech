<?php
    include('../teacher/data/student_model.php');
    include('../DatabaseService.php');

    use Database\DatabaseService;
    use Carbon\Carbon;
    
    $dbService = new DatabaseService;

    $id = $_GET['id'];
    $stud = $dbService->fetchRow("SELECT * from student where id = {$id}");

    $grades = $dbService->fetch(
        "SELECT subject.code, subject.title, subject.title, studentsubject.prelim_grade, studentsubject.midterm_grade, studentsubject.final_grade from studentsubject INNER JOIN subject ON studentsubject.subjectid = subject.id WHERE studid = '$id'"
    );


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


    function getRemarks($average) {
        if ($average > 3) {
            return '<font color="red">Failed</font>';
        }

        if ($average === 0) {
            return '<font color="black">No Grades Yet</font>';
        }

        return '<font color="green">Passed</font>';
    }
?>
        
