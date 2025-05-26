<!DOCTYPE html>
<?php
require 'connection.php';
session_start();
if(!ISSET($_SESSION['user'])) {
    header('location: index.php');
}

$id = $_SESSION['user'];
$sql = $conn->prepare("SELECT * FROM users WHERE userID = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();
$fetch = $sql->fetch();
?>
<html>
    <head>
        <title>Pablo's Fur Friends</title>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="home.css">
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
                        <li><a href="./home.php"><b style="color: #f88908;">HOME</b></a></li>
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
                    <a href="./appointments.php"><img src="./RESOURCES/images/button_calendar.png" height="50" alt="appointment_btn" class="navbar_app_btn"></a>
                </div>
                <?php endif; ?>
            </div>
        </header>
        <section class="image_slider">
            <div class="img_slides_container" id="img_slides_container">
                <div class="img_slide" style="background-image: url(./RESOURCES/images/slider_boarding.png)"></div>
                <div class="img_slide" style="background-image: url(./RESOURCES/images/slider_facilities.png)"></div>
                <div class="img_slide" style="background-image: url(./RESOURCES/images/slider_grooming_services.png)"></div>
                <div class="img_slide" style="background-image: url(./RESOURCES/images/slider_product.png)"></div>
            </div>
            <div class="dots_container" id="dots_container"></div>
        </section>
        <section class="section1">
            <div class="s1_contents">
                <div class="s1c1">
                    <span>WELCOME FUR FRIEND!</span>
                </div>
                <div class="s1c2">
                    <span>Groomed with Care, <br>Supplies to Spare, <br>and Cozy Stays <br>Everywhere!</span>
                </div>
                <a href="./aboutus.php"><div class="s1c3">
                    <span>LEARN MORE</span>
                </div></a>
            </div>
        </section>
        <section class="section2">
            <img src="./RESOURCES/images/pattern_paw1.png" alt="SECTION 2 bg pattern" class="s2c_bg">
            <div class="s2_contents">
                <div class="s2sc1">
                    <div class="s2sc1_1">
                        <div class="s2sc1_1_title">
                            <span>ABOUT US</span>
                        </div>
                        <span id="s2sc1_1_text1">We Care for Your Pets Like Family</span><br><br>
                        <span id="s2sc1_1_text2">Pablo’s Fur Friend Pet Shop offers top quality grooming, safe and comfortable pet boarding and a wide range pet supplies. We are dedicated to keeping your pets happy, healthy, and well-cared for.</span><br><br>
                        <div class="s2sc1_1_checkboxes">
                            <div class="s2sc_checkbox1">
                                <img src="./RESOURCES/images/symbol_checkbox.png" alt="CHECKBOX" height="50" class="checkbox">
                                <span>Professional Grooming</span>
                            </div>
                            <div class="s2sc_checkbox2">
                                <img src="./RESOURCES/images/symbol_checkbox.png" alt="CHECKBOX" height="50" class="checkbox">
                                <span>Trained and Friendly Staff</span>
                            </div>
                            <div class="s2sc_checkbox3">
                                <img src="./RESOURCES/images/symbol_checkbox.png" alt="CHECKBOX" height="50" class="checkbox">
                                <span>Safe Boarding Facilities</span>
                            </div>
                            <div class="s2sc_checkbox4">
                                <img src="./RESOURCES/images/symbol_checkbox.png" alt="CHECKBOX" height="50" class="checkbox">
                                <span>Quality Pet Supplies</span>
                            </div>
                        </div>
                    </div>
                    <div class="s2sc1_2">
                        <img src="./RESOURCES/images/s2c_dog.png" alt="SECTION 2 DOG" class="s2sc1_dog" height="500">
                    </div>
                </div>
            </div>
        </section>
        <section class="section3">
            <div class="s3_loyalty_card">
                <div class="inner_loyalty">
                    <div class="s3_loyalty_front">
                        <img src="./RESOURCES/images/pff_loyalty_card_front.png" alt="LOYALTY CARD FRONT">
                    </div>
                    <div class="s3_loyalty_back">
                        <img src="./RESOURCES/images/pff_loyalty_card_back.png" alt="LOYALTY CARD BACK">
                    </div>
                </div>
            </div>
            <div class="s3_promo_text">
                <span>Collect <span id="red">8 stamps </span>for a<br><span id="red">FREE</span> Grooming Service with our <br><span id="gold">LOYALTY CARD!</span>
            </div>
        </section>
        <section class="section4">
            <div class="s4_title">
                <span>OUR SERVICES</span>
            </div>
            <div class="s4_subtitle">
                <span>What we offer.</span>
            </div>
            <div class="s4_tiles">
                <a href="services.html">
                    <div class="s4_tile1">
                        <img src="./RESOURCES/images/s4_tile1_img.jpg" alt="boarding" class="s4_tile1_img">
                        <div class="tile1_wrapper">
                            <span id="s4_tile1_title">Pet Boarding</span>
                            <span id="s4_tile1_desc"> A cozy and safe place for your pets while you're away</span>
                        </div>
                    </div>
                </a>
                <a href="services.html">
                    <div class="s4_tile2">
                        <img src="./RESOURCES/images/s4_tile2_img.jpg" alt="grooming" class="s4_tile2_img">
                        <div class="tile2_wrapper">
                            <span id="s4_tile2_title">Pet Grooming</span>
                            <span id="s4_tile2_desc">Pamper your furry friends with expert care</span>
                        </div>
                    </div>
                </a>
                <a href="services.html">
                    <div class="s4_tile3">
                        <img src="./RESOURCES/images/s4_tile3_img.jpg" alt="supplies" class="s4_tile3_img">
                        <div class="tile3_wrapper">
                            <span id="s4_tile3_title">Pet Supplies</span>
                            <span id="s4_tile3_desc">Quality products to keep your pets happy and healthy</span>
                        </div>
                    </div>
                </a>
            </div>
        </section>
        <section class="section5">
            <div class="s5_title">
                <span>BEST SELLERS</span>
            </div>
            <div class="s5_subtitle">
                <span>Best Sellers and Recommended by customers.</span>
            </div>
            <div class="s5_product_cards">
                <div class="s5_card1">
                    <img src="./RESOURCES/images/s5_c1.png" alt="PRODUCT 1" class="s5_c1_img">
                    <span class="s5_c1_txt">Whiskas Tuna Cat Food Pouch for Adult Cat 80g</span>
                    <span class="s5_c1_price">Php 30.00</span>
                </div>
                <div class="s5_card2">
                    <img src="./RESOURCES/images/s5_c2.png" alt="PRODUCT 1" class="s5_c2_img">
                    <span class="s5_c2_txt">Pedigree Dentastix Large 112g</span>
                    <span class="s5_c2_price">Php 100.00</span>
                </div>
                <div class="s5_card3">
                    <img src="./RESOURCES/images/s5_c3.png" alt="PRODUCT 1" class="s5_c3_img">
                    <span class="s5_c3_txt">Pedigree Beef Canned Dog Food 400g</span>
                    <span class="s5_c3_price">Php 150.00</span>
                </div>
                <div class="s5_card4">
                    <img src="./RESOURCES/images/s5_c4.png" alt="PRODUCT 1" class="s5_c4_img">
                    <span class="s5_c4_txt">Royal Canin Miniature Schnauzer Puppy 1.5kg</span>
                    <span class="s5_c4_price">Php 1,800.00</span>
                </div>
                <div class="s5_card5">
                    <img src="./RESOURCES/images/s5_c5.png" alt="PRODUCT 1" class="s5_c5_img">
                    <span class="s5_c5_txt">Pedigree Adult Chicken and Vegetables Dry Dog Food 1.5kg</span>
                    <span class="s5_c5_price">Php 300.00</span>
                </div>
                <div class="s5_card6">
                    <span class="s5_c6_title">Browse Products</span>
                    <a href="products.html">
                        <img src="./RESOURCES/images/round_arrow_right_blk.png" alt="browse" class="s5_c6_btn">
                    </a>
                </div>
            </div>
        </section>
        <section class="section6">
            <div class="s6_title">
                <span>GALLERY</span>
            </div>
            <div class="s6_subtitle">
                <span>OUR PET SHOP'S GALLERY</span>
            </div>
            <div class="s6_desc">
                <span>Browse our gallery to see adorable transformations, happy tails, and the coziest stays for your fur babies. Because every pet deserves the best!</span>
            </div>
            <div class="s6_gallery">
                <div class="s6_gallery_track">
                    <img src="./RESOURCES/images/s6_track_img1.jpg" alt="img1">
                    <img src="./RESOURCES/images/s6_track_img2.jpg" alt="img2">
                    <img src="./RESOURCES/images/s6_track_img3.jpg" alt="img3">
                    <img src="./RESOURCES/images/s6_track_img4.jpg" alt="img4">
                    <img src="./RESOURCES/images/s6_track_img5.jpg" alt="img5">
                    <img src="./RESOURCES/images/s6_track_img6.jpg" alt="img6">
                    <img src="./RESOURCES/images/s6_track_img7.jpg" alt="img7">
                    <img src="./RESOURCES/images/s6_track_img8.jpg" alt="img8">
                    <img src="./RESOURCES/images/s6_track_img9.jpg" alt="img9">
                    <img src="./RESOURCES/images/s6_track_img10.jpg" alt="img10">
                    <img src="./RESOURCES/images/s6_track_img1.jpg" alt="img1">
                    <img src="./RESOURCES/images/s6_track_img2.jpg" alt="img2">
                    <img src="./RESOURCES/images/s6_track_img3.jpg" alt="img3">
                    <img src="./RESOURCES/images/s6_track_img4.jpg" alt="img4">
                    <img src="./RESOURCES/images/s6_track_img5.jpg" alt="img5">
                    <img src="./RESOURCES/images/s6_track_img6.jpg" alt="img6">
                    <img src="./RESOURCES/images/s6_track_img7.jpg" alt="img7">
                    <img src="./RESOURCES/images/s6_track_img8.jpg" alt="img8">
                    <img src="./RESOURCES/images/s6_track_img9.jpg" alt="img9">
                    <img src="./RESOURCES/images/s6_track_img10.jpg" alt="img10">
                </div>
            </div>
        </section>
        <section class="section7">
            <div class="s7_title">
                <span><span id="gold2">Premium Care</span> for your Furry Friends!</span>
            </div>
            <div class="s7_desc">
                <span>Discover Our Services and Give Your Pet the Best in Grooming, Wellness, and Love.</span>
            </div>
            <div class="s7_button">
                <a href="book_appointment.php"><span>Book Now</span></a>
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
                    <h3><strong>Contact Us!</strong></h3>
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
        <script src="home.js" defer></script>
    </body>
</html>