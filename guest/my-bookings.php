
<?php

session_start();

require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] != "Customer") {

    header("Location: ../auth/login.php");
    exit();
}

$email = $_SESSION["email"];

$sql = "SELECT * FROM bookings WHERE email = ? ORDER BY booking_id DESC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Ceylonora - My Bookings</title>

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

            <a href="dashboard.php">⌂ Dashboard</a>

            <a href="rooms.php">▣ Explore Rooms</a>

            <a href="booking.php">✧ Book a Villa</a>

            <a href="my-bookings.php" class="active">▤ My Bookings</a>

        </nav>

        <a href="../auth/logout.php" class="logout">↪ Logout</a>

    </aside>


    <main class="main-content">

        <header class="topbar">

            <div>

                <h2>My Bookings</h2>

                <p>Manage your villa reservations.</p>

            </div>

        </header>


        <section class="quick-section">

            <h2>Your Reservations</h2>

            <div class="booking-list">

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while ($booking = mysqli_fetch_assoc($result)) { ?>

                        <div class="stat-card">

                            <h3>Booking #<?php echo $booking["booking_id"]; ?></h3>

                            <p>Guest: <?php echo htmlspecialchars($booking["guest_name"]); ?></p>

                            <p>Email: <?php echo htmlspecialchars($booking["email"]); ?></p>

                            <p>Check-in: <?php echo htmlspecialchars($booking["checkin"]); ?></p>

                            <p>Check-out: <?php echo htmlspecialchars($booking["checkout"]); ?></p>

                            <p>Guests: <?php echo htmlspecialchars($booking["guests"]); ?></p>

                            <h3>Status: <?php echo htmlspecialchars($booking["status"]); ?></h3>

                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="stat-card">

                        <h3>No Bookings Yet</h3>

                        <p>You have not made any reservations yet.</p>

                        <a href="rooms.php">Explore Our Villas</a>

                    </div>

                <?php } ?>

            </div>

        </section>

        <footer>
            © 2026 CEYLONORA | LUXURY COASTAL LIVING
        </footer>

    </main>

</div>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
?>
