<?php
/*
    header.php
    ----------------------------------------------------------
    Common top navigation bar, included on every page with:
    require 'php/header.php';
    It automatically shows different links depending on
    whether a student or admin is logged in.
    ----------------------------------------------------------
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Viewer System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>📘 Student Result Viewer</h1>
    <nav>
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['student_id'])): ?>
            <a href="student_dashboard.php">My Dashboard</a>
            <a href="logout.php">Logout</a>

        <?php elseif (isset($_SESSION['admin_id'])): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="logout.php">Logout</a>

        <?php else: ?>
            <a href="student_register.php">Register</a>
            <a href="student_login.php">Student Login</a>
            <a href="admin_login.php">Admin Login</a>
        <?php endif; ?>
    </nav>
</header>
