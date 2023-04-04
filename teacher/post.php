<?php
include '../database.php';

if(isset($_POST['posted'])) {
    $sql = "INSERT INTO posts (content, author_id) VALUES (?, ?)";
  
    $connection->prepare($sql)->execute([
        $_POST['content'], 
        $_SESSION['user_id'], 
    ]);

    echo "<script type='text/javascript'>";
    echo "Swal.fire({
       title: 'Message posted successfully',
       icon: 'success',
     })";
    echo "</script>";
}

?>