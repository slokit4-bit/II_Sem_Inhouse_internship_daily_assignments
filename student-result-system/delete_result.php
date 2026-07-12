<?php
session_start();
require 'php/db_connect.php';
require 'php/functions.php';
require_admin_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// subject_marks rows are deleted automatically because of ON DELETE CASCADE
$stmt = mysqli_prepare($conn, "DELETE FROM results WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

redirect("manage_results.php?msg=Result deleted successfully");
