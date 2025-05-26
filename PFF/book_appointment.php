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

$booked_dates_array = [];
try {
    $sql = "SELECT appointment_date FROM appointments WHERE status = 'Approved'";
    $stmt = $conn->query($sql);
    $approved_appointments = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $booked_dates_array = $approved_appointments;
} catch (\PDOException $e) {
    error_log("Error fetching booked dates for calendar: " . $e->getMessage());
}

$booked_dates_json = json_encode($booked_dates_array);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pablo's Fur Friends - Appointment</title>
    <link rel="stylesheet" href="book_appointment.css" />
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="icon" type="image/x-icon" href="./RESOURCES/images/logo_pff.png" />
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
    <section class="appointment_header">
        <div class="appointment_h1">Appointment</div>
        <div class="appointment_subtext">Schedule an appointment today and let us provide the best care for your furry friends!</div>

        <div class="make_an_appointment">
            <div class="appointment_box">
                <section class="appointment_form_calendar">
                    <div class="form_section">
                        <form id="appointmentForm" >
                            <select class="services_menu" name="service" required>
                                <option value="" disabled selected>Services</option>
                                <option value="Grooming">Grooming</option>
                                <option value="Boarding">Boarding</option>
                            </select>
                            <input type="text" name="name" placeholder="Juan Dela Cruz" required />
                            <input type="tel" name="phone" placeholder="09271234567" required maxlength="11" />
                            <input type="email" name="email" placeholder="juandelacruz@gmail.com" required />

                            <div class="pet_details_group">
                                <input type="text" id="petNameInput" name="petName" placeholder="Name of Pet" required style="flex-grow: 2;" />
                            </div>
                            <label for="appointment_date" id="appDate_label">Appointment Date</label>
                            <input type="date" id="appointment_date" name="date" required />

                            <input type="hidden" id="petIDInput" name="petID" value="">
                            <input type="hidden" name="userID" value="1">

                            <div class="form_buttons">
                                <button type="submit" class="book_btn">Book Now</button>
                                <button type="button" class="cancel_btn">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <div class="calendar_placeholder">
                        <div style="width: 100%;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <button id="prevMonth" style="cursor:pointer;">← Prev</button>
                                <h3 id="monthYear"></h3>
                                <button id="nextMonth" style="cursor:pointer;">Next →</button>
                            </div>
                            <div id="calendar"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
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
        let bookedDates = new Set(<?php echo $booked_dates_json; ?>);
        let selectedDate = null;
        let selectedCell = null;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        const monthYearDisplay = document.getElementById('monthYear');
        const calendarContainer = document.getElementById('calendar');
        const dateInput = document.getElementById("appointment_date");

        function renderCalendar(month, year) {
            calendarContainer.innerHTML = '';
            monthYearDisplay.textContent = new Date(year, month).toLocaleString('default', { month: 'long', year: 'numeric' });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysOfWeek = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

            daysOfWeek.forEach(day => {
                const header = document.createElement('div');
                header.textContent = day;
                header.style.fontWeight = 'bold';
                header.style.textAlign = 'center';
                calendarContainer.appendChild(header);
            });

            for (let i = 0; i < firstDay; i++) {
                const spacer = document.createElement('div');
                spacer.innerHTML = ' ';
                spacer.classList.add('calendar-day', 'empty');
                calendarContainer.appendChild(spacer);
            }

            for (let date = 1; date <= daysInMonth; date++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                const dayCell = document.createElement('div');
                dayCell.textContent = date;
                dayCell.classList.add('calendar-day');
                dayCell.style.border = '0.13889vh solid #ccc';
                dayCell.style.padding = '0.83333vh 0.55556vw';
                dayCell.style.textAlign = 'center';
                dayCell.style.cursor = 'pointer';
                dayCell.style.borderRadius = '0.69444vh';
                dayCell.style.position = 'relative';

                const today = new Date();
                const cellDate = new Date(year, month, date);
                if (cellDate.getDate() === today.getDate() &&
                    cellDate.getMonth() === today.getMonth() &&
                    cellDate.getFullYear() === today.getFullYear()) {
                    dayCell.classList.add('current-day');
                }

                if (bookedDates.has(dateKey) || cellDate < today.setHours(0, 0, 0, 0)) {
                    dayCell.classList.add('booked');
                    dayCell.style.lineHeight = 'normal';
                    dayCell.style.cursor = 'default';
                    dayCell.style.opacity = '0.5'; // Optional: fade past dates
                } else {
                    dayCell.addEventListener('click', () => {
                        if (selectedCell) selectedCell.classList.remove('selected');
                        selectedDate = dateKey;
                        selectedCell = dayCell;
                        dayCell.classList.add('selected');
                        dateInput.value = dateKey;
                    });
                }
                calendarContainer.appendChild(dayCell);
            }

            calendarContainer.style.display = 'grid';
            calendarContainer.style.gridTemplateColumns = 'repeat(7, 1fr)';
            calendarContainer.style.gap = '0.27778vh';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const appointmentForm = document.getElementById('appointmentForm');
            const petNameInput = document.getElementById('petNameInput');
            const petIDInput = document.getElementById('petIDInput');
            const userIDInput = appointmentForm.querySelector('input[name="userID"]');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');

            if (appointmentForm) {
                appointmentForm.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const ownerName = appointmentForm.querySelector('input[name="name"]').value;
                    const phone = appointmentForm.querySelector('input[name="phone"]').value;
                    const email = appointmentForm.querySelector('input[name="email"]').value;
                    const service = appointmentForm.querySelector('select[name="service"]').value;
                    const appointmentDate = dateInput.value;
                    const petName = petNameInput.value;
                    const userID = userIDInput ? userIDInput.value : null;

                    if (!service || !ownerName || !phone || !email || !appointmentDate || !petName || !userID) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    try {
                        const petFormData = new FormData();
                        petFormData.append('petName', petName);
                        petFormData.append('userID', userID);

                        const petResponse = await fetch('submit_pet.php', {
                            method: 'POST',
                            body: petFormData
                        });
                        const petData = await petResponse.json();

                        if (!petData.success) {
                            alert('Error registering pet: ' + petData.message);
                            return;
                        }

                        petIDInput.value = petData.petID;

                        const appointmentFormData = new FormData();
                        appointmentFormData.append('service', service);
                        appointmentFormData.append('name', ownerName);
                        appointmentFormData.append('phone', phone);
                        appointmentFormData.append('email', email);
                        appointmentFormData.append('date', appointmentDate);
                        appointmentFormData.append('petID', petData.petID);
                        appointmentFormData.append('userID', userID);

                        const appResponse = await fetch('submit_appointment.php', {
                            method: 'POST',
                            body: appointmentFormData
                        });
                        const appData = await appResponse.json();

                        if (appData.success) {
                            alert(appData.message);
                            appointmentForm.reset();
                            window.location.href = 'appointments.php';
                        } else {
                            alert('Error scheduling appointment: ' + appData.message);
                        }

                    } catch (error) {
                        console.error('Submission error:', error);
                        alert('An unexpected error occurred during submission.');
                    }
                });
            }

            document.querySelector('.cancel_btn').addEventListener('click', () => {
                if (selectedCell) {
                    selectedCell.classList.remove('selected');
                    selectedDate = null;
                    selectedCell = null;
                    dateInput.value = '';
                }
                appointmentForm.reset();
            });

            dateInput.addEventListener("change", function() {
                const selectedDateFromInput = this.value;
                if (selectedDateFromInput) {
                    const [year, month, day] = selectedDateFromInput.split('-');
                    const dateKey = `${year}-${month}-${day}`;

                    const allCells = calendarContainer.querySelectorAll('.calendar-day');
                    allCells.forEach(cell => {
                        if (parseInt(cell.textContent) === parseInt(day, 10) &&
                            !cell.classList.contains('header') &&
                            !cell.classList.contains('empty') &&
                            !cell.classList.contains('booked')) {

                            const cellDate = new Date(currentYear, currentMonth, parseInt(cell.textContent, 10));
                            const cellDateKey = `${cellDate.getFullYear()}-${String(cellDate.getMonth() + 1).padStart(2, '0')}-${String(cellDate.getDate()).padStart(2, '0')}`;

                            if (cellDateKey === dateKey) {
                                if (selectedCell) selectedCell.classList.remove('selected');
                                selectedDate = dateKey;
                                selectedCell = cell;
                                cell.classList.add('selected');
                            }
                        }
                    });
                } else {
                    if (selectedCell) selectedCell.classList.remove('selected');
                    selectedDate = null;
                    selectedCell = null;
                }
            });

            prevMonthBtn.addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });

            nextMonthBtn.addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });

            renderCalendar(currentMonth, currentYear);
        });
    </script>
</body>
</html>