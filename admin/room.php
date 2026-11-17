
<?php

session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] != "Admin") {

    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . "/../config/database.php";

$message = "";

// Add, Edit and Delete Rooms

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"];

    // Delete Room
    if ($action == "delete") {

        $id = (int) $_POST["room_id"];

        $sql = "DELETE FROM rooms WHERE room_id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("Location: room.php");
        exit();
    }

    // Add or Edit Room
    if ($action == "add" || $action == "edit") {

        $name = trim($_POST["room_name"]);
        $description = trim($_POST["description"]);
        $price = (float) $_POST["price"];

        $id = (int) ($_POST["room_id"] ?? 0);

        $image = "";

        // Image Upload
        if (!empty($_FILES["image"]["name"])) {

            $extension = strtolower(
                pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
            );

            $allowed = ["jpg", "jpeg", "png", "webp"];

            $imageInfo = getimagesize($_FILES["image"]["tmp_name"]);

            if (
                !in_array($extension, $allowed) ||
                !$imageInfo ||
                $_FILES["image"]["error"] != 0
            ) {

                $message = "Please upload a valid image!";

            } else {

                $image = "room_" . uniqid() . "." . $extension;

                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    __DIR__ . "/../images/" . $image
                );
            }
        }

        if ($message == "") {

            if ($name == "" || $price <= 0) {

                $message = "Please enter valid room details!";

            } else {

                if ($action == "add") {

                    $sql = "INSERT INTO rooms
                    (room_name, description, price, image)
                    VALUES (?, ?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $sql);

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssds",
                        $name,
                        $description,
                        $price,
                        $image
                    );

                } else {

                    if ($image != "") {

                        $sql = "UPDATE rooms SET
                        room_name = ?, description = ?,
                        price = ?, image = ?
                        WHERE room_id = ?";

                        $stmt = mysqli_prepare($conn, $sql);

                        mysqli_stmt_bind_param(
                            $stmt,
                            "ssdsi",
                            $name,
                            $description,
                            $price,
                            $image,
                            $id
                        );

                    } else {

                        $sql = "UPDATE rooms SET
                        room_name = ?, description = ?,
                        price = ?
                        WHERE room_id = ?";

                        $stmt = mysqli_prepare($conn, $sql);

                        mysqli_stmt_bind_param(
                            $stmt,
                            "ssdi",
                            $name,
                            $description,
                            $price,
                            $id
                        );
                    }
                }

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                header("Location: room.php");
                exit();
            }
        }
    }
}

// Get Room for Editing

$editRoom = null;

if (isset($_GET["edit"])) {

    $id = (int) $_GET["edit"];

    $sql = "SELECT * FROM rooms WHERE room_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $editRoom = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
}

// Get All Rooms

$sql = "SELECT * FROM rooms ORDER BY room_id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Ceylonora - Manage Rooms</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body class="room-page">

<aside class="sidebar">

    <div class="admin-logo">
            <img src="../images/logo.jpg" alt="Ceylonora Logo">
            <h2>CEYLONORA</h2>
            <p>ADMIN PANEL</p>
        </div>

    

    <a href="dashboard.php">Dashboard</a>

    <a href="booking.php">Manage Bookings</a>

    <a href="room.php" class="active">Manage Rooms</a>

    

    <div class="sidebar-bottom">
        <a href="../index.php">Visit Website</a>
    </div>
    <a href="../auth/logout.php">Logout</a>

</aside>


<main class="room-management">

    <div class="room-header">

        <div>
            <h1>Manage Rooms</h1>
            <p>Add and manage Ceylonora villa rooms</p>
        </div>

    </div>


    <!-- Add / Edit Room Form -->

    <div class="room-form-card">

        <h2>
            <?php echo $editRoom ? "Edit Room" : "Add New Room"; ?>
        </h2>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="action"
                   value="<?php echo $editRoom ? "edit" : "add"; ?>">

            <?php if ($editRoom) { ?>

                <input type="hidden" name="room_id"
                       value="<?php echo $editRoom['room_id']; ?>">

            <?php } ?>

            <label>Room Name</label>

            <input type="text" name="room_name"
                   placeholder="Enter room name"
                   value="<?php echo htmlspecialchars($editRoom['room_name'] ?? ''); ?>"
                   required>


            <label>Description</label>

            <textarea name="description"
                      placeholder="Enter room description"
                      required><?php echo htmlspecialchars($editRoom['description'] ?? ''); ?></textarea>


            <label>Room Price</label>

            <input type="number" name="price"
                   placeholder="Enter price"
                   min="0.01" step="0.01"
                   value="<?php echo htmlspecialchars($editRoom['price'] ?? ''); ?>"
                   required>


            <label>Room Image</label>

            <input type="file" name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   <?php echo $editRoom ? "" : ""; ?>>

            <?php if ($editRoom && !empty($editRoom["image"])) { ?>

                <p>Current Image:</p>

                <img class="current-room-image"
                     src="../images/<?php echo htmlspecialchars($editRoom['image']); ?>"
                     alt="Current Room Image">

            <?php } ?>


            <button type="submit" class="room-submit-btn">

                <?php echo $editRoom ? "Update Room" : "Add Room"; ?>

            </button>

            <?php if ($editRoom) { ?>

                <a href="room.php" class="room-cancel-btn">
                    Cancel
                </a>

            <?php } ?>

        </form>

        <?php if ($message != "") { ?>

            <p class="room-message">
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>

    </div>


    <!-- Room List -->

    <div class="room-list-card">

        <h2>Available Rooms</h2>

        <div class="room-table-container">

            <table class="room-table">

                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Room Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>

                <?php while ($room = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td><?php echo $room["room_id"]; ?></td>

                    <td>

                        <?php if (!empty($room["image"])) { ?>

                            <img class="room-thumbnail"
                                 src="../images/<?php echo htmlspecialchars($room['image']); ?>"
                                 alt="Room Image">

                        <?php } else { ?>

                            No Image

                        <?php } ?>

                    </td>

                    <td>
                        <?php echo htmlspecialchars($room["room_name"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($room["description"]); ?>
                    </td>

                    <td>
                        Rs. <?php echo number_format($room["price"], 2); ?>
                    </td>

                    <td>

                        <a class="room-edit-btn"
                           href="room.php?edit=<?php echo $room['room_id']; ?>">
                            Edit
                        </a>

                        <form method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this room?');">

                            <input type="hidden" name="action" value="delete">

                            <input type="hidden" name="room_id"
                                   value="<?php echo $room['room_id']; ?>">

                            <button type="submit" class="room-delete-btn">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</main>

</body>
</html>
