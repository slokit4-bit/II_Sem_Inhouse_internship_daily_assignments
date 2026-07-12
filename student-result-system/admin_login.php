<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';

if (isset($_SESSION['admin_id'])) {
    redirect("admin_dashboard.php");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    if ($username === "" || $password === "") {
        $errors[] = "Please enter both username and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admin WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect("admin_dashboard.php");
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}

require 'php/header.php';
?>

<div class="center-box">
    <h2>Admin Login</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <form id="adminLoginForm" method="POST" action="admin_login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p style="margin-top:15px; font-size:13px; color:#777;">Default demo login → username: <b>admin</b>, password: <b>password</b></p>
</div>

<?php require 'php/footer.php'; ?>
