
<?php

session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] != "Admin") {

    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . "/../config/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int) $_POST["booking_id"];

    // Delete Booking
    if (isset($_POST["action"]) && $_POST["action"] == "delete") {

        $sql = "DELETE FROM bookings WHERE booking_id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("Location: booking.php");
        exit();
    }

    // Update Booking Status
    if (isset($_POST["status"])) {

        $status = $_POST["status"];

        if ($status == "Confirmed" || $status == "Cancelled") {

            $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "si", $status, $id);

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }
}



$search = trim($_GET["search"] ?? "");
$filterStatus = $_GET["status"] ?? "";

if (!in_array($filterStatus, ["", "Pending", "Confirmed", "Cancelled"])) {
    $filterStatus = "";
}

$keyword = "%" . $search . "%";

$sql = "SELECT * FROM bookings
        WHERE (? = '' OR CAST(booking_id AS CHAR) LIKE ?
        OR guest_name LIKE ? OR email LIKE ?)
        AND (? = '' OR status = ?)
        ORDER BY booking_id DESC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $search,
    $keyword,
    $keyword,
    $keyword,
    $filterStatus,
    $filterStatus
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


$total = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM bookings"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM bookings WHERE status='Pending'"))['total'];

$confirmed = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM bookings WHERE status='Confirmed'"))['total'];

$cancelled = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM bookings WHERE status='Cancelled'"))['total'];

?>


<!DOCTYPE html>
<html>
<head>
    <title>Ceylonora - Admin Bookings</title>
   
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body class="booking-page">

    <aside class="sidebar">

        <div class="admin-logo">
            <img src="../images/logo.jpg" alt="Ceylonora Logo">
            <h2>CEYLONORA</h2>
            <p>ADMIN PANEL</p>
        </div>

        <nav>
            <a href="dashboard.php">Dashboard</a>

            <a href="booking.php" class="active">
                Manage Bookings
            </a>

            <a href="room.php">Manage Rooms</a>

            <a href="../index.php">Visit Website</a>

            <a href="../auth/logout.php">Logout</a>
        </nav>

    </aside>



<section class="section">



<!-- Dashboard Header -->

<div class="dashboard-header">

    <div>
        <h2>Welcome Back, Admin!</h2>
        <p>Manage your Ceylonora reservations</p>
    </div>

    <div class="admin-profile">
        <span>CA</span>
        <div>
            <strong>Administrator</strong>
            <small>Hotel Management</small>
        </div>
    </div>

</div>

<!-- Dashboard Statistics -->

<div class="dashboard-cards">

    <div class="stat-card total">
        <h3>Total Bookings</h3>
        <h1><?php echo $total; ?></h1>
        <p>All reservations</p>
    </div>

    <div class="stat-card pending">
        <h3>Pending</h3>
        <h1><?php echo $pending; ?></h1>
        <p>Waiting for approval</p>
    </div>

    <div class="stat-card confirmed">
        <h3>Confirmed</h3>
        <h1><?php echo $confirmed; ?></h1>
        <p>Approved bookings</p>
    </div>

    <div class="stat-card cancelled">
        <h3>Cancelled</h3>
        <h1><?php echo $cancelled; ?></h1>
        <p>Cancelled reservations</p>
    </div>

</div>


<form method="GET" action="booking.php" class="booking-search">

    <input
        type="text"
        name="search"
        placeholder="Search Booking ID, Name or Email"
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <select name="status">
        <option value="">All Bookings</option>

        <option value="Pending"
            <?php if ($filterStatus == "Pending") echo "selected"; ?>>
            Pending
        </option>

        <option value="Confirmed"
            <?php if ($filterStatus == "Confirmed") echo "selected"; ?>>
            Confirmed
        </option>

        <option value="Cancelled"
            <?php if ($filterStatus == "Cancelled") echo "selected"; ?>>
            Cancelled
        </option>
    </select>

    <button type="submit">Search</button>

    <a href="booking.php">Reset</a>

</form>



    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Guest Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Room ID</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Guests</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?php echo $row['booking_id']; ?></td>
            <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo $row['room_id']; ?></td>
            <td><?php echo $row['checkin']; ?></td>
            <td><?php echo $row['checkout']; ?></td>
            <td><?php echo $row['guests']; ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>

<td>

    <a href="booking-details.php?id=<?php echo $row['booking_id']; ?>">
        View Details
    </a>

    <form method="POST">

        <input type="hidden" name="booking_id"
            value="<?php echo $row['booking_id']; ?>">

        <select name="status">
            <option value="Confirmed">Confirmed</option>
            <option value="Cancelled">Cancelled</option>
        </select>

        <button type="submit">Update</button>

    </form>


<form method="POST"
      onsubmit="return confirm('Are you sure you want to delete this booking?');">

    <input type="hidden" name="booking_id"
           value="<?php echo $row['booking_id']; ?>">

    <input type="hidden" name="action" value="delete">

    <button type="submit" class="delete-btn">
        Delete
    </button>

</form>


</td>


        </tr>

        <?php } ?>

    </table>

</section>

</body>
</html>
