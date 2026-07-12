<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = clean_input($_POST['name']);
    $roll_no      = clean_input($_POST['roll_no']);
    $dob          = clean_input($_POST['dob']);
    $studentClass = clean_input($_POST['class']);
    $email        = clean_input($_POST['email']);
    $phone        = clean_input($_POST['phone']);
    $password     = $_POST['password'];

    if ($name === "" || $roll_no === "" || $dob === "" || $studentClass === "") {
        $errors[] = "Please fill all required fields.";
    }

    if (empty($errors)) {
        $check = mysqli_prepare($conn, "SELECT id FROM students WHERE roll_no = ?");
        mysqli_stmt_bind_param($check, "s", $roll_no);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $errors[] = "A student with this Roll Number already exists.";
        }
        mysqli_stmt_close($check);
    }

    if (empty($errors)) {
        $hashedPassword = $password !== "" ? password_hash($password, PASSWORD_DEFAULT) : NULL;
        $stmt = mysqli_prepare($conn,
            "INSERT INTO students (roll_no, name, dob, password, class, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssss", $roll_no, $name, $dob, $hashedPassword, $studentClass, $email, $phone);

        if (mysqli_stmt_execute($stmt)) {
            redirect("manage_students.php?msg=Student added successfully");
        } else {
            $errors[] = "Failed to add student. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

require 'php/header.php';
?>

<div class="center-box">
    <h2>Add New Student</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <form method="POST" action="add_student.php">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Roll Number *</label>
            <input type="text" name="roll_no" required>
        </div>
        <div class="form-group">
            <label>Date of Birth *</label>
            <input type="date" name="dob" required>
        </div>
        <div class="form-group">
            <label>Class *</label>
            <input type="text" name="class" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone">
        </div>
        <div class="form-group">
            <label>Password (optional)</label>
            <input type="password" name="password">
        </div>
        <button type="submit">Add Student</button>
        <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require 'php/footer.php'; ?>
