<?php
$conn = mysqli_connect("localhost", "root", "", "library_managment");
if (!$conn){
    die("Database connection failed:" . mysqli_connect_error());
}
?>