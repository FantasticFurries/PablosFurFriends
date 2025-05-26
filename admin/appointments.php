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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Appointments</title>
    <link rel="stylesheet" href="appointments.css"/>
    <link rel="stylesheet" href="admin_sidebar.css">
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
            <div class="dashboard-container">
                <div class="section-header">
                    <h2>Appointments Overview</h2>
                    <button class="view-button">View previous appointments</button>
                </div>

                <div class="admin-filter-buttons">
                    <button id="filterPending" class="filter-btn active">Pending</button>
                    <button id="filterApproved" class="filter-btn">Approved</button>
                    <button id="filterCancelled" class="filter-btn">Cancelled</button>
                    <button id="filterCompleted" class="filter-btn">Completed</button>
                    <button id="filterAll" class="filter-btn">All</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>App ID</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Pet Name</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentsTableBody">
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">Loading appointments...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div> 
    
    <div id="editAppointmentModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Edit Appointment</h2>
            <form id="editAppointmentForm" method="POST">
                <input type="hidden" id="editAppID" name="editAppID">

                <label for="editClientName">Client Name:</label>
                <input type="text" id="editClientName" name="editClientName" required>

                <label for="editClientEmail">Email:</label>
                <input type="email" id="editClientEmail" name="editClientEmail" required>

                <label for="editClientPhone">Phone:</label>
                <input type="tel" id="editClientPhone" name="editClientPhone">

                <label for="editPetName">Pet Name:</label>
                <input type="text" id="editPetName" name="editPetName" required>

                <label for="editService">Service:</label>
                <select id="editService" name="editService" required>
                    <option value="Grooming">Grooming</option>
                    <option value="Boarding">Boarding</option>
                </select>

                <label for="editAppointmentDate">Appointment Date:</label>
                <input type="date" id="editAppointmentDate" name="editAppointmentDate" required>

                <label for="editStatus">Status:</label>
                <select id="editStatus" name="editStatus" required>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Completed">Completed</option>
                </select>

                <button type="submit">Save Changes</button>
                <button type="button" class="cancel-edit-btn">Cancel</button>
            </form>
        </div>
    </div>

    <script src="sidebar.js"></script>
    <script src="appointments.js"></script> 
</body>
</html>