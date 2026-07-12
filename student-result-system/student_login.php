<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';

// If already logged in, go straight to dashboard
if (isset($_SESSION['student_id'])) {
    redirect("student_dashboard.php");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roll_no  = clean_input($_POST['roll_no']);
    $dob      = clean_input($_POST['dob']);       // used when no password set
    $password = $_POST['password'];                // used when student has a password

    if ($roll_no === "") {
        $errors[] = "Please enter your Roll Number.";
    }

    if (empty($errors)) {
        // Look up the student by roll number (prepared statement = safe from SQL injection)
        $stmt = mysqli_prepare($conn, "SELECT id, name, dob, password FROM students WHERE roll_no = ?");
        mysqli_stmt_bind_param($stmt, "s", $roll_no);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $student = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$student) {
            $errors[] = "No student found with that Roll Number.";
        } else {
            $loginOk = false;

            // Case 1: student has a password set -> check password
            if (!empty($student['password'])) {
                if ($password !== "" && password_verify($password, $student['password'])) {
                    $loginOk = true;
                } else {
                    $errors[] = "Incorrect password.";
                }
            }
            // Case 2: no password set -> check Date of Birth instead
            else {
                if ($dob !== "" && $dob === $student['dob']) {
                    $loginOk = true;
                } else {
                    $errors[] = "Date of Birth does not match our records.";
                }
            }

            if ($loginOk) {
                // Save login info in the session
                $_SESSION['student_id']   = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                redirect("student_dashboard.php");
            }
        }
    }
}

require 'php/header.php';
?>

<div class="center-box">
    <h2>Student Login</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <form id="studentLoginForm" method="POST" action="student_login.php">
        <div class="form-group">
            <label for="roll_no">Roll Number *</label>
            <input type="text" id="roll_no" name="roll_no" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth (use this if you have no password)</label>
            <input type="date" id="dob" name="dob">
        </div>

        <div class="form-group">
            <label for="password">Password (use this if you set one at registration)</label>
            <input type="password" id="password" name="password">
        </div>

        <button type="submit">Login</button>
    </form>
    <p style="margin-top:15px;">New student? <a href="student_register.php">Register here</a></p>
</div>

<?php require 'php/footer.php'; ?>
