<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Product ID is required']));
}

$productId = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        header('Content-Type: application/json');
        echo json_encode($product);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}