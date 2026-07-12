<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';

$errors = [];
$success = "";

// ---------------- Handle form submission ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect & clean input
    $name        = clean_input($_POST['name']);
    $roll_no     = clean_input($_POST['roll_no']);
    $dob         = clean_input($_POST['dob']);
    $studentClass = clean_input($_POST['class']);
    $email       = clean_input($_POST['email']);
    $phone       = clean_input($_POST['phone']);
    $password    = $_POST['password']; // optional field

    // 2. Basic validation
    if ($name === "" || $roll_no === "" || $dob === "" || $studentClass === "") {
        $errors[] = "Please fill all required fields (Name, Roll Number, DOB, Class).";
    }

    if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // 3. Check roll number is not already used (using a prepared statement to prevent SQL injection)
    if (empty($errors)) {
        $check = mysqli_prepare($conn, "SELECT id FROM students WHERE roll_no = ?");
        mysqli_stmt_bind_param($check, "s", $roll_no);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $errors[] = "This Roll Number is already registered. Please login instead.";
        }
        mysqli_stmt_close($check);
    }

    // 4. Insert into database if no errors
    if (empty($errors)) {
        // Hash the password only if the student chose to set one
        $hashedPassword = $password !== "" ? password_hash($password, PASSWORD_DEFAULT) : NULL;

        $stmt = mysqli_prepare($conn,
            "INSERT INTO students (roll_no, name, dob, password, class, email, phone)
             VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssss", $roll_no, $name, $dob, $hashedPassword, $studentClass, $email, $phone);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Registration successful! You can now login using your Roll Number and Date of Birth.";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

require 'php/header.php';
?>

<div class="center-box">
    <h2>Student Registration</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <?php if ($success !== ""): ?>
        <div class="success-msg"><?php echo $success; ?></div>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="student_register.php">
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="roll_no">Roll Number *</label>
            <input type="text" id="roll_no" name="roll_no" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth *</label>
            <input type="date" id="dob" name="dob" required>
        </div>

        <div class="form-group">
            <label for="class">Class / Section *</label>
            <input type="text" id="class" name="class" placeholder="e.g. 10th A" required>
        </div>

        <div class="form-group">
            <label for="email">Email (optional)</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="phone">Phone (optional)</label>
            <input type="text" id="phone" name="phone">
        </div>

        <div class="form-group">
            <label for="password">Set a Password (optional)</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to login with DOB only">
        </div>

        <button type="submit">Register</button>
    </form>
</div>

<?php require 'php/footer.php'; ?>
