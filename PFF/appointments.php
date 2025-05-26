<?php
require_once 'connection.php';
session_start();
if(!ISSET($_SESSION['user'])) {
    header('location: index.php');
}

$id = $_SESSION['user'];
$sql = $conn->prepare("SELECT * FROM users WHERE userID = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();
$fetch = $sql->fetch();

$sql = "SELECT appID, name, phone, appointment_date, service, status FROM appointments ORDER BY appointment_date";
$appointments = [];

try {
    $stmt = $conn->query($sql);
    $appointments = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Error fetching appointments: " . $e->getMessage());
    echo "<p style='color: red; text-align: center;'>Error loading appointments. Please try again later.</p>";
}

$booked_dates_array = [];
foreach ($appointments as $app) {
    if (isset($app['status']) && $app['status'] === 'Approved') {
        $booked_dates_array[] = $app['appointment_date'];
    }
}
$booked_dates_json = json_encode($booked_dates_array);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Appointment Window</title>
    <link rel="stylesheet" href="appointments.css" />
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
        <link rel="icon" type="image/x-icon" href="./RESOURCES/images/logo_pff.png">
</head>
<body>
    <div id="loading-screen">
        <img src="./RESOURCES/images/logo_pff.png" alt="Loading" id="loading-image">
    </div>
    <div class="dim_overlay" id="dim_overlay"></div>
    <div class="sidebar" id="sidebar" hidden>
        <div class="sidebar_title">
            <span>
                Hello,
                <?php echo htmlspecialchars($fetch['fname']); ?>
            </span>
        </div>
        <div class="sidebar_menu">
            <ul class="sidebar_menu1">
                <li><a href="index.php"><img src="./RESOURCES/images/icon_home.png" alt="home"><span>HOME</span></a></li>
                <li><a href="services.php"><img src="./RESOURCES/images/icon_scissors.png" alt="services"><span>SERVICES</span></a></li>
                <li><a href="products.php"><img src="./RESOURCES/images/icon_bone.png" alt="products"><span>PRODUCTS</span></a></li>
                <li><a href="aboutus.php"><img src="./RESOURCES/images/icon_paw.png" alt="about us"><span>ABOUT US</span></a></li>
            </ul>
            <div class="sidebar_divider"></div>
            <ul class="sidebar_menu2">
                    <?php if(isset($_SESSION['user'])): ?>
                    <li class="dropdown_toggle">
                        <div class="dropdown_header">
                            <img src="./RESOURCES/images/icon_calendar1.png" id="dropdown_header_icon">
                            <span id="dropdown_title">APPOINTMENTS</span>
                            <img src="./RESOURCES/images/icon_arrowdown.png" class="drop_arrow" id="arrow_down">
                            <img src="./RESOURCES/images/icon_arrowup.png" class="drop_arrow" id="arrow_up" style="display: none;">
                        </div>
                        <div class="appointments_sidebar" id="appointments_sidebar">
                            <a href="book_appointment.php"><span>Book Appointment</span></a>
                            <a href="appointments.php"><span>Check Appointments</span></a>
                        </div>
                    </li>
                    <?php endif; ?>
                <li><a href="logout.php"><img src="./RESOURCES/images/icon_acc.png"><span>LOGOUT</span></a></li>
            </ul>
        </div>
    </div>
    <header class="navbar">
        <img src="./RESOURCES/images/phone_menu.png" alt="menu" class="menu_phone" id="menu_phone">
        <a href="./home.php"><div class="navbar_whole_logo">
            <img src="./RESOURCES/images/alt_logo_pff.png" height="90px" alt="logoproj" class="navbar_logo">
            <div class="navbar_title">
                <span class="navbar_bizname"><span class="pablo">PABLO'S</span> FUR FRIENDS</span><br>
                <span class="navbar_info">Boarding, Grooming, and Pet Supplies</span>
            </div>
        </div></a>
        <div class="menu_etc">
            <div class="menu">
                <ul>
                    <li><a href="./home.php">HOME</b></a></li>
                    <li><a href="./services.php">SERVICES</a></li>
                    <li><a href="./products.php">PRODUCTS</a></li>
                    <li><a href="./aboutus.php">ABOUT US</a></li>
                </ul>
            </div>
            <div class="account_btn">
                <?php if(isset($_SESSION['user'])): ?>
                    <div class="signup">
                        <span><?php echo htmlspecialchars($fetch['fname']); ?></span>
                    </div>
                    <div class="su_dropdown" id="dropdown">
                        <a href="./logout.php">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="./auth2.php">
                        <div class="signup">
                            <span>Sign Up</span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            <?php if(isset($_SESSION['user'])): ?>
            <div class="appointment_btn">
                <a href="appointments.php"><img src="./RESOURCES/images/button_calendar.png" height="50" alt="appointment_btn" class="navbar_app_btn"></a>
            </div>
            <?php endif; ?>
        </div>
    </header>
    <div class="container">
        <h2 class="section-title">Appointments</h2> <div class="calendar-section">
            <div class="calendar-header">
                <button onclick="changeMonth(-1)">← Prev</button>
                <h3 id="monthYear"></h3>
                <button onclick="changeMonth(1)">Next →</button>
            </div>
            <div id="calendar"></div>
        </div>

        <div class="approval-sections-wrapper">
            <div id="waiting-approval">
                <h3 class="status-header waiting">Waiting for Approval</h3>
                <?php
                $hasPending = false;
                foreach ($appointments as $index => $app): ?>
                    <?php if (isset($app['status']) && $app['status'] === 'Pending'):
                        $hasPending = true; ?>
                        <div class="card">
                            <div class="label pending">Pending</div>
                            <div class="card-content">
                                <p><strong>Name:</strong> <?= htmlspecialchars($app['name']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($app['phone']) ?></p>
                                <p><strong>Date:</strong> <?= htmlspecialchars($app['appointment_date']) ?></p>
                                <p><strong>Service:</strong> <?= htmlspecialchars($app['service']) ?></p>
                            </div>
                            <div class="card-actions-user">
                                <button class="cancel-btn" data-id="<?= htmlspecialchars($app['appID']) ?>">Cancel Appointment</button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$hasPending): ?>
                    <p>No pending appointments.</p>
                <?php endif; ?>
            </div>

            <div id="approved">
                <h3 class="status-header approved">Approved</h3>
                <?php
                $hasApproved = false;
                foreach ($appointments as $index => $app): ?>
                    <?php if (isset($app['status']) && $app['status'] === 'Approved'):
                        $hasApproved = true; ?>
                        <div class="card">
                            <div class="label approved">Approved</div>
                            <p><strong>Name:</strong> <?= htmlspecialchars($app['name']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($app['phone']) ?></p>
                            <p><strong>Date:</strong> <?= htmlspecialchars($app['appointment_date']) ?></p>
                            <p><strong>Service:</strong> <?= htmlspecialchars($app['service']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$hasApproved): ?>
                    <p>No approved appointments.</p>
                <?php endif; ?>
            </div>

            <a href="book_appointment.php" class="book_another_appointment_btn_link">
                <button class="book_another_appointment_btn">Book Another Appointment</button>
            </a>
            
            <button class="review_btn" onclick="showReviewBox()">Leave a Review</button>

            <div id="reviewSection" style="display: none; margin-top: 10px;">
                <input type="text" id="fname" placeholder="First Name" required><br>
                <input type="text" id="lname" placeholder="Last Name" required><br>
                <textarea id="feedback" placeholder="Your feedback..." rows="4" cols="50" required></textarea><br>
                <button onclick="submitReview()">Submit Review</button>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-texture">
            <div class="footer">
                <div class="left">
                    <h1>Pablo's Fur Friends</h1>
                    <p>Your One Stop Pet Shop.</p>
                </div>
                <div class="right">
                    <h3><strong>Contact</strong></h3>
                    <p>Address: 5073 Filmore St, Makati, 1235 Metro Manila</p>
                    <p>Phone: +63 967 300 3285</p>
                    <p>Email: <a href="mailto:pablosfurfriends@gmail.com">pablosfurfriends@gmail.com</a></p>
                    <div class="icons">
                        <a href="https://facebook.com" target="_blank"><img src="./RESOURCES/images/icon_fb.png" alt="Facebook" /></a>
                        <a href="https://instagram.com" target="_blank"><img src="./RESOURCES/images/icon_ig.png" alt="Instagram" /></a>
                        <a href="https://maps.app.goo.gl/7nULZKH7hjSSoawY8" target="_blank"><img src="./RESOURCES/images/icon_maps.jfif" alt="Maps" /></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="sidebar_loading.js"></script>
    <script>
        function showReviewBox() {
            const reviewSection = document.getElementById('reviewSection');
            if (reviewSection.style.display === 'none' || reviewSection.style.display === '') {
                reviewSection.style.display = 'block';
            } else {
                reviewSection.style.display = 'none';
            }
        }

        function submitReview() {
        const fname = document.getElementById('fname').value.trim();
        const lname = document.getElementById('lname').value.trim();
        const feedback = document.getElementById('feedback').value.trim();

        if (!fname || !lname || !feedback) {
            alert("Please fill in all fields.");
            return;
        }

        fetch('submit_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `fname=${encodeURIComponent(fname)}&lname=${encodeURIComponent(lname)}&feedback=${encodeURIComponent(feedback)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById('fname').value = '';
                document.getElementById('lname').value = '';
                document.getElementById('feedback').value = '';
                document.getElementById('reviewSection').style.display = 'none';
            } else {
                alert("Failed to submit review.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred during submission.");
        });
    }

        document.addEventListener('DOMContentLoaded', () => {
            const calendarContainer = document.getElementById("calendar");
            const monthYearDisplay = document.getElementById("monthYear");

            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();

            const bookedDates = new Set(<?php echo $booked_dates_json; ?>);

            function renderCalendar(month, year) {
                calendarContainer.innerHTML = "";
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                daysOfWeek.forEach(day => {
                    const header = document.createElement("div");
                    header.className = "calendar-day header";
                    header.textContent = day;
                    calendarContainer.appendChild(header);
                });

                for (let i = 0; i < firstDay; i++) {
                    const spacer = document.createElement("div");
                    spacer.className = "calendar-day empty";
                    spacer.textContent = "";
                    calendarContainer.appendChild(spacer);
                }

                for (let date = 1; date <= daysInMonth; date++) {
                    const cell = document.createElement("div");
                    const formattedMonth = (month + 1).toString().padStart(2, '0');
                    const formattedDate = date.toString().padStart(2, '0');
                    const dateKey = `${year}-${formattedMonth}-${formattedDate}`;

                    cell.className = "calendar-day";
                    cell.textContent = date;

                    if (bookedDates.has(dateKey)) {
                        cell.classList.add("booked");
                    }

                    calendarContainer.appendChild(cell);
                }

                monthYearDisplay.textContent = new Date(year, month).toLocaleString("default", {
                    month: "long",
                    year: "numeric"
                });
            }

            window.changeMonth = (direction) => {
                currentMonth += direction;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                } else if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            };

            renderCalendar(currentMonth, currentYear);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cancelButtons = document.querySelectorAll('.cancel-btn');

            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const appointmentId = this.dataset.id;

                    if (confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')) {
                        fetch('cancel_appointment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'appointment_id=' + encodeURIComponent(appointmentId)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                const cancelledCard = this.closest('.card');
                                if (cancelledCard) {
                                    cancelledCard.remove();
                                }
                                window.location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while trying to cancel the appointment.');
                        });
                    }
                });
            });
        });
        document.getElementById("reviewForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch("submit_review.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    this.reset();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Something went wrong. Please try again.");
            });
        });
    </script>
</body>
</html>