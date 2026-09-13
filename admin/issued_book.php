<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/database.php";

$success = "";
$error = "";

if (isset($_POST['issue_book'])) {

    $book_id = $_POST['book_id'];
    $student_id = $_POST['student_id'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];

    $check = mysqli_query($conn, "SELECT quantity FROM books WHERE id=$book_id");
    $book = mysqli_fetch_assoc($check);

    if (!$book || $book['quantity'] <= 0) {
        $error = "Book is not available!";
    } else {

        $query = "INSERT INTO issued_books
                  (book_id, student_id, issue_date, due_date, status)
                  VALUES
                  ($book_id, $student_id, '$issue_date', '$due_date', 'Issued')";

        if (mysqli_query($conn, $query)) {

            mysqli_query($conn,
                "UPDATE books SET quantity = quantity - 1 WHERE id=$book_id"
            );

            $success = "Book issued successfully!";

        } else {
            $error = "Book could not be issued!";
        }
    }
}

$books = mysqli_query($conn,
    "SELECT id, book_name, quantity FROM books WHERE quantity > 0 ORDER BY book_name"
);

$students = mysqli_query($conn,
    "SELECT id, name, enrollment_no FROM students ORDER BY name"
);

$issued_books = mysqli_query($conn,
    "SELECT issued_books.*,
            books.book_name,
            students.name,
            students.enrollment_no
     FROM issued_books
     JOIN books ON issued_books.book_id = books.id
     JOIN students ON issued_books.student_id = students.id
     ORDER BY issued_books.id DESC"
);

if (!$issued_books) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Issued Books</title>

    <link rel="stylesheet" href="../assets/css/admin_issued_book.css">
</head>

<body>

<div class="container">

    <h1>Issued Books</h1>

    <?php if ($success != "") { ?>
        <p class="success-message">
            <?php echo $success; ?>
        </p>
    <?php } ?>

    <?php if ($error != "") { ?>
        <p class="error-message">
            <?php echo $error; ?>
        </p>
    <?php } ?>

    <div class="issue-form">

        <h2>Issue New Book</h2>

        <form method="POST">

            <select name="book_id" required>
                <option value="">Select Book</option>

                <?php while ($book = mysqli_fetch_assoc($books)) { ?>

                    <option value="<?php echo $book['id']; ?>">
                        <?php echo $book['book_name']; ?>
                        (Available: <?php echo $book['quantity']; ?>)
                    </option>

                <?php } ?>

            </select>

            <select name="student_id" required>
                <option value="">Select Student</option>

                <?php while ($student = mysqli_fetch_assoc($students)) { ?>

                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['name']; ?>
                        - <?php echo $student['enrollment_no']; ?>
                    </option>

                <?php } ?>

            </select>

            <label>Issue Date</label>

            <input
                type="date"
                name="issue_date"
                value="<?php echo date('Y-m-d'); ?>"
                required
            >

            <label>Due Date</label>

            <input
                type="date"
                name="due_date"
                required
            >

            <button type="submit" name="issue_book">
                Issue Book
            </button>

        </form>

    </div>

    <div class="issued-list">

        <h2>Issued Books List</h2>

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
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($row = mysqli_fetch_assoc($issued_books)) { ?>

                <tr>

                    <td><?php echo $row['id']; ?></td>

                    <td><?php echo $row['book_name']; ?></td>

                    <td><?php echo $row['name']; ?></td>

                    <td><?php echo $row['enrollment_no']; ?></td>

                    <td><?php echo $row['issue_date']; ?></td>

                    <td><?php echo $row['due_date']; ?></td>

                    <td>
                        <?php echo $row['status']; ?>
                    </td>

                    <td>
                         <?php if ($row['status'] == 'Issued') { ?>

                        <a href="return_book.php?id=<?php echo $row['id']; ?>"
                         onclick="return confirm('Are you sure you want to return this book?');">
                         Return
                         </a>
 
                           <?php } else { ?>

                                Returned

                            <?php } ?>
                   </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

    </div>

</div>

</body>
</html>