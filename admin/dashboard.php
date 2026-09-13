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

$monthly_issued = array_fill(1, 12, 0);
$monthly_returned = array_fill(1, 12, 0);

$issued_chart = mysqli_query($conn, "
    SELECT MONTH(issue_date) AS month, COUNT(*) AS total
    FROM issued_books
    WHERE YEAR(issue_date) = YEAR(CURDATE())
    GROUP BY MONTH(issue_date)
");

while ($row = mysqli_fetch_assoc($issued_chart)) {
    $monthly_issued[(int)$row['month']] = (int)$row['total'];
}

$returned_chart = mysqli_query($conn, "
    SELECT MONTH(return_date) AS month, COUNT(*) AS total
    FROM issued_books
    WHERE status='Returned'
    AND return_date IS NOT NULL
    AND YEAR(return_date) = YEAR(CURDATE())
    GROUP BY MONTH(return_date)
");

while ($row = mysqli_fetch_assoc($returned_chart)) {
    $monthly_returned[(int)$row['month']] = (int)$row['total'];
}

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
    <a href="reports.php">📊 Reports</a>
    <a href="return_book.php">↩️ Return Book</a>

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

    <div class="middle-section">

    <div class="statistics-box">

        <div class="statistics-header">

            <div>
                <h2>📊 Book Issue/Return Statistics</h2>
            </div>

            <select>
                <option>This Year</option>
            </select>

        </div>

        <div class="chart">

            <div class="chart-legend">

                <span>
                    <i class="issued-color"></i>
                    Books Issued
                </span>

                <span>
                    <i class="returned-color"></i>
                    Books Returned
                </span>

            </div>

            <div class="bars">

                <?php

                $months = [
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'May',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Aug',
                    9 => 'Sep',
                    10 => 'Oct',
                    11 => 'Nov',
                    12 => 'Dec'
                ];

                for ($i = 1; $i <= 12; $i++) {

                    $issued_height = $monthly_issued[$i] > 0
                        ? min($monthly_issued[$i] * 8, 150)
                        : 3;

                    $returned_height = $monthly_returned[$i] > 0
                        ? min($monthly_returned[$i] * 8, 150)
                        : 3;

                ?>

                    <div class="month">

                        <div class="bar-area">

                            <div
                                class="bar issued"
                                style="height: <?php echo $issued_height; ?>px;"
                                title="Issued: <?php echo $monthly_issued[$i]; ?>"
                            ></div>

                            <div
                                class="bar returned"
                                style="height: <?php echo $returned_height; ?>px;"
                                title="Returned: <?php echo $monthly_returned[$i]; ?>"
                            ></div>

                        </div>

                        <span>
                            <?php echo $months[$i]; ?>
                        </span>

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>


    <div class="quick-box">

        <h2>⚡ Quick Actions</h2>

        <div class="quick-actions">

            <a href="books.php" class="quick-btn blue">
                <span>📚</span>
                <strong>Add New Book</strong>
            </a>

            <a href="student.php" class="quick-btn green">
                <span>👨‍🎓</span>
                <strong>Add Student</strong>
            </a>

            <a href="issued_book.php" class="quick-btn orange">
                <span>📋</span>
                <strong>Issue Book</strong>
            </a>

            <a href="return_book.php" class="quick-btn red">
                <span>↩️</span>
                <strong>Return Book</strong>
            </a>

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
        </div>

    </div>

</div>

</body>
</html>