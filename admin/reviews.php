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
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reviews</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <link href="reviews.css" rel="stylesheet"/>
  <link rel="stylesheet" href="admin_sidebar.css"/>
</head>
<body>
  <nav>
    <div class="ToggleLogoTitle">
      <button id="sidebarToggle" class="openbtn" aria-label="Toggle Sidebar">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
          <rect y="4" width="24" height="2" rx="1" fill="#fff" />
          <rect y="11" width="24" height="2" rx="1" fill="#fff" />
          <rect y="18" width="24" height="2" rx="1" fill="#fff" />
        </svg>
      </button>
      <img src="img/logo_pff.png" class="logo-img" alt="Admin Logo" />
      <h2 class="Title">Admin Panel</h2>
    </div>
    <div class="admin_acc">
      <h3 class="Admin">
        <?php echo htmlspecialchars($fetch['username']) ?>
      </h3>
      <img src="img/profile.png" class="profile-icon" alt="Profile" />
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
    <section class="reviews-header">
      <h2>Reviews</h2>
    </section>

    <section class="reviews-table">
      <table>
        <thead>
          <tr>
            <th data-sort="number">ID <span class="sort-arrow"></span></th>
            <th data-sort="string">Client Name <span class="sort-arrow"></span></th>
            <th data-sort="string">Feedback <span class="sort-arrow"></span></th>
            <th data-sort="date">Date <span class="sort-arrow"></span></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = $conn->query("SELECT reviewID, fname, lname, feedback, date FROM reviews");
          if ($query && $query->rowCount() > 0):
            while ($review = $query->fetch(PDO::FETCH_ASSOC)):
          ?>
            <tr data-review-id="<?= htmlspecialchars($review['reviewID']) ?>">
              <td><?= htmlspecialchars($review['reviewID']) ?></td>
              <td><?= htmlspecialchars($review['fname'] . ' ' . $review['lname']) ?></td>
              <td><?= htmlspecialchars($review['feedback']) ?></td>
              <td><?= htmlspecialchars($review['date']) ?></td>
              <td>
                <button class="btn delete" onclick="deleteReview(<?= $review['reviewID'] ?>)">Delete</button>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5">No reviews found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <p id="noReviewsMessage" style="display:none; text-align:center; margin-top: 1em; font-style: italic;">No reviews found.</p>
    </section>
  </main>
  <script src="sidebar.js"></script>
  <script>
    //for delete button
    function deleteReview(reviewID) {
        if (!confirm("Are you sure you want to delete this review?")) {
          return;
        }

        fetch(`delete_review.php?id=${reviewID}`, { method: 'GET' })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const tbody = document.querySelector('.reviews-table tbody');
              const noReviewsMessage = document.getElementById('noReviewsMessage');
              const row = document.querySelector(`tr[data-review-id="${reviewID}"]`);
              if (row) {
                row.remove();
              }
              if (tbody.querySelectorAll('tr').length === 0) {
                noReviewsMessage.style.display = 'block';
              } else {
                noReviewsMessage.style.display = 'none';
              }
              alert('Review deleted successfully.');
            } else {
              alert('Error: ' + (data.error || 'Failed to delete review.'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete review.');
          });
    }

    //sorting dun sa row titles
    const table = document.querySelector('.reviews-table table');
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
</body>
</html>