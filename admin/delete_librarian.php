<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID");
}

$id = (int)$_GET['id'];

$check = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");

if (!$check) {
    die("Check Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($check) == 0) {
    die("User not found!");
}

$user = mysqli_fetch_assoc($check);

if ($user['role'] != 'librarian') {
    die("This user is not a librarian!");
}

$query = "DELETE FROM users WHERE id=$id";

if (mysqli_query($conn, $query)) {

    if (mysqli_affected_rows($conn) > 0) {
        header("Location: librarian.php?deleted=1");
        exit();
    } else {
        die("Librarian delete nahi hua!");
    }

} else {

    die("Delete Error: " . mysqli_error($conn));
}
?>