<?php

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $stmt = $connection->prepare("SELECT title FROM subject WHERE code = ?");
    $stmt->execute([$code]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo $result['title'];
    } else {
        echo "";
    }
}
?>

