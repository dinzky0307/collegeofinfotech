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

.admin {
    color: #721c24 !important;
    background-color: #f8d7da !important;
    border-color: #f5c6cb !important;
    text-align: right !important;
}
.title{
  animation: wobble 2s ease 0s 1 normal forwards;
  text-align: center;
}
div#responsecontainer {
    max-height: 400px;
    overflow-y: scroll;
}
div.alert {
      display: flex;
    flex-direction: column-reverse;
}

.sendButton {
  background: #58B9B6;
  color: #F4F5F0;
  border: 0;
  cursor: pointer;
  display: inline-block;
  letter-spacing: 1px;
  padding: 1rem 1.5rem;
  border-radius: 5px;
  transition: background 100ms ease-in-out;
}
.sendButton:hover {
  background: #6ac1be;
}
.sendButton:active {
  background: #49aeab;
}
.sendButton .loading {
  display: inline-block;
  margin: -2px 2px;
  height: 8px;
  width: 8px;
  border-radius: 50%;
  border: 3px solid #F4F5F0;
  border-top-color: transparent;
  -webkit-animation: spin 800ms infinite linear;
          animation: spin 800ms infinite linear;
}
.dash {
  margin: 10px;
    padding: 20px;
    text-align: center;
}


@-webkit-keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes wobble {
  0%,
  100% {
    transform: translateX(0%);
    transform-origin: 50% 50%;
  }

  15% {
    transform: translateX(-30px) rotate(-6deg);
  }

  30% {
    transform: translateX(15px) rotate(6deg);
  }

  45% {
    transform: translateX(-15px) rotate(-3.6deg);
  }

  60% {
    transform: translateX(9px) rotate(2.4deg);
  }

  75% {
    transform: translateX(-6px) rotate(-1.2deg);
  }
}

</style>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">MCC - BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">                
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>  <?php echo $_SESSION['level']; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="settings.php"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
