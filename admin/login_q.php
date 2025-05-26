<?php
session_start();
require_once 'conn.php';

if (isset($_POST['login'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        $sql = "SELECT * FROM admin_users WHERE username = ?";
        $query = $conn->prepare($sql);
        $query->execute([$username]);
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if ($fetch && $password === $fetch['password']) {
            session_regenerate_id(true);
            $_SESSION['user'] = $fetch['adminID'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password')</script>";
            echo "<script>window.location = 'login.php'</script>";
        }
    } else {
        echo "<script>alert('Please complete the required field!')</script>";
        echo "<script>window.location = 'login.php'</script>";
    }
}
?>