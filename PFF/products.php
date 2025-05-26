<?php
require_once 'connection.php';
session_start();

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];
    $sql = $conn->prepare("SELECT * FROM users WHERE userID = :id");
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->execute();
    $fetch = $sql->fetch();
} else {
    $fetch = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="icon" type="image/x-icon" href="./RESOURCES/images/logo_pff.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                <?php if(isset($_SESSION['user'])): ?>
                    <?php echo htmlspecialchars($fetch['fname']); ?>
                <?php else: ?>
                    FurFriend!
                <?php endif; ?>
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
                <li>
                    <?php if(isset($_SESSION['user'])): ?>
                        <a href="logout.php"><img src="./RESOURCES/images/icon_acc.png"><span>LOGOUT</span></a>
                    <?php else: ?>
                        <a href="auth1.php"><img src="./RESOURCES/images/icon_acc.png"><span>LOGIN</span></a>
                    <?php endif; ?>
                </li>
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
                    <li><a href="./aboutus.php"><b style="color: #f88908;">ABOUT US</b></a></li>
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

    <section class="section1">
        <div class="s1_container">
            <div class="services_Title">
                <h1>Products</h1>
                <h3>High-quality pet essentials for a happy and healthy pet.</h2>
            </div>
        </div>
    </section>
    
 
    <section class="section2">
    <div class="s2_container">
        <div class="product-filter">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search...">
                <button id="searchButton"><i class="fas fa-magnifying-glass"></i></button>
            </div>

            <div class="filter-buttons" id="filterButtons">
                <!-- Buttons will be dynamically inserted here -->
            </div>
        </div>
        <div class="s3_container"></div>
    </div>
    </section>



    <section class="section3">
    <div class="s3_container">
        <div class="mailing_list">
            <div class="ml_title">
                <h3>MAILING LIST</h3>
                <h4>Be the first to find out our Products, Promos, Events.</h4>
            </div>
            <div class="ml_input">
                <form class="subscribe-form" action="subscribe.php" method="POST">
                    <input type="email" name="email" placeholder="Enter your email address" required>
                    <button type="submit" name="subscribe">SUBSCRIBE</button>
                </form>
                
        <?php
        //Display success/error messages
        if (isset($_SESSION['message'])) {
            echo '<div class="message '.$_SESSION['message_type'].'">'.$_SESSION['message'].'</div>';
            // Clear the message after displaying it
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

     <script>
setTimeout(() => {
    const msg = document.querySelector('.message');
    if (msg) {
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 300);
    }
}, 3000);
</script>

            </div>
            <div class="ml_figures">
                <div class="ml_figs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="auto" viewBox="0 0 24 24"><path fill="#f4f4f4" d="M12 22q-3.475-.875-5.738-3.988T4 11.1V5l8-3l8 3v6.1q0 3.8-2.262 6.913T12 22"/></svg>
                    <h3>100% <br> Genuine</h3>
                </div>
                <div class="ml_figs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="auto" viewBox="0 0 24 24"><path fill="#f4f4f4" d="M15.056 9.004q.692-2.14.693-3.754c0-2.398-.939-4.247-2.5-4.247c-.847 0-1.109.504-1.437 1.747c.018-.065-.163.634-.215.821q-.152.539-.527 1.831a.3.3 0 0 1-.03.065L8.174 9.953a5.9 5.9 0 0 1-2.855 2.326l-1.257.482a1.75 1.75 0 0 0-1.092 1.967l.686 3.539a2.25 2.25 0 0 0 1.673 1.757l8.25 2.022a4.75 4.75 0 0 0 5.733-3.44l1.574-6.173a2.75 2.75 0 0 0-2.665-3.43z"/></svg>
                    <h3>Satisfaction <br> Guaranteed</h3>

                </div>
                <div class="ml_figs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="auto" viewBox="0 0 24 24"><path fill="#f4f4f4" d="M18 4c-1.71 0-2.75.33-3.35.61C13.88 4.23 13 4 12 4s-1.88.23-2.65.61C8.75 4.33 7.71 4 6 4c-3 0-5 8-5 10c0 .83 1.32 1.59 3.14 1.9c.64 2.24 3.66 3.95 7.36 4.1v-4.28c-.59-.37-1.5-1.04-1.5-1.72c0-1 2-1 2-1s2 0 2 1c0 .68-.91 1.35-1.5 1.72V20c3.7-.15 6.72-1.86 7.36-4.1C21.68 15.59 23 14.83 23 14c0-2-2-10-5-10M4.15 13.87c-.5-.12-.89-.26-1.15-.37c.25-2.77 2.2-7.1 3.05-7.5c.54 0 .95.06 1.32.11c-2.1 2.31-2.93 5.93-3.22 7.76M9 12a1 1 0 0 1-1-1c0-.54.45-1 1-1a1 1 0 0 1 1 1c0 .56-.45 1-1 1m6 0a1 1 0 0 1-1-1c0-.54.45-1 1-1a1 1 0 0 1 1 1c0 .56-.45 1-1 1m4.85 1.87c-.29-1.83-1.12-5.45-3.22-7.76c.37-.05.78-.11 1.32-.11c.85.4 2.8 4.73 3.05 7.5c-.25.11-.64.25-1.15.37"/></svg>
                    <h3>Quality<br> Service</h3>

                </div>
            </div>
        </div>
    </div>
    </section>

    <div class="footer-texture">
        <footer class="footer">
            <div class="left">
                <h1>Pablo's Fur Friends</h1>
                <p>Your One Stop Pet Shop.</p>
            </div>
            <div class="divider2"></div>
            <div class="divider3"></div>
            <div class="right">
                <h3><strong>Contact</strong></h3>
                <p>Address: 5073 Filmore St, Makati, 1235 Metro Manila</p>
                <p>Phone: +63 967 300 3285</p>
                <p>Email: <a href="mailto:pablosfurfriends@gmail.com">pablosfurfriends@gmail.com</a></p>
                <div class="icons">
                    <a href="https://www.facebook.com/p/Pablos-Fur-Friends-Pet-Supplies-Grooming-Salon-Boarding-100086581667331/" target="_blank" title="Pablo's Fur Friends Facebook Page!"><img src="./RESOURCES/images/icon_fb.png" alt="Facebook"></a>
                    <a href="https://www.instagram.com/PablosFurFriends/" target="_blank" title="Pablo's Fur Friends Instagram Page!"><img src="./RESOURCES/images/icon_ig.png" alt="Instagram"></a>
                    <a href="https://maps.app.goo.gl/7nULZKH7hjSSoawY8" target="_blank" title="Pablo's Fur Friends Google Map Location!"><img src="./RESOURCES/images/icon_maps.jfif" alt="Google Maps"></a>
                </div>
            </div>
        </footer>
    </div>
 </body> 
<script>
// Categories data
const categories = ["All", "Pet Foods", "Pet Treats", "Grooming Supplies", "Medicine & Supplements", "Others"];
let visibleButtons = 4; // Default for desktop
let startIndex = 0;
let activeCategory = "All"; // Track active category

// Function to update visible buttons based on screen size
function updateVisibleButtons() {
    if (window.innerWidth < 768) { // Mobile
        visibleButtons = 2;
    } else if (window.innerWidth < 1024) { // Tablet
        visibleButtons = 3;
    } else { // Desktop
        visibleButtons = 4;
    }
    renderButtons();
}

// Function to render filter buttons
function renderButtons() {
    const container = document.getElementById('filterButtons');
    container.innerHTML = '';

    // Add previous button if not at start
    if (startIndex > 0) {
        const prevButton = document.createElement('button');
        prevButton.className = 'filter-nav';
        prevButton.innerHTML = '&lt;';
        prevButton.onclick = () => {
            startIndex = Math.max(0, startIndex - 1);
            renderButtons();
        };
        container.appendChild(prevButton);
    }

    // Add category buttons
    const endIndex = Math.min(startIndex + visibleButtons, categories.length);
    for (let i = startIndex; i < endIndex; i++) {
        const button = document.createElement('button');
        button.className = 'filter-btn' + (categories[i] === activeCategory ? ' active' : '');
        button.textContent = categories[i];
        button.onclick = () => {
            activeCategory = categories[i];
            loadProducts(activeCategory, document.getElementById('searchInput').value);
            renderButtons(); // Re-render to update active state
        };
        container.appendChild(button);
    }

    // Add next button if more categories available
    if (endIndex < categories.length) {
        const nextButton = document.createElement('button');
        nextButton.className = 'filter-nav';
        nextButton.innerHTML = '&gt;';
        nextButton.onclick = () => {
            startIndex = Math.min(categories.length - visibleButtons, startIndex + 1);
            renderButtons();
        };
        container.appendChild(nextButton);
    }
}

//Function to toggle product description
function toggleDescription(button) {
    const card = button.closest('.product-card');
    const description = card.querySelector('.product-description');
    
    if (description.style.display === 'none' || !description.style.display) {
        description.style.display = 'block';
        button.textContent = 'Hide Details';
    } else {
        description.style.display = 'none';
        button.textContent = 'View Details';
    }
}





//---------------------------------v2 loadproducts-----------------------------------
function loadProducts(category, searchTerm = '', page = 1) {
    const container = document.querySelector('.s3_container');
    container.innerHTML = '<div class="loading">Loading products...</div>';
    
    const xhr = new XMLHttpRequest();
    // xhr.open('GET', `get_products.php?category=${encodeURIComponent(category)}&search=${encodeURIComponent(searchTerm)}&page=${page}`, true);
    //---------added
    const encodedCategory = encodeURIComponent(category);
    xhr.open('GET', `get_products.php?category=${encodedCategory}&search=${encodeURIComponent(searchTerm)}&page=${page}`, true);
    //--------
    xhr.onload = function() {
        if (this.status === 200) {
            container.innerHTML = this.responseText;
            
            // Add click handlers for pagination links
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.dataset.page;
                    loadProducts(activeCategory, document.getElementById('searchInput').value, page);
                });
            });
        } else {
            container.innerHTML = '<div class="error">Error loading products. Please try again.</div>';
        }
    };
    
    xhr.onerror = function() {
        container.innerHTML = '<div class="error">Connection error. Please check your network.</div>';
    };
    
    xhr.send();
}


//---------------------------------v2 loadproducts-----------------------------------



//------------------------DOM V2-------------------------
document.addEventListener('DOMContentLoaded', function() {
    updateVisibleButtons();
    loadProducts(activeCategory);
    
    // Search button event
    document.getElementById('searchButton').addEventListener('click', function() {
        const searchTerm = document.getElementById('searchInput').value;
        loadProducts(activeCategory, searchTerm, 1); // Reset to page 1 on new search
    });
    
    // Search input enter key event
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value;
            loadProducts(activeCategory, searchTerm, 1); // Reset to page 1 on new search
        }
    });
    
    window.addEventListener('resize', updateVisibleButtons);
});

//------------------------DOM V2---------------------------------
</script>
<script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
<script src="sidebar_loading.js"></script>

</body>
</html>