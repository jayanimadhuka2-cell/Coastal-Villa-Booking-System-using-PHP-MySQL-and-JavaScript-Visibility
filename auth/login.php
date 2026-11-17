
<?php
session_start();

require_once __DIR__ . "/../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "Admin") {
                header("Location: ../admin/booking.php");
                exit();
            } else {
                header("Location: ../guest/dashboard.php");
                exit();
            }

        } else {
            $message = "Incorrect email or password!";
        }

    } else {
        $message = "Incorrect email or password!";
    }

    mysqli_stmt_close($stmt);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Ceylonora - Login</title>
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
            <h2>Welcome Back</h2>
            <p>Sign in to experience luxury living.</p>
        </div>

        <?php if ($message != "") { ?>
            <p class="error">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>

        <form method="POST">

            <label>Email Address</label>
            <input type="email"
                   name="email"
                   placeholder="Enter your email"
                   required>

            <label>Password</label>

            <div class="password-box">
                <input type="password"
                       name="password"
                       id="password"
                       placeholder="Enter your password"
                       required>

                <button type="button"
                        class="show-password"
                        onclick="togglePassword()">
                    Show
                </button>
            </div>

            <button type="submit" class="login-button">
                SIGN IN
            </button>

        </form>

        <p class="register-link">
            Don't have an account?
            <a href="register.php">Register Now</a>
        </p>

        <a href="../index.php" class="home-link">
            ← Back to Home
        </a>

    </div>

    <div class="bottom-text">
        © 2026 CEYLONORA | LUXURY COASTAL LIVING
    </div>

</div>

<script>
function togglePassword() {

    var password = document.getElementById("password");
    var button = document.querySelector(".show-password");

    if (password.type === "password") {
        password.type = "text";
        button.innerHTML = "Hide";
    } else {
        password.type = "password";
        button.innerHTML = "Show";
    }
}
</script>

</body>
</html>
