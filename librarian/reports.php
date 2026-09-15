<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$today = date("Y-m-d");

/* Total Books */

$books_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM books
");

$books_data = mysqli_fetch_assoc($books_result);
$total_books = $books_data['total'];


/* Total Students */

$students_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM students
");

$students_data = mysqli_fetch_assoc($students_result);
$total_students = $students_data['total'];


/* Total Issued */

$issued_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM issued_books
    WHERE status='Issued'
");

$issued_data = mysqli_fetch_assoc($issued_result);
$total_issued = $issued_data['total'];


/* Total Returned */

$returned_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM issued_books
    WHERE status='Returned'
");

$returned_data = mysqli_fetch_assoc($returned_result);
$total_returned = $returned_data['total'];


/* Overdue Books */

$overdue_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM issued_books
    WHERE status='Issued'
    AND due_date < '$today'
");

$overdue_data = mysqli_fetch_assoc($overdue_result);
$total_overdue = $overdue_data['total'];


/* Total Fine */

$fine_result = mysqli_query($conn, "
    SELECT COALESCE(SUM(fine), 0) AS total
    FROM issued_books
");

$fine_data = mysqli_fetch_assoc($fine_result);
$total_fine = $fine_data['total'];


/* Book Issue/Return Records */

$report_result = mysqli_query($conn, "
    SELECT
        issued_books.id,
        issued_books.issue_date,
        issued_books.due_date,
        issued_books.return_date,
        issued_books.status,
        issued_books.fine,

        books.book_name,
        books.author,

        students.name AS student_name,
        students.enrollment_no

    FROM issued_books

    JOIN books
    ON issued_books.book_id = books.id

    JOIN students
    ON issued_books.student_id = students.id

    ORDER BY issued_books.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Library Reports</title>

    <link rel="stylesheet" href="../assets/css/librarian_reports.css">
</head>

<body>

<div class="navbar">

    <div class="logo">
        📊 <span>LIBRARY REPORTS</span>
    </div>

    <div class="nav-right">

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <span class="divider">|</span>

        <span class="user">
            👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
        </span>

    </div>

</div>


<div class="container">

    <div class="page-heading">

        <div class="heading-icon">
            📊
        </div>

        <div>
            <h1>Library Reports</h1>
            <p>View library books, issue and return reports</p>
        </div>

    </div>


    <!-- Report Cards -->

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div>
                <h3>Total Books</h3>
                <p><?php echo $total_books; ?></p>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">👨‍🎓</div>
            <div>
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">📖</div>
            <div>
                <h3>Issued Books</h3>
                <p><?php echo $total_issued; ?></p>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">↩️</div>
            <div>
                <h3>Returned Books</h3>
                <p><?php echo $total_returned; ?></p>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div>
                <h3>Overdue Books</h3>
                <p><?php echo $total_overdue; ?></p>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div>
                <h3>Total Fine</h3>
                <p>₹ <?php echo number_format($total_fine, 2); ?></p>
            </div>
        </div>

    </div>


    <!-- Detailed Report -->

    <div class="table-box">

        <div class="table-header">

            <h2>Book Issue & Return Report</h2>

            <input
                type="text"
                id="search"
                placeholder="🔍 Search..."
                onkeyup="searchTable()"
            >

        </div>


        <div class="table-wrapper">

            <table id="reportTable">

                <thead>

                    <tr>

                        <th>S. No.</th>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Issued To</th>
                        <th>Enrollment No.</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Fine</th>
                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                $serial = 1;

                while ($row = mysqli_fetch_assoc($report_result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo $serial++; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['book_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['author']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['student_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['enrollment_no']); ?>
                        </td>

                        <td>
                            <?php
                            echo date(
                                "d/m/Y",
                                strtotime($row['issue_date'])
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo date(
                                "d/m/Y",
                                strtotime($row['due_date'])
                            );
                            ?>
                        </td>

                        <td>

                            <?php

                            if ($row['return_date']) {

                                echo date(
                                    "d/m/Y",
                                    strtotime($row['return_date'])
                                );

                            } else {

                                echo "-";

                            }

                            ?>

                        </td>

                        <td>
                            ₹ <?php echo number_format($row['fine'], 2); ?>
                        </td>

                        <td>

                            <?php if ($row['status'] == 'Returned') { ?>

                                <span class="status returned">
                                    Returned
                                </span>

                            <?php } else { ?>

                                <span class="status issued">
                                    Issued
                                </span>

                            <?php } ?>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>

function searchTable() {

    let input = document
        .getElementById("search")
        .value
        .toLowerCase();

    let rows = document
        .getElementById("reportTable")
        .getElementsByTagName("tbody")[0]
        .getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {

        let text = rows[i].innerText.toLowerCase();

        rows[i].style.display =
            text.includes(input) ? "" : "none";

    }

}

</script>

</body>

</html>