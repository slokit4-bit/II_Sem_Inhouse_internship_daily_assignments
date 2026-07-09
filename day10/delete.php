<?php

include("db_connect.php");

// URL se ID lena
$id = $_GET['id'];

// Student ka naam nikalna
$sql = "SELECT name FROM students WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$name = $row['name'];

// Delete Query
$delete = "DELETE FROM students WHERE id = $id";

if(mysqli_query($conn, $delete))
{
    header("Location: students.php?msg=deleted&name=".$name);
    exit();
}
else
{
    echo "Delete Failed : " . mysqli_error($conn);
}

?>
