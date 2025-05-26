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

$staffDetails = null;
if (isset($_GET['viewStaff'])) {
    $staffID = intval($_GET['viewStaff']);
    $stmt = $conn->prepare("SELECT * FROM staffs WHERE staffID = :id");
    $stmt->bindParam(':id', $staffID, PDO::PARAM_INT);
    $stmt->execute();
    $staffDetails = $stmt->fetch(PDO::FETCH_ASSOC);
}

$adminID = $fetch['adminID'];
if ($adminID !== 1) {
  echo '<script>alert("Access denied.\nYou are not allowed to access this page.");</script>';
  header('location: dashboard.php');
}

$petsQuery = $conn->query("SELECT COUNT(DISTINCT p.petID) as total 
                          FROM pets p
                          JOIN appointments a ON p.petID = a.petID
                          WHERE a.status = 'Completed'");
$totalPets = $petsQuery->fetch(PDO::FETCH_ASSOC)['total'];


// Get total active staff members
$staffQuery = $conn->query("SELECT COUNT(*) as total FROM staffs WHERE status = 'Active'");
$totalStaff = $staffQuery->fetch(PDO::FETCH_ASSOC)['total'];

// Get monthly bookings (completed appointments for current month)
$currentMonth = date('m');
$currentYear = date('Y');
$bookingsQuery = $conn->prepare("SELECT COUNT(*) as total FROM appointments 
                               WHERE status = 'Completed' 
                               AND MONTH(appointment_date) = :month 
                               AND YEAR(appointment_date) = :year");
$bookingsQuery->bindParam(':month', $currentMonth, PDO::PARAM_INT);
$bookingsQuery->bindParam(':year', $currentYear, PDO::PARAM_INT);
$bookingsQuery->execute();
$monthlyBookings = $bookingsQuery->fetch(PDO::FETCH_ASSOC)['total'];

$currentYear = date('Y');
$monthlyDataQuery = $conn->prepare("SELECT 
                                   MONTHNAME(appointment_date) as month, 
                                   COUNT(*) as count 
                                   FROM appointments 
                                   WHERE status = 'Completed' 
                                   AND YEAR(appointment_date) = :year
                                   GROUP BY MONTH(appointment_date) 
                                   ORDER BY MONTH(appointment_date)");
$monthlyDataQuery->bindParam(':year', $currentYear, PDO::PARAM_INT);
$monthlyDataQuery->execute();
$monthlyData = $monthlyDataQuery->fetchAll(PDO::FETCH_ASSOC);

// Ensure $monthlyData is always an array
if (empty($monthlyData)) {
    $monthlyData = [];
}

// Get pets serviced records
$petsServicedQuery = $conn->query("SELECT a.appID, a.service, a.appointment_date, a.status, 
                                  p.petID, p.petName 
                                  FROM appointments a
                                  JOIN pets p ON a.petID = p.petID
                                  WHERE a.status = 'Completed'
                                  ORDER BY a.appointment_date DESC");
$petsServiced = $petsServicedQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="reports.css"/>
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

    <!-- MAIN CONTENT -->
     <main class="main-content">
    <div class="reports-header">
      <h2>Reports</h2>
    </div>
    
    <div class="report-cards">
      <!-- Total Pets Assisted Card -->
      <div class="report-card">
        <div class="card-header totalpets">
          <select id="petsTimeRange" class="time-range-select">
            <option value="all">All Time</option>
            <option value="yearly">Yearly</option>
            <option value="monthly">Monthly</option>
          </select>
          <h3>Total Pets Assisted</h3>
        </div>
        <div class="card-value" id="totalPetsValue"><?= $totalPets ?></div>
        <!-- <button class="view-details-btn">View Details</button> -->
      </div>
      
      <!-- Total Staff Members Card -->
      <div class="report-card">
        <div class="card-header totalStaff">
          <h3>Total Staff Members</h3>
        </div>
        <div class="card-value"><?= $totalStaff ?></div>
        <!-- <button class="view-details-btn">View Details</button> -->
      </div>
      
      <!-- Monthly Bookings Card -->
      <div class="report-card">
        <div class="card-header monthlyBookings">
          <h3>Monthly Bookings (<?= date('F') ?>)</h3>
        </div>
        <div class="card-value"><?= $monthlyBookings ?></div>
        <!-- <button class="view-details-btn">View Details</button> -->
      </div>
    </div>
    
    <!-- Monthly Bookings Window -->
    <div class="report-window" id="monthlyBookingsWindow">
      <div class="window-header">
        <h3>Monthly Bookings</h3>
        <div class="window-controls">
          <button class="minimize-btn">−</button>
        </div>
      </div>
      <div class="window-content">
        <div class="year-selector">
    <label for="yearSelect">Year:</label>
    <select id="yearSelect">
        <?php
        $currentYear = date('Y');
        // Only show current year and next year
        for ($i = $currentYear; $i <= $currentYear + 1; $i++) {
            echo "<option value='$i'" . ($i == $currentYear ? ' selected' : '') . ">$i</option>";
        }
        ?>
    </select>
    </div>
        <table class="monthly-table">
          <thead>
            <tr>
              <th>Month</th>
              <th>Service Bookings</th>
            </tr>
          </thead>
          <tbody>
          
            <!-- ------ -->
             <?php if (!empty($monthlyData)): ?>
        <?php foreach ($monthlyData as $month): ?>
            <tr>
                <td><?= $month['month'] ?></td>
                <td><?= $month['count'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2" style="text-align: center;">No completed appointments found for this period</td>
        </tr>
    <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Pets Serviced Record Window -->
    <div class="report-window" id="petsServicedWindow">
      <div class="window-header">
        <h3>Pets Serviced Record</h3>
        <div class="window-controls">
          <button class="minimize-btn">−</button>
        </div>
      </div>
      <div class="window-content">
        <table class="pets-table">
          <thead>
            <tr>
              <th>App ID</th>
              <th>Service</th>
              <th>Pet Name</th>
              <th>Appointment Date</th>
              <th>Pet ID</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($petsServiced as $record): ?>
              <tr>
                <td><?= $record['appID'] ?></td>
                <td><?= $record['service'] ?></td>
                <td><?= $record['petName'] ?></td>
                <td><?= $record['appointment_date'] ?></td>
                <td><?= $record['petID'] ?></td>
                <td><?= $record['status'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
 <script src="sidebar.js"></script>
 <script src="reports.js"></script>
</body>
</html>