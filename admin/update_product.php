<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: login.php');
    exit();
}

header('Content-Type: application/json');

// Get form data
$productId = $_POST['product_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

try {
    // Validate required fields
    if (empty($productId) || empty($title) || empty($description) || empty($category) || empty($price)) {
        throw new Exception("All fields are required");
    }

    // Check if product exists
    $check_stmt = $conn->prepare("SELECT image_url FROM products WHERE id = :id");
    $check_stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() === 0) {
        throw new Exception("Product not found");
    }
    
    $product = $check_stmt->fetch(PDO::FETCH_ASSOC);
    $current_image = $product['image_url'];
    $image_url = $current_image;
    
    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
        
        // Corrected target path for upload
        $target_path = __DIR__ . '/../pff/RESOURCES/images/product_images/' . $unique_filename;
        
        // Move uploaded file
        if (!move_uploaded_file($temp_image, $target_path)) {
            throw new Exception("Failed to upload new image");
        }
        
        // Store relative path for DB
        $image_url = $unique_filename;
        
        // Delete old image file
        $old_image_path = __DIR__ . '/../' . $current_image;
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    }
    
    // Update product
    $stmt = $conn->prepare("UPDATE products SET 
                          name = :title, 
                          description = :description, 
                          category = :category, 
                          price = :price, 
                          image_url = :image_url 
                          WHERE id = :id");
    
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Product updated successfully!'
        ]);
    } else {
        // Remove new image if DB update failed
        if ($image_url !== $current_image) {
            $new_image_path = __DIR__ . '/../' . $image_url;
            if (file_exists($new_image_path)) {
                unlink($new_image_path);
            }
        }
        throw new Exception("Database error: Failed to update product");
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
