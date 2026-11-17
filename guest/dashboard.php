
<?php

session_start();

require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] != "Customer") {

    header("Location: ../auth/login.php");
    exit();
}

$userName = htmlspecialchars($_SESSION["name"]);
$userEmail = $_SESSION["email"];

$sql = "SELECT COUNT(*) AS total FROM bookings WHERE email = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $userEmail);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

$totalBookings = $data["total"];

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Ceylonora - Guest Dashboard</title>

    <link rel="stylesheet" href="../css/guest.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="brand">
            <img src="../images/logo.jpg" alt="Ceylonora Logo">
            <h2>CEYLONORA</h2>
            <p>DISCOVER THE BEAUTY OF CEYLON</p>
        </div>

        <nav>
            <a href="dashboard.php" class="active">⌂ Dashboard</a>
            <a href="rooms.php">▣ Explore Rooms</a>
            <a href="booking.php">✧ Book a Villa</a>
            <a href="my-bookings.php">▤ My Bookings</a>
        </nav>

        <a href="../auth/logout.php" class="logout">↪ Logout</a>

    </aside>


    <main class="main-content">

        <header class="topbar">
            <div>
                <h2>Guest Dashboard</h2>
                <p>Your luxury coastal experience starts here.</p>
            </div>

            <div class="profile">
                <span>Welcome, <?php echo $userName; ?></span>
            </div>
        </header>


        <section class="welcome-banner">

            <div class="welcome-info">

                <p>WELCOME TO CEYLONORA</p>

                <h1>Discover Your Perfect Escape.</h1>

                <p>
                    Relax, explore and enjoy unforgettable
                    moments at our luxury coastal villas.
                </p>

                <a href="rooms.php">EXPLORE OUR VILLAS →</a>

            </div>

        </section>


        <section class="stats">

            <div class="stat-card">
                <h3>My Bookings</h3>
                <h1><?php echo $totalBookings; ?></h1>
                <p>Total reservations made</p>
            </div>

            <div class="stat-card">
                <h3>Guest Account</h3>
                <h1>Active</h1>
                <p>Your account is ready</p>
            </div>

            <div class="stat-card">
                <h3>Luxury Experience</h3>
                <h1>★★★★★</h1>
                <p>Discover the beauty of Ceylon</p>
            </div>

        </section>


        <section class="quick-section">

            <h2>Quick Actions</h2>

            <div class="action-cards">

                <a href="rooms.php" class="action-card">
                    <span>🏝️</span>
                    <h3>Explore Villas</h3>
                    <p>Discover our beautiful rooms.</p>
                </a>

                <a href="booking.php" class="action-card">
                    <span>✧</span>
                    <h3>Book Your Stay</h3>
                    <p>Reserve your perfect getaway.</p>
                </a>

                <a href="my-bookings.php" class="action-card">
                    <span>▤</span>
                    <h3>My Bookings</h3>
                    <p>View your reservations.</p>
                </a>

            </div>

        </section>


        <footer>
            © 2026 CEYLONORA | LUXURY COASTAL LIVING
        </footer>

    </main>

</div>

</body>
</html>
