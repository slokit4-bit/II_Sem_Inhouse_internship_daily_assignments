<?php
/*
    db_connect.php
    ----------------------------------------------------------
    This file creates ONE connection to the MySQL database.
    Every other PHP file that needs the database will just
    write:  require 'php/db_connect.php';
    and then use the $conn variable.
    ----------------------------------------------------------
    CHANGE THESE VALUES to match your own computer's MySQL setup.
*/

$db_host = "localhost";      // usually "localhost" on XAMPP/WAMP
$db_user = "root";           // default XAMPP username
$db_pass = "";                // default XAMPP password is empty
$db_name = "student_result_system";

// Create connection using mysqli (beginner friendly, procedural style)
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// If connection fails, stop the script and show a friendly error
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
