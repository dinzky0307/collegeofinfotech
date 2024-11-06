<?php 
    include('../config.php'); 
    $level = isset($_SESSION['level']) ? $_SESSION['level']: null;
    if($level == null){
        header('location:../index.php');
    }else if($level != 'teacher'){
        header('location:../'.$level.'');
    }
    $id = $_SESSION['id'];
    $q = "select * from teacher where teachid='$id'";
    $r = mysql_query($q);
    if($row = mysql_fetch_array($r)){
        $id = $row['id'];   
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

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" class="rel">
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 

</head>
<style>
    .box-size {
        text-align: center;
        width: 70px;
        height: 25px;
        padding: 6px 12px;
        color: #555;
        background-color: transparent;
        border: none;
        margin-top: -7px;
    }
        input::-webkit-inner-spin-button {
            -webkit-appearance: inner-spin-button;
            display: inline-block;
            cursor: default;
            flex: 0 0 auto;
            align-self: stretch;
            -webkit-user-select: none;
            opacity: 0;
            pointer-events: none;
            -webkit-user-modify: read-only;
        }
            .btn-text-right{
                text-align: right;
        }


        .dropdown-menu-right {
    text-align: left;
    min-width: 160px;
}
.dropdown-menu a {
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    display: flex;
    align-items: center;
}
.dropdown-menu a i {
    margin-right: 8px;
}
.dropdown-menu a:hover {
    background-color: #f5f5f5;
}
.text-danger {
    color: #d9534f !important;
}

</style>

<body>

 <!-- Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Navbar Header -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php" style="font-size: 13px;">MADRIDEJOS COMMUNITY COLLEGE - BSIT</a>
        </div>
        
        <!-- Collapsible Navbar -->
        <div id="navbar" class="navbar-collapse collapse">
            <!-- Right-aligned Profile Dropdown -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle btn btn-primary" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white;">
                        <i class="fa fa-user" > </i>&nbsp;&nbsp;<?php echo $_SESSION['name']; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" data-toggle="modal" data-target="#changeEmailModal">
                                 <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#changepass">
                               <li><a href="subject.php"><i class="fa fa-book"></i> <span>My Subjects</span></a></li>
                            </a>
                        </li>
                           <li>
                            <a href="#" data-toggle="modal" data-target="#changepass">
                                <li><a href="list.php"><i class="fa fa-envelope"></i> <span>Consultation</span></a></li>
                            </a>
                        </li>
                         <li>
                             <li><a href="settings.php"><i class="fa fa-gear"></i> <span>Change Password</span></a></li>
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="../logout.php" class="text-danger">
                                <i class="fa fa-power-off"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

      
    </body>
</html>
      
            