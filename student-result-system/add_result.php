<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$errors = [];

// Get list of all students for the dropdown
$studentsList = mysqli_query($conn, "SELECT id, roll_no, name FROM students ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) $_POST['student_id'];
    $exam_name  = clean_input($_POST['exam_name']);
    $result_date = clean_input($_POST['result_date']);

    $subjectNames = $_POST['subject_name'] ?? [];
    $maxMarksArr  = $_POST['max_marks'] ?? [];
    $obtainedArr  = $_POST['obtained_marks'] ?? [];

    if ($student_id <= 0 || $exam_name === "" || $result_date === "") {
        $errors[] = "Please fill all required fields.";
    }

    if (count($subjectNames) === 0) {
        $errors[] = "Please add at least one subject.";
    }

    // Validate each subject row
    $totalMax = 0;
    $totalObtained = 0;
    $subjectData = [];

    for ($i = 0; $i < count($subjectNames); $i++) {
        $subName = clean_input($subjectNames[$i]);
        $max = (int) $maxMarksArr[$i];
        $obtained = (int) $obtainedArr[$i];

        if ($subName === "") continue; // skip empty rows

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

        // build array in the shape get_status() expects
        $subjectsForStatus = array_map(function ($s) {
            return ['marks_obtained' => $s['obtained'], 'max_marks' => $s['max']];
        }, $subjectData);
        $status = get_status($percentage, $subjectsForStatus);

        // Insert the result summary row first
        $stmt = mysqli_prepare($conn,
            "INSERT INTO results (student_id, exam_name, total_max_marks, total_obtained_marks, percentage, grade, status, result_date)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isiidsss", $student_id, $exam_name, $totalMax, $totalObtained, $percentage, $grade, $status, $result_date);
        mysqli_stmt_execute($stmt);
        $result_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        // Insert each subject mark row
        $stmt2 = mysqli_prepare($conn,
            "INSERT INTO subject_marks (result_id, subject_name, max_marks, marks_obtained) VALUES (?, ?, ?, ?)");
        foreach ($subjectData as $s) {
            mysqli_stmt_bind_param($stmt2, "isii", $result_id, $s['name'], $s['max'], $s['obtained']);
            mysqli_stmt_execute($stmt2);
        }
        mysqli_stmt_close($stmt2);

        redirect("manage_results.php?msg=Result added successfully");
    }
}

require 'php/header.php';
?>

<div class="container">
    <h2>Add New Result</h2>

    <?php foreach ($errors as $error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endforeach; ?>

    <form method="POST" action="add_result.php">
        <div class="form-group">
            <label>Student *</label>
            <select name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php while ($s = mysqli_fetch_assoc($studentsList)): ?>
                    <option value="<?php echo $s['id']; ?>">
                        <?php echo htmlspecialchars($s['roll_no'] . " - " . $s['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Exam Name *</label>
            <input type="text" name="exam_name" placeholder="e.g. Final Exam" required>
        </div>

        <div class="form-group">
            <label>Result Date *</label>
            <input type="date" name="result_date" required>
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
                <tr>
                    <td><input type="text" name="subject_name[]" required></td>
                    <td><input type="number" name="max_marks[]" class="marks-input max-marks" value="100" required></td>
                    <td><input type="number" name="obtained_marks[]" class="marks-input obtained-marks" required></td>
                    <td><button type="button" onclick="removeRow(this)" class="btn btn-danger">✕</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" onclick="addRow()" class="btn btn-secondary" style="margin-top:10px;">➕ Add Subject Row</button>

        <p id="livePercentage" style="margin-top:15px; font-weight:bold;">Total: 0 / 0 (0%)</p>

        <div style="margin-top:20px;">
            <button type="submit">Save Result</button>
            <a href="manage_results.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
// Add a new blank subject row to the table
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
    // re-attach the live total calculation listeners to the new inputs
    row.querySelectorAll(".marks-input").forEach(function (input) {
        input.addEventListener("input", function () {
            document.querySelectorAll(".marks-input")[0].dispatchEvent(new Event("input"));
        });
    });
}

// Remove a subject row
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
