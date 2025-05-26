<?php
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staffID'])) {
    $staffID = intval($_POST['staffID']);

    $stmt = $conn->prepare("DELETE FROM staffs WHERE staffID = ?");
    $success = $stmt->execute([$staffID]);

    if ($success) {
        echo "success";
    } else {
        echo "Failed to delete staff. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>