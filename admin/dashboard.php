<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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

$librarians = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='librarian'");
$total_librarians = mysqli_fetch_assoc($librarians)['total'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>

<div class="sidebar">

    <h2>📚 Library</h2>

    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="books.php">📖 Manage Books</a>
    <a href="student.php">👨‍🎓 Manage Students</a>
    <a href="librarian.php">👨‍💼 Manage Librarians</a>
    <a href="issued_book.php">📋 Issued Books</a>
    <a href="#">📊 Reports</a>

    <a href="../auth/logout.php" class="logout">Logout</a>

</div>


<div class="main">

    <div class="topbar">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo $_SESSION['username']; ?> 👋</p>
        </div>

        <div class="profile">
            <div class="avatar">A</div>
            <span>Admin</span>
        </div>
    </div>


    <div class="cards">

        <div class="card">
            <div class="icon">📚</div>
            <div>
                <p>Total Books</p>
                <h2><?php echo $total_books; ?></h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">👨‍🎓</div>
            <div>
                <p>Total Students</p>
                <h2><?php echo $total_students; ?></h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">📖</div>
            <div>
                <p>Issued Books</p>
                <h2><?php echo $total_issued; ?></h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">👨‍💼</div>
            <div>
                <p>Librarians</p>
                <h2><?php echo $total_librarians; ?></h2>
            </div>
        </div>

    </div>


    <div class="content">

        <div class="welcome-box">
            <h2>Library Management System</h2>

            <p>
                Manage books, students, librarians and library activities
                from one place.
            </p>

            <button>View Reports</button>
        </div>


        <div class="quick-box">

            <h2>Quick Actions</h2>

            <a href="books.php">➕ Add New Book</a>
            <a href="student.php">👨‍🎓 Add Student</a>
            <a href="librarian.php">👨‍💼 Add Librarian</a>
            <a href="issued_book.php">📋 Issue Book</a>

        </div>

    </div>

</div>

</body>
</html>