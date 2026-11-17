
<?php

require_once __DIR__ . "/../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (strlen($password) < 8) {

        $message = "Password must have at least 8 characters!";

    } else {

        $check = mysqli_prepare($conn,
            "SELECT user_id FROM users WHERE email = ?");

        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $message = "This email already exists!";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password, role)
                    VALUES (?, ?, ?, 'Admin')";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Admin account created successfully!";
            } else {
                $message = "Admin creation failed!";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>

<div class="auth-container">
    <div class="auth-card">

        <h1>CEYLONORA</h1>
        <h2>Create Admin Account</h2>

        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>

        <form method="POST">

            <label>Admin Name</label>
            <input type="text" name="name" required>

            <label>Admin Email</label>
            <input type="email" name="email" required>

            <label>Admin Password</label>
            <input type="password" name="password"
                   minlength="8" required>

            <button type="submit">Create Admin</button>

        </form>

    </div>
</div>

</body>
</html>
