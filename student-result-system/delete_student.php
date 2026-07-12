<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Deleting a student will also delete their results automatically,
// because of "ON DELETE CASCADE" in the database design.
$stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

redirect("manage_students.php?msg=Student deleted successfully");
