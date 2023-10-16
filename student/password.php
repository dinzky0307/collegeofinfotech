<?php
    $settings = new Datasettings();
    if(isset($_GET['q'])){
        $settings->$_GET['q']();
    }

    class Datasettings {
        
        function __construct(){
            if(!isset($_SESSION['id'])){
                header('location:../../');   
            }
        }
        
        function logs($act){            
            $date = date('m-d-Y h:i:s A');
            echo $q = "insert into log values(null,'$date','$act')";   
            mysql_query($q);
            return true;
        }
        
        function changepassword(){
            include('../config.php');
            $username = $_GET['username'];
            $current = $_POST['current'];
            $new = password_hash($_POST['new'], PASSWORD_DEFAULT);
            $confirm = sha1($_POST['confirm']);
            $q = "select * from userdata where user_id='".$_SESSION['user_id']."'";
            $r = mysql_query($q);
            if(mysql_num_rows($r) > 0){
                $data = mysql_fetch_assoc($r);

                if (password_verify($current, $data['password'])) {
                    if($new == $confirm){
                        $act = $username.' changes his/her password.';
                        $this->logs($act);
                        $r2 = mysql_query("update userdata set password='$new' where id='".$_SESSION['user_id']."' ");
                        header('location:index.php?msg=success&username='.$username.'');   
                    }else{
                        header('location:index.php?msg=error&username='.$username.'');   
                    }
                }else{
                    header('location:index.php?msg=error&username='.$username.'');   
                }
                
            }else{
                header('location:index.php?msg=error&username='.$username.'');   
            }   
        }
                
    }
?>
