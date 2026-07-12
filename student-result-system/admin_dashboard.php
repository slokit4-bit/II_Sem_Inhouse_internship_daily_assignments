<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

// Quick counts for a simple dashboard overview
$studentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'];
$resultCount  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM results"))['total'];
$passCount    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM results WHERE status = 'Pass'"))['total'];
$failCount    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM results WHERE status = 'Fail'"))['total'];

require 'php/header.php';
?>

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>.</p>

    <div class="dashboard-links" style="margin-bottom:10px;">
        <div class="btn" style="flex:1; text-align:center; cursor:default;">👨‍🎓 Students: <?php echo $studentCount; ?></div>
        <div class="btn" style="flex:1; text-align:center; cursor:default;">📄 Results: <?php echo $resultCount; ?></div>
        <div class="btn" style="flex:1; text-align:center; cursor:default; background:#196f3d;">✅ Pass: <?php echo $passCount; ?></div>
        <div class="btn" style="flex:1; text-align:center; cursor:default; background:#922b21;">❌ Fail: <?php echo $failCount; ?></div>
    </div>

    <hr style="margin:20px 0;">

    <h3>Manage</h3>
    <div class="dashboard-links">
        <a href="manage_students.php">👨‍🎓 Manage Students</a>
        <a href="manage_results.php">📄 Manage Results</a>
        <a href="add_student.php">➕ Add New Student</a>
        <a href="add_result.php">➕ Add New Result</a>
    </div>
</div>

<?php require 'php/footer.php'; ?>
