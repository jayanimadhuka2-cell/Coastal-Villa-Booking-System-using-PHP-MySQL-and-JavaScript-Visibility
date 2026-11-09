
<!DOCTYPE html>
<html>
<head>
    <title>Coastal Villa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navigation -->
<nav>
    <h2>Coastal Villa</h2>

    <div class="links" id="menu">
        <a href="index.php">Home</a>
        <a href="#rooms">Rooms</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
        <a href="auth/login.php">Login</a>
    </div>

    <button onclick="showMenu()">☰</button>
</nav>

<!-- Hero Section -->
<section class="hero">
    <h1>Welcome to Coastal Villa</h1>
    <p>Experience Luxury and Relaxation by the Ocean</p>
    <a href="#rooms" class="btn">Explore Rooms</a>
</section>

<!-- Booking Search -->
<section class="booking">
    <h2>Find Your Perfect Stay</h2>

    <form onsubmit="searchRooms(event)">
        <input type="date" id="checkin" required>
        <input type="date" id="checkout" required>

        <select id="guests">
            <option value="1">1 Guest</option>
            <option value="2" selected>2 Guests</option>
            <option value="3">3 Guests</option>
            <option value="4">4 Guests</option>
        </select>

        <button type="submit">Search Rooms</button>
    </form>
</section>

<!-- Featured Rooms -->
<section class="section" id="rooms">
    <h2>Our Featured Rooms</h2>
    <p>Choose your perfect accommodation</p>

    <div class="room-container">

        <div class="room-card">
            <img src="images/room1.jpg" alt="Deluxe Room">
            <h3>Deluxe Room</h3>
            <p>Comfortable room with ocean view.</p>
            <h3>Rs. 15,000 / Night</h3>
            <a href="guest/rooms.php" class="btn">Book Now</a>
        </div>

        <div class="room-card">
            <img src="images/room2.jpg" alt="Luxury Room">
            <h3>Luxury Room</h3>
            <p>Enjoy a beautiful luxury experience.</p>
            <h3>Rs. 20,000 / Night</h3>
            <a href="guest/rooms.php" class="btn">Book Now</a>
        </div>

        <div class="room-card">
            <img src="images/room3.jpg" alt="Family Room">
            <h3>Family Room</h3>
            <p>Perfect accommodation for families.</p>
            <h3>Rs. 25,000 / Night</h3>
            <a href="guest/rooms.php" class="btn">Book Now</a>
        </div>

    </div>
</section>

<!-- About -->
<section class="about" id="about">
    <h2>About Coastal Villa</h2>
    <p>
        Enjoy a peaceful holiday with beautiful ocean views,
        comfortable rooms and excellent hospitality.
    </p>
</section>

<!-- Facilities -->
<section class="section">
    <h2>Our Facilities</h2>

    <div class="facilities">
        <div>🏊 Swimming Pool</div>
        <div>📶 Free Wi-Fi</div>
        <div>🍽️ Restaurant</div>
        <div>🏖️ Beach Access</div>
    </div>
</section>

<!-- Footer -->
<footer id="contact">
    <h2>Coastal Villa</h2>
    <p>Enjoy your perfect holiday with us.</p>
    <p>Contact: info@coastalvilla.com</p>
    <p>© 2026 Coastal Villa. All Rights Reserved.</p>
</footer>

<script src="js/script.js"></script>

</body>
</html>


