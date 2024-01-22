<?php

include 'config.php';

if(isset($_POST['submit'])){
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $query = "select * from userdata where username='$user' ";
    $r = mysql_query($query);
    if(mysql_num_rows($r) == 1){
      $row = mysql_fetch_assoc($r);
        if (password_verify($pass, $row['password'])) {
          
          $_SESSION['message']="You are now logged in.";
          $_SESSION['level'] = $row['level'];
          $_SESSION['id'] = $row['username'];
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['name'] = $row['fname'].' '.$row['lname'];
          header('location:'.$row['level'].'');
        }

    }else{
        header('location:index.php?login=0');
    }
}

if(isset($_SESSION['level'])){
    header('location:'.$_SESSION['level'].'');   
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/mcc.png">

    <title>InfoTech</title>
  <link rel="stylesheet" href="css/style1.css" />
</head>

<body>
  <div class="container" >
    <div class="forms-container" >
      <div class="signin-signup">
        <form action="" method="post" class="sign-in-form">
          <h2 class="title">Log In</h2>
          <div class="form-group" style="color: red;">
                <?php if(isset($_GET['login'])): ?>
                    <label class="text-danger">Invalid Username/Password try again</label>&nbsp;
                <?php endif; ?>
          </div>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="ID number" name="user" value="<?php 
                        if (isset($_SESSION['username'])) {
                           echo $_SESSION['username'];
                        }
                    ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="pass" required />
          </div>
          <input type="submit" value="Login" name="submit" class="btn solid" style=""/>
          <p style="display: flex;justify-content: center;align-items: center;margin-top: 20px;"><a href="forgot_password.php" style="color: #4590ef;">Forgot Password?</a></p>
        </form>
        <!-- <form action="" class="sign-up-form" method="post">
          <h2 class="title">Sign up</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Full Name" name="signup_full_name" value="<?php echo $_POST["signup_full_name"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email Address" name="signup_email" value="<?php echo $_POST["signup_email"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="signup_password" value="<?php echo $_POST["signup_password"]; ?>" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Confirm Password" name="signup_cpassword" value="<?php echo $_POST["signup_cpassword"]; ?>" required />
          </div>
          <input type="submit" class="btn" name="signup" value="Sign up" />
        </form> -->
      </div>
    </div>
    

    <div class="panels-container" >
      <div class="panel left-panel">
        <div class="content">
        <img src="img/mcc.png" alt="mcc" width="230" height="200" style="padding: 0 4px; margin-bottom: 95px; margin-right: 50px; margin-top: 30px;">
        <p class="p" style=" text-shadow: 1px 2px 3px black;
  color: #f0f0f0;"></p>
          <h2 class="h2" style=" text-shadow: 1px 3px 3px black;
  color: #f0f0f0;">College of Computer Studies</h2>
        </div>
        <!-- <img src="img/register.svg" class="image" alt=""/> -->
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
<!--   <script src="app.js"></script> -->
  <script>
    window.addEventListener('DOMContentLoaded', (event) => {
  function focusOnUsername() {
    const usernameInput = document.querySelector('input[name="user"]');
    if (usernameInput) {
      usernameInput.focus(); // Focus on the username input field
    }
  }

  const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  if (isMobile) {
    focusOnUsername(); // Call the focus function for mobile devices
  }
});
 </script>
</body>

</html>
