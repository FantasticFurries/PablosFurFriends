<?php
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $bday = $_POST['bday'];
    $age = trim($_POST['age']);
    $address = trim($_POST['address']);
    $status = trim($_POST['status']);
    $type = trim($_POST['type']);
    $employment_date = $_POST['employment_date'];
    $adminID = $_POST['adminID'] === "0" ? null : intval($_POST['adminID']);

    $sql = "INSERT INTO staffs (fname, lname, email, bday, age, address, status, type, employment_date, adminID)
            VALUES (:fname, :lname, :email, :bday, :age, :address, :status, :type, :employment_date, :adminID)";

    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([
        ':fname' => $fname,
        ':lname' => $lname,
        ':email' => $email,
        ':bday' => $bday,
        ':age' => $age,
        ':address' => $address,
        ':status' => $status,
        ':type' => $type,
        ':employment_date' => $employment_date,
        ':adminID' => $adminID,
    ]);

    if ($success) {
        header("Location: staffs.php?message=staff_added");
        exit;
    } else {
        echo "Error: Failed to add staff.";
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>