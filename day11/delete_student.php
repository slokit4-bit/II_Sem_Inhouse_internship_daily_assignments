<?php session_start();
include "../common/db_connect.php";

$id = $_GET['id'];

$sql = "DELETE from students where id = $id";
$res = mysqli_query($conn, $sql);

if ($res) {
    $_SESSION['message'] = 'Student record deleted successfully!';
    header('Location: students.php');
    exit();
}

?>