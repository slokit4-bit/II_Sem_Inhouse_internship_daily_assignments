<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';

// Either a logged-in student OR a logged-in admin may view a result.
// A student may ONLY view their OWN result (important security check).
if (!isset($_SESSION['student_id']) && !isset($_SESSION['admin_id'])) {
    redirect("student_login.php");
}

$result_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get the result summary row
$stmt = mysqli_prepare($conn,
    "SELECT results.*, students.name, students.roll_no, students.class
     FROM results
     JOIN students ON students.id = results.student_id
     WHERE results.id = ?");
mysqli_stmt_bind_param($stmt, "i", $result_id);
mysqli_stmt_execute($stmt);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$result) {
    die("Result not found.");
}

// SECURITY CHECK: a student can only see their OWN result
if (isset($_SESSION['student_id']) && $_SESSION['student_id'] != $result['student_id']) {
    die("Access denied. You can only view your own result.");
}

// Get subject-wise marks for this result
$stmt2 = mysqli_prepare($conn, "SELECT * FROM subject_marks WHERE result_id = ?");
mysqli_stmt_bind_param($stmt2, "i", $result_id);
mysqli_stmt_execute($stmt2);
$subjects = mysqli_stmt_get_result($stmt2);

require 'php/header.php';
?>

<div class="container">
    <h2>Result Details</h2>

    <p><strong>Student Name:</strong> <?php echo htmlspecialchars($result['name']); ?></p>
    <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($result['roll_no']); ?></p>
    <p><strong>Class:</strong> <?php echo htmlspecialchars($result['class']); ?></p>
    <p><strong>Exam:</strong> <?php echo htmlspecialchars($result['exam_name']); ?></p>
    <p><strong>Result Date:</strong> <?php echo $result['result_date']; ?></p>

    <h3 style="margin-top:20px;">Subject-wise Marks</h3>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Max Marks</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($subject = mysqli_fetch_assoc($subjects)): ?>
                <tr>
                    <td data-label="Subject"><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                    <td data-label="Obtained"><?php echo $subject['marks_obtained']; ?></td>
                    <td data-label="Max"><?php echo $subject['max_marks']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="margin-top:20px;">
        <p><strong>Total Marks:</strong> <?php echo $result['total_obtained_marks'] . " / " . $result['total_max_marks']; ?></p>
        <p><strong>Percentage:</strong> <?php echo $result['percentage']; ?>%</p>
        <p><strong>Grade:</strong> <?php echo htmlspecialchars($result['grade']); ?></p>
        <p><strong>Status:</strong>
            <span class="badge <?php echo $result['status'] === 'Pass' ? 'badge-pass' : 'badge-fail'; ?>">
                <?php echo $result['status']; ?>
            </span>
        </p>
    </div>

    <div style="margin-top:20px;">
        <?php if (isset($_SESSION['student_id'])): ?>
            <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        <?php else: ?>
            <a href="manage_results.php" class="btn btn-secondary">Back to Results</a>
        <?php endif; ?>
        <button onclick="window.print()">🖨️ Print Result</button>
    </div>
</div>

<?php require 'php/footer.php'; ?>
