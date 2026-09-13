<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$success = "";
$error = "";

$books_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM books");
$books = mysqli_fetch_assoc($books_result);
$total_books = $books['total'];

$students_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$students = mysqli_fetch_assoc($students_result);
$total_students = $students['total'];

$librarians_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='librarian'");
$librarians = mysqli_fetch_assoc($librarians_result);
$total_librarians = $librarians['total'];

$issued_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM issued_books WHERE status='Issued'");
$issued = mysqli_fetch_assoc($issued_result);
$total_issued = $issued['total'];

$returned_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM issued_books WHERE status='Returned'");
$returned = mysqli_fetch_assoc($returned_result);
$total_returned = $returned['total'];

$fine_result = mysqli_query($conn, "SELECT SUM(fine) AS total FROM issued_books");
$fine = mysqli_fetch_assoc($fine_result);
$total_fine = $fine['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Library Reports</title>

    <link rel="stylesheet" href="../assets/css/admin_reports.css">
</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>Library Management System</h1>
            <p>Complete Library Reports</p>
        </div>

        <div class="header-buttons">

            <button onclick="window.print()" class="print-btn">
                🖨️ Print Report
            </button>

            <a href="dashboard.php" class="back-btn">
                ← Dashboard
            </a>

        </div>

    </div>


    <div class="report-title">

        <h2>Library Statistics Report</h2>

        <p>
            Generated on:
            <?php echo date("d-m-Y"); ?>
        </p>

    </div>


    <div class="cards">

        <div class="card">

            <h3>Total Books</h3>

            <h2>
                <?php echo $total_books; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Students</h3>

            <h2>
                <?php echo $total_students; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Librarians</h3>

            <h2>
                <?php echo $total_librarians; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Currently Issued</h3>

            <h2>
                <?php echo $total_issued; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Returned Books</h3>

            <h2>
                <?php echo $total_returned; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Fine</h3>

            <h2>
                ₹<?php echo $total_fine; ?>
            </h2>

        </div>

    </div>


    <div class="summary">

        <h2>Report Summary</h2>

        <table>

            <tr>
                <th>Report</th>
                <th>Total</th>
            </tr>

            <tr>
                <td>Total Books</td>
                <td><?php echo $total_books; ?></td>
            </tr>

            <tr>
                <td>Total Students</td>
                <td><?php echo $total_students; ?></td>
            </tr>

            <tr>
                <td>Total Librarians</td>
                <td><?php echo $total_librarians; ?></td>
            </tr>

            <tr>
                <td>Currently Issued Books</td>
                <td><?php echo $total_issued; ?></td>
            </tr>

            <tr>
                <td>Returned Books</td>
                <td><?php echo $total_returned; ?></td>
            </tr>

            <tr>
                <td>Total Fine</td>
                <td>₹<?php echo $total_fine; ?></td>
            </tr>

        </table>

    </div>


    <div class="footer">

        <p>Library Management System</p>

    </div>

</div>

</body>
</html>