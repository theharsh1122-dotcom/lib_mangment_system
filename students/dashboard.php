<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT * FROM users WHERE id='$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

$student_query = "SELECT * FROM students WHERE user_id='$user_id'";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

if (!$student) {
    die("Student profile not found.");
}

$student_id = $student['id'];

$issued_query = "
    SELECT COUNT(*) AS total
    FROM issued_books
    WHERE student_id='$student_id' AND status='Issued'
";
$issued_result = mysqli_query($conn, $issued_query);
$issued_books = mysqli_fetch_assoc($issued_result)['total'];

$returned_query = "
    SELECT COUNT(*) AS total
    FROM issued_books
    WHERE student_id='$student_id' AND status='Returned'
";
$returned_result = mysqli_query($conn, $returned_query);
$returned_books = mysqli_fetch_assoc($returned_result)['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="../assets/css/student_dashboard.css">
</head>

<body>

<div class="sidebar">

    <div class="logo">
        <h2>Library</h2>
        <span>Management System</span>
    </div>

    <nav>
        <a href="dashboard.php" class="active">
            Dashboard
        </a>

        <a href="students_book.php">
            Books
        </a>

        <a href="issued_book.php">
            My Issued Books
        </a>

        <a href="profile.php">
            My Profile
        </a>

        <a href="../auth/logout.php" class="logout">
            Logout
        </a>
    </nav>

</div>


<div class="main">

    <header class="topbar">

        <div>
            <h1>Student Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($student['name']); ?></p>
        </div>

        <div class="user-info">
            <div class="avatar">
                <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
            </div>

            <div>
                <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                <small>Student</small>
            </div>
        </div>

    </header>


    <section class="welcome-card">

        <div>
            <h2>Welcome to Library</h2>

            <p>
                Manage your books, check issued books and view your library information.
            </p>
        </div>

        <div class="welcome-icon">
            📚
        </div>

    </section>


    <section class="stats">

        <div class="stat-card">

            <div class="stat-icon">
                📖
            </div>

            <div>
                <p>Issued Books</p>
                <h2><?php echo $issued_books; ?></h2>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ↩
            </div>

            <div>
                <p>Returned Books</p>
                <h2><?php echo $returned_books; ?></h2>
            </div>

        </div>

    </section>


    <section class="content-grid">

        <div class="info-card">

            <div class="card-header">
                <h2>Student Information</h2>

                <a href="profile.php">
                    View Profile
                </a>
            </div>

            <div class="info-list">

                <div>
                    <span>Full Name</span>
                    <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                </div>

                <div>
                    <span>Father Name</span>
                    <strong><?php echo htmlspecialchars($student['father_name']); ?></strong>
                </div>

                <div>
                    <span>Course</span>
                    <strong><?php echo htmlspecialchars($student['course']); ?></strong>
                </div>

                <div>
                    <span>Semester</span>
                    <strong><?php echo htmlspecialchars($student['semester']); ?></strong>
                </div>

                <div>
                    <span>Enrollment No.</span>
                    <strong><?php echo htmlspecialchars($student['enrollment_no']); ?></strong>
                </div>

                <div>
                    <span>Email</span>
                    <strong><?php echo htmlspecialchars($student['email']); ?></strong>
                </div>

            </div>

        </div>


        <div class="quick-card">

            <div class="card-header">
                <h2>Quick Actions</h2>
            </div>

            <a href="books.php" class="quick-action">
                <span>📚</span>

                <div>
                    <strong>Browse Books</strong>
                    <small>View available library books</small>
                </div>
            </a>


            <a href="issued_books.php" class="quick-action">
                <span>📖</span>

                <div>
                    <strong>My Issued Books</strong>
                    <small>Check your issued books</small>
                </div>
            </a>


            <a href="profile.php" class="quick-action">
                <span>👤</span>

                <div>
                    <strong>My Profile</strong>
                    <small>View your personal details</small>
                </div>
            </a>

        </div>

    </section>

</div>

</body>
</html>