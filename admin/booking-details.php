
<?php

session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] != "Admin") {

    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . "/../config/database.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid Booking ID!");
}

$id = (int) $_GET["id"];

$sql = "SELECT * FROM bookings WHERE booking_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$booking = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$booking) {
    die("Booking not found!");
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Booking Details - Ceylonora</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body class="booking-details-page">

<aside class="sidebar">

    <h2>CEYLONORA</h2>

    <p class="sidebar-label">ADMIN PANEL</p>

    <a href="dashboard.php">Dashboard</a>

    <a href="booking.php" class="active">Bookings</a>

    <a href="room.php">Rooms</a>

    <a href="#">Customers</a>

    <a href="#">Reports</a>

    <div class="sidebar-bottom">
        <a href="../index.php">Visit Website</a>
    </div>

</aside>

<main class="booking-details-content">

    <div class="details-header">

        <div>
            <h1>Booking Details</h1>
            <p>Complete reservation information</p>
        </div>

        <a href="booking.php" class="back-button">
            ← Back to Bookings
        </a>

    </div>

    <div class="booking-detail-card">

        <div class="detail-title">

            <div>
                <h2>Reservation #<?php echo $booking["booking_id"]; ?></h2>
                <p>Ceylonora Guest Reservation</p>
            </div>

            <span class="booking-status">
                <?php echo htmlspecialchars($booking["status"]); ?>
            </span>

        </div>

        <hr>

        <h3>Guest Information</h3>

        <div class="details-grid">

            <div>
                <small>Guest Name</small>
                <p><?php echo htmlspecialchars($booking["guest_name"]); ?></p>
            </div>

            <div>
                <small>Email Address</small>
                <p><?php echo htmlspecialchars($booking["email"]); ?></p>
            </div>

            <div>
                <small>Phone Number</small>
                <p><?php echo htmlspecialchars($booking["phone"]); ?></p>
            </div>

            <div>
                <small>Room ID</small>
                <p><?php echo htmlspecialchars($booking["room_id"]); ?></p>
            </div>

        </div>

        <hr>

        <h3>Reservation Information</h3>

        <div class="details-grid">

            <div>
                <small>Check-in Date</small>
                <p><?php echo htmlspecialchars($booking["checkin"]); ?></p>
            </div>

            <div>
                <small>Check-out Date</small>
                <p><?php echo htmlspecialchars($booking["checkout"]); ?></p>
            </div>

            <div>
                <small>Number of Guests</small>
                <p><?php echo htmlspecialchars($booking["guests"]); ?></p>
            </div>

            <div>
                <small>Booking Status</small>
                <p><?php echo htmlspecialchars($booking["status"]); ?></p>
            </div>

        </div>

    </div>

</main>

</body>
</html>
