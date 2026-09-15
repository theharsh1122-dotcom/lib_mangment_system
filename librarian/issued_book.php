<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$message = "";

if (isset($_POST['issue_book'])) {

    $book_id = $_POST['book_id'];
    $student_id = $_POST['student_id'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];

    $book_query = mysqli_query($conn, "SELECT quantity FROM books WHERE id='$book_id'");
    $book = mysqli_fetch_assoc($book_query);

    if (!$book) {
        $message = "Book not found!";
    } elseif ($book['quantity'] <= 0) {
        $message = "Book is not available!";
    } else {

        $query = "INSERT INTO issued_books 
                  (book_id, student_id, issue_date, due_date, status)
                  VALUES 
                  ('$book_id', '$student_id', '$issue_date', '$due_date', 'Issued')";

        if (mysqli_query($conn, $query)) {

            mysqli_query($conn, 
                "UPDATE books SET quantity = quantity - 1 WHERE id='$book_id'"
            );

            $message = "Book issued successfully!";

        } else {
            $message = "Error issuing book!";
        }
    }
}

$books = mysqli_query($conn, "SELECT * FROM books WHERE quantity > 0 ORDER BY book_name ASC");

$students = mysqli_query($conn, "SELECT * FROM students ORDER BY name ASC");

$issued_books = mysqli_query($conn, "
    SELECT 
        issued_books.*,
        books.book_name,
        students.name AS student_name,
        students.enrollment_no
    FROM issued_books
    JOIN books ON issued_books.book_id = books.id
    JOIN students ON issued_books.student_id = students.id
    ORDER BY issued_books.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book - Librarian</title>
    <link rel="stylesheet" href="../assets/css/librarian_issued_book.css">
</head>

<body>

<div class="sidebar">

    <h2>📚 Library</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="books.php">📖 Books</a>
    <a href="student.php">👨‍🎓 Students</a>
    <a href="issued_book.php" class="active">📋 Issue Book</a>
    <a href="return_book.php">↩️ Return Book</a>
    <a href="reports.php">📊 Reports</a>

    <a href="../auth/logout.php" class="logout">🚪 Logout</a>

</div>

<div class="main">

    <div class="topbar">

        <div>
            <h1>Issue Book</h1>
            <p>Issue books to registered students</p>
        </div>

        <div class="profile">
            <div class="avatar">L</div>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>

    </div>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php } ?>

    <div class="issue-box">

        <h2>Issue New Book</h2>

        <form method="POST">

            <div class="form-group">

                <label>Select Book</label>

                <select name="book_id" required>

                    <option value="">Select Book</option>

                    <?php while ($book = mysqli_fetch_assoc($books)) { ?>

                        <option value="<?php echo $book['id']; ?>">
                            <?php echo $book['book_name']; ?>
                            (Available: <?php echo $book['quantity']; ?>)
                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-group">

                <label>Select Student</label>

                <select name="student_id" required>

                    <option value="">Select Student</option>

                    <?php while ($student = mysqli_fetch_assoc($students)) { ?>

                        <option value="<?php echo $student['id']; ?>">
                            <?php echo $student['name']; ?>
                            - <?php echo $student['enrollment_no']; ?>
                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label>Issue Date</label>

                    <input type="date" name="issue_date" required>

                </div>

                <div class="form-group">

                    <label>Due Date</label>

                    <input type="date" name="due_date" required>

                </div>

            </div>

            <button type="submit" name="issue_book">
                Issue Book
            </button>

        </form>

    </div>

    <div class="table-box">

        <div class="table-header">
            <h2>Issued Books</h2>
        </div>

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Book</th>
                        <th>Student</th>
                        <th>Enrollment No</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                <?php while ($row = mysqli_fetch_assoc($issued_books)) { ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>

                        <td><?php echo $row['book_name']; ?></td>

                        <td><?php echo $row['student_name']; ?></td>

                        <td><?php echo $row['enrollment_no']; ?></td>

                        <td><?php echo $row['issue_date']; ?></td>

                        <td><?php echo $row['due_date']; ?></td>

                        <td>

                            <span class="status <?php echo strtolower($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>

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