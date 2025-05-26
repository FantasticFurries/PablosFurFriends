<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffID = $_POST['staffID'] ?? null;
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $bday = $_POST['bday'] ?? '';
    $age = $_POST['age'] ?? '';
    $address = $_POST['address'] ?? '';
    $status = $_POST['status'] ?? '';
    $type = $_POST['type'] ?? '';
    $employment_date = $_POST['employment_date'] ?? '';
    $adminID = $_POST['adminID'] ?: null;

    if (!$staffID) {
        die('Invalid staff ID');
    }

    $sql = "UPDATE staffs SET
                fname = :fname,
                lname = :lname,
                email = :email,
                bday = :bday,
                age = :age,
                address = :address,
                status = :status,
                type = :type,
                employment_date = :employment_date,
                adminID = :adminID
            WHERE staffID = :staffID";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
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
        ':staffID' => $staffID,
    ]);

    header("Location: staffs.php");
    exit;
} else {
    die('Invalid request method');
}