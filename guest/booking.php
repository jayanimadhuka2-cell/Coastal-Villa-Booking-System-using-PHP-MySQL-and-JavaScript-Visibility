
<?php
require_once __DIR__ . "/../config/database.php";
$conn = $GLOBALS["conn"] ?? null;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $room_id = $_POST["room_id"];
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];
    $guests = $_POST["guests"];

    if ($checkout <= $checkin) {
        $message = "Please select a valid checkout date!";
    } else {

        $sql = "INSERT INTO bookings
        (guest_name, email, phone, room_id, checkin, checkout, guests)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssissi",
            $name,
            $email,
            $phone,
            $room_id,
            $checkin,
            $checkout,
            $guests
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Booking submitted successfully!";
        } else {
            $message = "Booking failed!";
        }

        mysqli_stmt_close($stmt);
    }
}

$rooms = mysqli_query($conn, "SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Booking</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <h2>Coastal Villa</h2>
    <a href="../index.php">Home</a>
</nav>

<section class="section">

    <h1>Book Your Room</h1>
    <p>Enter your booking details below.</p>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form class="booking-form" method="POST">

        <input type="text" name="name"
               placeholder="Full Name" required>

        <input type="email" name="email"
               placeholder="Email Address" required>

        <input type="tel" name="phone"
               placeholder="Phone Number" required>

        <select name="room_id" required>
            <option value="">Select Room</option>

            <?php while ($room = mysqli_fetch_assoc($rooms)) { ?>
                <option value="<?php echo $room['room_id']; ?>">
                    <?php echo htmlspecialchars($room['room_name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Check-in Date</label>
        <input type="date" name="checkin" required>

        <label>Check-out Date</label>
        <input type="date" name="checkout" required>

        <select name="guests" required>
            <option value="">Number of Guests</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <button type="submit">Submit Booking</button>

    </form>

</section>

</body>
</html>

