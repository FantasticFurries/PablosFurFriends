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
//if ($adminID == 2 || $adminID == 0) {
//  echo '<script>alert("Access denied.\nYou are not allowed to access this page.");</script>'
//}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="admin_sidebar.css"/>
  <link rel="icon" type="image/x-icon" href="img/logo_pff.png">
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
    <h2>Dashboard</h2>
    <div class="card-container">
      <a href="products.php">
        <div class="card green">
          <div class="card-content">
            <img src="img/products.png" alt="Products Icon" class="card-icon">
            <h3>Products</h3>
            <p>Manage store inventory</p>
          </div>
          <div class="card-footer">
            <button>
              <span>More info</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
      <a href="services.php">
        <div class="card orange">
          <div class="card-content">
            <img src="img/services.png" alt="Services Icon" class="card-icon">
            <h3>Services</h3>
            <p>Edit your service list</p>
          </div>
          <div class="card-footer">
            <button>
              <span>More info</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
      <a href="appointments.php">
        <div class="card blue">
          <div class="card-content">
            <img src="img/appointments.png" alt="Appointments Icon" class="card-icon">
            <h3>Appointments</h3>
            <p>View Appointments</p>
          </div>
          <div class="card-footer">
            <button>
              <span>More info</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
      <a href="reviews.php">
        <div class="card red">
          <div class="card-content">
            <img src="img/reviews.png" alt="Reviews Icon" class="card-icon">
            <h3>Reviews</h3>
            <p>Customers feedback</p>
          </div>
          <div class="card-footer">
            <button>
              <span>More info</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
      <?php if($adminID == 1): ?>
      <a href="staffs.php">
        <div class="card gray">
          <div class="card-content">
            <img src="img/staffs.png" alt="Staffs Icon" class="card-icon">
            <h3>Management</h3>
            <p>Manage current employees</p>
          </div>
          <div class="card-footer">
            <button>
              <span>More info</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
      <?php endif; ?>
      <a href="reports.php">
        <div class="card yellow">
          <div class="card-content">
            <img src="img/reports.png" alt="Reports Icon" class="card-icon">
            <h3>Reports</h3>
            <p>See summary of reports</p>
          </div>
          <div class="card-footer">
            <button>
              <span>More info</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
      <a href="/gian files/PFF/index.php">
        <div class="card violet">
          <div class="card-content">
            <img src="img/alt_logo_pff.png" alt="Website Icon" class="card-icon">
            <h3>Website</h3>
            <p>Visit the main website!</p>
          </div>
          <div class="card-footer">
            <button>
              <span>Visit</span>
              <img src="img/arrow.png" class="footer-icon" alt="Arrow icon">
            </button>
          </div>
        </div>
      </a>
    </div>
  </main>

  <script src="sidebar.js"></script>
</body>
</html>