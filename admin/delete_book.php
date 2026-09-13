<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$id = (int) $_GET['id'];

$result = mysqli_query($conn, "SELECT id FROM books WHERE id=$id");

if (mysqli_num_rows($result) == 0) {
    header("Location: books.php");
    exit();
}

$query = "DELETE FROM books WHERE id=$id";

if (mysqli_query($conn, $query)) {
    header("Location: books.php?deleted=1");
    exit();
} else {
    header("Location: books.php?deleted=0");
    exit();
}

?>