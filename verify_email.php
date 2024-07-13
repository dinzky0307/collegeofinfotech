<?php
//session_start();
$host = '127.0.0.1';
$user = 'u510162695_infotechMCC';
$pass = 'infotechMCC2023';
$db = 'u510162695_infotechMCC';

//mysql_connect($host, $user, $pass) or die(mysql_error());
//mysql_select_db($db);

// Include the configuration file
//include 'config.php';

// Database connection (same as in send_verification.php)

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $stmt = $pdo->prepare('SELECT email FROM email_verifications WHERE token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        echo "Email " . $user['email'] . " has been successfully verified.";
        // Optionally, delete the token from the database or mark it as used
        $stmt = $pdo->prepare('DELETE FROM email_verifications WHERE token = ?');
        $stmt->execute([$token]);
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
