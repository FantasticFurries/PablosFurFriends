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
  header('location: ../dashboard.php');


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mailing List</title>
    <link rel="stylesheet" href="mailingList.css"/>
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
      <li><a href="appointments.php">Appointments</a></li>
      <li><a href="reviews.php">Reviews</a></li>
      <li><a href="mailingList.php">Mailing List</a></li>
      <?php if($adminID == 1): ?>
      <li><a href="staffs.php">Management</a></li>
      <?php endif; ?>
      <li><a href="reports.php">Reports</a></li>
    </ul>
  </div>

    <!-- MAIN CONTENT -->

  <main class="main-content">
    <section class="mailing-list-header">
      <h2>Mailing List</h2>
    </section>

    <section class="mailing-list-table">
      <table>
        <thead>
          <tr>
            <th data-sort="number">ID <span class="sort-arrow"></span></th>
            <th data-sort="string">Email <span class="sort-arrow"></span></th>
            <th data-sort="date">Subscribed At <span class="sort-arrow"></span></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = $conn->query("SELECT id, email, subscribed_at FROM mail_subscribers ORDER BY subscribed_at DESC");
          if ($query && $query->rowCount() > 0):
            while ($subscriber = $query->fetch(PDO::FETCH_ASSOC)):
          ?>
            <tr data-subscriber-id="<?= htmlspecialchars($subscriber['id']) ?>">
              <td><?= htmlspecialchars($subscriber['id']) ?></td>
              <td><?= htmlspecialchars($subscriber['email']) ?></td>
              <td><?= htmlspecialchars($subscriber['subscribed_at']) ?></td>
              <td>
                <button class="btn delete" onclick="deleteSubscriber(<?= $subscriber['id'] ?>)">Delete</button>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="4">No subscribers found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <p id="noSubscribersMessage" style="display:none; text-align:center; margin-top: 1em; font-style: italic;">No subscribers found.</p>
    </section>
  </main>

  <script>
    function deleteSubscriber(id) {
        if (!confirm("Are you sure you want to delete this subscriber?")) {
          return;
        }

        fetch(`delete_subscriber.php?id=${id}`, { method: 'GET' })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const tbody = document.querySelector('.mailing-list-table tbody');
              const noSubscribersMessage = document.getElementById('noSubscribersMessage');
              const row = document.querySelector(`tr[data-subscriber-id="${id}"]`);
              if (row) {
                row.remove();
              }
              if (tbody.querySelectorAll('tr').length === 0) {
                noSubscribersMessage.style.display = 'block';
              } else {
                noSubscribersMessage.style.display = 'none';
              }
              alert('Subscriber deleted successfully.');
            } else {
              alert('Error: ' + (data.error || 'Failed to delete subscriber.'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete subscriber.');
          });
    }

    // Sorting functionality
    const table = document.querySelector('.mailing-list-table table');
    const headers = table.querySelectorAll('th[data-sort]');
    let sortDirection = {};

    headers.forEach((header, index) => {
      sortDirection[index] = 'asc';

      header.style.cursor = 'pointer';
      header.title = 'Click to sort';

      header.addEventListener('click', () => {
        const type = header.getAttribute('data-sort');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        headers.forEach(h => h.querySelector('.sort-arrow').textContent = '');

        rows.sort((a, b) => {
          let aText = a.children[index].textContent.trim();
          let bText = b.children[index].textContent.trim();

          if (type === 'number') {
            aText = Number(aText);
            bText = Number(bText);
          } else if (type === 'date') {
            aText = new Date(aText);
            bText = new Date(bText);
          } else {
            aText = aText.toLowerCase();
            bText = bText.toLowerCase();
          }

          if (aText < bText) return sortDirection[index] === 'asc' ? -1 : 1;
          if (aText > bText) return sortDirection[index] === 'asc' ? 1 : -1;
          return 0;
        });

        while (tbody.firstChild) {
          tbody.removeChild(tbody.firstChild);
        }

        rows.forEach(row => tbody.appendChild(row));

        header.querySelector('.sort-arrow').textContent = sortDirection[index] === 'asc' ? '▲' : '▼';

        sortDirection[index] = sortDirection[index] === 'asc' ? 'desc' : 'asc';
      });
    });
  </script>
  
    <!-- MAIN CONTENT -->

 <script src="../sidebar.js"></script>

</body>
</html>