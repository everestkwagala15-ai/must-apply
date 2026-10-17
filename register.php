<?php
require_once __DIR__ . "/db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($full_name === "" || $email === "" || $password === "") {
        $message = "Please complete all fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($password) < 8) {
        $message = "Your password must contain at least 8 characters.";
        $messageType = "error";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "This email is already registered.";
            $messageType = "error";

        } else {

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $full_name,
                $email,
                $passwordHash
            );

            if ($stmt->execute()) {
                $message = "Account created successfully! You can now sign in.";
                $messageType = "success";
            } else {
                $message = "Unable to create your account. Please try again.";
                $messageType = "error";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | MUST Apply</title>

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
            <h2>Create Your Account</h2>
            <p>Register to begin your application journey.</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">

            <label for="full_name">Full Name</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                placeholder="Enter your full name"
                autocomplete="name"
                maxlength="100"
                required
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email address"
                autocomplete="email"
                maxlength="150"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Create a password"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <button type="submit">CREATE ACCOUNT</button>

        </form>

        <p class="login-link">
            Already have an account?
            <a href="login.php">Sign in</a>
        </p>

        <div class="footer">
            MUST APPLY PORTAL
        </div>

    </div>
</div>

</body>
</html>