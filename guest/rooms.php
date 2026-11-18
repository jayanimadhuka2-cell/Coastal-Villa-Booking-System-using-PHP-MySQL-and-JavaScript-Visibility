
<?php
require_once __DIR__ . "/../config/database.php";

$sql = "SELECT * FROM rooms ORDER BY room_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Rooms - Ceylonora</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <h2>Ceylonora</h2>
    <div class="links">
        <a href="../index.php">Home</a>
        <a href="rooms.php">Rooms</a>
        <a href="../auth/login.php">Login</a>
    </div>
</nav>

<section class="section">

    <h1>Our Rooms</h1>
    <p>Choose your perfect stay</p>

    <div class="room-container">

        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

            <?php while ($room = mysqli_fetch_assoc($result)) { ?>

                <div class="room-card">

                    <img
                        src="../images/<?php echo htmlspecialchars($room['image']); ?>"
                        alt="<?php echo htmlspecialchars($room['room_name']); ?>"
                    >

                    <h3>
                        <?php echo htmlspecialchars($room['room_name']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($room['description']); ?>
                    </p>

                    <h3>
                        Rs. <?php echo number_format($room['price']); ?> / Night
                    </h3>

                    <a href="booking.php" class="btn">Book Now</a>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>No rooms available at the moment.</p>

        <?php } ?>

    </div>

</section>

</body>
</html>
