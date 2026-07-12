<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

// Fetch the result summary row
$stmt = mysqli_prepare($conn, "SELECT * FROM results WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$result) {
    die("Result not found.");
}

// Fetch the current subject marks for this result
$stmt2 = mysqli_prepare($conn, "SELECT * FROM subject_marks WHERE result_id = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$currentSubjects = mysqli_fetch_all(mysqli_stmt_get_result($stmt2), MYSQLI_ASSOC);
mysqli_stmt_close($stmt2);

$studentsList = mysqli_query($conn, "SELECT id, roll_no, name FROM students ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id  = (int) $_POST['student_id'];
    $exam_name   = clean_input($_POST['exam_name']);
    $result_date = clean_input($_POST['result_date']);

    $subjectNames = $_POST['subject_name'] ?? [];
    $maxMarksArr  = $_POST['max_marks'] ?? [];
    $obtainedArr  = $_POST['obtained_marks'] ?? [];

    if ($student_id <= 0 || $exam_name === "" || $result_date === "") {
        $errors[] = "Please fill all required fields.";
    }

    $totalMax = 0;
    $totalObtained = 0;
    $subjectData = [];

    for ($i = 0; $i < count($subjectNames); $i++) {
        $subName = clean_input($subjectNames[$i]);
        $max = (int) $maxMarksArr[$i];
        $obtained = (int) $obtainedArr[$i];

        if ($subName === "") continue;

        if ($max <= 0) {
            $errors[] = "Max marks for '$subName' must be greater than 0.";
        }
        if ($obtained < 0 || $obtained > $max) {
            $errors[] = "Marks obtained for '$subName' must be between 0 and $max.";
        }

        $totalMax += $max;
        $totalObtained += $obtained;
        $subjectData[] = ['name' => $subName, 'max' => $max, 'obtained' => $obtained];
    }

    if (empty($errors)) {
        $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
        $grade = get_grade($percentage);
        $subjectsForStatus = array_map(function ($s) {
            return ['marks_obtained' => $s['obtained'], 'max_marks' => $s['max']];
        }, $subjectData);
        $status = get_status($percentage, $subjectsForStatus);

        // Update the result summary
        $stmt3 = mysqli_prepare($conn,
            "UPDATE results SET student_id=?, exam_name=?, total_max_marks=?, total_obtained_marks=?, percentage=?, grade=?, status=?, result_date=? WHERE id=?");
        mysqli_stmt_bind_param($stmt3, "isiidsssi", $student_id, $exam_name, $totalMax, $totalObtained, $percentage, $grade, $status, $result_date, $id);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_close($stmt3);

        // Simplest beginner-friendly approach: delete old subject rows, insert fresh ones
        $del = mysqli_prepare($conn, "DELETE FROM subject_marks WHERE result_id = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
        mysqli_stmt_close($del);

        $ins = mysqli_prepare($conn,
            "INSERT INTO subject_marks (result_id, subject_name, max_marks, marks_obtained) VALUES (?, ?, ?, ?)");
        foreach ($subjectData as $s) {
            mysqli_stmt_bind_param($ins, "isii", $id, $s['name'], $s['max'], $s['obtained']);
            mysqli_stmt_execute($ins);
        }
        mysqli_stmt_close($ins);

        redirect("manage_results.php?msg=Result updated successfully");
    }
}

require 'php/header.php';
?>

<div class="container">
    <h2>Edit Result</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <form method="POST" action="edit_result.php?id=<?php echo $id; ?>">
        <div class="form-group">
            <label>Student *</label>
            <select name="student_id" required>
                <?php while ($s = mysqli_fetch_assoc($studentsList)): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $s['id'] == $result['student_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['roll_no'] . " - " . $s['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Exam Name *</label>
            <input type="text" name="exam_name" value="<?php echo htmlspecialchars($result['exam_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Result Date *</label>
            <input type="date" name="result_date" value="<?php echo $result['result_date']; ?>" required>
        </div>

        <h3 style="margin-top:20px;">Subject-wise Marks</h3>
        <table id="subjectTable">
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Max Marks</th>
                    <th>Marks Obtained</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="subjectRows">
                <?php foreach ($currentSubjects as $sub): ?>
                    <tr>
                        <td><input type="text" name="subject_name[]" value="<?php echo htmlspecialchars($sub['subject_name']); ?>" required></td>
                        <td><input type="number" name="max_marks[]" class="marks-input max-marks" value="<?php echo $sub['max_marks']; ?>" required></td>
                        <td><input type="number" name="obtained_marks[]" class="marks-input obtained-marks" value="<?php echo $sub['marks_obtained']; ?>" required></td>
                        <td><button type="button" onclick="removeRow(this)" class="btn btn-danger">✕</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="button" onclick="addRow()" class="btn btn-secondary" style="margin-top:10px;">➕ Add Subject Row</button>

        <div style="margin-top:20px;">
            <button type="submit">Update Result</button>
            <a href="manage_results.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function addRow() {
    const tbody = document.getElementById("subjectRows");
    const row = document.createElement("tr");
    row.innerHTML = `
        <td><input type="text" name="subject_name[]" required></td>
        <td><input type="number" name="max_marks[]" class="marks-input max-marks" value="100" required></td>
        <td><input type="number" name="obtained_marks[]" class="marks-input obtained-marks" required></td>
        <td><button type="button" onclick="removeRow(this)" class="btn btn-danger">✕</button></td>
    `;
    tbody.appendChild(row);
}

function removeRow(button) {
    const row = button.closest("tr");
    if (document.querySelectorAll("#subjectRows tr").length > 1) {
        row.remove();
    } else {
        alert("At least one subject row is required.");
    }
}
</script>

<?php require 'php/footer.php'; ?>
