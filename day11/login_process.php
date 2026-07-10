<!-- <?php
session_start();

include 'db_connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";

$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);

if($row){

    $_SESSION['admin'] = $row;
    $_SESSION['is_loggedin'] = true;

    header("Location: student.php");
    exit;

}else{

    header("Location: index.php");
    exit;
}
?>