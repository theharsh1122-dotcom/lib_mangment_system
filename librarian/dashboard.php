<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$books = mysqli_query($conn, "SELECT COUNT(*) AS total FROM books");
$total_books = mysqli_fetch_assoc($books)['total'];

$students = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$total_students = mysqli_fetch_assoc($students)['total'];

$issued = mysqli_query($conn, "SELECT COUNT(*) AS total FROM issued_books WHERE status='Issued'");
$total_issued = mysqli_fetch_assoc($issued)['total'];

$returned = mysqli_query($conn, "SELECT COUNT(*) AS total FROM issued_books WHERE status='Returned'");
$total_returned = mysqli_fetch_assoc($returned)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Librarian Dashboard</title>

    <link rel="stylesheet" href="../assets/css/librarian_dashboard.css">

</head>

<body>

<div class="sidebar">

    <h2>📚 Library</h2>

    <a href="dashboard.php" class="active">🏠 Dashboard</a>

    <a href="books.php">📖 Books</a>

    <a href="student.php">👨‍🎓 Students</a>

    <a href="issued_book.php">📋 Issue Book</a>

    <a href="return_book.php">↩️ Return Book</a>

    <a href="reports.php">📊 Reports</a>

    <a href="../auth/logout.php" class="logout">🚪 Logout</a>

</div>


<div class="main">

    <div class="topbar">

        <div>

            <h1>Welcome, Librarian!</h1>

            <p>
                Manage library resources efficiently.
            </p>

        </div>

        <div class="profile">

            <div class="avatar">L</div>

            <span>
                <?php echo $_SESSION['username']; ?>
            </span>

        </div>

    </div>


    <div class="cards">

        <div class="card">

            <div class="icon">📚</div>

            <div>

                <p>Total Books</p>

                <h2>
                    <?php echo $total_books; ?>
                </h2>

            </div>

        </div>


        <div class="card">

            <div class="icon">👨‍🎓</div>

            <div>

                <p>Total Students</p>

                <h2>
                    <?php echo $total_students; ?>
                </h2>

            </div>

        </div>


        <div class="card">

            <div class="icon">📖</div>

            <div>

                <p>Issued Books</p>

                <h2>
                    <?php echo $total_issued; ?>
                </h2>

            </div>

        </div>


        <div class="card">

            <div class="icon">↩️</div>

            <div>

                <p>Returned Books</p>

                <h2>
                    <?php echo $total_returned; ?>
                </h2>

            </div>

        </div>

    </div>


    <div class="content">

        <div class="welcome-box">

            <h2>Library Management</h2>

            <p>
                Manage books, students and library transactions
                from one place.
            </p>

        </div>


        <div class="quick-box">

            <h2>⚡ Quick Actions</h2>

            <a href="books.php">
                📚 Add New Book
            </a>

            <a href="student.php">
                👨‍🎓 Add Student
            </a>

            <a href="issued_book.php">
                📋 Issue Book
            </a>

            <a href="return_book.php">
                ↩️ Return Book
            </a>

        </div>

    </div>

</div>

</body>

</html>