<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$student_query = "SELECT id FROM students WHERE user_id='$user_id'";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

if (!$student) {
    die("Student profile not found.");
}

$student_id = $student['id'];

$query = "
    SELECT 
        issued_books.*,
        books.book_name,
        books.author
    FROM issued_books
    INNER JOIN books ON issued_books.book_id = books.id
    WHERE issued_books.student_id = '$student_id'
    ORDER BY issued_books.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Issued Books</title>

    <link rel="stylesheet" href="../assets/css/student_issued_book.css">
</head>

<body>

<div class="sidebar">

    <div class="logo">
        <h2>Library</h2>
        <span>Management System</span>
    </div>

    <nav>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="books.php">
            Books
        </a>

        <a href="issued_books.php" class="active">
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

    <div class="topbar">

        <div>
            <h1>My Issued Books</h1>
            <p>View your issued and returned books</p>
        </div>

        <div class="user-info">

            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>

            <div>
                <strong>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </strong>

                <small>Student</small>
            </div>

        </div>

    </div>


    <div class="books-card">

        <div class="card-header">

            <div>
                <h2>My Books</h2>
                <p>Details of books issued to you</p>
            </div>

            <span class="book-count">
                <?php echo mysqli_num_rows($result); ?> Books
            </span>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>SR. No</th>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                    </tr>

                </thead>

                <tbody>

                <?php

                if (mysqli_num_rows($result) > 0) {

                    $count = 1;

                    while ($book = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo $count++; ?>
                        </td>

                        <td>
                            <strong>
                                <?php echo htmlspecialchars($book['book_name']); ?>
                            </strong>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($book['author']); ?>
                        </td>

                        <td>
                            <?php echo date("d M Y", strtotime($book['issue_date'])); ?>
                        </td>

                        <td>
                            <?php echo date("d M Y", strtotime($book['due_date'])); ?>
                        </td>

                        <td>

                            <?php
                            if ($book['return_date'] != NULL) {
                                echo date("d M Y", strtotime($book['return_date']));
                            } else {
                                echo "-";
                            }
                            ?>

                        </td>

                        <td>

                            <?php if ($book['status'] == 'Issued') { ?>

                                <span class="issued">
                                    Issued
                                </span>

                            <?php } else { ?>

                                <span class="returned">
                                    Returned
                                </span>

                            <?php } ?>

                        </td>

                        <td>

                            <?php if ($book['fine'] > 0) { ?>

                                <span class="fine">
                                    ₹<?php echo $book['fine']; ?>
                                </span>

                            <?php } else { ?>

                                <span class="no-fine">
                                    ₹0
                                </span>

                            <?php } ?>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="8" class="no-books">
                            You have not issued any books yet.
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>