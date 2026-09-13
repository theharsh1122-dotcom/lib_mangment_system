<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: issued_books.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM issued_books WHERE id=$id AND status='Issued'");

if (mysqli_num_rows($result) == 0) {
    header("Location: issued_books.php");
    exit();
}

$issued = mysqli_fetch_assoc($result);

$return_date = date('Y-m-d');

$due_date = new DateTime($issued['due_date']);
$return = new DateTime($return_date);

$fine = 0;

if ($return > $due_date) {
    $late_days = $due_date->diff($return)->days;
    $fine = $late_days * 5;
}

$query = "UPDATE issued_books SET
          return_date='$return_date',
          status='Returned',
          fine=$fine
          WHERE id=$id";

if (mysqli_query($conn, $query)) {

    mysqli_query($conn,
        "UPDATE books SET quantity = quantity + 1
         WHERE id=" . $issued['book_id']
    );

    header("Location: issued_books.php?returned=1");
    exit();

} else {

    header("Location: issued_books.php?returned=0");
    exit();

}
?>