<?php
require_once 'connection.php';
header('Content-Type: application/json');

$fname = $_POST['fname'] ?? null;
$lname = $_POST['lname'] ?? null;
$feedback = $_POST['feedback'] ?? null;
$date = date('Y-m-d');

if (empty($fname) || empty($lname) || empty($feedback)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

try {
    $sql = "INSERT INTO reviews (fname, lname, feedback, date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fname, $lname, $feedback, $date]);

    if ($stmt->rowCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully!']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to submit review. No rows affected.']);
    }

} catch (\PDOException $e) {
    error_log("Review Submission PDO Error: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'An error occurred during submission. Please try again.']);
}
?>