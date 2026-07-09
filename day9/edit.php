<?php
include("db_connect.php");
$id = $_GET['id'];
$sql = "SELECT * FROM students WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $college = $_POST['college'];
    $branch = $_POST['branch'];

    $update = "UPDATE students
               SET
               name='$name',
               email='$email',
               college='$college',
               branch='$branch'
               WHERE id=$id";
if (mysqli_query($conn, $update)) {

    header("Location: students.php?msg=updated");
    exit();

} else {

    echo "Update Failed";

}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<style>
     body{
    font-family: Arial, sans-serif;
    background:#f4f6f9;
}

form{
    width:400px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.2);
}

h2{
    text-align:center;
    color:#333;
}

label{
    font-weight:bold;
}

input[type=text],
input[type=email]{

    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:6px;
    box-sizing:border-box;
}

input[type=submit]{

    width:100%;
    padding:12px;
    background:#00bcd4;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

input[type=submit]:hover{

    background:#0097a7;
}
</style>
<body>
<h2>Edit Student</h2>

<form method="POST">

    <label>Name</label><br>
    <input type="text"
           name="name"
           value="<?php echo $row['name']; ?>"
           required>

    <br><br>

    <label>Email</label><br>
    <input type="email"
           name="email"
           value="<?php echo $row['email']; ?>"
           required>

    <br><br>

    <label>College</label><br>
    <input type="text"
           name="college"
           value="<?php echo $row['college']; ?>"
           required>

    <br><br>

    <label>Branch</label><br>
    <input type="text"
           name="branch"
           value="<?php echo $row['branch']; ?>"
           required>

    <br><br>

    <input type="submit"
           name="update"
           value="Update Student">

</form>


</body>

</html>