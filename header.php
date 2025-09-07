<?php
if(session_status()===PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Health Monitoring System</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header class="site-header">
<div class="wrap">
<h1><a href="index.php" class="logo">Health Monitoring</a></h1>

<!-- Hamburger button -->
<button class="hamburger" onclick="toggleMenu()">☰</button>

<nav id="mainNav">
<?php if(isset($_SESSION['user_id'])): ?>
    <a href="index.php">Dashboard</a>

    <?php if($_SESSION['is_admin'] ?? 0): ?>
        <a href="users.php">Patients</a>
        <a href="add_reading.php">Add Reading</a>
        <a href="readings.php">Readings</a>
        <a href="export_csv.php">Export CSV</a>
    <?php else: ?>
        <a href="readings.php">My Readings</a>
        <a href="add_reading.php">Add Reading</a>
    <?php endif; ?>

    <span style="margin-left:10px; color:gold;"><?= ($_SESSION['is_admin'] ?? 0) ? '(Admin)' : '' ?></span>
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Login</a>
    <a href="signup.php">Sign Up</a>
<?php endif; ?>
</nav>
</div>
</header>

<script>
function toggleMenu() {
    const nav = document.getElementById('mainNav');
    nav.classList.toggle('show');
}
</script>
<main class="wrap">
