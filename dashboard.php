<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$full_name = $_SESSION["full_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | MUST Apply</title>

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
            <h2>Welcome, <?= htmlspecialchars($full_name, ENT_QUOTES, "UTF-8") ?>!</h2>

            <p>You have successfully signed in to your account.</p>
        </div>

        <a href="logout.php" class="button-link">SIGN OUT</a>

        <div class="footer">
            MUST APPLY PORTAL
        </div>

    </div>
</div>

</body>
</html>