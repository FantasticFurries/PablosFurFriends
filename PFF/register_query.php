<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'connection.php';

if (isset($_POST['register'])) {
    if (!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        try {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $checkQuery = "SELECT * FROM users WHERE email = :email";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->execute([':email' => $email]);

            if ($checkStmt->rowCount() > 0) {
                echo "<script>alert('Email already exists!');</script>";
                echo "<script>window.location = 'auth2.php';</script>";
            } else {
                $sql = "INSERT INTO users (fname, lname, email, userPW) 
                        VALUES (:fname, :lname, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':fname' => $fname,
                    ':lname' => $lname,
                    ':email' => $email,
                    ':password' => password_hash($password, PASSWORD_DEFAULT)
                ]);

                $_SESSION['message'] = array("text" => "User Successfully created.", "alert" => "info");
                header('Location: auth1.php');
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Please fill up all required fields!');</script>";
        echo "<script>window.location = 'auth2.php';</script>";
    }
}
?>