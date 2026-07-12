<?php
/*
    functions.php
    ----------------------------------------------------------
    Small reusable helper functions used across the project.
    Keeping them in one place avoids repeating code (DRY rule).
    ----------------------------------------------------------
*/

// Clean user input - removes extra spaces and dangerous HTML tags
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Work out grade from percentage
function get_grade($percentage)
{
    if ($percentage >= 90) return "A+";
    if ($percentage >= 80) return "A";
    if ($percentage >= 70) return "B";
    if ($percentage >= 60) return "C";
    if ($percentage >= 50) return "D";
    if ($percentage >= 33) return "E";
    return "F"; // below 33 is fail
}

// Work out Pass / Fail status
// Rule used in this project: student must score at least 33% overall
// AND at least 33% in every individual subject to Pass.
function get_status($percentage, $subject_marks)
{
    if ($percentage < 33) {
        return "Fail";
    }
    foreach ($subject_marks as $subject) {
        $subject_percent = ($subject['marks_obtained'] / $subject['max_marks']) * 100;
        if ($subject_percent < 33) {
            return "Fail";
        }
    }
    return "Pass";
}

// Redirect helper
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

// Make sure a student is logged in, otherwise send back to login page
function require_student_login()
{
    if (!isset($_SESSION['student_id'])) {
        redirect("../student_login.php");
    }
}

// Make sure an admin is logged in, otherwise send back to admin login page
function require_admin_login()
{
    if (!isset($_SESSION['admin_id'])) {
        redirect("../admin_login.php");
    }
}
