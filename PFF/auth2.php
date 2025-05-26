<!DOCTYPE html>
<?php
require_once 'connection.php';
session_start();

if(isset($_SESSION['user'])) {
    header('location: home.php');
}
?>
<html>
    <head>
        <title>Authentication</title>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="auth2.css">
        <link rel="icon" type="image/x-icon" href="./RESOURCES/images/logo_pff.png">
    </head>
    <body>
        <div id="loading-screen">
			<img src="./RESOURCES/images/logo_pff.png" alt="Loading" id="loading-image">
		</div>
        <div class="dim_overlay" id="dim_overlay"></div>
        <div class="sidebar" id="sidebar" hidden>
            <div class="sidebar_title">
                <span>Hello, FurFriend!</span>
            </div>
            <div class="sidebar_menu">
                <ul class="sidebar_menu1">
                    <li><a href="index.php"><img src="./RESOURCES/images/icon_home.png" alt="home"><span>HOME</span></a></li>
                    <li><a href="services.html"><img src="./RESOURCES/images/icon_scissors.png" alt="services"><span>SERVICES</span></a></li>
                    <li><a href="products.html"><img src="./RESOURCES/images/icon_bone.png" alt="products"><span>PRODUCTS</span></a></li>
                    <li><a href="about_us.html"><img src="./RESOURCES/images/icon_paw.png" alt="about us"><span>ABOUT US</span></a></li>
                </ul>
                <div class="sidebar_divider"></div>
                <ul class="sidebar_menu2">
                    <li class="dropdown_toggle">
                        <div class="dropdown_header">
                            <img src="./RESOURCES/images/icon_calendar1.png" id="dropdown_header_icon">
                            <span id="dropdown_title">APPOINTMENTS</span>
                            <img src="./RESOURCES/images/icon_arrowdown.png" class="drop_arrow" id="arrow_down">
                            <img src="./RESOURCES/images/icon_arrowup.png" class="drop_arrow" id="arrow_up" style="display: none;">
                        </div>
                        <div class="appointments_sidebar" id="appointments_sidebar">
                            <a href="appointments.html"><span>Book Appointment</span></a>
                            <a href="appointments.html"><span>Check Appointments</span></a>
                        </div>
                    </li>
                    <li><a href="auth.php"><img src="./RESOURCES/images/icon_acc.png"><span>ACCOUNT</span></a></li>
                </ul>
            </div>
        </div>
        <header class="navbar">
            <img src="./RESOURCES/images/phone_menu.png" alt="menu" class="menu_phone" id="menu_phone">
            <a href="./index.php"><div class="navbar_whole_logo">
                <img src="./RESOURCES/images/alt_logo_pff.png" height="90px" alt="logoproj" class="navbar_logo">
                <div class="navbar_title">
				    <span class="navbar_bizname"><span class="pablo">PABLO'S</span> FUR FRIENDS</span><br>
				    <span class="navbar_info">Boarding, Grooming, and Pet Supplies</span>
			    </div>
            </div></a>
            <div class="menu_etc">
                <div class="menu">
                    <ul>
                        <li><a href="./index.php">HOME</a></li>
                        <li><a href="#">SERVICES</a></li>
                        <li><a href="#">PRODUCTS</a></li>
                        <li><a href="#">ABOUT US</a></li>
                    </ul>
                </div>
                <a href="./auth1.php">
                    <div class="signup">
                        <span>Sign Up</span>
                    </div>
                </a>
                <?php if(isset($_SESSION['user'])): ?>
                <div class="appointment_btn">
                    <a href="#"><img src="./RESOURCES/images/button_calendar.png" height="50" alt="appointment_btn" class="navbar_app_btn"></a>
                </div>
                <?php endif; ?>
            </div>
        </header>
        <section class="section1">
            <div class="paw_container">
                <img src="./RESOURCES/images/icon_paw.png" alt="auth icon" id="paw_auth">
            </div>
            <div class="branding">
                <span id="title_brand"><span id="pablos">PABLO'S</span><br> FUR FRIENDS</span>
                <span id="tagline">Your One Stop Pet Shop.</span>
            </div>
            <div class="main_auth">
                <span id="auth_title2">Create an account</span>
                <span id="auth_title">Sign up</span>
                <div class="auth_box">
                    <form action="register_query.php" method="post" class="auth_form">
                        <input type="text" id="fname" name="fname" placeholder="First Name" required>
                        <input type="text" id="lname" name="lname" placeholder="Last Name" required>
                        <input type="text" id="email_user" name="email" placeholder="Email" required>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <button type="submit" id="submit_signin" name="register">Sign up</button>
                    </form>
                    <div class="alt_container" style="display: none;">
                        <div id="line"></div>
                        <span id="line_text">or sign up with</span>
                    </div>
                    <div class="alt_signin_container" style="display: none;">
                        <a href="#"><img src="./RESOURCES/images/icon_facebook.png" alt="fb signin" class="fb_signin"></a>
                        <a href="#"><img src="./RESOURCES/images/icon_google.png" alt="google_signin" class="google_signin"></a>
                        <a href="#"><img src="./RESOURCES/images/icon_apple.png" alt="apple_signin" class="apple_signin"></a>
                    </div>
                    <div class="have_acc">
                        <span>Already have an account? </span><a href="auth1.php"><span id="have_acc_bold">Sign in</span></a>
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
        <script src="sidebar_loading.js"></script>
    </body>
</html>