<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM mail_subscribers WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();

        if ($success) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete subscriber']);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}
?>