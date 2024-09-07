<?php
include("config.php"); 
include("fetch_products.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends Telecom</title>
    <link rel="shortcut icon" href="logo.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="style_css/homepage_style.css">
</head>
<body>
    <header>
        <div class="header_main">
            <div class="logo">
                <img src="logo_images/logo.jpeg" alt="Friends Telecom" width="100px">
            </div>
            <div class="search-container">
                <input type="text" placeholder="Search for products...">
                <button type="submit">Search</button>
            </div>
            <div class="loginSignup">
                <?php if (isset($_SESSION['email'])): ?>
                    <div id="profileSection">
                        <a href="profile.php"><img src="logo_images/profile_lofo.jpeg" alt="Profile" width="50px" id="profileIcon"><?php echo $_SESSION['UserID']; ?></a>
                        <button><a href="logout.php" id="logoutButton">Logout</a></button>
                    </div>
                <?php else: ?>
                    <div id="loginSignupButtons">
                        <button><a href="login.html" class="login">Login</a></button>
                        <button><a href="signup.html" class="signup">Register</a></button>
                    </div>
                <?php endif; ?>
            </div>
            <div class="cart">
                <img src="logo_images/cart.jpeg" alt="Cart" width="50px">
            </div>
            <div class="repairService">
                <a href="fetch_service_requests.php"><img src="logo_images/repairService.jpeg" alt="Service" width="40px"></a>
            </div>
        </div>
        <div class="header_navbar">
            <div class="nav">
                <a href="products.php?category=Mobiles">Mobiles</a>
                <a href="products.php?category=Accessories">Accessories</a>
                <a href="products.php?category=Audio store">Audio store</a>
                <a href="products.php?category=Laptops">Laptops</a>
                <a href="products.php?category=Tablets">Tablets</a>
            </div>
        </div>

    </header>
    <main>
        <div class="main_content">
            <div class="slider-container">
                <div class="slides">
                    <div class="slide"><img src="slider_images/Galaxy_S24_Ultra_Desktop_.jpg" alt="Galaxy S24 Ultra"></div>
                    <div class="slide"><img src="slider_images/Samsung_Galaxy_AI_Desktop.jpg" alt="Samsung Galaxy AI"></div>
                    <div class="slide"><img src="slider_images/Smart_TV_Banner.jpg" alt="Smart TV"></div>
                    <div class="slide"><img src="slider_images/Earbuds_Banner.jpg" alt="Earbuds"></div>
                    <div class="slide"><img src="slider_images/Laptops_Banner.jpg" alt="Laptops"></div>
                    <div class="slide"><img src="slider_images/Smart_Watch_Banner.jpg" alt="Smart Watch"></div>
                </div>
                <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
                <button class="next" onclick="changeSlide(1)">&#10095;</button>
            </div>
            
            <div class="mobile_section">
                <div class="mobile_section_parts">
                    <ul class="mobile_section_desc">
                        <h3>MOBILES</h3>
                        <li>Apple</li>
                        <li>Vivo</li>
                        <li>Oppo</li>
                        <li>Mi</li>
                        <li>Realme</li>
                        <li>Nothing Phone</li>
                    </ul>
                </div>
                <div class="mobile_section_slider">
                    <?php 
                    $mobiles = fetchProductsByCategory('Mobiles');
                    foreach ($mobiles as $mobile): ?>
                    <div class="slide_mobiles">
                        <figure>
                            <img src="<?php echo $mobile['image_url']; ?>" alt="<?php echo $mobile['ProductName']; ?>">
                            <figcaption><?php echo $mobile['ProductName']; ?> - <?php echo $mobile['Description']; ?></figcaption>
                        </figure>
                    </div>
                    <?php endforeach; ?>
                    <div class="slide_mobiles">
                        <button style="padding-right: 25px; margin-top: 115px; margin-right: 30px;">View more</button>
                    </div>
                </div>
            </div>
            
            <div class="laptop_section">
                <div class="laptop_section_parts">
                    <ul class="laptop_section_desc">
                        <h3>LAPTOPS</h3>
                        <li>MAC</li>
                        <li>HP</li>
                        <li>Dell</li>
                        <li>Asus</li>
                    </ul>
                </div>
                <div class="laptop_section_slider">
                    <?php 
                    $laptops = fetchProductsByCategory('Laptops');
                    foreach ($laptops as $laptop): ?>
                    <div class="slide_laptop">
                        <figure>
                            <img src="<?php echo $laptop['image_url']; ?>" alt="<?php echo $laptop['ProductName']; ?>">
                            <figcaption><?php echo $laptop['ProductName']; ?> - <?php echo $laptop['Description']; ?></figcaption>
                        </figure>
                    </div>
                    <?php endforeach; ?>
                    <div class="slide_laptop">
                        <button style="padding-right: 25px; margin-top: 115px; margin-right: 30px;">View more</button>
                    </div>
                </div>
            </div>
            
            <div class="accessories_section">
                <div class="accessories_section_parts">
                    <ul class="accessories_section_desc">
                        <h3>ACCESSORIES</h3>
                        <li>Neck Band</li>
                        <li>Earphones</li>
                        <li>Earbuds</li>
                        <li>Speaker</li>
                        <li>Charger</li>
                    </ul>
                </div>
                <div class="accessories_section_slider">
                    <?php 
                    $accessories = fetchProductsByCategory('Accessories');
                    foreach ($accessories as $accessory): ?>
                    <div class="slide_accessories">
                        <figure>
                            <img src="<?php echo $accessory['image_url']; ?>" alt="<?php echo $accessory['ProductName']; ?>">
                            <figcaption><?php echo $accessory['ProductName']; ?> - <?php echo $accessory['Description']; ?></figcaption>
                        </figure>
                    </div>
                    <?php endforeach; ?>
                    <div class="slide_accessories">
                        <button style="padding-right: 25px; margin-top: 115px; margin-right: 30px;">View more</button>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
    <footer>
        <div class="footer">
            &copy; 2024 Friends Telecom. All rights reserved.
        </div>
    </footer>
    <script src="scripts/carousel_script.js"></script>
</body>
</html>
