<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: login.php');
    exit();
}

header('Content-Type: application/json');

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

try {
    // Validate required fields
    if (empty($title) || empty($description) || empty($category) || empty($price)) {
        throw new Exception("All fields are required");
    }

    // Image handling
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Product image is required");
    }

    $image = $_FILES['image'];
    $temp_image = $image['tmp_name'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $temp_image);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        throw new Exception("Only JPG, PNG, GIF, and WebP images are allowed");
    }

    // Generate unique filename
    $image_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '.' . $image_ext;
    
    // Corrected target path: go up one level from admin, then to pff/RESOURCES/images/product_images/
    $target_path = __DIR__ . '/../pff/RESOURCES/images/product_images/' . $unique_filename;
    
    // Move uploaded file
    if (!move_uploaded_file($temp_image, $target_path)) {
        throw new Exception("Failed to upload image");
    }
    
    // Store only the relative path from main folder
    $image_url = $unique_filename;
    
    // Insert product
    $stmt = $conn->prepare("INSERT INTO products 
                          (name, description, category, price, image_url) 
                          VALUES 
                          (:title, :description, :category, :price, :image_url)");
    
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Product added successfully!',
            'product_id' => $conn->lastInsertId()
        ]);
    } else {
        // Remove the uploaded file if DB insert fails
        unlink($target_path);
        throw new Exception("Database error: Failed to save product");
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
