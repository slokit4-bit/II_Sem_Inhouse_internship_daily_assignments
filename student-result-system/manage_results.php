<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$search = isset($_GET['search']) ? clean_input($_GET['search']) : "";

if ($search !== "") {
    $stmt = mysqli_prepare($conn,
        "SELECT results.*, students.name, students.roll_no
         FROM results JOIN students ON students.id = results.student_id
         WHERE students.name LIKE ? OR students.roll_no LIKE ? OR results.exam_name LIKE ?
         ORDER BY results.id DESC");
    $likeSearch = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "sss", $likeSearch, $likeSearch, $likeSearch);
} else {
    $stmt = mysqli_prepare($conn,
        "SELECT results.*, students.name, students.roll_no
         FROM results JOIN students ON students.id = results.student_id
         ORDER BY results.id DESC");
}

mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);

require 'php/header.php';
?>

<div class="container">
    <h2>Manage Results</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="success-msg"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:15px;">
        <form method="GET" action="manage_results.php" style="display:flex; gap:10px;">
            <input type="text" name="search" placeholder="Search by student, roll no, or exam..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        <a href="add_result.php" class="btn">➕ Add New Result</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Roll No</th>
                <th>Student</th>
                <th>Exam</th>
                <th>Marks</th>
                <th>%</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($results) === 0): ?>
                <tr><td colspan="8">No results found.</td></tr>
            <?php endif; ?>

            <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td data-label="Roll No"><?php echo htmlspecialchars($row['roll_no']); ?></td>
                    <td data-label="Student"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td data-label="Exam"><?php echo htmlspecialchars($row['exam_name']); ?></td>
                    <td data-label="Marks"><?php echo $row['total_obtained_marks'] . "/" . $row['total_max_marks']; ?></td>
                    <td data-label="%"><?php echo $row['percentage']; ?>%</td>
                    <td data-label="Grade"><?php echo htmlspecialchars($row['grade']); ?></td>
                    <td data-label="Status">
                        <span class="badge <?php echo $row['status'] === 'Pass' ? 'badge-pass' : 'badge-fail'; ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td data-label="Actions">
                        <a href="view_result.php?id=<?php echo $row['id']; ?>" class="btn">View</a>
                        <a href="edit_result.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                        <a href="delete_result.php?id=<?php echo $row['id']; ?>" class="btn btn-danger confirm-delete">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p style="margin-top:15px;"><a href="admin_dashboard.php">&larr; Back to Dashboard</a></p>
</div>

<?php require 'php/footer.php'; ?>
