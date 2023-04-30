<?php 
    include('../config.php'); 
    $level = isset($_SESSION['level']) ? $_SESSION['level']: null;
    if($level == null){
        header('location:../index.php');
    }else if($level != 'admin'){
        header('location:../'.$level.'');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../img/mcc.png">

    <title>InfoTech</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" class="rel">

    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"></script> -->

    <script src="../js/Chart.min.js"></script>
    <script src="../js/jquery-1.9.1.js"></script>

    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <link href="../css/style.css" rel="stylesheet">


    <style type="text/css">
.containers {
    padding: 20px;
}
.tabs-left {
  border-bottom: none;
  border-right: 1px solid #ddd;
}

.tabs-left>li {
  float: none;
 margin:0px;
  
}

.tabs-left>li.active>a,
.tabs-left>li.active>a:hover,
.tabs-left>li.active>a:focus {
  border-bottom-color: #ddd;
  border-right-color: transparent;
  background:#f90;
  border:none;
  border-radius:0px;
  margin:0px;
}
.nav-tabs>li>a:hover {
    /* margin-right: 2px; */
    line-height: 1.42857143;
    border: 1px solid transparent;
    /* border-radius: 4px 4px 0 0; */
}
.tabs-left>li.active>a::after{content: "";
    position: absolute;
    top: 10px;
    right: -10px;
    border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  
  border-left: 10px solid #f90;
    display: block;
    width: 0;}


.AlertModal {
  width: 500px;
  height: 200px;
  overflow: hidden;
  background: #34495e;
  position: fixed;
  bottom: -300px;
  right: 20px;
  transition: 1s;
}

.AlertModal .AlertModalContent{
  text-align:center;
  margin: auto;
  width: 300px;
  padding: 20px;
  box-sizing: border-box;
  color: white;
}
.AlertModal .AlertModalContent h1{
  font-size: 20px;
}
.AlertModal .AlertModalContent p{
  font-size: 15px;
}
.AlertModal .AlertModalContent a{
  display: inline-block;
  background: #e74c3c;
  padding: 10px 30px;
  border-radius: 8px;
  color: white;
}

.AlertModal .hide{
  position: absolute;
  z-index: 9999;
  top: 14px;
  right: 14px;
  font-size: 20px;
  color: white;
  opacity: 0.7;
  transition: 0.3s;
  cursor: pointer;
}

.AlertModal .hide:hover{
  transform: rotate(90deg);
  opacity: 1;
}

.AlertModal .btn{
  color: #e74c3c;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  border-radius: 8px;
  border: 2px solid;
  padding: 10px 20px;
}

/* charts */
.grapBox{
    position: relative;
    width: 100%;
    padding: 20px;
    display: grid;
    grid-template-columns: 2fr 1fr;
    grid-gap: 30px;
}
.grapBox .box{
    position: relative;
    background-color: black;
    padding: 20px;
    width: 100%;
    box-shadow: 0 7px 25px rgb(0, 0, 0);
    border-radius: 10px;
}






<style>
.dropbtn{
  margin-top: 10px;
  margin-right: 10px;
  background-color: #03bafc;
  color: black;
  padding-left: 6px;
  padding-right: 6px;
  padding-top: 3px;
  padding-bottom: 3px;
  font-size: 12px;
  border-style: solid;
  border-color: none;
  border-radius: 5px;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  margin-right: 10px;
  border-style: solid 1px;
  border-color: black;
  border-radius: 5px;
  display: none;
  position: absolute;
  right: 0;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 10px 16px;
  text-decoration: none;
  display: block;
  font-size: 12px;
}

.dropdown-content a:hover {background-color: black; color: white; border-radius: 5px;}
.dropdown:hover .dropbtn {background-color: black; color: white;}
</style>
<body>
<div class="dropdown" style="float:right;">
  <button onclick="myFunction()" class="dropbtn"><i class="fa fa-list" aria-hidden="true"></i> Dropdown Menu</button>
  <div id="myDropdown" class="dropdown-content">
    <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
    <a href="studentlist.php"><i class="fa fa-fw fa-users"></i> Students</a>
    <a href="subject.php"><i class="fa fa-fw fa-bar-chart-o"></i> Subjects</a>
    <a href="teacherlist.php"><i class="fa fa-fw fa-user"></i> Instructors</a>
    <a href="class.php"><i class="fa fa-fw fa-table"></i> Class Info</a>
    <a href="list.php"><i class="fa fa-envelope"></i> Consultation</a>
    <a href="report.php"><i class="fa fa-fw fa-folder"></i> Generate Reports</a>
    <a href="users.php"><i class="fa fa-fw fa-users"></i> Users</a>
    <a href="settings.php"><i class="fa fa-fw fa-gear"></i> Settings</a>
    <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
  </div>
</div>
<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

    <div id="wrapper">

      
            
            
