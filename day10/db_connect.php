<?php
$host= "localhost";
$user="root";
$password="";
$database="students_management";
$conn =mysqli_connect($host,$user,$password,$database);

if(!$conn){
    die("database connection failed" . mysqli_connect_error());

}
// echo "connected succesfully";