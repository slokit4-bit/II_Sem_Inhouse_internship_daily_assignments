<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_student_login(); // if not logged in, this sends the user back to login page

$student_id = $_SESSION['student_id'];

// Get student's own details
$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

// ---------------- Search results by exam name (optional) ----------------
$search = isset($_GET['search']) ? clean_input($_GET['search']) : "";

if ($search !== "") {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM results WHERE student_id = ? AND exam_name LIKE ? ORDER BY result_date DESC");
    $likeSearch = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "is", $student_id, $likeSearch);
} else {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM results WHERE student_id = ? ORDER BY result_date DESC");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
}

mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);

require 'php/header.php';
?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?> 👋</h2>
    <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll_no']); ?> &nbsp; | &nbsp;
       <strong>Class:</strong> <?php echo htmlspecialchars($student['class']); ?></p>

    <hr style="margin:20px 0;">

    <h3>My Results</h3>

    <form method="GET" action="student_dashboard.php" style="margin:15px 0; display:flex; gap:10px;">
        <input type="text" name="search" placeholder="Search by exam name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <?php if ($search !== ""): ?>
            <a href="student_dashboard.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>

    <?php if (mysqli_num_rows($results) === 0): ?>
        <p>No results found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Marks Obtained</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Result Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td data-label="Exam"><?php echo htmlspecialchars($row['exam_name']); ?></td>
                        <td data-label="Marks"><?php echo $row['total_obtained_marks'] . " / " . $row['total_max_marks']; ?></td>
                        <td data-label="Percentage"><?php echo $row['percentage']; ?>%</td>
                        <td data-label="Grade"><?php echo htmlspecialchars($row['grade']); ?></td>
                        <td data-label="Status">
                            <span class="badge <?php echo $row['status'] === 'Pass' ? 'badge-pass' : 'badge-fail'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td data-label="Date"><?php echo $row['result_date']; ?></td>
                        <td data-label="Action"><a href="view_result.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require 'php/footer.php'; ?>
