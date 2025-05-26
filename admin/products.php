<!DOCTYPE html>
<?php
require_once 'conn.php';
session_start();

if (!isset($_SESSION['user'])) {
  header('location: login.php');
}

$id = $_SESSION['user'];
$sql = $conn->prepare("SELECT * FROM admin_users WHERE adminID = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();
$fetch = $sql->fetch();

$adminID = $fetch['adminID'];

$products_sql = $conn->prepare("SELECT * FROM products");
$products_sql->execute();
$products = $products_sql->fetchAll(PDO::FETCH_ASSOC);

$categories = $conn->query("SELECT * FROM `categories`")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
    <link href="products.css" rel="stylesheet"/>
    <link rel="stylesheet" href="admin_sidebar.css"/>
</head>
<body>
  <nav>
    <div class="ToggleLogoTitle">
      <button id="sidebarToggle" class="openbtn" aria-label="Toggle Sidebar">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
          <rect y="4" width="24" height="2" rx="1" fill="#fff"/>
          <rect y="11" width="24" height="2" rx="1" fill="#fff"/>
          <rect y="18" width="24" height="2" rx="1" fill="#fff"/>
        </svg>
      </button>
      <img src="img/logo_pff.png" class="logo-img" alt="Admin Logo">
      <h2 class="Title">Admin Panel</h2>
    </div>
    <div class="admin_acc">
      <h3 class="Admin">
        <?php
          echo htmlspecialchars($fetch['username']);
        ?>
      </h3>
      <img src="img/profile.png" class="profile-icon" alt="Profile">
      <div class="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

  <div class="sidebar">
    <ul>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="appointments.php">Appointments</a></li>
      <li><a href="reviews.php">Reviews</a></li>
      <?php if($adminID == 1): ?>
      <li><a href="staffs.php">Management</a></li>
      <?php endif; ?>
      <li><a href="reports.php">Reports</a></li>
    </ul>
  </div>

  <main class="main-content">
        <div class="products-container">
            <div class="products-header">
                <h2>Products Management</h2>
                <button id="addProductBtn" class="btn-add">Add Product</button>
            </div>

            <!-- Product Table -->
            <div class="product-table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td>
                                <img src="../pff/RESOURCES/images/product_images/<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-thumbnail">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="description-cell">
                                <?php echo nl2br(htmlspecialchars(substr($product['description'], 0, 50) . (strlen($product['description']) > 50 ? '...' : ''))); ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td>₱<?php echo number_format($product['price'], 2); ?></td>
                            <td class="actions-cell">
                                <button class="btn-edit" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-delete" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Add New Product</h2>
            <form id="productForm" method="post" enctype="multipart/form-data">
                <input type="hidden" id="productId" name="product_id" value="">
                <div class="form-group">
                    <label for="title">Product Name</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['category_title']); ?>">
                                <?php echo htmlspecialchars($category['category_title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div id="imagePreview" class="image-preview"></div>
                </div>
                <div class="form-actions">
                    <button type="submit" name="insert_product" class="btn-submit">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content confirm-modal">
            <p>Are you sure you want to delete this product?</p>
            <div class="confirm-actions">
                <button id="confirmDelete" class="btn-confirm">Yes, Delete</button>
                <button id="cancelDelete" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <script src="sidebar.js"></script>
    <script src="products.js"></script>
</body>
</html>