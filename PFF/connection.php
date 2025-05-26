<?php
$db_username = 'root';
$db_password = 'GIAN.MYSQL.PASSWORD';
try {
    $conn = new PDO('mysql:host=localhost;dbname=pff', $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>