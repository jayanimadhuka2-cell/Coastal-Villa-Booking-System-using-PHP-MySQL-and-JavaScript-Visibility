
<?php
require_once __DIR__ . "/../config/database.php";

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check whether email already exists
    $check = "SELECT user_id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        $message = "Email already registered!";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES (?, ?, ?, 'Customer')";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Registration successful! Please login.";
            $success = true;
        } else {
            $message = "Registration failed. Please try again.";
        }
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ceylonora - Register</title>
    <link rel="stylesheet" href="../css/auth.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="login-page">

    <div class="login-card">

        <div class="logo-area">
            <img src="../images/logo.jpg"
                 alt="Ceylonora Logo"
                 class="site-logo">

            <p>DISCOVER THE BEAUTY OF CEYLON</p>
        </div>

        <div class="welcome-text">
            <h2>Create Account</h2>
            <p>Join us and experience luxury living.</p>
        </div>

        <?php if ($message != "") { ?>
            <p class="<?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>

        <form method="POST">

            <label>Full Name</label>
            <input type="text"
                   name="name"
                   placeholder="Enter your full name"
                   required>

            <label>Email Address</label>
            <input type="email"
                   name="email"
                   placeholder="Enter your email"
                   required>

            <label>Password</label>
            <input type="password"
                   name="password"
                   placeholder="Create a password"
                   minlength="6"
                   required>

            <button type="submit" class="login-button">
                CREATE ACCOUNT
            </button>

        </form>

        <p class="register-link">
            Already have an account?
            <a href="login.php">Login Now</a>
        </p>

        <a href="../index.php" class="home-link">
            ← Back to Home
        </a>

    </div>

    <div class="bottom-text">
        © 2026 CEYLONORA | LUXURY COASTAL LIVING
    </div>

</div>

</body>
</html>
