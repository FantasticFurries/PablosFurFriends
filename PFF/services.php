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
    <title>Services</title>
    <link rel="stylesheet" href="services.css">
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
                <h1>Our Services</h1>
                <h3>Providing the best care for your furry friends!</h2>
            </div>
            <div class="services_image"></div>
        </div>
    </section>    

    <section class="section2">
        <div class="s2_wrapper">
            <div class="s2_title">
                <div class="s2_titlediv">
                    <h2>Pet Boarding</h2>
                    <img src="./RESOURCES/images/Services_images/paw_vector.png" alt="">
                </div>
            </div>
            <div class="s2_gridwrap">
                <div class="dog_groom dog_groom1" style="grid-area: box-1"><img src="./RESOURCES/images/Services_images/boarding2.jpg" alt=""></div>
                <div class="pet_grid" style="grid-area: box-2">
                    <div class="pet_gridtop">
                    <h3>Dogs</h3>
                    <p>Our trustworthy and caring staff ensure your pet receives the best care, giving you peace of mind while you're away.</p>
                    </div>
                    <div class="s3_dogprice">
                        <div class="dog_span">
                            <div class="dog_spandiv">
                                <span>SMALL-MEDIUM</span>
                                <img src="./RESOURCES/images/Services_images/paw_small.png" alt="">
                            </div>
                            <div class="dog_spanprice">
                                <span class="spanprice">₱450.00</span>
                            </div>
                        </div>
                        <div class="dog_span">
                            <div class="dog_spandiv">
                                <span>LARGE-GIANT</span>
                                <img src="./RESOURCES/images/Services_images/paw_medium.png" alt="">
                            </div>
                            <div class="dog_spanprice">
                                <span class="spanprice">₱550.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pet_grid" style="grid-area: box-3">
                    <div class="pet_gridtop">
                    <h3>Cats</h3>
                    <p>Includes warm bath, nail trim, ear cleaning, teeth brushing, anal sac draining, and desired haircut. </p>
                    </div>
                    <div class="cat_span">
                        <div class="dog_spandiv">
                            <span>KITTEN-ADULT</span>
                            <img src="./RESOURCES/images/Services_images/paw_xl.png" alt="">
                        </div>
                        <div class="dog_spanprice">
                            <span class="spanprice">₱450.00</span>
                        </div>
                    </div>
                </div>
                <div class="note" style="grid-area: box-4">
                    <div class="note_div">
                        <h3>IMPORTANT:</h3>
                        <ul>
                                <li>₱550 down payment to reserve the slot. Refundable only if reservation is cancelled 3 days before the scheduled booking.</li>
                                <li>₱550 security deposit is required upon check-in (deductible from the total bill or refundable upon check out).</li>
                                <li>If the acommodation needs to be extended, client must notify the boarding attendant at least a day before the check out date.</li>
                                <li>Check in and check out time must be done within the store hours, otherwise emergency checkout fee of ₱200 will be charged. </li>
                            
                                <li>Food is not included. Owners are encouraged to bring their pet’s food and bowl or purchase from the store. Bringing of<br> clean drinking water is also advised.</li>
                                <li>Only pets in good condition will be accepted. We also encourage to bring the updated vet records of pets.</li>
                                <li>Updates will be sent to the owner twice daily (AM & PM) including photos and videos.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="section3">
        <div class="s3_wrapper">
            <div class="s3_title">
                <div class="s3_titlediv">
                    <h2>Pet Grooming</h2>
                    <img src="./RESOURCES/images/Services_images/paw_orange.png" alt="">
                </div>    
            </div>
            <div class="s3_gridwrap">
                <div class="pet_grid" style="grid-area: box-1">
                    <div class="pet_gridtop">
                    <h3>Dogs</h3>
                    <p>Includes warm bath, nail trim, ear cleaning, teeth brushing, anal sac draining, and desired haircut.</p>
                    </div>
                    <div class="s3_dogprice">
                        <div class="dog_span">
                            <div class="dog_spandiv">
                                <span>SMALL</span>
                                <img src="./RESOURCES/images/Services_images/paw_small.png" alt="">
                            </div>
                            <div class="dog_spanprice">
                                <span class="spanprice">₱550.00</span>
                            </div>
                        </div>
                        <div class="dog_span">
                            <div class="dog_spandiv">
                                <span>MEDIUM</span>
                                <img src="./RESOURCES/images/Services_images/paw_medium.png" alt="">
                            </div>
                            <div class="dog_spanprice">
                                <span class="spanprice">₱650.00</span>
                            </div>
                        </div>
                        <div class="dog_span">
                            <div class="dog_spandiv">
                                <span>LARGE</span>
                                <img src="./RESOURCES/images/Services_images/paw_large.png" alt="">
                            </div>
                            <div class="dog_spanprice">
                                <span class="spanprice">₱750.00</span>
                            </div>
                        </div>
                        <div class="dog_span">
                            <div class="dog_spandiv">
                                <span>XL-GIANT</span>
                                <img src="./RESOURCES/images/Services_images/paw_xl.png" alt="">
                            </div>
                            <div class="dog_spanprice">
                                <span class="spanprice">₱1200.00</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="pet_grid" style="grid-area: box-2">
                    <div class="pet_gridtop">
                    <h3>Cats</h3>
                    <p>Includes warm bath, nail trim, ear cleaning, teeth brushing, anal sac draining, and desired haircut. </p>
                    </div>
                    <div class="cat_span">
                        <div class="dog_spandiv">
                            <span>KITTEN-ADULT</span>
                            <img src="./RESOURCES/images/Services_images/paw_xl.png" alt="">
                        </div>
                        <div class="dog_spanprice">
                            <span class="spanprice">₱600.00</span>
                        </div>
                    </div>
                </div>
                <div class="dog_groom" style="grid-area: box-3"><img src="./RESOURCES/images/Services_images/dogroom1.png" alt=""></div>
                <div class="inclusions" style="grid-area: box-4">
                    <div class="inclusions_div">
                        <h3>INCLUSIONS:</h3>
                        <ul>
                            <li>
                                Full bath and dry with shampoo and perfume
                            </li>
                            <li>
                                Nail trim
                            </li>
                            <li>
                                Ear Cleaning and ear hair removal
                            </li>
                            <li>
                                Teeth cleaning
                            </li>
                            <li>
                                Hair Cut
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="note" style="grid-area: box-5">
                    <div class="note_div">
                        <h3>NOTE:</h3>
                        <ul>
                            <li>For teeth cleaning service, pet owners must bring their pet’s toothbrush or avail from the store.</li>
                            <li>Groomer may request to add additional de-matting fee depending on the condition of the pet’s coat</li>
                            <li>The duration of each grooming services may vary depending on the pet’s behavior and cooperation all throughout the process</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section4">
        <div class="s4_wrapper">
            <div class="s4_title">
                <div class="s4_titlediv">
                    <h2>Other Services</h2>
                    <img src="./RESOURCES/images/Services_images/paw_vector.png" alt="">
                </div>
            </div>
            <div class="s4_gridwrap">
                <div class="small_grid" style="grid-area: box-1">
                    <div class="sgrid_title">
                        <span>Nail Trim</span>
                    </div>
                    <div class="sgrid_price">
                        <span>₱100.00</span>
                    </div>
                </div>
                <div class="small_grid" style="grid-area: box-2">
                    <div class="sgrid_title">
                        <span>Ear Cleaning</span>
                    </div>
                    <div class="sgrid_price">
                        <span>₱100.00</span>
                    </div>
                </div>
                <div class="small_grid" style="grid-area: box-3">
                    <div class="sgrid_title">
                        <span>Teeth Cleaning</span>
                    </div>
                    <div class="sgrid_price">
                        <span>₱100.00</span>
                    </div>
                </div>
                <div class="small_grid" style="grid-area: box-4">
                    <div class="sgrid_title">
                        <span>Dematting</span>
                    </div>
                    <div class="sgrid_price">
                        <span>₱250.00</span>
                    </div>
                </div>
                <div class="big_grid" style="grid-area: box-5">
                    <div class="bgrid_title">
                        <span>Daycare Services</span>
                        <img src="./RESOURCES/images/Services_images/paw_vector.png" alt="">
                    </div>
                    <div class="bgrid_pricediv">
                    <div class="bgrid_price bgridprice-1">
                        <span>₱200 / 3 hrs</span>
                    </div>
                    <div class="bgrid_price">
                        <span>₱50 for every<br> succeeding time</span>
                    </div>
                    </div>
                    <div class="bgrid_promo">
                        <p>*FREE bath and blow for 7 days stay and up</p>
                    </div>
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

    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
    <script src="sidebar_loading.js"></script>
</body>
</html>