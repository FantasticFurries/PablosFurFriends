<?php
header('Content-Type: text/html; charset=utf-8');
include('connection.php');

// Get parameters with validation
// $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'All';
// ------changed category
$category = isset($_GET['category']) ? urldecode($_GET['category']) : 'All';
// ----------
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 16; // Products per page

// Validate page number
if ($page < 1) {
    $page = 1;
}

// Calculate offset
$offset = ($page - 1) * $perPage;

try {
    // Build base SQL query with prepared statements
    $sql = "SELECT * FROM products WHERE 1=1";
    $countSql = "SELECT COUNT(*) as total FROM products WHERE 1=1";
    
    $params = [];
    $countParams = [];

    if ($category !== 'All') {
        // $sql .= " AND category = :category";
        // $countSql .= " AND category = :category";
        $sql .= " AND LOWER(category) = LOWER(:category)";
        $countSql .= " AND LOWER(category) = LOWER(:category)";
        $params[':category'] = $category;
        $countParams[':category'] = $category;
    }

    if (!empty($search)) {
        $searchTerm = "%$search%";
        $sql .= " AND (name LIKE :search OR description LIKE :search)";
        $countSql .= " AND (name LIKE :search OR description LIKE :search)";
        $params[':search'] = $searchTerm;
        $countParams[':search'] = $searchTerm;
    }

    // Get total count
    $stmt = $conn->prepare($countSql);
    $stmt->execute($countParams);
    $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalProducts / $perPage);

    // Add pagination to main query
    $sql .= " LIMIT :perPage OFFSET :offset";
    $params[':perPage'] = $perPage;
    $params[':offset'] = $offset;

    // Prepare and execute main query
    $stmt = $conn->prepare($sql);
    
    // Bind parameters with proper types
    foreach ($params as $key => $value) {
        $paramType = PDO::PARAM_STR;
        if ($key === ':perPage' || $key === ':offset') {
            $paramType = PDO::PARAM_INT;
        }
        $stmt->bindValue($key, $value, $paramType);
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($products) > 0) {
        echo '<div class="products-grid">';
        foreach ($products as $row) {
            // Use relative path for images instead of absolute URL
            // $image_url = '../admin_area/product_images/' . basename($row['image_url']);
            $image_url = 'RESOURCES/images/product_images/' . basename($row['image_url']);
            
            echo '<div class="product-card">';
            echo '<div class="product-image-container">';
            // echo '<img src="' . htmlspecialchars($image_url) . '" class="product-image" alt="' . htmlspecialchars($row['name']) . '">';
            echo '<img src="' . htmlspecialchars($image_url) . '" class="product-image" alt="' . htmlspecialchars($row['name']) . '">';
            echo '</div>';
            echo '<h3 class="product-title">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</h3>';
            echo '<div class="product-footer">';
            echo '<button class="view-details" onclick="toggleDescription(this)">View Details</button>';
            echo '<div class="product-price">₱' . number_format($row['price'], 2) . '</div>';
            echo '</div>';
            echo '<div class="product-description" style="display:none;">' . nl2br(htmlspecialchars($row['description'], ENT_QUOTES)) . '</div>';
            echo '</div>';
        }
        echo '</div>';
        
        // Pagination controls
        if ($totalPages > 1) {
            echo '<div class="pagination">';
            if ($page > 1) {
                echo '<a href="#" class="page-link" data-page="'.($page-1).'">Previous</a>';
            }
            
            // Show limited page numbers for better UX
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1) {
                echo '<a href="#" class="page-link" data-page="1">1</a>';
                if ($startPage > 2) echo '<span class="page-dots">...</span>';
            }
            
            for ($i = $startPage; $i <= $endPage; $i++) {
                $active = $i == $page ? 'active' : '';
                echo '<a href="#" class="page-link '.$active.'" data-page="'.$i.'">'.$i.'</a>';
            }
            
            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) echo '<span class="page-dots">...</span>';
                echo '<a href="#" class="page-link" data-page="'.$totalPages.'">'.$totalPages.'</a>';
            }
            
            if ($page < $totalPages) {
                echo '<a href="#" class="page-link" data-page="'.($page+1).'">Next</a>';
            }
            echo '</div>';
        }
        
    } else {
        echo '<p class="no-products">No products found matching your criteria.</p>';
    }

} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database error: " . $e->getMessage());
    echo '<p class="error">Error loading products. Please try again later.</p>';
}
?>