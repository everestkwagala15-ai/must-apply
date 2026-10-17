<?php
session_start();

require_once __DIR__ . "/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Please enter your email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, full_name, password FROM users WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows === 1) {

            $stmt->bind_result(
                $user_id,
                $full_name,
                $password_hash
            );

            $stmt->fetch();

            if (password_verify($password, $password_hash)) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user_id;
                $_SESSION["full_name"] = $full_name;

                header("Location: dashboard.php");
                exit;

            } else {
                $message = "Invalid email or password.";
            }

        } else {
            $message = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In | MUST Apply</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="page-container">
    <div class="register-card">

        <div class="brand">
            <div class="brand-logo">M</div>
            <h1>MUST <span>APPLY</span></h1>
            <p>STUDENT APPLICATION PORTAL</p>
        </div>

        <div class="form-heading">
            <h2>Welcome Back</h2>
            <p>Sign in to continue to your account.</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message error">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">

            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your registered email"
                autocomplete="email"
                required
            >

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
            >

            <button type="submit">SIGN IN</button>

        </form>

        <p class="login-link">
            Don't have an account?
            <a href="register.php">Create account</a>
        </p>

        <div class="footer">
            MUST APPLY PORTAL
        </div>

    </div>
</div>

</body>
</html>