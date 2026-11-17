
<?php
session_start();

require_once __DIR__ . "/../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

$adminName = htmlspecialchars($_SESSION["name"]);

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))["total"];

$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE status='Pending'"))["total"];

$confirmed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE status='Confirmed'"))["total"];

$cancelled = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE status='Cancelled'"))["total"];

$recentBookings = mysqli_query($conn, "SELECT * FROM bookings ORDER BY booking_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylonora | Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body class="dashboard-page">

<div class="admin-container">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="admin-logo">
            <img src="../images/logo.jpg" alt="Ceylonora">
            <h2>CEYLONORA</h2>
            <p>ADMIN PANEL</p>
        </div>

        <nav>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="booking.php">Manage Bookings</a>
            <a href="room.php">Manage Rooms</a>
            <a href="../index.php">Visit Website</a>
            <a href="../auth/logout.php">Logout</a>
        </nav>

    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <header class="topbar">
            <div>
                <h2>Admin Dashboard</h2>
                <p>Welcome back, <?php echo $adminName; ?>!</p>
            </div>

            <div class="admin-profile">
                <span>CA</span>
                <div>
                    <strong>Administrator</strong>
                    <small>Hotel Management</small>
                </div>
            </div>
        </header>

        <!-- Welcome -->
        <section class="welcome">
            <h1>Welcome to Ceylonora!</h1>
            <p>Manage your luxury coastal villa reservations in one place.</p>
        </section>

        <!-- Statistics -->
        <section class="stats">

            <div class="stat-card gold">
                <p>Total Bookings</p>
                <h1><?php echo $total; ?></h1>
                <small>All reservations</small>
            </div>

            <div class="stat-card orange">
                <p>Pending Bookings</p>
                <h1><?php echo $pending; ?></h1>
                <small>Awaiting confirmation</small>
            </div>

            <div class="stat-card green">
                <p>Confirmed Bookings</p>
                <h1><?php echo $confirmed; ?></h1>
                <small>Approved reservations</small>
            </div>

            <div class="stat-card red">
                <p>Cancelled Bookings</p>
                <h1><?php echo $cancelled; ?></h1>
                <small>Cancelled reservations</small>
            </div>

        </section>

        <!-- Recent Bookings -->
        <section class="recent-section">

            <div class="section-heading">
                <h2>Recent Bookings</h2>
                <a href="booking.php">View All</a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($recentBookings && mysqli_num_rows($recentBookings) > 0): ?>

                        <?php while ($booking = mysqli_fetch_assoc($recentBookings)): ?>
                            <tr>
                                <td>#<?php echo $booking["booking_id"]; ?></td>
                                <td><?php echo htmlspecialchars($booking["guest_name"]); ?></td>
                                <td><?php echo htmlspecialchars($booking["email"]); ?></td>
                                <td>
                                    <span class="status">
                                        <?php echo htmlspecialchars($booking["status"]); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="4">No bookings available.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </section>

        <!-- Quick Links -->
        <section class="quick-management">
            <h2>Quick Management</h2>

            <div class="quick-links">
                <a href="booking.php">Manage Bookings →</a>
                <a href="room.php">Manage Villa Rooms →</a>
            </div>
        </section>

    </main>

</div>

</body>
</html>
