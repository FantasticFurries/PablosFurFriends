<?php
require_once 'conn.php';
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $conn->prepare("DELETE FROM reviews WHERE reviewID = ?");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Review not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>