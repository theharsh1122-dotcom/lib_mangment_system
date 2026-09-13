<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: librarians.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT id FROM users WHERE id=$id AND role='librarian'");

if (mysqli_num_rows($result) == 0) {
    header("Location: librarians.php");
    exit();
}

$query = "DELETE FROM users WHERE id=$id AND role='librarian'";

if (mysqli_query($conn, $query)) {
    header("Location: librarians.php?deleted=1");
    exit();
} else {
    header("Location: librarians.php?deleted=0");
    exit();
}
?>