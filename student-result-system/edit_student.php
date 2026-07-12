<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

// Fetch existing student data
$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$student) {
    die("Student not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = clean_input($_POST['name']);
    $roll_no      = clean_input($_POST['roll_no']);
    $dob          = clean_input($_POST['dob']);
    $studentClass = clean_input($_POST['class']);
    $email        = clean_input($_POST['email']);
    $phone        = clean_input($_POST['phone']);
    $newPassword  = $_POST['password']; // only updated if not left blank

    if ($name === "" || $roll_no === "" || $dob === "" || $studentClass === "") {
        $errors[] = "Please fill all required fields.";
    }

    if (empty($errors)) {
        if ($newPassword !== "") {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn,
                "UPDATE students SET roll_no=?, name=?, dob=?, class=?, email=?, phone=?, password=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "sssssssi", $roll_no, $name, $dob, $studentClass, $email, $phone, $hashedPassword, $id);
        } else {
            $stmt = mysqli_prepare($conn,
                "UPDATE students SET roll_no=?, name=?, dob=?, class=?, email=?, phone=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssssssi", $roll_no, $name, $dob, $studentClass, $email, $phone, $id);
        }

        if (mysqli_stmt_execute($stmt)) {
            redirect("manage_students.php?msg=Student updated successfully");
        } else {
            $errors[] = "Failed to update student. Roll Number might already be in use.";
        }
        mysqli_stmt_close($stmt);
    }
}

require 'php/header.php';
?>

<div class="center-box">
    <h2>Edit Student</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <form method="POST" action="edit_student.php?id=<?php echo $id; ?>">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Roll Number *</label>
            <input type="text" name="roll_no" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
        </div>
        <div class="form-group">
            <label>Date of Birth *</label>
            <input type="date" name="dob" value="<?php echo $student['dob']; ?>" required>
        </div>
        <div class="form-group">
            <label>Class *</label>
            <input type="text" name="class" value="<?php echo htmlspecialchars($student['class']); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
        </div>
        <div class="form-group">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password">
        </div>
        <button type="submit">Update Student</button>
        <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require 'php/footer.php'; ?>
