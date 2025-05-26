<?php
session_start();
require_once 'connection.php';

if (isset($_POST['login'])) {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $query = $conn->prepare($sql);
        $query->execute([$email]);
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if ($fetch && password_verify($password, $fetch['userPW'])) {
            $_SESSION['user'] = $fetch['userID'];
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password')</script>";
            echo "<script>window.location = 'auth1.php'</script>";
        }
    } else {
        echo "<script>alert('Please complete the required field!')</script>";
        echo "<script>window.location = 'index.php'</script>";
    }
}
?>