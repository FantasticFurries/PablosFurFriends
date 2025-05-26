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
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Management</title>
  <link rel="stylesheet" href="staffs.css"/>
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
      <li><a href="staffs.php">Management</a></li>
      <li><a href="reports.php">Reports</a></li>
    </ul>
  </div>

  <main class="main-content">
    <div class="staff-header">
        <h2>Management</h2>
        <button class="add-btn" id="add-btn">Add New Employee</button>
    </div>
    <div class="staffs-table">
        <table>
            <thead>
                <tr>
                    <th data-sort="number">ID <span class="sort-arrow"></span></th>
                    <th data-sort="string">Staff Name<span class="sort-arrow"></span></th>
                    <th data-sort="date">Employment Date<span class="sort-arrow"></span></th>
                    <th data-sort="string">Status<span class="sort-arrow"></span></th>
                    <th data-sort="string">Type<span class="sort-arrow"></span></th>
                    <th data-sort="string">Admin Access<span class="sort-arrow"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conn->query("SELECT staffID, fname, lname, age, bday, address, employment_date, email, status, type, adminID FROM staffs");
                if ($query && $query->rowCount() > 0):
                    while ($staff = $query->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <tr data-staff-id="<?= htmlspecialchars($staff['staffID']) ?>">
                    <td><?= htmlspecialchars($staff['staffID']) ?></td>
                    <td><?= htmlspecialchars($staff['fname'] . ' ' . $staff['lname']) ?></td>
                    <td><?= htmlspecialchars($staff['employment_date']) ?></td>
                    <td><?= htmlspecialchars($staff['status']) ?></td>
                    <td><?= htmlspecialchars($staff['type']) ?></td>
                    <td><?= htmlspecialchars(is_null($staff['adminID']) ? 'NO ACCESS' : 'HAVE ACCESS') ?></td>
                    <td>
                        <div class="action-buttons">
                        <form method="GET" style="display:inline;">
                            <input type="hidden" name="viewStaff" value="<?= $staff['staffID'] ?>">
                            <button type="submit" class="detailsBtn">Details</button>
                        </form>
                        <button class="deleteBtn" onclick="deleteStaff(<?= $staff['staffID'] ?>)">Delete</button>
                        </div>
                    </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="7"><center>No available staffs.</center></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <p id="noStaffsMessage" style="display:none; text-align:center; margin-top: 1em; font-style: italic;">No available staffs.</p>
    </div>
    <?php if ($staffDetails): ?>
    <div class="view-staff-window">
        <div class="view-title">
            <h3>Staff Details</h3>
        </div>
        <div class="name-pic-container">
            <div class="staff-deets">
                <h2><?= htmlspecialchars($staffDetails['fname'] . ' ' . $staffDetails['lname']) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($staffDetails['email']) ?></p>
                <p><strong>Birthday:</strong> <?= htmlspecialchars($staffDetails['bday']) ?> (Age: <?= htmlspecialchars($staffDetails['age']) ?>)</p>
                <p><strong>Address:</strong> <?= htmlspecialchars($staffDetails['address']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($staffDetails['status']) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($staffDetails['type']) ?></p>
                <p><strong>Employment Date:</strong> <?= htmlspecialchars($staffDetails['employment_date']) ?></p>
                <p><strong>Admin Access:</strong> <?= is_null($staffDetails['adminID']) ? 'NO ACCESS' : 'HAVE ACCESS' ?></p>
            </div>
            <img src="./img/staff-pictures/<?= htmlspecialchars(isset($staffDetails['staff_pic']) && $staffDetails['staff_pic'] != '' ? $staffDetails['staff_pic'] : 'profile.png') ?>" alt="Staff Picture" id="staff-pic">
        </div>
        <div class="buttons-container">
            <button class="btn-edit" id="updateDetailsBtn" data-staff-id="<?= htmlspecialchars($staffDetails['staffID']); ?>">Update Details</button>
            <button id="btnBack">Back</button>
        </div>
    </div>
    <div class="edit-staff-window" style="display:none;">
        <div class="edit-title">
            <h3>Edit Staff Details</h3>
        </div>
        <form id="editStaffForm" method="POST" action="edit_staff.php">
            <input type="hidden" name="staffID" value="<?= htmlspecialchars($staffDetails['staffID']) ?>" />
            <div class="form-group">
              <label for="fname">First Name:</label>
              <input type="text" name="fname" id="fname" value="<?= htmlspecialchars($staffDetails['fname']) ?>" required />
            </div>
            <div class="form-group">
              <label for="lname">Last Name:</label>
              <input type="text" name="lname" id="lname" value="<?= htmlspecialchars($staffDetails['lname']) ?>" required />
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" name="email" id="email" value="<?= htmlspecialchars($staffDetails['email']) ?>" required />
            </div>
            <div class="form-group">
              <label for="bday">Birthday:</label>
              <input type="date" name="bday" id="bday" value="<?= htmlspecialchars($staffDetails['bday']) ?>" required />
            </div>
            <div class="form-group">
              <label for="age">Age:</label>
              <input type="number" name="age" id="age" required />
            </div>
            <div class="form-group">
              <label for="address">Address:</label>
              <input type="text" name="address" id="address" value="<?= htmlspecialchars($staffDetails['address']) ?>" required />
            </div>
            <div class="form-group">
              <label for="status">Status:</label>
              <input type="text" name="status" id="status" value="<?= htmlspecialchars($staffDetails['status']) ?>" required />
            </div>
            <div class="form-group">
              <label for="type">Type:</label>
              <input type="text" name="type" id="type" value="<?= htmlspecialchars($staffDetails['type']) ?>" required />
            </div>
            <div class="form-group">
              <label for="employment_date">Employment Date:</label>
              <input type="date" name="employment_date" id="employment_date" value="<?= htmlspecialchars($staffDetails['employment_date']) ?>" required />
            </div>
            <div class="form-group">
            <label for="adminID">Admin Access:</label>
            <select name="adminID" id="adminID" required>
              <option value="0" <?= is_null($staffDetails['adminID']) ? 'selected' : '' ?>>No Access</option>
              <option value="2" <?= !is_null($staffDetails['adminID']) ? 'selected' : '' ?>>Have Access</option>
            </select>
            </div>
            <div class="buttons-container">
            <button type="submit" id="btnSaveEdit">Save</button>
            <button type="button" id="btnCancelEdit">Cancel</button>
            </div>
        </form>
        </div>
    <?php endif; ?>
    <div class="add-staff-window" id="addStaffWindow" style="display:none;">
          <div class="add-title">
            <h3>Add New Staff</h3>
          </div>
          <form id="addStaffForm" method="POST" action="add_staff.php">
            <div class="form-group">
              <label for="add_fname">First Name:</label>
              <input type="text" name="fname" id="add_fname" required />
            </div>
            <div class="form-group">
              <label for="add_lname">Last Name:</label>
              <input type="text" name="lname" id="add_lname" required />
            </div>
            <div class="form-group">
              <label for="add_email">Email:</label>
              <input type="email" name="email" id="add_email" required />
            </div>
            <div class="form-group">
              <label for="add_bday">Birthday:</label>
              <input type="date" name="bday" id="add_bday" required />
            </div>
            <div class="form-group">
              <label for="add_age">Age:</label>
              <input type="number" name="age" id="add_age" required />
            </div>
            <div class="form-group">
              <label for="add_address">Address:</label>
              <input type="text" name="address" id="add_address" required />
            </div>
            <div class="form-group">
              <label for="add_status">Status:</label>
              <input type="text" name="status" id="add_status" required />
            </div>
            <div class="form-group">
              <label for="add_type">Type:</label>
              <input type="text" name="type" id="add_type" required />
            </div>
            <div class="form-group">
              <label for="add_employment_date">Employment Date:</label>
              <input type="date" name="employment_date" id="add_employment_date" required />
            </div>
            <div class="form-group">
              <label for="add_adminID">Admin Access:</label>
              <select name="adminID" id="add_adminID" required>
                <option value="0">No Access</option>
                <option value="2">Have Access</option>
              </select>
            </div>
            <div class="buttons-container">
              <button type="submit">Add Staff</button>
              <button type="button" id="btnCancelAdd">Cancel</button>
            </div>
          </form>
        </div>
  </main>
  <script src="sidebar.js"></script>
  <script src="staffs.js"></script>
    <div id="popupMessage" style="
    display:none;
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #323232;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    z-index: 9999;
    font-size: 1em;
    "></div>
</body>
</html>