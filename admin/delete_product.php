<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: login.php');
    exit();
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;

if (!$productId) {
    http_response_code(400);
    echo json_encode(['error' => 'Product ID is required']);
    exit();
}

try {
    // First get the product to delete the image file
    $stmt = $conn->prepare("SELECT image_url FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        throw new Exception("Product not found");
    }
    
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Corrected image path: go up one level from admin/, then to pff/RESOURCES/images/product_images/
    $image_path = __DIR__ . '/../' . $product['image_url'];
    
    // Delete the product
    $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $delete_stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    
    if ($delete_stmt->execute()) {
        // Delete the image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    } else {
        throw new Exception("Failed to delete product");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    error_log("Product Delete Error: " . $e->getMessage());
}