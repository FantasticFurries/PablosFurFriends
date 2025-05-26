<!DOCTYPE html>
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
<html>
<head>
    <title>About Us</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="aboutus.css">
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
                    <li><a href="./services.php"><b style="color: #f88908;">SERVICES</b></a></li>
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
    <div class="about_us_background">
        <section class="about_us">
            <p class="about_us_title"> About Us</p>
            <p class="about_us_subtext"> Dedicated to keeping your furry friends happy, healthy, and loved! </p>
            <img class="header_image" src=".\RESOURCES\images\461256342_1060828145502185_1238550519440078270_n 1.png">
        </section>
        <section class="our_story">
            <div class="our_story_bg">
                <p class="our_story_title">Our Story</p>
            </div>
            <section class="our_story_content">
                <img class="our_story_img" src="./RESOURCES/images/459941845_1377520959870821_2577349788883359782_n 1.png" alt="A dog and cat">
                <div class="our_story_text_container"> <p class="our_story_text">At Pablo's Fur Friends, we believe pets are family. <br><br>Founded in December 2022, our shop was inspired by Pablo, a beloved pet dog who brought joy and unconditional love. In his memory, we are committed to providing exceptional care and services for your furry companions. <br><br>From quality pet food and supplies to grooming and boarding, we ensure your pets stay happy, healthy, and loved. <br><br>Located in Palanan, Makati, we're here to support every pet owner in giving their pets the best life possible!</p>
                </div>
            </section>
        </section>
        <section class="why-choose-us">
            <p class="why-choose-us_title">Why Choose Us?</p>
            <div class="grid-container">
                <div class="choose-box">
                    <p class="box-title">Trained and friendly staff</p>
                    <p class="box-desc">Our team is passionate, experienced, and dedicated to your pet's care.</p>
                </div>
                <div class="choose-box">
                    <p class="box-title">Quality pet supplies</p>
                    <p class="box-desc">Only the best products for your furry friend's health and happiness.</p>
                </div>
                <div class="choose-box">
                    <p class="box-title">Professional Grooming</p>
                    <p class="box-desc">We keep your pet looking and feeling great with expert grooming services.</p>
                </div>
                <div class="choose-box">
                    <p class="box-title">Safe boarding facilities</p>
                    <p class="box-desc">Comfortable, secure, and clean spaces for your pet to stay while you're away.</p>
                </div>
            </div>
        </section>
        <section class="find-us">
            <p class="find_us_title">Where to find us?</p>
            <div class="find-us-content">
                <div class="info-box_address">
                    <p class="box_header">Address</p>
                    <p class="store_location">
                        Our store is located at<br />
                        5073 Filmore St, Makati, 1235 Metro Manila.
                    </p>
                    <div class="info-box_map">
                        <iframe class="Maps" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.644711312315!2d121.00124827370317!3d14.562298678016042!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9007c13d563%3A0xda4ab1c517a16912!2sPablo's%20Fur%20Friends!5e0!3m2!1sen!2sph!4v1744555621515!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="info-box_socials">
                    <p class="socials_title">Socials</p>

                    <div class="social_row">
                        <img src="RESOURCES\images\icon_phone.png" alt="Phone_Icon" class="social_icon">
                        <span class="social_details">+63 967 300 3285</span>
                    </div>
                    <div class="social_row">
                        <img src="RESOURCES\images\icon_gmail.png" alt="Gmail_Icon" class="social_icon">
                        <a href="mailto:pablosfurfriends@gmail.com" class="social_details">pablosfurfriends@gmail.com</a>
                    </div>
                    <div class="social_row">
                        <img src="./RESOURCES/images/icon_facebook.png" alt="Facebook_Icon" class="social_icon" >
                        <a href="https://www.facebook.com/profile.php?id=100086581667331" class="social_details">@Pablo's Fur Friends</a>
                    </div>
                    <div class="social_row">
                        <img src= "./RESOURCES/images/icon_instagram.png" alt="Instagram_Icon" class="social_icon">
                        <a href="https://www.instagram.com/PablosFurFriends/" class="social_details">@pablosfurfriends</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="calltoaction">
            <div class="cta_title">
                <span><span id="gold2">Premium Care</span> for your Furry Friends!</span>
            </div>
            <div class="cta_desc">
                <span>Discover Our Services and Give Your Pet the Best in Grooming, Wellness, and Love.</span>
            </div>
            <div class="cta_button">
                <?php if(isset($_SESSION['user'])): ?>
                    <a href="book_appointment.php"><span>Book Now</span></a>
                <?php else: ?>
                    <a href="auth1.php"><span>Book Now</span></a>
                <?php endif; ?>
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
    </div>
    <script src="sidebar_loading.js"></script>
</body>
</html>