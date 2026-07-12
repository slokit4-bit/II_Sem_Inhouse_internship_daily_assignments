<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$search = isset($_GET['search']) ? clean_input($_GET['search']) : "";

if ($search !== "") {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM students WHERE name LIKE ? OR roll_no LIKE ? ORDER BY id DESC");
    $likeSearch = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $likeSearch, $likeSearch);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM students ORDER BY id DESC");
}

mysqli_stmt_execute($stmt);
$students = mysqli_stmt_get_result($stmt);

require 'php/header.php';
?>

<div class="container">
    <h2>Manage Students</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="success-msg"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:15px;">
        <form method="GET" action="manage_students.php" style="display:flex; gap:10px;">
            <input type="text" name="search" placeholder="Search by name or roll no..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        <a href="add_student.php" class="btn">➕ Add New Student</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Class</th>
                <th>DOB</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($students) === 0): ?>
                <tr><td colspan="6">No students found.</td></tr>
            <?php endif; ?>

            <?php while ($row = mysqli_fetch_assoc($students)): ?>
                <tr>
                    <td data-label="Roll No"><?php echo htmlspecialchars($row['roll_no']); ?></td>
                    <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td data-label="Class"><?php echo htmlspecialchars($row['class']); ?></td>
                    <td data-label="DOB"><?php echo $row['dob']; ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td data-label="Actions">
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-danger confirm-delete">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p style="margin-top:15px;"><a href="admin_dashboard.php">&larr; Back to Dashboard</a></p>
</div>

<?php require 'php/footer.php'; ?>
