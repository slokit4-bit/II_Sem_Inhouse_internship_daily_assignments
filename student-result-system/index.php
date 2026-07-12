<?php
// Start the session on every page that needs to check login status
session_start();
require 'php/header.php';
?>

<div class="container">
    <h2>Welcome to the Student Result Viewer System</h2>
    <p>This system lets students securely view their exam results online, and lets the admin manage student records and results.</p>

    <div class="dashboard-links">
        <a href="student_register.php">📝 New Student? Register</a>
        <a href="student_login.php">🎓 Student Login</a>
        <a href="admin_login.php">🔐 Admin Login</a>
    </div>
</div>

<?php require 'php/footer.php'; ?>
